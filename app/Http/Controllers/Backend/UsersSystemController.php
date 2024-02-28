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
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UsersSystemController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('web')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Kelola Data User System',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.css',
            '/dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            '/dist/plugins/Magnific-Popup/magnific-popup.css',
        );
        //Data Source JS
        $data['js'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.js',
            '/dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            '/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            '/dist/js/jquery.mask.min.js',
            '/dist/js/app.backend.init.js',
            '/scripts/backend/manage_users.init.js'
        );
        Shortcut::addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.manage_users', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        if(isset($request->idp)){
            try {
                $getRow = UserSystem::where('users_system.id', $request->idp)->first();
                if($getRow != null){
                    //Thumb Site
                    $thumb = $getRow->thumb;
                    if($thumb==''){
                        $getRow->url_thumb = NULL;
                    } else {
                        if (!file_exists(public_path(). '/dist/img/users-img/'.$thumb)){
                            $getRow->url_thumb = NULL;
                            $getRow->thumb = NULL;
                        }else{
                            $getRow->url_thumb = url('dist/img/users-img/'.$thumb);
                        }
                    }
                    return Shortcut::jsonResponse(true, 'Success', 200, $getRow);
                } else {
                    return Shortcut::jsonResponse(false, "Credentials not match", 401);
                }
            } catch (Exception $exception) {
                return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else {
            $query = UserSystem::orderByDesc('id');
            $data = $query->get();
            $output = Datatables::of($data)->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $user_thumb = $row->thumb;
                    $getHurufAwal = $row->username[0];
                    $symbolThumb = strtoupper($getHurufAwal);
                    if($user_thumb == ''){
                        $url_userThumb = url('dist/img/default-user-img.jpg');
                        $userThumb = '<span class="symbol-label bg-secondary text-danger fw-bold fs-1">'.$symbolThumb.'</span>';
                    } else if (!file_exists(public_path(). '/dist/img/users-img/'.$user_thumb)){
                        $url_userThumb = url('dist/img/default-user-img.jpg');
                        $user_thumb = NULL;
                        $userThumb = '<span class="symbol-label bg-secondary text-danger fw-bold fs-1">'.$symbolThumb.'</span>';
                    }else{
                        $url_userThumb = url('dist/img/users-img/'.$user_thumb);
                        $userThumb = '<a class="image-popup" href="'.$url_userThumb.'" title="'.$user_thumb.'">
                            <div class="symbol-label">
                                <img alt="'.$user_thumb.'" src="'.$url_userThumb.'" class="w-100" />
                            </div>
                        </a>';
                    }
                    $userCustom = '<div class="d-flex align-items-center">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-circle symbol-50px overflow-hidden">
                            '.$userThumb.'
                        </div>
                        <!--end::Avatar-->
                        <div class="ms-2">
                            <a href="javascript:void(0);" class="fw-bold text-gray-900 text-hover-primary mb-2">'.$row->name.'</a>
                            <div class="fw-bold text-muted">'.$row->username.'</div>
                        </div>
                    </div>';
                    return $userCustom;
                })
                ->editColumn('is_active', function ($row) {
                    if($row->is_active == 'Y'){
                        $activeCustom = '<button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="tooltip" title="User Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'N'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                    } else {
                        $activeCustom = '<button type="button" class="btn btn-sm btn-light mb-1" data-bs-toggle="tooltip" title="User Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'Y'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                    }
                    return $activeCustom;
                })
                ->editColumn('last_login', function ($row) {
                    $ipLogin = $row->ip_login;
                    $lastLogin = $row->last_login;
                    if($ipLogin == '' || $ipLogin == null) { $ipLogin = '-';}
                    if($lastLogin == '' || $lastLogin == null) { $lastLogin = '-'; } else { $lastLogin = Shortcut::timeago($lastLogin); }
                    $last_login = $ipLogin.' <br/><div class="fw-bold text-muted">'.$lastLogin.'</div>';
                    return $last_login;
                })
                ->addColumn('action', function($row){
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editUser('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    $btnResetPass = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-warning mb-1 ms-1" data-bs-toggle="tooltip" title="Reset Password!" onclick="_resetUserPass('."'".$row->id."'".');"><i class="las la-unlock-alt fs-3"></i></button>';
                    return $btnEdit.$btnResetPass;
                })
                ->rawColumns(['name', 'is_active', 'last_login', 'action'])
                ->make(true);

            return $output;
        }
    }
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request) {
        $userSesIdp = Auth::guard('web')->user()->id;
        $form = [
            'name' => 'required|max:150',
            'username' => 'required|max:50',
            'email' => 'required|max:225',
            'phone_number' => 'required|max:13',
            'pass_user' => 'required|min:6',
            'repass_user' => 'required|min:6',
            'avatar' => 'mimes:png,jpg,jpeg|max:2048',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => bcrypt($request->repass_user),
                'user_add' => $userSesIdp
            );
            $cekUser = UserSystem::where('username', $request->username)->first();
            if($cekUser==true) {
                Shortcut::addToLog('Data cannot be saved, the same username already exists in the system');
                return Shortcut::jsonResponse(false, 'Gagal menambahkan data, username yang sama sudah ada pada sistem. Coba gunakan username yang lain', 200, array('error_code' => 'username_available'));
            } else {
                $cekUser = UserSystem::where('email', $request->email)->first();
                if($cekUser==true) {
                    Shortcut::addToLog('Data cannot be saved, the same email already exists in the system');
                    return Shortcut::jsonResponse(false, 'Gagal menambahkan data, email yang sama sudah ada pada sistem. Coba gunakan email yang lain', 200, array('error_code' => 'email_available'));
                } else {
                    //If Update Avatar User
                    if(!empty($_FILES['avatar']['name'])) {
                        $avatarDestinationPath = public_path('/dist/img/users-img');
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
                    $insertUser = UserSystem::insertGetId($data);
                    Shortcut::addToLog('User has been successfully added');
                }
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Data user berhasil ditambahkan', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request) {
        $userSesIdp = Auth::guard('web')->user()->id;
        $form = [
            'name' => 'required|max:150',
            'username' => 'required|max:50',
            'email' => 'required|max:225',
            'phone_number' => 'required|max:13',
            'avatar' => 'mimes:png,jpg,jpeg|max:2048',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'is_active' => isset($request->is_active) ? 'Y' : 'N',
                'user_updated' => $userSesIdp
            );
            $cekUser = UserSystem::where('username', $request->username)->where('id', '!=' , $request->id)->first();
            if($cekUser==true) {
                Shortcut::addToLog('Data cannot be updated, the same username already exists in the system');
                return Shortcut::jsonResponse(false, 'Gagal memperbarui data, username yang sama sudah ada pada sistem. Coba gunakan username yang lain', 200, array('error_code' => 'username_available'));
            } else {
                $cekUser = UserSystem::where('email', $request->email)->where('id', '!=' , $request->id)->first();
                if($cekUser==true) {
                    Shortcut::addToLog('Data cannot be updated, the same email already exists in the system');
                    return Shortcut::jsonResponse(false, 'Gagal memperbarui data, email yang sama sudah ada pada sistem. Coba gunakan email yang lain', 200, array('error_code' => 'email_available'));
                } else {
                    //If Update Avatar User
                    if(!empty($_FILES['avatar']['name'])) {
                        $avatarDestinationPath = public_path('/dist/img/users-img');
                        $getUser = UserSystem::select()->whereId($request->id)->first();
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
                    UserSystem::whereId($request->id)->update($data);
                    Shortcut::addToLog('User has been successfully updated');
                }
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Data User berhasil diperbarui', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * update_statususers
     *
     * @param  mixed $request
     * @return void
     */
    public function update_statususers(Request $request) {
        $userSesIdp = Auth::guard('web')->user()->id;
        $idp = $request->idp;
        $value = $request->value;
        DB::beginTransaction();
        try {
            $data = array(
                'is_active' => $value,
                'user_updated' => $userSesIdp
            );
            UserSystem::whereId($idp)->update($data);
            if($value=='N') {
                Shortcut::addToLog('User status has been successfully updated to Inactive');
                $textMsg = 'Status user berhasil diubah menjadi <strong>Nonaktif</strong>';
            } else {
                Shortcut::addToLog('User status has been successfully updated to Active');
                $textMsg = 'Status user berhasil diubah menjadi <strong>Aktif</strong>';
            }
            DB::commit();
            return Shortcut::jsonResponse(true, $textMsg, 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * reset_userpass
     *
     * @param  mixed $request
     * @return void
     */
    public function reset_userpass(Request $request) {
        $userSesIdp = Auth::guard('web')->user()->id;
        $idp = $request->idp;
        $textMsg = "";
        DB::beginTransaction();
        try {
            $getUser = UserSystem::whereId($idp)->first();
            $randPass = Str::random(6);
            $newPass = $randPass;
            $data = array(
                'password' => bcrypt($newPass),
                'user_updated' => $userSesIdp
            );
            $textMsg = "Update password success, New password is <b>".$newPass."</b>";
            UserSystem::whereId($idp)->update($data);
            Shortcut::addToLog('User has been successfully reset password');
            DB::commit();
            return Shortcut::jsonResponse(true, $textMsg, 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}