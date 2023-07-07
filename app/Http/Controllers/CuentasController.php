<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuentas;

class CuentasController extends Controller
{
    public function index()
    {
        $countries = Cuentas::all();

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

    public function addCuenta(Request $request)
    {
        $countriesArray = $request->toArray();
        /* $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'last_name' => 'nullable|string', //opcional
            'phone' => 'nullable|string', //opcional
            'id_countrie' => 'nullable|integer', //opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'id_countrie' => $request->input('id_countrie'),
        ]); */

        return response()->json(['message' => 'Usuario registrado correctamente'], 201);
    }
}
