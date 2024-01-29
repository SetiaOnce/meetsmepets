<?php

namespace App\Http\Controllers;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function siteInfo()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getSiteInfo->login_logo_url = asset('dist/img/site-img/'.$getSiteInfo->login_logo);
        $getSiteInfo->login_bg_url = asset('dist/img/site-img/'.$getSiteInfo->login_bg);
        $getSiteInfo->headbackend_logo_url = asset('dist/img/site-img/'.$getSiteInfo->headbackend_logo);
        $getSiteInfo->headbackend_logo_dark_url = asset('dist/img/site-img/'.$getSiteInfo->headbackend_logo_dark);
        $getSiteInfo->headbackend_icon_url = asset('dist/img/site-img/'.$getSiteInfo->headbackend_icon);
        $getSiteInfo->headbackend_icon_dark_url = asset('dist/img/site-img/'.$getSiteInfo->headbackend_icon_dark);
        $getSiteInfo->headpublic_logo_url = asset('dist/img/site-img/'.$getSiteInfo->headpublic_logo);
        return Shortcut::jsonResponse(true, 'Success', 200, $getSiteInfo);
    }
    public function userInfo()
    {
        $userInfo = Auth::guard('web')->user();
        $userInfo->thumb_url = asset('dist/img/users-img/'.$userInfo->thumb);
        $userInfo->last_login = Shortcut::timeago($userInfo->last_login);
        return Shortcut::jsonResponse(true, 'Success', 200, $userInfo);
    }
}
