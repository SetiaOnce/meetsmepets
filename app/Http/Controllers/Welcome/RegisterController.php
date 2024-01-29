<?php

namespace App\Http\Controllers\Welcome;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Owners;
use App\Models\SiteInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
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
            'title' => 'Register',
            'desc' => 'Halaman register aplikasi '.$getSiteInfo->name,
            'keywords' => 'Register Application, ' .$getSiteInfo->keyword,
            'url' => url()->current(),
            'thumb' => $getSiteInfo->randomThumb_url,
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
        );
        return view('welcome.register', compact('data'));
    }
    /**
     * first_step
     *
     * @param  mixed $request
     * @return void
     */
    public function first_step(Request $request) {
        try {
            $user = Owners::where('username', $request->username)->first();
            if($user) {
                Shortcut::addToLog('First step register failed, email username exist on system !');
                return Shortcut::jsonResponse(false, 'Data cannot be prosses, email username exists', 200);
            }else{
                $user = Owners::where('email', $request->email)->first();
                if($user) {
                    Shortcut::addToLog('First step register failed, email already exist on system !');
                    return Shortcut::jsonResponse(false, 'Data cannot be prosses, email already exists', 200);
                } else {
                    $user = Owners::where('phone_number', $request->phone_number)->first();
                    if($user) {
                        Shortcut::addToLog('First step register failed, phone number already exist on system !');
                        return Shortcut::jsonResponse(false, 'Data cannot be prosses, phone number already exists', 200);
                    }
                }
            }
            $output = [
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ];
            Shortcut::addToLog('First step register was successful');
            return Shortcut::jsonResponse(true, 'Success', 200, $output);
        } catch (Exception $exception) {
            Shortcut::addToLog($exception->getMessage());
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }  
    /**
     * second_login
     *
     * @param  mixed $request
     * @return void
     */
    public function second_step(Request $request) {
        try {
            $password = $request->confirm_2.$request->confirm_3.$request->confirm_4.$request->confirm_5;
            //array data
            $data = array(
                'name' => $request->hideUsername,
                'username' => $request->hideUsername,
                'email' => $request->hideMail,
                'phone_number' => $request->hidePhoneNumber,
                'is_active' => 'Y',
                'password' => bcrypt($password),
                'thumb' => '-',
                'user_add' => 1,
                'created_at' => Carbon::now()
            );
            $userId = Owners::insertGetId($data);
            Shortcut::addToLog('User has been successfully added');
            Auth::guard('owner')->login(Owners::whereId($userId)->first());
            Shortcut::addToLog('Second step register successful, the user session has been created');
            //Update Data User Session
            Owners::where('id', Auth::guard('owner')->user()->id)->update([
                'is_login' => 'Y',
                'ip_login' => Shortcut::getUserIp(),
                'last_login' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            //Set Cookie
            $arrColor= ["text-primary", "text-success", "text-info", "text-warning", "text-danger", "text-dark"];
            $randomColor = array_rand($arrColor,2);
            $expCookie = 60 * 60 * 24 * 365 * 100; //100 Tahun
            Cookie::queue('phone_number', Auth::guard('owner')->user()->phone_number, $expCookie);
            Cookie::queue('email', Auth::guard('owner')->user()->email, $expCookie);
            Cookie::queue('userThumb_color', $arrColor[$randomColor[0]], $expCookie);
            Cookie::queue('remember', TRUE, $expCookie);

            $lastVisitedUrl = null;
            return Shortcut::jsonResponse(true, 'Success', 200, ['last_visited_url' => $lastVisitedUrl]);
        } catch (Exception $exception) {
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }  
}
