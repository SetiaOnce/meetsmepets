<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Apisik;
use App\Http\Controllers\Controller;
use App\Models\DataSdm;
use App\Models\InformasiWebsite;
use App\Models\SoapSik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    public function siteInfo()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $response = array(
            'status' => TRUE,
            'row' => $getSiteInfo,
            'headbackend_logo_url' => asset('dist/img/site-img/'.$getSiteInfo->headbackend_logo),
            'headbackend_logo_dark_url' => asset('dist/img/site-img/'.$getSiteInfo->headbackend_logo_dark),
            'headbackend_icon_url' => asset('dist/img/site-img/'.$getSiteInfo->headbackend_icon),
            'headbackend_icon_dark_url' => asset('dist/img/site-img/'.$getSiteInfo->headbackend_icon_dark),
        );
        return response()->json($response);
    }
    public function userInfo()
    {
        if(!empty(Auth::user())){
            $userInfo = DataSdm::whereId(Auth::user()->id)->first();
        }else{
            $userInfo = session()->all();
        }
        $response = array(
            'status' => TRUE,
            'row' => $userInfo,
        );
        return response()->json($response);
    }
    public function sincronizeSik()
    {
        try{  
            $dataKantor = Apisik::dataKantor();
            $pegawaiPerKantor = Apisik::pegawaiPerKantor();
            $pegawaiHonorer = Apisik::pegawaiHonorer();
            SoapSik::whereId(1)->update([
                'data_kantor' => $dataKantor,
                'pegawai_perkantor' => $pegawaiPerKantor,
                'pegawai_honorer' => $pegawaiHonorer,
            ]);
            $output = array("status" => TRUE, 'message' => 'Sukses Melakukan Sincronisasi');
            return response()->json($output);
        }catch(\GuzzleHttp\Exception\ConnectException $e){
            $output = array("status" => FALSE);
        } 
    }
}
