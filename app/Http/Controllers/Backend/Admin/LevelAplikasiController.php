<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\InformasiWebsite;
use App\Models\LevelAplikasi;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LevelAplikasiController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Level Aplikasi',
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
            'scripts/backend/admin/level_aplikasi.js'
        );
        Shortcut::logActivities('Mengakses Halaman Level Aplikasi');
        return view('backend.admin.level_aplikasi', compact('data'));
    }
    public function data(Request $request)
    {
        $data = LevelAplikasi::select(
            'aplikasi.nama_aplikasi',
            'level_aplikasi.*'
        )
        ->join('aplikasi', 'aplikasi.id', '=', 'level_aplikasi.fid_aplikasi')
        ->orderBy('aplikasi.id', 'DESC')
        ->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('status', function ($row) {
                if($row->status == 1){
                    $activeCustom = '<button type="button" class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip" title="Status Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'0'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                } else {
                    $activeCustom = '<button type="button" class="btn btn-icon btn-sm btn-danger" data-bs-toggle="tooltip" title="Status Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'1'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                }
                return $activeCustom;
            })
            ->editColumn('action', function ($row) {
                $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".');"><i class="ki-outline ki-pencil fs-3"></i></button>';
                return $btnEdit;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_aplikasi' => 'required',
            'nama_level' => 'required|max:120',
        ],[
            'fid_aplikasi.required' => 'Aplikasi tidak boleh kosong.',
            'nama_level.required' => 'Nama level tidak boleh kosong.',
            'nama_level.max' => 'Nama level tidak lebih dari 120 karakter.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $data = [
                'fid_aplikasi' => $request->input('fid_aplikasi'),
                'nama_level' => $request->input('nama_level'),
                'access_key' => $this->generateAccesKey(),
                'user_add' => Auth::user()->nama_pegawai,
                'created_at' => Carbon::now()
            ];
            // create new level aplication
            LevelAplikasi::create($data);
            Shortcut::logActivities('Menambah Data Level Aplikasi');
            $output = ['status' => TRUE, 'message' => 'Sukses menambah data level aplikasi',];
        }
        return response()->json($output);
    }
    public function edit(Request $request)
    {
        $getRow = LevelAplikasi::whereId($request->idp)->first();
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
            'fid_aplikasi' => 'required',
            'nama_level' => 'required|max:120',
        ],[
            'fid_aplikasi.required' => 'Aplikasi tidak boleh kosong.',
            'nama_level.required' => 'Nama level tidak boleh kosong.',
            'nama_level.max' => 'Nama level tidak lebih dari 120 karakter.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $data = [
                'fid_aplikasi' => $request->input('fid_aplikasi'),
                'nama_level' => $request->input('nama_level'),
                'status' => isset($request->status) ? 1 : 0,
                'user_updated' => Auth::user()->nama_pegawai,
                'updated_at' => Carbon::now()
            ];
            // update level aplication
            LevelAplikasi::whereId($request->id)->update($data);
            Shortcut::logActivities('Melakukan Update Data Level Aplikasi');
            $output = ['status' => TRUE,'message' => 'Sukses memperbarui data level aplikasi',];
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
            LevelAplikasi::whereId($idp)->update($data);
            if($value==0) {
                $activities = 'Menonaktifkan Level Aplikasi';
                $textMsg = 'Status level aplikasi berhasil diubah menjadi <strong class="text-danger">Nonaktif</strong>';
            } else {
                $activities = 'Mengaktifkan Level Aplikasi';
                $textMsg = 'Status level aplikasi berhasil diubah menjadi <strong class="text-success">Aktif</strong>';
            }
            $output = array('status' => TRUE, 'message' => $textMsg);
        } catch (Exception $exception) {
            $output = array('status' => 401, 'message' => $exception->getMessage());
        }
        Shortcut::logActivities($activities);
        return response()->json($output);
    }
    private function generateAccesKey()
    {
        while (true) {
            $accessKey = bin2hex(random_bytes(10));
            $checkExist = LevelAplikasi::where('access_key', $accessKey)->first();
            // if not exist access key return the acces key
            if (empty($checkExist)) {
                return 'sisaka'.$accessKey;
                break;
            }
        }
    }
}