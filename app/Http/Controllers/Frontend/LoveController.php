<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\LikePets;
use App\Models\Owners;
use App\Models\Pets;
use App\Models\SiteInfo;
use App\Models\SuperLike;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoveController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        $fl_category = null;
        $fl_breed = null;
        if(isset($_COOKIE['fl_category'])) {
            $fl_category = $_COOKIE['fl_category'];
        }
        if(isset($_COOKIE['fl_breed'])) {
            $fl_breed = $_COOKIE['fl_breed'];
        }
        //Data WebInfo
        $data = array(
            'title' => 'Love',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession,
            'fl_category' => $fl_category,
            'fl_breed' => $fl_breed,
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/assets/vendor/swiper/swiper-bundle.min.js',
            'dist/assets/js/tinderSwiper.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/assets/js/dz.carousel.js',
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/love.init.js',
        );
        return view('frontend.love', compact('data'));
    }
    public function dataFilter(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;
        $distance = Auth::guard('owner')->user()->distance;
        $fl_category = null;
        $fl_breed = null;
        if($distance == null){
            $distance = 40;
        }
        if(isset($_COOKIE['fl_category'])) {
            $fl_category = $_COOKIE['fl_category'];
        }
        if(isset($_COOKIE['fl_breed'])) {
            $fl_breed = $_COOKIE['fl_breed'];
        }
        $data = [
            'distance' => $distance,
            'fl_category' => $fl_category,
            'fl_breed' => $fl_breed,
        ];
        return Shortcut::jsonResponse(true, 'Success', 200, $data);
    }
    public function saveFilter(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        try {
            $fl_category = '';
            if($request->category) {
                $fl_category = implode(", ", $request->category);
            }
            $fl_breed = '';
            if($request->breed) {
                $fl_breed = implode(", ", $request->breed);
            }
            $expiration_time = time() + (10 * 365 * 24 * 60 * 60);
            setcookie('fl_category', $fl_category, $expiration_time, '/');
            setcookie('fl_breed', $fl_breed, $expiration_time, '/');
            return Shortcut::jsonResponse(true, 'Success', 200);
        } catch (Exception $exception) {
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function petsPupuler(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $maxDistance = Auth::guard('owner')->user()->distance;  
        $lat =Auth::guard('owner')->user()->lat;
        $lng =Auth::guard('owner')->user()->lng;  
        
        $query =  Pets::select(
            'pets.*',
            'owners.id as fid_owner',
            'owners.name',
            'owners.lat',
            'owners.lng',
            DB::raw('COUNT(like_pets.fid_pets) as count')
        )
        ->join('owners', 'owners.id', '=', 'pets.fid_owner')
        ->join('like_pets', 'like_pets.fid_pets', '=', 'pets.id')
        ->orderByDesc('count')
        ->whereNot('owners.id', $iduserSesIdp)
        ->where('owners.is_active', 'Y');        
        if(isset($_COOKIE['fl_category'])) {
            $query = $query->whereIn('pets.category', explode(', ', $_COOKIE['fl_category']));
        }
        if(isset($_COOKIE['fl_breed'])) {
            $query = $query->whereIn('pets.breed', explode(', ', $_COOKIE['fl_breed']));
        }
        $getRow = $query->limit(10)->get();
        $dataPopuler = [];
        foreach ($getRow as $item) {
            $distance = $this->haversineDistance($lat, $lng, $item->lat, $item->lng);
            if ($distance <= $maxDistance) {
                $image_url = asset('dist/img/default-user-img.jpg');
                if($item->image1 != null){
                    $image_url = $this->_checkExistImage($item->image1);
                }else if($item->image2 != null){
                    $image_url = $this->_checkExistImage($item->image2);
                }else if($item->image3 != null){
                    $image_url = $this->_checkExistImage($item->image3);
                }else if($item->image4 != null){
                    $image_url = $this->_checkExistImage($item->image4);
                }else if($item->image5 != null){
                    $image_url = $this->_checkExistImage($item->image5);
                }else if($item->image6 != null){
                    $image_url = $this->_checkExistImage($item->image6);
                }
                $statusLike = LikePets::whereFidPets($item->id)->whereLikeBy($iduserSesIdp)->first();
                $dataPopuler[] = [
                    'id' => $item->id,
                    'fid_owner' => $item->fid_owner,
                    'category' => $item->category,
                    'breed' => $item->breed,
                    'name' => $item->name,
                    'distance' => number_format($distance, 2),
                    'image_url' => $image_url,
                    'has_like' => ($statusLike == null)? 'N' : 'Y'
                ];
            }
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $dataPopuler);
    }
    public function petsAll(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  
        $maxDistance = Auth::guard('owner')->user()->distance;  
        $lat =Auth::guard('owner')->user()->lat;
        $lng =Auth::guard('owner')->user()->lng;  
        
        $query =  Pets::select(
            'pets.*',
            'owners.id as fid_owner',
            'owners.name',
            'owners.lat',
            'owners.lng',
        )
        ->join('owners', 'owners.id', '=', 'pets.fid_owner')
        ->whereNot('owners.id', $iduserSesIdp)
        ->orderByDesc('pets.id')
        ->where('owners.is_active', 'Y');        
        if(isset($_COOKIE['fl_category'])) {
            $query = $query->whereIn('pets.category', explode(', ', $_COOKIE['fl_category']));
        }
        if(isset($_COOKIE['fl_breed'])) {
            $query = $query->whereIn('pets.breed', explode(', ', $_COOKIE['fl_breed']));
        }
        $getRow = $query->limit(10)->get();
        $dataPopuler = [];
        foreach ($getRow as $item) {
            $distance = $this->haversineDistance($lat, $lng, $item->lat, $item->lng);
            if ($distance <= $maxDistance) {
                $image_url = asset('dist/img/default-user-img.jpg');
                if($item->image1 != null){
                    $image_url = $this->_checkExistImage($item->image1);
                }else if($item->image2 != null){
                    $image_url = $this->_checkExistImage($item->image2);
                }else if($item->image3 != null){
                    $image_url = $this->_checkExistImage($item->image3);
                }else if($item->image4 != null){
                    $image_url = $this->_checkExistImage($item->image4);
                }else if($item->image5 != null){
                    $image_url = $this->_checkExistImage($item->image5);
                }else if($item->image6 != null){
                    $image_url = $this->_checkExistImage($item->image6);
                }
                $statusLike = LikePets::whereFidPets($item->id)->whereLikeBy($iduserSesIdp)->first();
                $dataPopuler[] = [
                    'id' => $item->id,
                    'fid_owner' => $item->fid_owner,
                    'category' => $item->category,
                    'breed' => $item->breed,
                    'name' => $item->name,
                    'distance' => number_format($distance, 2),
                    'image_url' => $image_url,
                    'has_like' => ($statusLike == null)? 'N' : 'Y'
                ];
            }
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $dataPopuler);
    }

    private function _checkExistImage($file_name){
        if($file_name==''){
            $image_url = asset('dist/img/default-user-img.jpg');
        } else {
            if (!file_exists(public_path(). '/dist/img/pets-img/'.$file_name)){
                $image_url = asset('dist/img/default-user-img.jpg');
            }else{
                $image_url = url('dist/img/pets-img/'.$file_name);
            }
        }
        return $image_url;
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
