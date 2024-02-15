<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Owners;
use App\Models\Pets;
use App\Models\SiteInfo;
use App\Models\Subscribers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('web')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Dashboard',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css'
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/js/app.backend.init.js',
            'https://js.pusher.com/8.2.0/pusher.min.js',
            'scripts/backend/dashboard.js'
        );
        Shortcut::addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.index', compact('data'));
    }
    public function firstWidget()
    {
        $userInfo = Auth::guard('web')->user();
        $userInfo->thumb_url = asset('dist/img/users-img/'.$userInfo->thumb);
        $response = array(
            'userProfle' => $userInfo,
            'counter' => [
                'users' => Owners::all()->count(),
                'pets' => Pets::all()->count(),
                'subcribers' => Subscribers::all()->count(),
            ]
        );
        return response()->json($response);
    }
}
