<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Models\UserSystem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserProfilesController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('web')->user();
        //Data WebInfo
        $data = array(
            'title' => $getUserSession->name.' on User Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
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
            'scripts/backend/user_profile.init.js'
        );
        Shortcut::addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.user_profiles', compact('data'));
    }
     /**
     * update_userprofile
     *
     * @param  mixed $request
     * @return void
     */
    protected function update_userprofile(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'name' => 'required|max:100',
            'username' => 'required|max:100',
            'email' => 'required|max:225',
            'phone_number' => 'required|max:13',
            'avatar' => 'mimes:png,jpg,jpeg|max:2048',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            $data = array(
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'user_updated' => $userSesIdp
            );
            $cekUser = UserSystem::where('username', $request->username)->where('id', '!=' , $userSesIdp)->first();
            if($cekUser==true) {
                Shortcut::addToLog('Data cannot be updated, the same username already exists in the system');
                return Shortcut::jsonResponse(false, 'Gagal memperbarui data, username yang sama sudah ada pada sistem. Coba gunakan username yang lain', 200, array('error_code' => 'username_available'));
            } else {
                $cekUser = UserSystem::where('email', $request->email)->where('id', '!=' , $userSesIdp)->first();
                if($cekUser==true) {
                    Shortcut::addToLog('Data cannot be updated, the same email already exists in the system');
                    return Shortcut::jsonResponse(false, 'Gagal memperbarui data, email yang sama sudah ada pada sistem. Coba gunakan email yang lain', 200, array('error_code' => 'email_available'));
                } else {
                    //If Update Avatar User
                    if(!empty($_FILES['avatar']['name'])) {
                        $avatarDestinationPath = public_path('/dist/img/users-img');
                        $getUser = UserSystem::select()->whereId($userSesIdp)->first();
                        $getAvatarFile = $avatarDestinationPath.'/'.$getUser->thumb;

                        if(file_exists($getAvatarFile) && $getUser->thumb)
                            unlink($getAvatarFile);

                        $avatarFile = $request->file('avatar');
                        $avatarExtension = $avatarFile->getClientOriginalExtension();
                        //Cek and Create Avatar Destination Path
                        if(!is_dir($avatarDestinationPath)){ mkdir($avatarDestinationPath, 0755, TRUE); }

                        $avatarOriginName = $avatarFile->getClientOriginalName();
                        $avatarNewName = strtolower(Str::slug($request->username.bcrypt(pathinfo($avatarOriginName, PATHINFO_FILENAME)))) . time();
                        $avatarNewNameExt = $avatarNewName . '.' . $avatarExtension;
                        $avatarFile->move($avatarDestinationPath, $avatarNewNameExt);

                        $data['thumb'] = $avatarNewNameExt;
                    }

                    UserSystem::whereId($userSesIdp)->update($data);
                    Shortcut::addToLog('The user profile data has been successfully updated');
                }
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Profil User berhasil diperbarui', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * update_userpassprofil
     *
     * @param  mixed $request
     * @return void
     */
    protected function update_userpassprofil(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'pass_lama' => 'required|min:6|max:50',
            'pass_baru' => 'required|min:6|max:50',
            'repass_baru' => 'required|min:6|max:50'
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //Check Old Password
            $user = UserSystem::whereId($userSesIdp)->first();
            $hashedPassword = $user->password;
            if (!Hash::check($request->pass_lama, $hashedPassword)) {
                Shortcut::addToLog('Password cannot be updated, The inputted old password is not found in the system');
                return Shortcut::jsonResponse(false, 'Gagal memperbarui data! Password lama tidak valid, coba lagi dengan isian yang benar', 200, array('error_code' => 'invalid_passold'));
            }
            // Update the new Password
            UserSystem::whereId($userSesIdp)->update([
                'password' => Hash::make($request->repass_baru),
                'user_updated' => $userSesIdp
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Success', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * upload_imgeditor
     *
     * @param  mixed $request
     * @return void
     */
    protected function upload_imgeditor(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'image' => 'mimes:png,jpg,jpeg|max:1024',
        ];
        $request->validate($form);
        try {
            if(!empty($_FILES['image']['name'])) {
                $filePath = date("m-Y");
                $destinationPath = public_path('/dist/img/summernote-img/' .$filePath);
                $file = $request->file('image');
                $imageExtension = $file->getClientOriginalExtension();
                //Cek and Create Avatar Destination Path
                if(!is_dir($destinationPath)){ mkdir($destinationPath, 0755, TRUE); }
                $imageOriginName = $file->getClientOriginalName();
                $imageNewName = strtolower(Str::slug(bcrypt(pathinfo($imageOriginName, PATHINFO_FILENAME)))) . time();
                $imageNewNameExt = $imageNewName . '.' . $imageExtension;
                $file->move($destinationPath, $imageNewNameExt);

                $data = array(
                    "url_img" => url('dist/img/summernote-img/'.$filePath.'/'.$imageNewNameExt),
                );
            }
            return Shortcut::jsonResponse(true, 'Image has been successfully upload', 200, $data);
        } catch (Exception $exception) {
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    } 
}
