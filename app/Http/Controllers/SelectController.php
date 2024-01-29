<?php

namespace App\Http\Controllers;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function lookingFor(Request $request)
    {
        $getRow = [
            [
                'id' => 'CAT',
                'text' => 'CAT',
            ],
            [
                'id' => 'DOG',
                'text' => 'DOG',
            ],
            [
                'id' => 'BIRD',
                'text' => 'BIRD',
            ],
            [
                'id' => 'FISH',
                'text' => 'FISH',
            ],
        ];
        return Shortcut::jsonResponse(true, 'Success', 200, $getRow);
    }
    public function interest(Request $request)
    {
        $getRow = [
            [
                'id' => 'CAT',
                'text' => 'CAT',
            ],
            [
                'id' => 'DOG',
                'text' => 'DOG',
            ],
            [
                'id' => 'BIRD',
                'text' => 'BIRD',
            ],
            [
                'id' => 'FISH',
                'text' => 'FISH',
            ],
        ];
        return Shortcut::jsonResponse(true, 'Success', 200, $getRow);
    }
}
