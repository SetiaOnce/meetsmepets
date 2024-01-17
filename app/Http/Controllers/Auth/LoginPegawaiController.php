<?php

namespace App\Http\Controllers\Auth;

use App\Events\StatusOnline;
use App\Http\Controllers\Controller;
use App\Models\DataSdm;
use App\Models\LogLoginAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginPegawaiController extends Controller
{
    public function index()
    {
        if(session()->get('login_akses')) { 
            return redirect('/app_pegawai/dashboard'); 
        }else{
            return view('auth.login_pegawai');
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
            'email_nik.required' => 'Email masih kosong...',
            'password.required' => 'Password masih kosong....',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            if (filter_var($request->email_nik, FILTER_VALIDATE_EMAIL)) {
                $user = DataSdm::whereEmail($request->email_nik)->whereStatus(1)->first();
            }else{
                $user = DataSdm::whereNik($request->email_nik)->whereStatus(1)->first();
            }
            if ($user && Hash::check($request->password, $user->password)) {
                session([
                    'login_akses' => true, 
                    'id'=> $user->id,
                    'sub_kantor'=> $user->sub_kantor,
                    'jabatan_fungsional'=> $user->jabatan_fungsional,  
                    'nik'=> $user->nik,  
                    'nip'=> $user->nip,  
                    'nama_pegawai'=> $user->nama_pegawai,  
                    'alamat'=> $user->alamat,  
                    'telp'=> $user->telp,  
                    'email'=> $user->email,  
                    'tempat_lahir'=> $user->tempat_lahir,  
                    'tanggal_lahir'=> $user->tanggal_lahir,  
                    'status_keuseran'=> $user->status_keuseran,  
                    'foto'=> $user->foto,  
                    'status'=> $user->status,  
                ]);
                //Log login
                LogLoginAplikasi::create([             
                    'fid_aplikasi' => 1,       
                    'fid_pegawai' => $user->id,     
                    'activity' => 'Login ke aplikasi PEGAWAI',    
                ]);
                DataSdm::whereId($user->id)->update([
                    'is_online' => 1
                ]);
                // event realtime online
                event(new StatusOnline($user->nama_pegawai, 'online', DataSdm::whereIsOnline(1)->count()));
                $output =  ['status' => true];
            }else{
                $output = ['wrongAkses' => true,];
            }
        }
        return response()->json($output);
    }
    public function logout(Request $request)
    {
        DataSdm::whereId(session()->get('id'))->update(['is_online' => 0]);
        event(new StatusOnline(session()->get('nama_pegawai'), 'offline', DataSdm::whereIsOnline(1)->count()));
        session()->flush();
        return redirect('/login');
    }
}