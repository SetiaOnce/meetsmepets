<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Apisik;
use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\InformasiWebsite;
use App\Models\SoapSik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SikDataKantorController extends Controller
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
            'scripts/backend/admin/sik_datakantor.js'
        );
        Shortcut::logActivities('Mengakses Halaman Data Kantor SIK');
        return view('backend.admin.sik_datakantor', compact('data'));
    }
    public function data(Request $request)
    {
        $data = json_decode(SoapSik::whereId(1)->first()->data_kantor);
        return Datatables::of($data)->addIndexColumn()
            ->rawColumns([])
            ->make(true);
    }
}
