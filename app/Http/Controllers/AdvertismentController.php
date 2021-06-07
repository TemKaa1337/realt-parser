<?php

namespace App\Http\Controllers;

use App\Models\Advertisment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdvertismentController extends Controller
{
    public function index(Request $request) : Response
    {
        $advertisment = new Advertisment();
        $advertisments = $advertisment->getAllAdvertisments($request->skip ?? 0);

        return response($advertisments, 200);
    }
}
