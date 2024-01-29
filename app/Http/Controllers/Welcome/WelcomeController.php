<?php

namespace App\Http\Controllers\Welcome;

use App\Http\Controllers\Controller;
use App\Models\Owners;
use App\Models\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::guard('owner')->user()){
            return redirect('/home');
        }else{
            if ($request->hasCookie('email')) {
                Auth::guard('owner')->login(Owners::whereEmail($request->cookie('email'))->first());
                return redirect('/home');
            }
        }
        $getSiteInfo = SiteInfo::whereId(1)->first();
        //Data WebInfo
        $data = array(
            'title' => 'Welcome',
            'desc' => 'Halaman embuka aplikasi '.$getSiteInfo->name,
            'keywords' => 'Welcome Page Application, ' .$getSiteInfo->keyword,
            'url' => url()->current(),
            'thumb' => $getSiteInfo->randomThumb_url,
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
            'siteInfo' => $getSiteInfo
        );
        //Data Source CSS
        $data['css'] = null;
        //Data Source JS
        $data['js'] = array(
            'dist/js/app.auth.init.js'
        );
        return view('welcome.index', compact('data'));
    }
}
