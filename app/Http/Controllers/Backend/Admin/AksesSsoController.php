<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\AksesSso;
use App\Models\InformasiWebsite;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AksesSsoController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Akses SSO',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/custom/datatables/datatables.bundle.css',
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/plugins/summernote/summernote-lite.min.css',
            'dist/plugins/dropify-master/css/dropify.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css'
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/custom/datatables/datatables.bundle.js',
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/plugins/summernote/summernote-lite.min.js',
            'dist/plugins/summernote/lang/summernote-id-ID.min.js',
            'dist/plugins/dropify-master/js/dropify.min.js',
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            'dist/js/app.backend.init.js',
            // 'dist/scripts/backend/main.init.js',
            'scripts/backend/admin/akses_sso.js'
        );
        Shortcut::logActivities('Mengakses Halaman Akses SSO');
        return view('backend.admin.akses_sso', compact('data'));
    }
    public function data(Request $request)
    {
        $data = AksesSso::select(
            'akses_sso.*',
            'data_sdm.nama_pegawai',
            'data_sdm.email',
            'data_sdm.nik',
            'data_sdm.foto',
            'aplikasi.nama_aplikasi',
            'level_aplikasi.nama_level',
        )
        ->join('data_sdm', 'data_sdm.id', 'akses_sso.fid_pegawai')
        ->join('aplikasi', 'aplikasi.id', 'akses_sso.fid_aplikasi')
        ->join('level_aplikasi', 'level_aplikasi.id', 'akses_sso.fid_level_aplikasi')
        ->orderBy('aplikasi.id', 'ASC')
        ->orderBy('data_sdm.nama_pegawai', 'ASC')
        ->get();
        return Datatables::of($data)->addIndexColumn()
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
                    $activeCustom = '<button type="button" class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip" title="Status Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'0'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                } else {
                    $activeCustom = '<button type="button" class="btn btn-icon btn-sm btn-danger" data-bs-toggle="tooltip" title="Status Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'1'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                }
                return $activeCustom;
            })
            ->editColumn('action', function ($row) {
                $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".', '."'".$row->fid_aplikasi."'".');"><i class="ki-outline ki-pencil fs-3"></i></button>';
                $btnDelete = '';
                $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteData('."'".$row->id."'".');"><i class="ki-outline ki-trash fs-3"></i></button>';
                return $btnEdit.$btnDelete;
            })
            ->rawColumns(['foto', 'status', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_pegawai' => 'required',
            'fid_aplikasi' => 'required',
            'fid_level_aplikasi' => 'required',
        ],[
            'fid_pegawai.required' => 'Pegawai tidak boleh kosong.',
            'fid_aplikasi.required' => 'Aplikasi tidak boleh kosong.',
            'fid_level_aplikasi.required' => 'Level aplikasi tidak boleh kosong.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $checkExist = AksesSso::where('fid_pegawai', $request->fid_pegawai)->where('fid_aplikasi', $request->fid_aplikasi)->where('fid_level_aplikasi', $request->fid_level_aplikasi)->first();
            if(!empty($checkExist)){
                $output = array("status" => FALSE, 'data_available' => true, 'message' => 'Tidak dapat memproses data, level yang sama sudah terkait pada pegawai ini!');
            }else{
                $data = [
                    'fid_pegawai' => $request->input('fid_pegawai'),
                    'fid_aplikasi' => $request->input('fid_aplikasi'),
                    'fid_level_aplikasi' => $request->input('fid_level_aplikasi'),
                    'user_add' => Auth::user()->nama_pegawai,
                    'created_at' => Carbon::now()
                ];
                // create new access sso
                AksesSso::create($data);
                Shortcut::logActivities('Melakukan Penambahan Data Pengelola Portal');
                $output = array("status" => TRUE, 'message' => 'Berhasil menambahkan data pengelola baru');
            }
        }
        return response()->json($output);
    }
    public function edit(Request $request)
    {
        $getRow = AksesSso::whereId($request->idp)->first();
        return response()->json([
            'status' => TRUE,
            'row' =>$getRow,
        ]);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_pegawai' => 'required',
            'fid_aplikasi' => 'required',
            'fid_level_aplikasi' => 'required',
        ],[
            'fid_pegawai.required' => 'Pegawai tidak boleh kosong.',
            'fid_aplikasi.required' => 'Aplikasi tidak boleh kosong.',
            'fid_level_aplikasi.required' => 'Level aplikasi tidak boleh kosong.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $checkExist = AksesSso::WhereNot('id', $request->id)->where('fid_pegawai', $request->fid_pegawai)->where('fid_aplikasi', $request->fid_aplikasi)->where('fid_level_aplikasi', $request->fid_level_aplikasi)->first();
            if(!empty($checkExist)){
                $output = array("status" => FALSE, 'data_available' => true, 'message' => 'Tidak dapat memproses data, level yang sama sudah terkait pada pegawai ini!');
            }else{
                $data = [
                    'fid_pegawai' => $request->input('fid_pegawai'),
                    'fid_aplikasi' => $request->input('fid_aplikasi'),
                    'fid_level_aplikasi' => $request->input('fid_level_aplikasi'),
                    'status' => isset($request->status) ? 1 : 0,
                    'user_updated' => Auth::user()->nama_pegawai,
                    'updated_at' => Carbon::now()
                ];
                // update akses sso
                AksesSso::whereId($request->id)->update($data);
                Shortcut::logActivities('Melakukan Update Data Akses SSO');
                $output = ['status' => TRUE,'message' => 'Sukses memperbarui data akses SSO',];
            }
        }
        return response()->json($output);
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
            AksesSso::whereId($idp)->update($data);
            if($value==0) {
                $activities = 'Menonaktifkan Akses SSO';
                $textMsg = 'Akses SSO berhasil diubah menjadi <strong class="text-danger">Nonaktif</strong>';
            } else {
                $activities = 'Mengaktifkan Akses SSO';
                $textMsg = 'Akses SSO berhasil diubah menjadi <strong class="text-success">Aktif</strong>';
            }
            $output = array('status' => TRUE, 'message' => $textMsg);
        } catch (Exception $exception) {
            $output = array('status' => 401, 'message' => $exception->getMessage());
        }
        Shortcut::logActivities($activities);
        return response()->json($output);
    }
    public function destroy(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        AksesSso::whereId($request->idp)->delete();
        Shortcut::logActivities('Melakukan Penghapusan Data Akses SSO');
        return response()->json(["status" => TRUE, 'message' => 'Berhasil menghapus data akses']);
    }
}