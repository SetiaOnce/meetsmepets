<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformasiWebsite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Helpers\Shortcut;
use App\Models\Tautan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InformasiWebsiteController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Informasi Website',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->short_name,
            'user_session' => $getUserSession,
            'totalTautan' => Tautan::all()->count()
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
            'scripts/backend/admin/informasi_website.js'
        );
        Shortcut::logActivities('Mengakses Halaman Infomasi Website');
        return view('backend.admin.informasi_website', compact('data'));
    }
    public function data(Request $request)
    {
        $website = InformasiWebsite::whereId(1)->first();
        $response = array(
            'status' => TRUE,
            'row' => $website,
            'keyword_explode' => explode(',', $website->keyword),
            'headpublic_logo_url' => asset('dist/img/site-img/'.$website->headpublic_logo),
            'login_logo_url' => asset('dist/img/site-img/'.$website->login_logo),
            'login_bg_url' => asset('dist/img/site-img/'.$website->login_bg),
            'headbackend_logo_url' => asset('dist/img/site-img/'.$website->headbackend_logo),
            'headbackend_logo_dark_url' => asset('dist/img/site-img/'.$website->headbackend_logo_dark),
            'headbackend_icon_url' => asset('dist/img/site-img/'.$website->headbackend_icon),
            'headbackend_icon_dark_url' => asset('dist/img/site-img/'.$website->headbackend_icon_dark),
        );
        return response()->json($response);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'short_name' => 'required|max:60',
            'description' => 'required|max:160',
            'keyword' => 'required',
            'about_portal' => 'required',
            'copyright' => 'required',
            'headpublic_logo' => 'mimes:png,jpg,jpeg|max:2048',
            'login_logo' => 'mimes:png,jpg,jpeg|max:2048',
            'login_bg' => 'mimes:png,jpg,jpeg|max:2048',
            'headbackend_logo' => 'mimes:png,jpg,jpeg|max:2048',
            'headbackend_logo_dark' => 'mimes:png,jpg,jpeg|max:2048',
            'headbackend_icon' => 'mimes:png,jpg,jpeg|max:2048',
            'headbackend_icon_dark' => 'mimes:png,jpg,jpeg|max:2048'
        ],[
            'name.required' => 'Nama situs tidak boleh kosong.',
            'name.max' => 'Nama situs tidak lebih dari 255 karakter.',
            'short_name.required' => 'Nama pendek tidak boleh kosong.',
            'short_name.max' => 'Nama pendek tidak lebih dari 60 karakter.',
            'description.required' => 'Deskripsi tidak boleh kosong.',
            'description.max' => 'Deskripsi tidak lebih dari 160 karakter.',
            'keyword.required' => 'Keyword tidak boleh kosong.',
            'about_portal.required' => 'Tentang portal tidak boleh kosong.',
            'copyright.required' => 'Copyright tidak boleh kosong.',
            'copyright.max' => 'Copyright tidak lebih dari 1000 karakter.',
            'headpublic_logo.required' => 'Logo public tidak boleh kosong.',
            'headpublic_logo.max' => 'Logo public tidak lebih dari 2MB.',
            'headpublic_logo.mimes' => 'Logo public berekstensi jpg jepg png.',
            'login_logo.required' => 'Logo login tidak boleh kosong.',
            'login_logo.max' => 'Logo login tidak lebih dari 2MB.',
            'login_logo.mimes' => 'Logo login berekstensi jpg jepg png.',
            'login_bg.required' => 'Background login tidak boleh kosong.',
            'login_bg.max' => 'Background login tidak lebih dari 2MB.',
            'login_bg.mimes' => 'Background login berekstensi jpg jepg png.',
            'headbackend_logo.required' => 'Logo backend tidak boleh kosong.',
            'headbackend_logo.max' => 'Logo backend tidak lebih dari 2MB.',
            'headbackend_logo.mimes' => 'Logo backend berekstensi jpg jepg png.',
            'headbackend_logo_dark.required' => 'Logo backend dark tidak boleh kosong.',
            'headbackend_logo_dark.max' => 'Logo backend dark tidak lebih dari 2MB.',
            'headbackend_logo_dark.mimes' => 'Logo backend dark berekstensi jpg jepg png.',
            'headbackend_icon.required' => 'Logo icon backend tidak boleh kosong.',
            'headbackend_icon.max' => 'Logo icon backend tidak lebih dari 2MB.',
            'headbackend_icon.mimes' => 'Logo icon backend berekstensi jpg jepg png.',
            'headbackend_icon_dark.required' => 'Logo icon backend dark tidak boleh kosong.',
            'headbackend_icon_dark.max' => 'Logo icon backend dark tidak lebih dari 2MB.',
            'headbackend_icon_dark.mimes' => 'Logo icon backend dark berekstensi jpg jepg png.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            //keyword to implode
            if(!empty($request->keyword)){
                $keyword = implode(", ", $request->keyword);
            }
            $data = [
                'name' => $request->input('name'),
                'short_name' => $request->input('short_name'),
                'description' => $request->input('description'),
                'keyword' => $keyword,
                'about_portal' => $request->input('about_portal'),
                'copyright' => urldecode(urldecode($request->copyright)),
                'user_updated' => Auth::user()->nama_pegawai,
                'updated_at' => Carbon::now()
            ];
            if(!empty($_FILES['headpublic_logo']['name']) ||
                !empty($_FILES['login_logo']['name']) ||
                !empty($_FILES['login_bg']['name']) ||
                !empty($_FILES['headbackend_logo']['name']) ||
                !empty($_FILES['headbackend_logo_dark']['name']) ||
                !empty($_FILES['headbackend_icon']['name']) ||
                !empty($_FILES['headbackend_icon_dark']['name'])
            ) {
                $destinationPath = public_path('/dist/img/site-img');
                $getFile = InformasiWebsite::whereId(1)->first();
                //Login public
                if(!empty($_FILES['headpublic_logo']['name'])){
                    if($getFile==true) {
                        $get_headpublic_logo = $destinationPath.'/'.$getFile->headpublic_logo;
                        if(file_exists($get_headpublic_logo) && $getFile->headpublic_logo)
                            unlink($get_headpublic_logo);
                    }
                    $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headpublic_logo'), $destinationPath, 'headpublic_logo');
                    $data['headpublic_logo'] = $doUploadFile['file_name'];
                }
                //Login Logo
                if(!empty($_FILES['login_logo']['name'])){
                    if($getFile==true) {
                        $getFileLoginLogo = $destinationPath.'/'.$getFile->login_logo;
                        if(file_exists($getFileLoginLogo) && $getFile->login_logo)
                            unlink($getFileLoginLogo);
                    }
                    $doUploadFile = $this->_doUploadFileSiteInfo($request->file('login_logo'), $destinationPath, 'login_logo');
                    $data['login_logo'] = $doUploadFile['file_name'];
                }
                //Login Background
                if(!empty($_FILES['login_bg']['name'])){
                    if($getFile==true) {
                        $getFileLoginBg = $destinationPath.'/'.$getFile->login_bg;
                        if(file_exists($getFileLoginBg) && $getFile->login_bg)
                            unlink($getFileLoginBg);
                    }
                    $doUploadFile = $this->_doUploadFileSiteInfo($request->file('login_bg'), $destinationPath, 'login_bg');
                    $data['login_bg'] = $doUploadFile['file_name'];
                }
                //Backend Logo
                if(!empty($_FILES['headbackend_logo']['name'])){
                    if($getFile==true) {
                        $get_headbackend_logo = $destinationPath.'/'.$getFile->headbackend_logo;
                        if(file_exists($get_headbackend_logo) && $getFile->headbackend_logo)
                            unlink($get_headbackend_logo);
                    }
                    $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headbackend_logo'), $destinationPath, 'headbackend_logo');
                    $data['headbackend_logo'] = $doUploadFile['file_name'];
                } if(!empty($_FILES['headbackend_logo_dark']['name'])){
                    if($getFile==true) {
                        $get_headbackend_logo_dark = $destinationPath.'/'.$getFile->headbackend_logo_dark;
                        if(file_exists($get_headbackend_logo_dark) && $getFile->headbackend_logo_dark)
                            unlink($get_headbackend_logo_dark);
                    }
                    $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headbackend_logo_dark'), $destinationPath, 'headbackend_logo_dark');
                    $data['headbackend_logo_dark'] = $doUploadFile['file_name'];
                }
                //Backend Icon Logo
                if(!empty($_FILES['headbackend_icon']['name'])){
                    if($getFile==true) {
                        $get_headbackend_icon = $destinationPath.'/'.$getFile->headbackend_icon;
                        if(file_exists($get_headbackend_icon) && $getFile->headbackend_icon)
                            unlink($get_headbackend_icon);
                    }
                    $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headbackend_icon'), $destinationPath, 'headbackend_icon');
                    $data['headbackend_icon'] = $doUploadFile['file_name'];
                } if(!empty($_FILES['headbackend_icon_dark']['name'])){
                    if($getFile==true) {
                        $get_headbackend_icon_dark = $destinationPath.'/'.$getFile->headbackend_icon_dark;
                        if(file_exists($get_headbackend_icon_dark) && $getFile->headbackend_icon_dark)
                            unlink($get_headbackend_icon_dark);
                    }
                    $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headbackend_icon_dark'), $destinationPath, 'headbackend_icon_dark');
                    $data['headbackend_icon_dark'] = $doUploadFile['file_name'];
                }
            }
            // update site info
            InformasiWebsite::whereId(1)->update($data);
            Shortcut::logActivities('Melakukan Update Data Infomasi Website');
            $output = [
                'status' => TRUE,
                'message' => 'Sukses melakukan update konten',
            ];
        }
        return response()->json($output);
    }   
    public function tautanSave(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'nama_tautan' => 'required|max:120',
            'link_tautan' => 'required',
        ],[
            'nama_tautan.required' => 'Nama tautan tidak boleh kosong.',
            'nama_tautan.max' => 'Nama tautan tidak lebih dari 120 karakter.',
            'link_tautan.required' => 'Link tautan tidak boleh kosong.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $data = [
                'nama_tautan' => $request->input('nama_tautan'),
                'link_tautan' => $request->input('link_tautan'),
                'user_add' => Auth::user()->nama_pegawai,
                'updated_at' => Carbon::now()
            ];
            // save tautan
            Tautan::create($data);
            Shortcut::logActivities('Menambahkan data tautan');
            $output = [
                'status' => TRUE,
                'message' => 'Data tautan berhasil disimpan',
                'totalTautan' => Tautan::all()->count(),
            ];
        }
        return response()->json($output);
    }  
    public function tautanLoad(Request $request)
    {
        $data = Tautan::orderBy('id', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('action', function ($row) {
                $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteTautan('."'".$row->id."'".');"><i class="ki-outline ki-trash fs-3"></i></button>';
                return $btnEdit;
            })
            ->rawColumns(['action'])
            ->make(true);
    } 
    public function tautanDestroy(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        Tautan::whereId($request->idp)->delete();
        Shortcut::logActivities('Melakukan Penghapusan Data Tautan');
        return response()->json(["status" => TRUE, 'message' => 'Berhasil menghapus data tautan', 'totalTautan' => Tautan::all()->count()]);
    }
    /**
     * _doUploadFileSiteInfo
     *
     * @param  mixed $fileInput
     * @param  mixed $destinationPath
     * @param  mixed $nameInput
     * @return void
     */
    private function _doUploadFileSiteInfo($fileInput, $destinationPath, $nameInput) {
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
