<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        $countries = Countries::all();

        /* $pruebaName = $countries[0]->name;
        foreach ($countries as $pais) {
            $name = $pais->name;
            $iso2 = $pais->iso2;
            $iso3 = $pais->iso3;
            $phone_code = $pais->phone_code;
        } */
        
        //$countriesArray = $countries->toArray();
        //return response()->json($countries);

        return response()->json($countries, 200);
    }
}
