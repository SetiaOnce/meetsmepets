<?php

namespace App\Http\Controllers\Backend\Pegawai;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DataSdm;
use App\Models\InformasiWebsite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiProfileController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = session()->all();
        //Data WebInfo
        $data = array(
            'title' => session()->get('nama_pegawai'),
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
            'scripts/backend/pegawai/pegawai_profile.js'
        );
        return view('backend.pegawai.pegawai_profile', compact('data'));
    }
    public function pegawaiInfo(Request $request)
    {
        $idp = session()->get('id');
        try {
            $getRow = DataSdm::whereId($idp)->first();
            if($getRow != null){
                $getRow->tanggal_lahir = date('d-m-Y', strtotime($getRow->tanggal_lahir));
                $getRow->jabatan_struktural = ($getRow->jabatan_struktural != null) ? $getRow->jabatan_struktural : '-';
                $getRow->jabatan_fungsional = ($getRow->jabatan_fungsional != null) ? $getRow->jabatan_fungsional : '-';
                return response()->json([
                    'status' => TRUE,
                    'message' => 'success',
                    'row' => $getRow
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Credentials not match',
                    'row' => ''
                ]);
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => FALSE,
                'message' => $exception->getMessage(),
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function resetPassword(Request $request)
    {
        $pegawaiId = session()->get('id');
        $form = [
            'pass_lama' => 'required|min:6|max:50',
            'pass_baru' => 'required|min:8|max:50',
            'repass_baru' => 'required|min:8|max:50'
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //Check Old Password
            $user = DataSdm::whereId($pegawaiId)->first();
            $hashedPassword = $user->password;
            if (!Hash::check($request->pass_lama, $hashedPassword)) {
                $output = array('status' => false, 'message' => 'Gagal memperbarui data! Password lama tidak valid, coba lagi dengan isian yang benar', array('error_code' => 'invalid_passold'));
            }else{
                // Update the new Password
                DataSdm::whereId($pegawaiId)->update([
                    'password' => Hash::make($request->repass_baru),
                    'user_updated' => session()->get('nama_pegawai')
                ]);
                DB::commit();
                $output = array('status' => true, 'message' => 'Success', 200);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            $output = array('status' => false, 'message' => $exception->getMessage(), [
                "Trace" => $exception->getTrace()
            ]);
        }
        return response()->json($output);
    }
}
