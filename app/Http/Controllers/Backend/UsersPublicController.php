<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Owners;
use App\Models\SiteInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UsersPublicController extends Controller
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
            'title' => 'Users Public',
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
            '/scripts/backend/users_public.init.js'
        );
        Shortcut::addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.users_public', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        $query = Owners::orderByDesc('id');
        $data = $query->get();
        $output = Datatables::of($data)->addIndexColumn()
            ->editColumn('name', function ($row) {
                $user_thumb = $row->thumb;
                $getHurufAwal = $row->name[0];
                $symbolThumb = strtoupper($getHurufAwal);
                $lat = $row->lat;$lng = $row->lng;
                if($row->lat == null || $row->lng == null){
                    $lat = -6.175355298652318;$lng = 106.82714162911925;
                }
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
                        <a href="javascript:void(0);" class="fw-bold text-gray-900 mb-2">'.$row->name.'</a><br>
                        <a href="https://www.google.com/maps?q='.$lat.','.$lng.'" target="_blank" class="fw-bold text-muted text-hover-primary mb-2 " data-bs-toggle="tooltip" title="Show location on google maps!">Klik to show location</a>
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
                return '';
            })
            ->rawColumns(['name', 'is_active', 'last_login', 'action'])
            ->make(true);

        return $output;
    }
    /**
     * update_status
     *
     * @param  mixed $request
     * @return void
     */
    public function update_status(Request $request) {
        $userSesIdp = Auth::guard('web')->user()->id;
        $idp = $request->idp;
        $value = $request->value;
        DB::beginTransaction();
        try {
            $data = array(
                'is_active' => $value,
                'user_updated' => $userSesIdp
            );
            Owners::whereId($idp)->update($data);
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
}