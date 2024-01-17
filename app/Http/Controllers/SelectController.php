<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use App\Models\DataSdm;
use App\Models\LevelAplikasi;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function getPegawai(Request $request)
    {
        $query = DataSdm::whereStatus(1)->orderBy('id', 'ASC');
        if($request->is_admin == 'Y'){
            $query = $query->whereIsAdmin(0);
        }
        $query = $query->get();
        return response()->json($query);
    }
    public function getAplikasi(Request $request)
    {
        $query = Aplikasi::whereStatus(1)->orderBy('id', 'ASC')->get();
        return response()->json($query);
    }
    public function getLevelAplikasi(Request $request)
    {
        $query = LevelAplikasi::whereFidAplikasi($request->fid_aplikasi)->whereStatus(1)->orderBy('id', 'ASC')->get();
        return response()->json($query);
    }
}
