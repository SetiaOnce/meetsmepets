<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\LikePets;
use App\Models\Owners;
use App\Models\Pets;
use App\Models\PetsAlbum;
use App\Models\SiteInfo;
use App\Models\Subscribers;
use App\Models\SuperLike;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileOwnerController extends Controller
{
    public function index()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        $statusSub = Subscribers::whereEmail($getSiteInfo->email)->first();
        //Data WebInfo
        $data = array(
            'title' => 'Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession,
            'subscribe' => ($statusSub == null)? 'Y' : 'N',
            'superLike' => SuperLike::whereFidOwner($getUserSession->id)->count(),
        );
        //Data Source CSS
        $data['css'] = array(
        );
        //Data Source JS
        $data['js'] = array(
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/profile.init.js',
        );
        return view('frontend.profile', compact('data'));
    }
    public function settingPage()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
            'dist/plugins/toastr/toastr.min.css',
            'dist/plugins/sweetalert2/sweetalert2.min.css',
            'dist/plugins/leaflet/leaflet.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
            'dist/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/plugins/toastr/toastr.min.js',
            'dist/plugins/sweetalert2/sweetalert2.min.js',
            'dist/plugins/leaflet/leaflet.js',
            'dist/plugins/leaflet/leaflet-ajax-gh-pages/dist/leaflet.ajax.min.js',
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/profile_setting.init.js',
        );
        return view('frontend.profile_setting', compact('data'));
    }
    public function editPage()
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('owner')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
            'dist/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/assets/vendor/nouislider/nouislider.min.css',
            'dist/assets/vendor/swiper/swiper-bundle.min.css',
            'dist/plugins/toastr/toastr.min.css',
            'dist/plugins/dropify-master/css/dropify.min.css',
            'dist/plugins/sweetalert2/sweetalert2.min.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
            'dist/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/assets/vendor/wnumb/wNumb.js',
            'dist/assets/vendor/nouislider/nouislider.min.js',
            'dist/assets/js/noui-slider.init.js',
            'dist/plugins/toastr/toastr.min.js',
            'dist/plugins/sweetalert2/sweetalert2.min.js',
            'dist/plugins/dropify-master/js/dropify.min.js',
            'scripts/frontend/siteinfo.init.js',
            'scripts/frontend/profile_edit.init.js',
        );
        return view('frontend.profile_edit', compact('data'));
    }
    public function updateSubscribe(Request $request)
    {
        $user = Auth::guard('owner')->user();  
        DB::beginTransaction();
        try {
            if ($request->sub_status == 'Y') {
                $statusSub = Subscribers::whereEmail($user->email)->first();
                if(empty($statusSub)){
                    Subscribers::insertGetId([
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => Carbon::now(),
                    ]);
                }
            }else{
                Subscribers::whereEmail($user->email)->delete();
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Subscribe or unsubscribe successfully', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveUsername(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            $cekUser = Owners::where('username', $request->username)->where('id', '!=' , $userSesIdp)->first();
            if($cekUser==true) {
                return Shortcut::jsonResponse(false, 'Failed to update data, the same username already exists in the system. Try using a different username', 200, array('error_code' => 'username'));
            } else {
                Owners::whereId($userSesIdp)->update([
                    'username' => $request->username,
                    'updated_at' => Carbon::now(),
                ]);
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Username updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function savePhone(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            $cekUser = Owners::where('phone_number', $request->phone_number)->where('id', '!=' , $userSesIdp)->first();
            if($cekUser==true) {
                return Shortcut::jsonResponse(false, 'Failed to update data, the same phone number already exists in the system. Try using a different phone number', 200, array('error_code' => 'phone_number'));
            } else {
                Owners::whereId($userSesIdp)->update([
                    'phone_number' => $request->phone_number,
                    'updated_at' => Carbon::now(),
                ]);
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Phone number updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveEmail(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            $cekUser = Owners::where('email', $request->email)->where('id', '!=' , $userSesIdp)->first();
            if($cekUser==true) {
                return Shortcut::jsonResponse(false, 'Failed to update data, the same email already exists in the system. Try using a different email', 200, array('error_code' => 'email'));
            } else {
                Owners::whereId($userSesIdp)->update([
                    'email' => $request->email,
                    'updated_at' => Carbon::now(),
                ]);
            }
            DB::commit();
            return Shortcut::jsonResponse(true, 'Email updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveLocation(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            Owners::whereId($userSesIdp)->update([
                'location' => $request->location,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Location updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveGender(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            Owners::whereId($userSesIdp)->update([
                'gender' => $request->gender,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Location updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveDistance(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            Owners::whereId($userSesIdp)->update([
                'distance' => $request->distance,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Distance updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveLookingFor(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            $lookingFor = null;
            if($request->looking_for){
                $lookingFor = implode(', ', $request->looking_for);
            }
            Owners::whereId($userSesIdp)->update([
                'looking_for' => $lookingFor,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Looking for updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }

    public function loadPets()
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        $getRow = Pets::whereFidOwner($userSesIdp)->orderByDesc('id')->get();
        $data = [];
        foreach($getRow as $row){
            $image_url = asset('dist/img/drop-bx.png');
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
                'category' => $row->category,
                'breed' => $row->breed,
                'image_url' =>  $image_url,
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $data);
    }
    public function saveFullName(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            Owners::whereId($userSesIdp)->update([
                'name' => $request->name,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Name updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    public function saveFotoProfiles(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        $errors					= [];
        DB::beginTransaction();
        $validator = Validator::make($request->all(), [
            'foto_profiles' => 'mimes:png,jpg,jpeg|max:2048',
        ],[
            'foto_profiles.max' => 'Max file is 2MB.',
            'foto_profiles.mimes' => 'File extension is jpg jepg png.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            return Shortcut::jsonResponse(false, 'Validation errror', 200, $errors);
        }else{
            try {
                $destinationPath = public_path('/dist/img/users-img');
                $data = [
                    'updated_at' => Carbon::now()
                ];
                //Foto profiles
                if(!empty($_FILES['foto_profiles']['name'])){
                    $owners = Owners::whereId($userSesIdp)->first();
                    if($owners==true) {
                        $get_imageprofiles = $destinationPath.'/'.$owners->thumb;
                        if(file_exists($get_imageprofiles) && $owners->thumb)
                            unlink($get_imageprofiles);
                    }
                    $doUploadFile = $this->_doUploadFileImages($request->file('foto_profiles'), $destinationPath, 'fotoprofiles');
                    $data['thumb'] = $doUploadFile['file_name'];
                }
                Owners::whereId($userSesIdp)->update($data);
                DB::commit();
                return Shortcut::jsonResponse(true, 'Success', 200, $data);
            } catch (Exception $exception) {
                DB::rollBack();
                return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        }
    }
    public function saveInterest(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        DB::beginTransaction();
        try {
            $interest = null;
            if($request->interest){
                $interest = implode(', ', $request->interest);
            }
            Owners::whereId($userSesIdp)->update([
                'interest' => $interest,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return Shortcut::jsonResponse(true, 'Interest for updated successfuly', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
        
    public function savePets(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        $errors					= [];
        DB::beginTransaction();
        $validator = Validator::make($request->all(), [
            'pet_name' => 'required|max:120',
            'category' => 'required',
            'breed' => 'required|max:100',
            'imageUpload' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload2' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload3' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload4' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload5' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload6' => 'mimes:png,jpg,jpeg|max:2048',
        ],[
            'imageUpload.max' => 'Max file is 2MB.',
            'imageUpload.mimes' => 'File extension is jpg jepg png.',
            'imageUpload2.max' => 'Max file is 2MB.',
            'imageUpload2.mimes' => 'File extension is jpg jepg png.',
            'imageUpload3.max' => 'Max file is 2MB.',
            'imageUpload3.mimes' => 'File extension is jpg jepg png.',
            'imageUpload4.max' => 'Max file is 2MB.',
            'imageUpload4.mimes' => 'File extension is jpg jepg png.',
            'imageUpload5.max' => 'Max file is 2MB.',
            'imageUpload5.mimes' => 'File extension is jpg jepg png.',
            'imageUpload6.max' => 'Max file is 2MB.',
            'imageUpload6.mimes' => 'File extension is jpg jepg png.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            return Shortcut::jsonResponse(false, 'Validation errror', 200, $errors);
        }else{
            try {
                $destinationPath = public_path('/dist/img/pets-img');
                $data = [
                    'pet_name' => strtoupper($request->pet_name),
                    'category' => $request->category,
                    'breed' => strtoupper($request->breed),
                    'fid_owner' => $userSesIdp,
                    'created_at' => Carbon::now()
                ];
                if(!empty($_FILES['imageUpload']['name']) ||
                    !empty($_FILES['imageUpload2']['name']) ||
                    !empty($_FILES['imageUpload3']['name']) ||
                    !empty($_FILES['imageUpload4']['name']) ||
                    !empty($_FILES['imageUpload5']['name']) ||
                    !empty($_FILES['imageUpload6']['name'])
                ) {             
                    //Image 1
                    if(!empty($_FILES['imageUpload']['name'])){
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload'), $destinationPath, 'imageUpload');
                        $data['image1'] = $doUploadFile['file_name'];
                    }
                    //Image 2
                    if(!empty($_FILES['imageUpload2']['name'])){
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload2'), $destinationPath, 'imageUpload2');
                        $data['image2'] = $doUploadFile['file_name'];
                    }
                    //Image 3
                    if(!empty($_FILES['imageUpload3']['name'])){
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload3'), $destinationPath, 'imageUpload3');
                        $data['image3'] = $doUploadFile['file_name'];
                    }
                    //Image 4
                    if(!empty($_FILES['imageUpload4']['name'])){
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload4'), $destinationPath, 'imageUpload4');
                        $data['image4'] = $doUploadFile['file_name'];
                    }
                    //Image 5
                    if(!empty($_FILES['imageUpload5']['name'])){
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload5'), $destinationPath, 'imageUpload5');
                        $data['image5'] = $doUploadFile['file_name'];
                    }
                    //Image 6
                    if(!empty($_FILES['imageUpload6']['name'])){
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload6'), $destinationPath, 'imageUpload6');
                        $data['image6'] = $doUploadFile['file_name'];
                    }
                }
                $insertPets = Pets::insertGetId($data);
                DB::commit();
                return Shortcut::jsonResponse(true, 'Success', 200, $data);
            } catch (Exception $exception) {
                DB::rollBack();
                return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        }
    }
    public function editPets(Request $request)
    {
        $idp = $request->idp;
        $getRow = Pets::whereId($idp)->first();
        $getRow->image1_url = $this->_checkExistImage($getRow->image1);
        $getRow->image2_url = $this->_checkExistImage($getRow->image2);
        $getRow->image3_url = $this->_checkExistImage($getRow->image3);
        $getRow->image4_url = $this->_checkExistImage($getRow->image4);
        $getRow->image5_url = $this->_checkExistImage($getRow->image5);
        $getRow->image6_url = $this->_checkExistImage($getRow->image6);
        return Shortcut::jsonResponse(true, 'Success', 200, $getRow);
    }
    public function updatePets(Request $request)
    {
        $userSesIdp = Auth::guard('owner')->user()->id;
        $idp = $request->id;
        $errors					= [];
        DB::beginTransaction();
        $validator = Validator::make($request->all(), [
            'pet_name' => 'required|max:120',
            'category' => 'required',
            'breed' => 'required|max:100',
            'imageUpload' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload2' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload3' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload4' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload5' => 'mimes:png,jpg,jpeg|max:2048',
            'imageUpload6' => 'mimes:png,jpg,jpeg|max:2048',
        ],[
            'imageUpload.max' => 'Max file is 2MB.',
            'imageUpload.mimes' => 'File extension is jpg jepg png.',
            'imageUpload2.max' => 'Max file is 2MB.',
            'imageUpload2.mimes' => 'File extension is jpg jepg png.',
            'imageUpload3.max' => 'Max file is 2MB.',
            'imageUpload3.mimes' => 'File extension is jpg jepg png.',
            'imageUpload4.max' => 'Max file is 2MB.',
            'imageUpload4.mimes' => 'File extension is jpg jepg png.',
            'imageUpload5.max' => 'Max file is 2MB.',
            'imageUpload5.mimes' => 'File extension is jpg jepg png.',
            'imageUpload6.max' => 'Max file is 2MB.',
            'imageUpload6.mimes' => 'File extension is jpg jepg png.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            return Shortcut::jsonResponse(false, 'Validation errror', 200, $errors);
        }else{
            $getFile = Pets::whereId($idp)->first();
            try {
                $destinationPath = public_path('/dist/img/pets-img');
                $data = [
                    'pet_name' => strtoupper($request->pet_name),
                    'category' => $request->category,
                    'breed' => strtoupper($request->breed),
                    'updated_at' => Carbon::now()
                ];
                if(!empty($_FILES['imageUpload']['name']) ||
                    !empty($_FILES['imageUpload2']['name']) ||
                    !empty($_FILES['imageUpload3']['name']) ||
                    !empty($_FILES['imageUpload4']['name']) ||
                    !empty($_FILES['imageUpload5']['name']) ||
                    !empty($_FILES['imageUpload6']['name'])
                ) {             
                    //Image 1
                    if(!empty($_FILES['imageUpload']['name'])){
                        if($getFile==true) {
                            $get_image1 = $destinationPath.'/'.$getFile->image1;
                            if(file_exists($get_image1) && $getFile->image1)
                                unlink($get_image1);
                        }
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload'), $destinationPath, 'imageUpload');
                        $data['image1'] = $doUploadFile['file_name'];
                    }
                    //Image 2
                    if(!empty($_FILES['imageUpload2']['name'])){
                        if($getFile==true) {
                            $get_image2 = $destinationPath.'/'.$getFile->image2;
                            if(file_exists($get_image2) && $getFile->image2)
                                unlink($get_image2);
                        }
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload2'), $destinationPath, 'imageUpload2');
                        $data['image2'] = $doUploadFile['file_name'];
                    }
                    //Image 3
                    if(!empty($_FILES['imageUpload3']['name'])){
                        if($getFile==true) {
                            $get_image3 = $destinationPath.'/'.$getFile->image3;
                            if(file_exists($get_image3) && $getFile->image3)
                                unlink($get_image3);
                        }
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload3'), $destinationPath, 'imageUpload3');
                        $data['image3'] = $doUploadFile['file_name'];
                    }
                    //Image 4
                    if(!empty($_FILES['imageUpload4']['name'])){
                        if($getFile==true) {
                            $get_image4 = $destinationPath.'/'.$getFile->image4;
                            if(file_exists($get_image4) && $getFile->image4)
                                unlink($get_image4);
                        }
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload4'), $destinationPath, 'imageUpload4');
                        $data['image4'] = $doUploadFile['file_name'];
                    }
                    //Image 5
                    if(!empty($_FILES['imageUpload5']['name'])){
                        if($getFile==true) {
                            $get_image5 = $destinationPath.'/'.$getFile->image5;
                            if(file_exists($get_image5) && $getFile->image5)
                                unlink($get_image5);
                        }
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload5'), $destinationPath, 'imageUpload5');
                        $data['image5'] = $doUploadFile['file_name'];
                    }
                    //Image 6
                    if(!empty($_FILES['imageUpload6']['name'])){
                        if($getFile==true) {
                            $get_image6 = $destinationPath.'/'.$getFile->image6;
                            if(file_exists($get_image6) && $getFile->image6)
                                unlink($get_image6);
                        }
                        $doUploadFile = $this->_doUploadFileImages($request->file('imageUpload6'), $destinationPath, 'imageUpload6');
                        $data['image6'] = $doUploadFile['file_name'];
                    }
                }
                Pets::whereId($idp)->update($data);
                DB::commit();
                return Shortcut::jsonResponse(true, 'Update pets succeed', 200, $data);
            } catch (Exception $exception) {
                DB::rollBack();
                return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        }
    }
    public function deletePets(Request $request)
    {
        $idp = $request->idp;
        $getRow = Pets::whereId($idp)->first();
        $destinationPath = public_path('/dist/img/pets-img');
        if($getRow==true) {
            $get_image1 = $destinationPath.'/'.$getRow->image1;
            if(file_exists($get_image1) && $getRow->image1)
                unlink($get_image1);
            $get_image2 = $destinationPath.'/'.$getRow->image2;
            if(file_exists($get_image2) && $getRow->image2)
                unlink($get_image2);
            $get_image3 = $destinationPath.'/'.$getRow->image3;
            if(file_exists($get_image3) && $getRow->image3)
                unlink($get_image3);
            $get_image4 = $destinationPath.'/'.$getRow->image4;
            if(file_exists($get_image4) && $getRow->image4)
                unlink($get_image4);
            $get_image5 = $destinationPath.'/'.$getRow->image5;
            if(file_exists($get_image5) && $getRow->image5)
                unlink($get_image5);
            $get_image6 = $destinationPath.'/'.$getRow->image6;
            if(file_exists($get_image6) && $getRow->image6)
                unlink($get_image6);
        }
        LikePets::whereFidPets($idp)->delete();
        Pets::whereId($idp)->delete();
        return Shortcut::jsonResponse(true, 'Delete pet successfully', 200);
    }

    
    /**
     * _doUploadFileImages
     *
     * @param  mixed $fileInput
     * @param  mixed $destinationPath
     * @param  mixed $nameInput
     * @return void
     */
    private function _doUploadFileImages($fileInput, $destinationPath, $nameInput) {
        try {
            $fileExtension = $fileInput->getClientOriginalExtension();
            $fileOriginName = $fileInput->getClientOriginalName();
            $fileNewName = strtolower(Str::slug($nameInput.bcrypt(pathinfo($fileOriginName, PATHINFO_FILENAME)))) . time();
            $fileNewNameExt = $fileNewName . '.' . $fileExtension;
            // $fileInput->move($destinationPath, $fileNewNameExt);
            $compressedImage = Image::make($fileInput)
                ->save($destinationPath . '/' . $fileNewNameExt);
            return [
                'file_name' => $fileNewNameExt
            ];
        } catch (Exception $exception) {
            return [
                "Message" => $exception->getMessage(),
                "Trace" => $exception->getTrace()
            ];
        }
    }
    private function _checkExistImage($file_name){
        if($file_name==''){
            $image_url = asset('dist/img/drop-bx.png');
        } else {
            if (!file_exists(public_path(). '/dist/img/pets-img/'.$file_name)){
                $image_url = asset('dist/img/drop-bx.png');
            }else{
                $image_url = url('dist/img/pets-img/'.$file_name);
            }
        }
        return $image_url;
    }
}
