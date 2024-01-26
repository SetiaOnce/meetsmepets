<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DataSdm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginAdminController extends Controller
{
    public function index()
    {
        if(Auth::check()) { 
            return redirect('/app_admin/dashboard'); 
        } else{
            return view('auth.login_admin');
        }
    }
    public function requestLogin(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $errors					= [];
        $validator = Validator::make($request->all(), [
            'email_nik' => 'required',
            'password' => 'required',
            ],[
            'email_nik.required' => 'Email/NIK masih kosong...',
            'password.required' => 'Password masih kosong....',
        ]);

        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            if (filter_var($request->email_nik, FILTER_VALIDATE_EMAIL)) {
                $user = DataSdm::whereEmail($request->email_nik)->whereIsAdmin(1)->whereStatus(1)->first();
            }else{
                $user = DataSdm::whereNik($request->email_nik)->whereIsAdmin(1)->whereStatus(1)->first();
            }
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                Shortcut::logActivities('Melakukan Login Pada Aplikasi Ini');
                $output =  ['status' => true];
            }else{
                $output = ['wrongAkses' => true,];
            }
        }
        return response()->json($output);
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Shortcut::logActivities('Melakukan Logout Pada Aplikasi Ini');
        return redirect('/login');
    }
}
