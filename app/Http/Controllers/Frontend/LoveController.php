<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Owners;
use App\Models\SiteInfo;
use App\Models\SuperLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoveController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Love',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/swiper/swiper-bundle.min.js',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
            'dist/assets/js/tinderSwiper.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/assets/js/dz.carousel.js',
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/love.init.js',
        );
        return view('frontend.love', compact('data'));
    }
}
