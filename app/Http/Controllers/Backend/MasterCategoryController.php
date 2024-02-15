<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\MasterCategory;
use App\Models\SiteInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class MasterCategoryController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $getSiteInfo = SiteInfo::whereId(1)->first();
        $getUserSession = Auth::guard('web')->user();
        //Data WebInfo
        $data = array(
            'title' => 'Master Category',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.css',
            '/dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            '/dist/plugins/Magnific-Popup/magnific-popup.css',
        );
        //Data Source JS
        $data['js'] = array(
            '/dist/plugins/custom/datatables/datatables.bundle.js',
            '/dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            '/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            '/dist/js/jquery.mask.min.js',
            '/dist/js/app.backend.init.js',
            '/scripts/backend/master_category.init.js'
        );
        Shortcut::addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.master_category', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        if(isset($request->idp)){
            try {
                $getRow = MasterCategory::where('id', $request->idp)->first();
                if($getRow != null){
                    return Shortcut::jsonResponse(true, 'Success', 200, $getRow);
                } else {
                    return Shortcut::jsonResponse(false, "Credentials not match", 401);
                }
            } catch (Exception $exception) {
                return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else {
            $query = MasterCategory::orderByDesc('id');
            $data = $query->get();
            $output = Datatables::of($data)->addIndexColumn()
                ->editColumn('is_active', function ($row) {
                    if($row->is_active == 'Y'){
                        $activeCustom = '<button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="tooltip" title="Category Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'N'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                    } else {
                        $activeCustom = '<button type="button" class="btn btn-sm btn-light mb-1" data-bs-toggle="tooltip" title="Category Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'Y'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                    }
                    return $activeCustom;
                })
                ->addColumn('action', function($row){
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    return $btnEdit;
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);

            return $output;
        }
    }
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request) {
        $userSesIdp = Auth::guard('web')->user()->id;
        $form = [
            'category_name' => 'required|max:120',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'category' => strtoupper($request->category_name),
                'user_add' => $userSesIdp
            );
            $insertUser = MasterCategory::insertGetId($data);
            Shortcut::addToLog('Category has been successfully added');
            DB::commit();
            return Shortcut::jsonResponse(true, 'Data category berhasil ditambahkan', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request) {
        $userSesIdp = Auth::guard('web')->user()->id;
        $form = [
            'category_name' => 'required|max:120',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            //array data
            $data = array(
                'category' => $request->category_name,
                'is_active' => isset($request->is_active) ? 'Y' : 'N',
                'user_updated' => $userSesIdp
            );
            MasterCategory::whereId($request->id)->update($data);
            Shortcut::addToLog('Category has been successfully updated');
            DB::commit();
            return Shortcut::jsonResponse(true, 'Data Category berhasil diperbarui', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * update_status
     *
     * @param  mixed $request
     * @return void
     */
    public function update_status(Request $request) {
        $userSesIdp = Auth::guard('web')->user()->id;
        $idp = $request->idp;
        $value = $request->value;
        DB::beginTransaction();
        try {
            $data = array(
                'is_active' => $value,
                'user_updated' => $userSesIdp
            );
            MasterCategory::whereId($idp)->update($data);
            if($value=='N') {
                Shortcut::addToLog('Category status has been successfully updated to Inactive');
                $textMsg = 'Status category berhasil diubah menjadi <strong>Nonaktif</strong>';
            } else {
                Shortcut::addToLog('Category status has been successfully updated to Active');
                $textMsg = 'Status category berhasil diubah menjadi <strong>Aktif</strong>';
            }
            DB::commit();
            return Shortcut::jsonResponse(true, $textMsg, 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return Shortcut::jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}