<?php
namespace App\Helpers;

use App\Models\AuthorizationSik;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime, DateInterval, DatePeriod;
use Illuminate\Support\Facades\Request;
use SoapClient;

class Apisik {  
	public static function dataKantor()
	{
		$authorization = AuthorizationSik::where('id', 1)->first();
		$apiEndpoint = $authorization->url_wsdl;
        $objClient = new SoapClient($apiEndpoint, [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'login' => $authorization->user,
            'password' => $authorization->key,
        ]);
        $request = [
            'kode_unit' => '006'
        ];
        $response = $objClient->__soapCall('get_struktur_kantor', $request);   
        return $response['return'];
	}
	public static function pegawaiPerKantor()
	{
		$authorization = AuthorizationSik::where('id', 1)->first();
		$apiEndpoint = $authorization->url_wsdl;
        $objClient = new SoapClient($apiEndpoint, [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'login' => $authorization->user,
            'password' => $authorization->key,
        ]);
        $request = [
            'kode_kantor' => '006004000000000',
            'nama_kantor' => 'Direktorat Sarana Perkeretaapian',
        ];
        $response = $objClient->__soapCall('get_pegawai_per_kantor', $request);   
        return $response['return'];
	}
	public static function pegawaiAsn($nip, $nik)
	{
		$authorization = AuthorizationSik::where('id', 1)->first();
		$apiEndpoint = $authorization->url_wsdl;
        $objClient = new SoapClient($apiEndpoint, [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'login' => $authorization->user,
            'password' => $authorization->key,
        ]);
        $request = [
            'nip' => $nip,
            'nik' => $nik,
        ];
        $response = $objClient->__soapCall('get_pegawai_by_nip', $request);   
        return $response['return'];
	}
	public static function pegawaiHonorer()
	{
		$authorization = AuthorizationSik::where('id', 1)->first();
		$apiEndpoint = $authorization->url_wsdl;
        $objClient = new SoapClient($apiEndpoint, [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'login' => $authorization->user,
            'password' => $authorization->key,
        ]);
        $request = [
            'kode_kantor' => '006004000000000'
        ]; 
        $response = $objClient->__soapCall('get_honorer_per_kantor', $request);  
        return $response['return'];
	}

}