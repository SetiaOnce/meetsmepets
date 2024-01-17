<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Helpers\Shortcut;
use App\Models\HeadBanner;
use App\Models\InformasiWebsite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HeadBannerController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Head Banner',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/plugins/summernote/summernote-lite.min.css',
            'dist/plugins/dropify-master/css/dropify.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css'
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/plugins/summernote/summernote-lite.min.js',
            'dist/plugins/summernote/lang/summernote-id-ID.min.js',
            'dist/plugins/dropify-master/js/dropify.min.js',
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            'dist/js/app.backend.init.js',

            'dist/scripts/backend/main.init.js',
            'scripts/backend/admin/head_banner.js'
        );
        Shortcut::logActivities('Mengakses Halaman Head Banner');
        return view('backend.admin.head_banner', compact('data'));
    }
    public function data(Request $request)
    {
        $banner = HeadBanner::whereId(1)->first();
        $response = array(
            'status' => TRUE,
            'row' => $banner,
            'headbanner_url' => asset('dist/img/bg-img/'.$banner->file_image),
        );
        return response()->json($response);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'head_title' => 'required|max:70',
            'description' => 'required|max:255',
            'background_banner' => 'mimes:png,jpg,jpeg|max:2048'
        ],[
            'head_title.required' => 'Nama situs tidak boleh kosong.',
            'head_title.max' => 'Nama situs tidak lebih dari 70 karakter.',
            'description.required' => 'Deskripsi tidak boleh kosong.',
            'description.max' => 'Deskripsi tidak lebih dari 255 karakter.',
            'background_banner.max' => 'Background banner tidak lebih dari 2MB.',
            'background_banner.mimes' => 'Background banner berekstensi jpg jepg png.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $data = [
                'head_title' => $request->input('head_title'),
                'description' => $request->input('description'),
                'user_updated' => Auth::user()->nama_pegawai,
                'updated_at' => Carbon::now()
            ];
            if(!empty($_FILES['background_banner']['name'])) {
                $destinationPath = public_path('/dist/img/bg-img');
                $getFile = HeadBanner::whereId(1)->first();
                //background image
                if(!empty($_FILES['background_banner']['name'])){
                    if($getFile==true) {
                        $get_file_image = $destinationPath.'/'.$getFile->file_image;
                        if(file_exists($get_file_image) && $getFile->file_image)
                            unlink($get_file_image);
                    }
                    $doUploadFile = $this->_doUploadFileHeadBanner($request->file('background_banner'), $destinationPath, 'background_banner');
                    $data['file_image'] = $doUploadFile['file_name'];
                }
            }
            // update head banner
            HeadBanner::whereId(1)->update($data);
            Shortcut::logActivities('Melakukan Update Data Head Banner');
            $output = [
                'status' => TRUE,
                'message' => 'Sukses melakukan update konten',
            ];
        }
        return response()->json($output);
    }   
    /**
     * _doUploadFileHeadBanner
     *
     * @param  mixed $fileInput
     * @param  mixed $destinationPath
     * @param  mixed $nameInput
     * @return void
     */
    private function _doUploadFileHeadBanner($fileInput, $destinationPath, $nameInput) {
        try {
            $fileExtension = $fileInput->getClientOriginalExtension();
            $fileOriginName = $fileInput->getClientOriginalName();
            $fileNewName = strtolower(Str::slug($nameInput.bcrypt(pathinfo($fileOriginName, PATHINFO_FILENAME)))) . time();
            $fileNewNameExt = $fileNewName . '.' . $fileExtension;
            // $fileInput->move($destinationPath, $fileNewNameExt);
            $compressedImage = Image::make($fileInput)
                ->save($destinationPath . '/' . $fileNewNameExt, 10);
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
}
