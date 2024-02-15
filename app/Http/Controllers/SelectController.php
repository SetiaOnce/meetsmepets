<?php

namespace App\Http\Controllers;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\MasterCategory;
use App\Models\MasterInterest;
use App\Models\Pets;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function lookingFor(Request $request)
    {
        $getRow = MasterInterest::orderByDesc('id')->whereIsActive('Y')->get();
        $data = [];
        foreach($getRow as $row){            
            $data[] = [
                'id' => $row->interest,
                'text' => $row->interest,
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $data);
    }
    public function interest(Request $request)
    {
        $getRow = MasterInterest::orderByDesc('id')->whereIsActive('Y')->get();
        $data = [];
        foreach($getRow as $row){            
            $data[] = [
                'id' => $row->interest,
                'text' => $row->interest,
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $data);
    }
    public function category(Request $request)
    {
        $getRow = MasterCategory::orderByDesc('id')->whereIsActive('Y')->get();
        $data = [];
        foreach($getRow as $row){            
            $data[] = [
                'id' => $row->category,
                'text' => $row->category,
            ];
        }
        return Shortcut::jsonResponse(true, 'Success', 200, $data);
    }
    public function breed(Request $request)
    {
        $getRow = Pets::whereIn('category', $request->category)->get(['id', 'breed']);
        return Shortcut::jsonResponse(true, 'Success', 200, $getRow);
    }
}
