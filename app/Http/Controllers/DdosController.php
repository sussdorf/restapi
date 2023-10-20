<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ipadress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class DdosController
{
    public function getStatus(Request $request, $ip)
    {
        $out = Ipadress::where("ip", $ip)->first();

        if ($out == null) {
            return response()->json(
                [
                    "error" => 403,
                    "Message" => "IP not found in our System",
                ],
                403
            );
        } else {
            $token = $request->bearerToken();
            $user = User::where("token", $token)->first();
            $uip = DB::table("ip_addresses")
                ->where("ip", "=", $ip)
                ->where("customer", "=", $user->email)
                ->first();
            if ($uip || $user->is_admin == 1) {
                $response = Http::withHeaders([
                    "X-Token" => env("APIKEY_DSH"),
                ])->get(
                    env("DSH_APIURL") . "protection/routing/" . $ip . "/32"
                );
                $resp = $response->body();

                return response($resp, 200);
            } else {
                return response()->json(
                    [
                        "error" => 403,
                        "Message" => "IP not found in your Service",
                    ],
                    403
                );
            }
        }
    }

    public function setRouting(Request $request, $ip)
    {
        $out = Ipadress::where("ip", $ip)->first();

        if ($out == null) {
            return response()->json(
                [
                    "error" => 403,
                    "Message" => "IP not found in our System",
                ],
                403
            );
        } else {
            $token = $request->bearerToken();
            $user = User::where("token", $token)->first();
            $uip = DB::table("ip_addresses")
                ->where("ip", "=", $ip)
                ->where("customer", "=", $user->email)
                ->first();
            if ($uip || $user->is_admin == 1) {
                $response = Http::asJson()
                    ->withHeaders(["X-Token" => env("APIKEY_DSH")])
                    ->put(
                        env("DSH_APIURL") . "protection/routing/" . $ip . "/32",
                        [
                            "l4_permanent" => $request->input("l4_permanent"),
                            "l7_permanent" => $request->input("l7_permanent"),
                            "l7_only" => $request->input("l7_only"),
                        ]
                    );

                $resp = $response->body();

                return response($resp, 200);
            } else {
                return response()->json(
                    [
                        "error" => 403,
                        "Message" => "IP not found in your Service",
                    ],
                    403
                );
            }
        }
    }

    public function setThresholds(Request $request, $ip)
    {
        $response = Http::asJson()
            ->withHeaders(["X-Token" => env("APIKEY_DSH")])
            ->post(env("DSH_APIURL") . "protection/thresholds", [
                "prefix" => $ip . "/32",
                "mbit" => $request->input("mbit"),
                "kpps" => $request->input("kpps"),
            ]);

        $resp = $response->body();

        return response($resp, 200);
    }

    public function getThresholds(Request $request)
    {
        $response = Http::withHeaders([
            "X-Token" => env("APIKEY_DSH"),
        ])->get(env("DSH_APIURL") . "protection/thresholds");
        $resp = $response->body();
        return response($resp, 200);
    }

    public function removeThresholds(Request $request, $uid)
    {
        $response = Http::withHeaders([
            "X-Token" => env("APIKEY_DSH"),
        ])->delete(env("DSH_APIURL") . "protection/thresholds/" . $uid);
        $resp = $response->body();
        return response($resp, 200);
    }

    public function getIncidents(Request $request, $ip)
    {
        $out = Ipadress::where("ip", $ip)->first();

        if ($out == null) {
            return response()->json(
                [
                    "error" => 403,
                    "Message" => "IP not found in our System",
                ],
                403
            );
        } else {
            $token = $request->bearerToken();
            $user = User::where("token", $token)->first();
            $uip = DB::table("ip_addresses")
                ->where("ip", "=", $ip)
                ->where("customer", "=", $user->email)
                ->first();
            if ($uip || $user->is_admin == 1) {
                $response = Http::withHeaders([
                    "X-Token" => env("APIKEY_DSH"),
                ])->get(env("DSH_APIURL") . "protection/incidents/" . $ip);

                return response()->json(
                    [
                        "status" => $response["status"],
                        "data" => $response["items"],
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        "error" => 403,
                        "Message" => "IP not found in your Service",
                    ],
                    403
                );
            }
        }
    }
}