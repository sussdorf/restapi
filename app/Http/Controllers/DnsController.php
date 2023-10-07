<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
class DnsController
{

    public function setPtr(Request $request, $ip):string
    {
        $response = Http::asJson()->withHeaders(['X-Token' => env('APIKEY_DSH')
        ])->put(env('DSH_APIURL').'dns/reverse/'.$ip.'/record', [
            'record'=> $request->input('record')


        ]);

        $resp= $response->body();

        return response($resp, 200);
    }


}
