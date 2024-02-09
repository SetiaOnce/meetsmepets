<?php

namespace App\Http\Controllers\Welcome;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Owners;
use App\Models\SiteInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::guard('owner')->user()){
            return redirect('/home');
        }else{
            if ($request->hasCookie('email')) {
                $owner = Owners::whereEmail($request->cookie('email'))->first();
                if(!empty($owner)){
                    Auth::guard('owner')->login($owner);
                    return redirect('/home');
                }
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
        );
        return view('welcome.login', compact('data'));
    }
    /**
     * first_step
     *
     * @param  mixed $request
     * @return void
     */
    public function first_step(Request $request) {
        try {
            $email = $request->email;
            $user = Owners::where('email', $email)->first();
            if($user) {
                $output = array(
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                );
            } else {
                $user = Owners::where('phone_number', $email)->first();
                if($user) {
                    $output = array(
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_active' => $user->is_active,
                    );
                } else {
                    Shortcut::addToLog('First step login failed, System cannot find user according to the Username or Email entered !');
                    return Shortcut::jsonResponse(false, 'Sistem tidak dapat menemukan akun', 200);
                }
            }
            if($user->is_active=='N') {
                Shortcut::addToLog('First step login failed, The user is currently disabled !');
                return Shortcut::jsonResponse(false, 'Akun user sedang dinonactifkan, silahkan coba lagi nanti atau hubungi admin untuk mengatasi masalah ini.', 200);
            }
            Shortcut::addToLog('First step login was successful, username and email found');
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
            $email = $request->hideMail;
            $password = $request->digit_2.$request->digit_3.$request->digit_4.$request->digit_5;
            $user = Owners::where('email', $email)->first();
            $hashedPassword = $user->password;
            if (!Hash::check($password, $hashedPassword)) {
                Shortcut::addToLog('Second step login failed, System cannot find user according to the Password entered !');
                return Shortcut::jsonResponse(false, 'PIN yang dimasukkan tidak sesuai, coba lagi dengan PIN yang benar!', 200, ['error_code' => 'PASSWORD_NOT_VALID']);
            }
            Auth::guard('owner')->login($user);
            Shortcut::addToLog('Second step login successful, the user session has been created');
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
    /**
     * logout_sessions
     *
     * @param  mixed $request
     * @return void
     */
    public function logout_sessions(Request $request) {
        $arrayCookie = array();
        foreach (Cookie::get() as $key => $item){
            $arrayCookie []= cookie($key, null, -2628000, null, null);
        }
        Cookie::queue(Cookie::forget('phone_number'));
        Cookie::queue(Cookie::forget('email'));
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->flush();
        Artisan::call('cache:clear');
        return redirect('/');
    }
}
