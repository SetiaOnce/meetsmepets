<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Events\ApiSoapsik;
use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DataSdm;
use App\Models\InformasiWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class DashboardController extends Controller
{
    // protected $pusher;

    // public function __construct()
    // {
    //     $this->pusher = new Pusher(
    //         config('broadcasting.connections.pusher.key'),
    //         config('broadcasting.connections.pusher.secret'),
    //         config('broadcasting.connections.pusher.app_id'),
    //         config('broadcasting.connections.pusher.options')
    //     );
    // }
    public function index()
    {
        event(new ApiSoapsik('Someone'));
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Dashboard',
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
            'dist/js/app.backend.init.js',
            'https://js.pusher.com/8.2.0/pusher.min.js',
            'scripts/backend/admin/dashboard.js'
        );
        Shortcut::logActivities('Mengakses Halaman Dashboard');

        // Broadcast the new message to Pusher channel
        // $this->pusher->trigger('my-channel', 'my-event', [
        //     'message' => 'I Gede Yoga Setiawan',
        // ]);

        return view('backend.admin.index', compact('data'));
    }
    public function UserProfile()
    {
        $counter = [
            'pegawai_asn' => DataSdm::whereStatusKepegawaian(3)->whereStatus(1)->count(),
            'pegawai_honorer' => DataSdm::whereIn('status_kepegawaian', [1,2])->whereStatus(1)->count(),
            'pegawai_pengelola' => DataSdm::whereIsAdmin(1)->whereStatus(1)->count(),
        ];
        $response = array(
            'userProfle' => Auth::user(),
            'counter' => $counter
        );
        return response()->json($response);
    }
}
