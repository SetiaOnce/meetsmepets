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
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PengelolaPortalController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Pengelola Portal',
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
            'dist/scripts/backend/main.init.js',
            'scripts/backend/admin/pengelola_portal.js'
        );
        Shortcut::logActivities('Mengakses Halaman Pengelola Portal');
        return view('backend.admin.pengelola_portal', compact('data'));
    }
    public function data(Request $request)
    {
        $data = DataSdm::whereIsAdmin(1)->whereStatus(1)->get();
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
            ->editColumn('action', function ($row) {
                $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".');"><i class="ki-outline ki-pencil fs-3"></i></button>';
                $btnDelete = '';
                $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteData('."'".$row->id."'".');"><i class="ki-outline ki-trash fs-3"></i></button>';
                return $btnDelete;
            })
            ->rawColumns(['foto','action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_pegawai' => 'required',
        ],[
            'fid_pegawai.required' => 'Pegawai tidak boleh kosong.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            DataSdm::whereId($request->fid_pegawai)->update([
                'is_admin' => 1,
                'user_add' => Auth::user()->nama_pegawai,
                'created_at' => Carbon::now()
            ]);
            Shortcut::logActivities('Melakukan Penambahan Data Pengelola Portal');
            $output = array("status" => TRUE, 'message' => 'Berhasil menambahkan data pengelola baru');
        }
        return response()->json($output);
    }
    public function destroy(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        DataSdm::whereId($request->idp)->update([
            'is_admin' => 0,
            'user_add' => Auth::user()->nama_pegawai,
            'created_at' => Carbon::now()
        ]);
        Shortcut::logActivities('Melakukan Penghapusan Data Pengelola Portal');
        return response()->json(["status" => TRUE, 'message' => 'Berhasil menghapus data pengelola']);
    }
}