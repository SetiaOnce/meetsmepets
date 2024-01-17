<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Aplikasi;
use App\Models\InformasiWebsite;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AplikasiController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Aplikasi',
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
            'scripts/backend/admin/aplikasi.js'
        );
        Shortcut::logActivities('Mengakses Halaman Aplikasi');
        return view('backend.admin.aplikasi', compact('data'));
    }
    public function data(Request $request)
    {
        $data = Aplikasi::orderBy('id', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('icon', function ($row) {
                $file_name = $row->icon;
                if($file_name==''){
                    $url_file = asset('dist/img/default-placeholder.png');
                } else {
                    if (!file_exists(public_path(). '/dist/img/icon/'.$file_name)){
                        $url_file = asset('dist/img/default-placeholder.png');
                        $file_name = NULL;
                    }else{
                        $url_file = url('dist/img/icon/'.$file_name);
                    }
                }
                $fileCustom = '<a class="d-block overlay w-100 image-popup" href="'.$url_file.'" title="'.$file_name.'">
                    <img src="'.$url_file.'" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="'.$file_name.'" />
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                    </div>    
                </a>';
                return $fileCustom;
            })
            ->editColumn('status', function ($row) {
                if($row->status == 1){
                    $activeCustom = '<button type="button" class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip" title="Status Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'0'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                } else {
                    $activeCustom = '<button type="button" class="btn btn-icon btn-sm btn-danger" data-bs-toggle="tooltip" title="Status Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'1'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                }
                return $activeCustom;
            })
            ->editColumn('action', function ($row) {
                $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".');"><i class="ki-outline ki-pencil fs-3"></i></button>';
                return $btnEdit;
            })
            ->rawColumns(['status', 'icon','action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'nama_aplikasi' => 'required|max:120',
            'link_url' => 'required',
            'link_login' => 'required',
            'keterangan' => 'required|max:255',
            'icon' => 'mimes:png,jpg,jpeg|max:2048'
        ],[
            'nama_aplikasi.required' => 'Nama aplikasi tidak boleh kosong.',
            'nama_aplikasi.max' => 'Nama aplikasi tidak lebih dari 120 karakter.',
            'link_url.required' => 'Link aplikasi tidak boleh kosong.',
            'link_login.required' => 'Link login aplikasi tidak boleh kosong.',

            'keterangan.required' => 'Keterangan tidak boleh kosong.',
            'keterangan.max' => 'Keterangan tidak lebih dari 255 karakter.',
            'icon.max' => 'Icon aplikasi tidak lebih dari 2MB.',
            'icon.mimes' => 'Icon aplikasi berekstensi jpg jepg png.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $data = [
                'nama_aplikasi' => $request->input('nama_aplikasi'),
                'access_key' => $this->generateAccesKey(),
                'link_url' => $request->input('link_url'),
                'link_login' => $request->input('link_login'),
                'keterangan' => $request->input('keterangan'),
                'user_add' => Auth::user()->nama_pegawai,
                'created_at' => Carbon::now()
            ];
            $destinationPath = public_path('/dist/img/icon');
            $doUploadFile = $this->_doUploadFileIcon($request->file('icon'), $destinationPath, 'iconaplikasi');
            $data['icon'] = $doUploadFile['file_name'];
            // create new aplication
            Aplikasi::create($data);
            Shortcut::logActivities('Menambah Data Aplikasi');
            $output = ['status' => TRUE,'message' => 'Sukses menambah data aplikasi',];
        }
        return response()->json($output);
    }
    public function edit(Request $request)
    {
        $dataAplikasi = Aplikasi::whereId($request->idp)->first();
        $url_icon = asset('dist/img/icon/'.$dataAplikasi->icon); 
        return response()->json([
            'status' => TRUE,
            'row' =>$dataAplikasi,
            'url_icon' =>$url_icon,
        ]);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'nama_aplikasi' => 'required|max:120',
            'link_url' => 'required',
            'link_login' => 'required',
            'keterangan' => 'required|max:255',
            'icon' => 'mimes:png,jpg,jpeg|max:2048'
        ],[
            'nama_aplikasi.required' => 'Nama aplikasi tidak boleh kosong.',
            'nama_aplikasi.max' => 'Nama aplikasi tidak lebih dari 120 karakter.',
            'link_url.required' => 'Link aplikasi tidak boleh kosong.',
            'link_login.required' => 'Link login aplikasi tidak boleh kosong.',

            'keterangan.required' => 'Keterangan tidak boleh kosong.',
            'keterangan.max' => 'Keterangan tidak lebih dari 255 karakter.',
            'icon.max' => 'Icon aplikasi tidak lebih dari 2MB.',
            'icon.mimes' => 'Icon aplikasi berekstensi jpg jepg png.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $data = [
                'nama_aplikasi' => $request->input('nama_aplikasi'),
                'link_url' => $request->input('link_url'),
                'link_login' => $request->input('link_login'),
                'keterangan' => $request->input('keterangan'),
                'status' => isset($request->status) ? 1 : 0,
                'user_updated' => Auth::user()->nama_pegawai,
                'updated_at' => Carbon::now()
            ];
            if(!empty($_FILES['icon']['name'])) {
                $destinationPath = public_path('/dist/img/icon');
                $getFile = Aplikasi::whereId($request->id)->first();
                //background image
                if(!empty($_FILES['icon']['name'])){
                    if($getFile==true) {
                        $get_file_image = $destinationPath.'/'.$getFile->file_image;
                        if(file_exists($get_file_image) && $getFile->file_image)
                            unlink($get_file_image);
                    }
                    $doUploadFile = $this->_doUploadFileIcon($request->file('icon'), $destinationPath, 'iconaplikasi');
                    $data['icon'] = $doUploadFile['file_name'];
                }
            }
            // update aplication
            Aplikasi::whereId($request->id)->update($data);
            Shortcut::logActivities('Melakukan Update Data Aplikasi');
            $output = ['status' => TRUE,'message' => 'Sukses memperbarui data aplikasi',];
        }
        return response()->json($output);
    }
    public function updateStatus(Request $request)
    {
        $idp = $request->idp;
        $value = $request->value;
        try {
            $data = array(
                'status' => $value,
                'user_updated' => Auth::user()->nama_pegawai,
                'updated_at' =>Carbon::now()
            );
            Aplikasi::whereId($idp)->update($data);
            if($value==0) {
                $activities = 'Menonaktifkan Aplikasi Pelayanan';
                $textMsg = 'Status aplikasi berhasil diubah menjadi <strong class="text-danger">Nonaktif</strong>';
            } else {
                $activities = 'Mengaktifkan Aplikasi Pelayanan';
                $textMsg = 'Status aplikasi berhasil diubah menjadi <strong class="text-success">Aktif</strong>';
            }
            $output = array('status' => TRUE, 'message' => $textMsg);
        } catch (Exception $exception) {
            $output = array('status' => 401, 'message' => $exception->getMessage());
        }
        Shortcut::logActivities($activities);
        return response()->json($output);
    }
    /**
     * _doUploadFileIcon
     *
     * @param  mixed $fileInput
     * @param  mixed $destinationPath
     * @param  mixed $nameInput
     * @return void
     */
    private function _doUploadFileIcon($fileInput, $destinationPath, $nameInput) {
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
    private function generateAccesKey()
    {
        while (true) {
            $accessKey = bin2hex(random_bytes(15));
            $checkExist = Aplikasi::where('access_key', $accessKey)->first();
            // if not exist access key return the acces key
            if (empty($checkExist)) {
                return 'sisaka'.$accessKey;
                break;
            }
        }
    }
}