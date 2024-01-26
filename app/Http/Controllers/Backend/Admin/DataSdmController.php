<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DataSdm;
use App\Models\InformasiWebsite;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class DataSdmController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Data SDM',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession,
            'totalOnline' => DataSdm::whereIsOnline(1)->count(),
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/custom/datatables/datatables.bundle.css',
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/plugins/select2/css/select2.min.css',
            'dist/plugins/summernote/summernote-lite.min.css',
            'dist/plugins/dropify-master/css/dropify.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css'
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/custom/datatables/datatables.bundle.js',
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/plugins/select2/js/select2.min.js',
            'dist/plugins/summernote/summernote-lite.min.js',
            'dist/plugins/summernote/lang/summernote-id-ID.min.js',
            'dist/plugins/dropify-master/js/dropify.min.js',
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            'dist/js/app.backend.init.js',
            'dist/scripts/backend/main.init.js',
            'https://js.pusher.com/8.2.0/pusher.min.js',
            'scripts/backend/admin/data_sdm.js'
        );
        Shortcut::logActivities('Mengakses Halaman Data SDM');
        return view('backend.admin.data_sdm', compact('data'));
    }
    public function data(Request $request)
    {
        $data = DataSdm::orderBy('id', 'ASC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('pegawai', function ($row) {
                // status online
                $onlineStatus = '<div class="mb-0 lh-1">
                    <span class="badge badge-danger badge-circle w-10px h-10px me-1"></span>
                    <span class="fs-7 fw-semibold text-muted">Offline</span>
                </div>';
                if($row->is_online == 1){
                    $onlineStatus = '<div class="mb-0 lh-1">
                        <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                        <span class="fs-7 fw-semibold text-muted">Online</span>
                    </div>';
                }
                // jabatan pegawai
                if(empty($row->jabatan_struktural)){
                    $jabatan = '<i class="bi bi-bookmark-star-fill align-middle me-1"></i>'.$row->jabatan_fungsional;
                }else{
                    $jabatan = '<i class="bi bi-bookmark-star-fill align-middle me-1"></i>'.$row->jabatan_struktural;
                }
                return $row->nama_pegawai.'<br><span class="text-primary">'.$row->email.'</span><br>'.$jabatan.''.$onlineStatus;
            })
            ->editColumn('nik', function ($row) {
                if(is_null($row->nik)){
                    $nik =  '-';
                }else{
                    $nik =  $row->nik;
                }
                return $nik;
            })
            ->editColumn('nip', function ($row) {
                if(is_null($row->nip)){
                    $nip =  '-';
                }else{
                    $nip =  $row->nip;
                }
                return $nip;
            })
            ->editColumn('status_pegawai', function ($row) {
                if($row->status_kepegawaian == 1){
                    $statusPegawai = 'P3K';
                }else if($row->status_kepegawaian == 2){
                    $statusPegawai = 'PPNPN';
                }else if($row->status_kepegawaian == 3){
                    $statusPegawai = 'ASN';
                }
                return $statusPegawai;
            })
            ->editColumn('foto', function ($row) {
                $fileCustom = '<a class="d-block overlay w-100 image-popup" href="'.$row->foto.'" title="'.$row->nama_pegawai.'">
                    <img src="'.$row->foto.'" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="'.$row->nama_pegawai.'" />
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                    </div>    
                </a>';
                return $fileCustom;
            })
            ->editColumn('status', function ($row) {
                if($row->status == 1){
                    $activeCustom = '<button type="button" class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip" title="Status Pegawai Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."0".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                } else {
                    $activeCustom = '<button type="button" class="btn btn-icon btn-sm btn-danger" data-bs-toggle="tooltip" title="Status Pegawai Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."1".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                }
                return $activeCustom;
            })
            ->editColumn('action', function ($row) {
                $btnDetail = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Detail SDM!" onclick="_detailSdm('."'".$row->id."'".');"><i class="ki-outline ki-mouse-square fs-3"></i></button>';
                $btnReset = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-warning mb-1 ms-1" data-bs-toggle="tooltip" title="Reset Password" onclick="_resetPassword('."'".$row->id."'".');"><i class="ki-outline ki-arrows-circle fs-3"></i></button>';
                return $btnDetail.$btnReset;
            })
            ->rawColumns(['pegawai', 'nik', 'nip', 'status_pegawai', 'foto', 'status', 'action'])
            ->make(true);
    }
    public function detailSdm(Request $request)
    {
        $sdm = DataSdm::whereId($request->idp)->first();
        $result = array();
        if(!empty($sdm)){
            if($sdm->status_kepegawaian == 1){
                $statusPegawai = 'P3K';
            }else if($sdm->status_kepegawaian == 2){
                $statusPegawai = 'PPNPN';
            }else if($sdm->status_kepegawaian == 3){
                $statusPegawai = 'ASN';
            }
            //Data Array
            $result[] = [
                'id' => $sdm->id,
                'unit' => $sdm->unit,
                'kantor' => $sdm->kantor,
                'sub_kantor' => $sdm->sub_kantor,
                'jabatan_struktural' => ($sdm->jabatan_struktural != null) ? $sdm->jabatan_struktural : '-',
                'jabatan_fungsional' => ($sdm->jabatan_fungsional != null) ? $sdm->jabatan_fungsional : '-',
                'nik' => $sdm->nik,
                'nip' => ($sdm->nip != null) ? $sdm->nip : '-',
                'nama_pegawai' => $sdm->nama_pegawai,
                'alamat' => $sdm->alamat,
                'telp' => $sdm->telp,
                'email' => $sdm->email,
                'tempat_lahir' => $sdm->tempat_lahir,
                'tanggal_lahir' => date('d-m-Y', strtotime($sdm->tanggal_lahir)),
                'status_kepegawaian' => $statusPegawai,
                'foto' => $sdm->foto,
                'status' => $sdm->status,
            ];
        } else {
            $result = null;
        }
        $response = array(
            'status' => TRUE,
            'row' => $result
        );
        return response()->json($response);
    }
    public function updateStatus(Request $request)
    {
        $idp = $request->idp;
        $value = $request->value;
        try {
            $data = array(
                'status' => $value,
                'user_updated' => Auth::user()->nama_pegawai,
                'updated_at' =>Carbon::now()
            );
            DataSdm::whereId($idp)->update($data);
            if($value==0) {
                $activities = 'Menonaktifkan Data Pegawai';
                $textMsg = 'Status pegawai berhasil diubah menjadi <strong class="text-danger">Nonaktif</strong>';
            } else {
                $activities = 'Mengaktifkan Data Pegawai';
                $textMsg = 'Status pegawai berhasil diubah menjadi <strong class="text-success">Aktif</strong>';
            }
            $output = array('status' => TRUE, 'message' => $textMsg);
        } catch (Exception $exception) {
            $output = array('status' => 401, 'message' => $exception->getMessage());
        }
        Shortcut::logActivities($activities);
        return response()->json($output);
    }
    public function resetPassword(Request $request)
    {
        $idp = $request->idp;
        try {
            $data = array(
                'password' => Hash::make('p@ssword'),
                'user_updated' => 'YOGA SETIAWAN',
                'updated_at' =>Carbon::now()
            );
            DataSdm::whereId($idp)->update($data);
            Shortcut::logActivities('Melakukan Reset Password Pegawai');
            $textMsg = 'Password pegawai berhasil direset menjadi <strong class="text-info">p@ssword</strong>';
            $output = array('status' => TRUE, 'message' => $textMsg);
        } catch (Exception $exception) {
            $output = array('status' => 401, 'message' => $exception->getMessage());
        }
        return response()->json($output);
    }
}