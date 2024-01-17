<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\InformasiWebsite;
use App\Models\Layanan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LayananController extends Controller
{
    public function index()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Layanan',
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
            // 'dist/scripts/backend/main.init.js',
            'scripts/backend/admin/layanan.js'
        );
        Shortcut::logActivities('Mengakses Halaman Layanan');
        return view('backend.admin.layanan', compact('data'));
    }
    public function data(Request $request)
    {
        $data = Layanan::orderBy('id', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('description', function ($row) {
                return Str::limit($row->description, 400, '...');
            })
            ->editColumn('thumbnail', function ($row) {
                $file_name = $row->thumbnail;
                if($file_name==''){
                    $url_file = asset('dist/img/default-placeholder.png');
                } else {
                    if (!file_exists(public_path(). '/dist/img/layanan/'.$file_name)){
                        $url_file = asset('dist/img/default-placeholder.png');
                        $file_name = NULL;
                    }else{
                        $url_file = url('dist/img/layanan/'.$file_name);
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
                $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteData('."'".$row->id."'".');"><i class="ki-outline ki-trash fs-3"></i></button>';
                return $btnEdit.$btnDelete;
            })
            ->rawColumns(['description', 'thumbnail', 'status', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'thumbnail' => 'mimes:png,jpg,jpeg|max:2048'
        ],[
            'title.required' => 'Judul tidak boleh kosong.',
            'title.max' => 'Judul tidak lebih dari 255 karakter.',
            'description.required' => 'Deskripsi tidak boleh kosong.',
            'thumbnail.max' => 'Thumbnail tidak lebih dari 2MB.',
            'thumbnail.mimes' => 'Thumbnail berekstensi jpg jepg png.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $data = [
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'user_add' => Auth::user()->nama_pegawai,
                'created_at' => Carbon::now()
            ];
            $destinationPath = public_path('/dist/img/layanan');
            $doUploadFile = $this->_doUploadFileThumbnail($request->file('thumbnail'), $destinationPath, 'thumbnaillayanan');
            $data['thumbnail'] = $doUploadFile['file_name'];
            // create new layanan
            Layanan::create($data);
            Shortcut::logActivities('Menambah Data Layanan');
            $output = ['status' => TRUE,'message' => 'Sukses menambah data layanan',];
        }
        return response()->json($output);
    }
    public function edit(Request $request)
    {
        $getRow = Layanan::whereId($request->idp)->first();
        $getRow->thumbnail_url = asset('dist/img/layanan/'.$getRow->thumbnail); 
        return response()->json([
            'status' => TRUE,
            'row' =>$getRow,
        ]);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'thumbnail' => 'mimes:png,jpg,jpeg|max:2048'
        ],[
            'title.required' => 'Judul tidak boleh kosong.',
            'title.max' => 'Judul tidak lebih dari 255 karakter.',
            'description.required' => 'Deskripsi tidak boleh kosong.',
            'thumbnail.max' => 'Thumbnail tidak lebih dari 2MB.',
            'thumbnail.mimes' => 'Thumbnail berekstensi jpg jepg png.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $data = [
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => isset($request->status) ? 1 : 0,
                'user_updated' => Auth::user()->nama_pegawai,
                'updated_at' => Carbon::now()
            ];
            if(!empty($_FILES['thumbnail']['name'])) {
                $destinationPath = public_path('/dist/img/layanan');
                $getFile = Layanan::whereId($request->id)->first();
                //background image
                if(!empty($_FILES['thumbnail']['name'])){
                    if($getFile) {
                        $get_thumbnail = $destinationPath.'/'.$getFile->thumbnail;
                        if(file_exists($get_thumbnail) && $getFile->thumbnail)
                            unlink($get_thumbnail);
                    }
                    $doUploadFile = $this->_doUploadFileThumbnail($request->file('thumbnail'), $destinationPath, 'thumbnaillayanan');
                    $data['thumbnail'] = $doUploadFile['file_name'];
                }
            }
            // update layanan
            Layanan::whereId($request->id)->update($data);
            Shortcut::logActivities('Melakukan Update Data Layanan');
            $output = ['status' => TRUE,'message' => 'Sukses memperbarui data layanan',];
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
            Layanan::whereId($idp)->update($data);
            if($value==0) {
                $activities = 'Menonaktifkan Data Layanan';
                $textMsg = 'Layanan berhasil diubah menjadi <strong class="text-danger">Nonaktif</strong>';
            } else {
                $activities = 'Mengaktifkan Data Layanan';
                $textMsg = 'Layanan berhasil diubah menjadi <strong class="text-success">Aktif</strong>';
            }
            $output = array('status' => TRUE, 'message' => $textMsg);
        } catch (Exception $exception) {
            $output = array('status' => 401, 'message' => $exception->getMessage());
        }
        Shortcut::logActivities($activities);
        return response()->json($output);
    }
    public function destroy(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $getFile = Layanan::whereId($request->idp)->first();
        $destinationPath = public_path('/dist/img/layanan');
        if($getFile==true) {
            $get_thumbnail = $destinationPath.'/'.$getFile->thumbnail;
            if(file_exists($get_thumbnail) && $getFile->thumbnail)
                unlink($get_thumbnail);
        }
        Layanan::whereId($request->idp)->delete();
        Shortcut::logActivities('Melakukan Penghapusan Data Layanan');
        return response()->json(["status" => TRUE, 'message' => 'Berhasil menghapus data layanan']);
    }
    /**
     * _doUploadFileThumbnail
     *
     * @param  mixed $fileInput
     * @param  mixed $destinationPath
     * @param  mixed $nameInput
     * @return void
     */
    private function _doUploadFileThumbnail($fileInput, $destinationPath, $nameInput) {
        try {
            $fileExtension = $fileInput->getClientOriginalExtension();
            $fileOriginName = $fileInput->getClientOriginalName();
            $fileNewName = strtolower(Str::slug($nameInput.bcrypt(pathinfo($fileOriginName, PATHINFO_FILENAME)))) . time();
            $fileNewNameExt = $fileNewName . '.' . $fileExtension;
            // $fileInput->move($destinationPath, $fileNewNameExt);
            $compressedImage = Image::make($fileInput)->resize(571,625)
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