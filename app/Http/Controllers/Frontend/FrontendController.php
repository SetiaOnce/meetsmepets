<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Aplikasi;
use App\Models\HeadBanner;
use App\Models\InformasiWebsite;
use App\Models\Layanan;
use App\Models\Tautan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $data = [
            'dtAplikasi' => Aplikasi::whereNot('id', 1)->whereStatus(1)->orderBy('id', 'ASC')->get(),
        ];
        return view('frontend.index', compact('data'));
    }
    public function loginInfo()
    {
        $getSiteInfo = InformasiWebsite::whereId(1)->first();
        $getSiteInfo->headpublic_logo_url = asset('dist/img/site-img/'.$getSiteInfo->headpublic_logo);
        $getSiteInfo->login_bg_url = asset('dist/img/site-img/'.$getSiteInfo->login_bg);
        $getSiteInfo->login_logo_url = asset('dist/img/site-img/'.$getSiteInfo->login_logo);
        $getSiteInfo->headbackend_icon_dark_url = asset('dist/img/site-img/'.$getSiteInfo->headbackend_icon_dark);
        $getLinkTerkait = Tautan::orderByDesc('id')->get();
        $response = array(
            'status' => TRUE,
            'row' => $getSiteInfo,
            'tautan' => $getLinkTerkait,
        );
        return response()->json($response);
    }


    /*All Ajax Request*/
    public function BannerView()
    {
        $getRow = HeadBanner::whereId(1)->first();
        $getRow->background_url = asset('dist/img/bg-img/'.$getRow->file_image);
        $response = array(
            'status' => TRUE,
            'row' => $getRow
        );
        return response()->json($response);
    } 
    public function AplikasiView()
    {
        $getRow = Aplikasi::whereNot('id', 1)->whereStatus(1)->orderBy('id', 'ASC')->get();
        $data = [];
        foreach($getRow as $row){
            $data[] = [
                'nama_aplikasi' => $row->nama_aplikasi,
                'link_url' => $row->link_url,
                'icon' => $row->icon,
                'icon_url' => asset('dist/img/icon/'.$row->icon),
            ];
        }
        $response = array(
            'status' => TRUE,
            'row' => $data
        );
        return response()->json($response);
    } 
    public function LayananView()
    {
        $getRow = Layanan::orderByDesc('id')->get();
        $data = [];
        foreach($getRow as $row){
            $data[] = [
                'title' => $row->title,
                'description' => $row->description,
                'description_short' => Str::limit($row->description, 200, '...'),
                'thumbnail' => $row->thumbnail,
                'thumbnail_url' => asset('dist/img/layanan/'.$row->thumbnail),
            ];
        }
        $response = array(
            'status' => TRUE,
            'row' => $data
        );
        return response()->json($response);
    } 
}
