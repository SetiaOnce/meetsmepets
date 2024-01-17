<?php

namespace App\Http\Controllers\Backend\Pegawai;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\AksesSso;
use App\Models\Aplikasi;
use App\Models\DataSdm;
use App\Models\InformasiWebsite;
use App\Models\LogLoginAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
            'title' => 'Dashboard Pegawai',
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
            'https://code.highcharts.com/highcharts.js',
            'https://code.highcharts.com/modules/exporting.js',
            'https://code.highcharts.com/modules/export-data.js',
            'https://code.highcharts.com/modules/accessibility.js',
            'dist/js/app.backend.init.js',
            'scripts/backend/pegawai/dashboard.js'
        );
        return view('backend.pegawai.index', compact('data'));
    }
    public function statistikLogin()
    {
        $dtLogin = LogLoginAplikasi::join('aplikasi', 'log_login_aplikasi.fid_aplikasi', '=', 'aplikasi.id')
        ->join('data_sdm', 'log_login_aplikasi.fid_pegawai', '=', 'data_sdm.id')
        ->where('data_sdm.id', '=', session()->get('id'))
        ->groupBy('aplikasi.nama_aplikasi')
        ->select('aplikasi.nama_aplikasi', DB::raw('COUNT(log_login_aplikasi.id) AS jumlah_login'))
        ->get();
        $response = []; 
        foreach ($dtLogin as $row) {
            $response[] = [
                'name' => $row->nama_aplikasi,
                'y' => intval($row->jumlah_login)
            ];
        }
        return response()->json($response);
    }
    public function application()
    {
        $getRow = AksesSso::select(
            'aplikasi.*',
            'level_aplikasi.nama_level'
        )
        ->join('aplikasi', 'aplikasi.id', '=', 'akses_sso.fid_aplikasi')
        ->join('level_aplikasi', 'level_aplikasi.id', '=', 'akses_sso.fid_level_aplikasi')
        ->where('akses_sso.fid_pegawai', session()->get('id'))
        ->orderBy('aplikasi.id', 'ASC')
        ->get();
        $response = array(
            'status' => TRUE,
            'row' => $getRow
        );
        return response()->json($response);
    }
}
