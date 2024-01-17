<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Apisik;
use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DataSdm;
use App\Models\InformasiWebsite;
use App\Models\SoapSik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class SikPegawaiHonorerController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Sistem Informasi Kepegawaian',
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
            'scripts/backend/admin/sik_pegawai_honorer.js'
        );
        Shortcut::logActivities('Mengakses Halaman Pegawai Honorer');
        return view('backend.admin.sik_pegawai_honorer', compact('data'));
    }
    public function data(Request $request)
    {
        $data = json_decode(SoapSik::whereId(1)->first()->pegawai_honorer);
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('tanggal_lahir', function ($row) {
                return date('d-m-Y', strtotime($row->tanggal_lahir));
            })
            ->rawColumns(['tanggal_lahir'])
            ->make(true);
    }
    public function sincronize()
    {
        date_default_timezone_set("Asia/Jakarta");
        try{  
            $dataPegawai = json_decode(SoapSik::whereId(1)->first()->pegawai_honorer);
            if(!empty($dataPegawai)) {   
                foreach($dataPegawai as $row){
                    DataSdm::updateOrCreate(
                    [
                        'nik' => $row->nik
                    ],
                    [
                        'kantor' => $row->kantor,
                        'sub_kantor' => $row->subkantor,
                        'unit' => $row->unit,
                        'jabatan_struktural' =>null,
                        'jabatan_fungsional' => $row->jabatan_fungsional,
                        'nik' => $row->nik,
                        'nip' => null,
                        'nama_pegawai' => $row->nama,
                        'alamat' => '-',
                        'telp' => substr($row->telepon, 0, 14),
                        'email' => $row->email,
                        'tempat_lahir' => $row->tempat_lahir,
                        'tanggal_lahir' => $row->tanggal_lahir,
                        'status_kepegawaian' => 2,
                        'password' => Hash::make('p@ssword'),
                        'foto' => $row->foto,
                        'user_add' => 'Yoga Setiawan',
                        'created_at' => Carbon::now()
                    ]);
                }
                $output = array("status" => TRUE);
            } else {
                $output = array("status" => FALSE, "pesan_error"=>['Data Pegawai tidak ditemukan...']);
            }			
            Shortcut::logActivities('Melakukan Sinkronisasi Data Pegawai Honorer');
        }catch(\GuzzleHttp\Exception\ConnectException $e){
            $output = array("status" => FALSE);
        } 
        return response()->json($output);
    }
}