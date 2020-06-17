<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Advertise;

//thông tin về cửa hàng
class AboutPage extends Controller
{
    //lấy thông tin quảng cáo
    public function __invoke(Request $request)
    {
        $advertises = Advertise::where([
          ['start_date', '<=', date('Y-m-d')],
          ['end_date', '>=', date('Y-m-d')],
          ['at_home_page', '=', false]
        ])->latest()->limit(5)->get(['id', 'title', 'image']);

        return view('pages.about')->with(['advertises' => $advertises]);
    }
}
