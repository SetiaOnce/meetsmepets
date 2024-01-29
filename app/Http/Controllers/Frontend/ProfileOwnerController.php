<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Owners;
use App\Models\SiteInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileOwnerController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
        );
        //Data Source JS
        $data['js'] = array(
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/profile.init.js',
        );
        return view('frontend.profile', compact('data'));
    }
    public function settingPage()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
            'dist/plugins/toastr/toastr.min.css',
            'dist/plugins/sweetalert2/sweetalert2.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
            'dist/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/plugins/toastr/toastr.min.js',
            'dist/plugins/sweetalert2/sweetalert2.min.js',
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/profile_setting.init.js',
        );
        return view('frontend.profile_setting', compact('data'));
    }
    public function editPage()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
            'dist/plugins/toastr/toastr.min.css',
            'dist/plugins/sweetalert2/sweetalert2.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
            'dist/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/plugins/toastr/toastr.min.js',
            'dist/plugins/sweetalert2/sweetalert2.min.js',
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/profile_edit.init.js',
        );
        return view('frontend.profile_edit', compact('data'));
    }
    public function savePhone(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            $cekUser = Owners::where('phone_number', $request->phone_number)->where('id', '!=' , $userSesIdp)->first();
            if($cekUser==true) {
                return Shortcut::jsonResponse(false, 'Failed to update data, the same phone number already exists in the system. Try using a different phone number', 200, array('error_code' => 'phone_number'));
            } else {
                Owners::whereId($userSesIdp)->update([
                    'phone_number' => $request->phone_number,
                    'updated_at' => Carbon::now(),
                ]);
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Phone number updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveEmail(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            $cekUser = Owners::where('email', $request->email)->where('id', '!=' , $userSesIdp)->first();
            if($cekUser==true) {
                return Shortcut::jsonResponse(false, 'Failed to update data, the same email already exists in the system. Try using a different email', 200, array('error_code' => 'email'));
            } else {
                Owners::whereId($userSesIdp)->update([
                    'email' => $request->email,
                    'updated_at' => Carbon::now(),
                ]);
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Email updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveLocation(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            Owners::whereId($userSesIdp)->update([
                'location' => $request->location,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Location updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveGender(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            Owners::whereId($userSesIdp)->update([
                'gender' => $request->gender,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Location updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}
