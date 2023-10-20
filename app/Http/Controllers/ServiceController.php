<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
class ServiceController
{
    public function listService(Request $request)
    {
        //$response = Http::withHeaders(['X-Token' => env('APIKEY_DSH'),NULL])->get(env('DSH_APIURL').'/service');
        $response = Http::withHeaders([
            'X-Token' => env('APIKEY_DSH')

        ])->get(env('DSH_APIURL').'service');
        $resp= $response->body();
        return response($resp, 200);
    }

    public function getService(Request $request, $sid)
    {
        //$response = Http::withHeaders(['X-Token' => env('APIKEY_DSH'),NULL])->get(env('DSH_APIURL').'/service');
        $response = Http::withHeaders([
            'X-Token' => env('APIKEY_DSH')

        ])->get(env('DSH_APIURL').'service/'.$sid.'');
        //$resp= $response->body();
        if ($response['status']==='OK'){

            return response($response['items'][0], 200);
        }
        else{
            return response('Unknown Error', 400);
        }
        return;
    }

    public function getTemplates(Request $request, $sid)

    {

        //$response = Http::withHeaders(['X-Token' => env('APIKEY_DSH'),NULL])->get(env('DSH_APIURL').'/service');

        $response = Http::withHeaders([

            'X-Token' => env('APIKEY_DSH')



        ])->get(env('DSH_APIURL').'service/'.$sid.'/templates');

        $resp= $response->body();

        return response($resp, 200);

    }

    public function Provisioning(Request $request, $sid)

    {

        //$response = Http::withHeaders(['X-Token' => env('APIKEY_DSH'),NULL])->get(env('DSH_APIURL').'/service');
        $pwd = ($request->input('typ')=='kvm' ? 'password' : 'rootpass');

        $response = Http::asJson()->withHeaders(['X-Token' => env('APIKEY_DSH')
        ])->post(env('DSH_APIURL').'service/'.$sid.'/provisioning', [
            ''.$pwd.''=> $request->input('password'),
            'template' => $request->input('template'),
            'templateid'=> '',

        ]);

        $resp= $response->body();

        return response($resp, 200);

    }

    public function provisioningStatus(Request $request, $sid)

    {

        //$response = Http::withHeaders(['X-Token' => env('APIKEY_DSH'),NULL])->get(env('DSH_APIURL').'/service');

        $response = Http::withHeaders([

            'X-Token' => env('APIKEY_DSH')



        ])->get(env('DSH_APIURL').'service/'.$sid.'/provisioning/status');

        $resp= $response->body();

        return response($resp, 200);

    }

    public function getConsole(Request $request, $sid)

    {

        //$response = Http::withHeaders(['X-Token' => env('APIKEY_DSH'),NULL])->get(env('DSH_APIURL').'/service');

        $response = Http::withHeaders([

            'X-Token' => env('APIKEY_DSH')



        ])->get(env('DSH_APIURL').'service/'.$sid.'/console');

        $resp= $response->body();

        return response($resp, 200);

    }

    public function getStatus(Request $request, $sid)

    {

        //$response = Http::withHeaders(['X-Token' => env('APIKEY_DSH'),NULL])->get(env('DSH_APIURL').'/service');

        $response = Http::withHeaders([

            'X-Token' => env('APIKEY_DSH')



        ])->get(env('DSH_APIURL').'service/'.$sid.'/status');

        $resp= $response->body();

        return response($resp, 200);

    }
}
