<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\LikePets;
use App\Models\Pets;
use App\Models\SiteInfo;
use App\Models\SuperLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExploreController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Explore',
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
            'scripts/frontend/explore.init.js',
        );
        return view('frontend.explore', compact('data'));
    }
    public function data(Request $request)
    {
        $iduserSesIdp = Auth::guard('owner')->user()->id;  

        $getRow = Pets::select(
            'pets.id',
            'pets.pet_name',
            'pets.category',
            'pets.breed',
            'pets.image1',
            'pets.image2',
            'pets.image3',
            'pets.image4',
            'pets.image5',
            'pets.image6',
            'owners.name',
        )
        ->join('owners', 'owners.id', '=', 'pets.fid_owner')
        ->whereNot('owners.id', $iduserSesIdp)
        ->inRandomOrder()
        ->paginate(10);
        $data = [];
        foreach($getRow as $row){
            $image_url = asset('dist/img/default-user-img.jpg');
            if($row->image1 != null){
                $image_url = $this->_checkExistImage($row->image1);
            }else if($row->image2 != null){
                $image_url = $this->_checkExistImage($row->image2);
            }else if($row->image3 != null){
                $image_url = $this->_checkExistImage($row->image3);
            }else if($row->image4 != null){
                $image_url = $this->_checkExistImage($row->image4);
            }else if($row->image5 != null){
                $image_url = $this->_checkExistImage($row->image5);
            }else if($row->image6 != null){
                $image_url = $this->_checkExistImage($row->image6);
            }
            $data[] = [
                'id' => $row->id,
                'pet_name' => $row->pet_name,
                'category' => $row->category,
                'breed' => $row->breed,
                'name' => $row->name,
                'image_url' => $image_url,
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $data);
    }
    public function viewPets($fid_pets)
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        $getRow = Pets::select(
            'pets.*',
            'owners.id as fid_owner',
            'owners.name',
            'owners.location',
            'owners.interest',
            'owners.thumb',
        )
        ->join('owners', 'owners.id', '=', 'pets.fid_owner')
        ->where('pets.id', $fid_pets)
        ->first();
        $file_name = $getRow->thumb;
        if($file_name==''){
            $getRow->thumb_url = asset('dist/img/default-user-img.jpg');
        } else {
            if (!file_exists(public_path(). '/dist/img/users-img/'.$file_name)){
                $getRow->thumb_url = asset('dist/img/default-user-img.jpg');
            }else{
                $getRow->thumb_url = url('dist/img/users-img/'.$file_name);
            }
        }
        $getImage = [];
        for ($i=1; $i < 7; $i++) { 
            $image = 'image'.$i;
            if($getRow->$image != null){
                $getImage[] = $this->_checkExistImage($getRow->$image);
            }
        }
        $getRow->interest = explode(',', $getRow->interest);
        $followStatus = SuperLike::whereFidOwner($getRow->fid_owner)->whereLikeBy($getUserSession->id)->first();
        $likeStatus = LikePets::whereFidPets($getRow->id)->whereLikeBy($getUserSession->id)->get();
        //Data WebInfo
        $data = array(
            'title' => 'Explore View',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession,
            'getRow' => $getRow,
            'getImage' => $getImage,
            'followStatus' => $followStatus,
            'likeStatus' => $likeStatus,
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
            'dist/assets/vendor/lightgallery/dist/css/lightgallery.css',
            'dist/assets/vendor/lightgallery/dist/css/lg-thumbnail.css',
            'dist/assets/vendor/lightgallery/dist/css/lg-zoom.css',
            '/dist/plugins/Magnific-Popup/magnific-popup.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/swiper/swiper-bundle.min.js',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
            'dist/assets/js/tinderSwiper.min.js',
            'dist/assets/vendor/lightgallery/dist/lightgallery.umd.js',
            'dist/assets/vendor/lightgallery/dist/plugins/thumbnail/lg-thumbnail.umd.js',
            'dist/assets/vendor/lightgallery/dist/plugins/zoom/lg-zoom.umd.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/assets/js/dz.carousel.js',
            '/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/view_pets.init.js',
        );
        return view('frontend.view_pets', compact('data'));
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
}
