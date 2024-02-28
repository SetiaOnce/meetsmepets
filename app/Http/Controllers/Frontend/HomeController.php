<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Owners;
use App\Models\Pets;
use App\Models\SiteInfo;
use App\Models\SuperLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Home',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/swiper/swiper-bundle.min.js',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
            'dist/assets/js/tinderSwiper.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/assets/js/dz.carousel.js',
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/home.init.js',
        );
        return view('frontend.index', compact('data'));
    }
    public function nearOwner(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $maxDistance = Auth::guard('owner')->user()->distance;  
        if($request->lat AND $request->lat){
            $lat = $request->lat;
            $lng = $request->lng;  
        }else{
            $lat =Auth::guard('owner')->user()->lat;
            $lng =Auth::guard('owner')->user()->lng;  
        }
        // $getRow =  Owners::whereNot('id', $iduserSesIdp)->whereIsActive('Y')->get();
        $getRow = Pets::select(
            'pets.*',
            'owners.id as fid_owner',
            'owners.lat',
            'owners.lng',
        )
        ->join('owners', 'owners.id', '=', 'pets.fid_owner')
        ->where('owners.is_active', 'Y')
        ->get();
        $dataNearby = [];
        foreach ($getRow as $item) {
            $distance = $this->haversineDistance($lat, $lng, $item->lat, $item->lng);
            if ($distance <= $maxDistance) {
                $file_name = $item->image1;
                if($file_name==''){
                    $thumb_url = asset('dist/img/default-user-img.jpg');
                } else {
                    if (!file_exists(public_path(). '/dist/img/pets-img/'.$file_name)){
                        $thumb_url = asset('dist/img/default-user-img.jpg');
                    }else{
                        $thumb_url = url('dist/img/pets-img/'.$file_name);
                    }
                }
                $statusLike = SuperLike::whereFidOwner($item->fid_owner)->whereLikeBy($iduserSesIdp)->first();
                $dataNearby[] = [
                    'id' => $item->fid_owner,
                    'name' => $item->category,
                    'breed' => $item->breed,
                    'distance' => number_format($distance, 2),
                    'thumb_url' => $thumb_url,
                    'has_like' => ($statusLike == null)? 'N' : 'Y'
                ];
            }
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $dataNearby);
    }
    // Fungsi untuk menghitung jarak antara dua titik pada bola bumi menggunakan formula Haversine
    protected function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // radius of the Earth in kilometers

        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Distance in kilometers

        return $distance;
    }
}
