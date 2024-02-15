<?php

namespace App\Http\Controllers;

use App\Events\StatusOnline;
use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\LikePets;
use App\Models\Owners;
use App\Models\SiteInfo;
use App\Models\SuperLike;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function ownerInfo()
    {
        $userInfo = Auth::guard('owner')->user();
        $file_name = $userInfo->thumb;
        if($file_name==''){
            $userInfo->thumb_url = asset('dist/img/default-user-img.jpg');
        } else {
            if (!file_exists(public_path(). '/dist/img/users-img/'.$file_name)){
                $userInfo->thumb_url = asset('dist/img/default-user-img.jpg');
            }else{
                $userInfo->thumb_url = url('dist/img/users-img/'.$file_name);
            }
        }
        $userInfo->last_login = Shortcut::timeago($userInfo->last_login);
        return Shortcut::jsonResponse(true, 'Success', 200, $userInfo);
    }
    public function updateOnline(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        try {
            //Update time last online
            Owners::where('id', $iduserSesIdp)->update([
                'last_login' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            // event realtime online
            event(new StatusOnline(Auth::guard('owner')->user()->name, 'online'));
            return Shortcut::jsonResponse(true, 'update last online successfully', 200);
        } catch (Exception $exception) {
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function statusLike(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        DB::beginTransaction();
        try {
            if ($request->like_status == 'Y') {
                SuperLike::insertGetId([
                    'fid_owner' => $request->idp_owner,
                    'like_by' => $iduserSesIdp,
                    'created_at' => Carbon::now(),
                ]);
            }else{
                SuperLike::whereFidOwner($request->idp_owner)->whereLikeBy($iduserSesIdp)->delete();
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Like or unlike successfully', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function statusPets(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        DB::beginTransaction();
        try {
            if ($request->like_status == 'Y') {
                LikePets::insertGetId([
                    'fid_pets' => $request->fid_pets,
                    'like_by' => $iduserSesIdp,
                    'created_at' => Carbon::now(),
                ]);
            }else{
                LikePets::whereFidPets($request->fid_pets)->whereLikeBy($iduserSesIdp)->delete();
            }
            DB::commit();
            $totalLike = LikePets::whereFidPets($request->fid_pets)->whereLikeBy($iduserSesIdp)->count();
            return Shortcut::jsonResponse(true, 'Like or unlike successfully', 200, $totalLike);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}
