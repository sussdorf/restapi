<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class ApiController
{
    public function getHealth(Request $request)
    {

        return response()->json([
            'Status' => 200,
            'Message' => 'System OK'
        ],200);
    }
}


