<?php

namespace App\Http\Controllers;

use App\Models\Tipos;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TiposController extends Controller
{
    public function getAllTipos()
    {
        $tipos = Tipos::all();
        return response()->json($tipos, 200);
    }
}
