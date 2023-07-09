<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bancos;
use Illuminate\Support\Facades\Validator;

class BancosController extends Controller
{
    public function addBanco(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'id_cuenta' => 'required|integer',
            'id_countrie' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $banco = Bancos::create([
                'name' => $request->input('name'),
                'id_cuenta' => $request->input('id_cuenta'),
                'id_countrie' => $request->input('id_countrie'),
            ]);

            return response()->json(['message' => 'Banco registrado correctamente', 'banco' => $banco], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    public function updateBanco(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_banco' => 'required|integer',
            'id_cuenta' => 'required|integer',
            'id_countrie' => 'required|integer',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $banco = Bancos::find($request->input('id_banco'));

            if (!$banco) {
                return response()->json(['error' => 'El banco no fue encontrada'], 404);
            }

            $banco->name = $request->input('name');
            $banco->id_cuenta = $request->input('id_cuenta');
            $banco->id_countrie = $request->input('id_countrie');
            $banco->save();

            return response()->json(['message' => 'Banco actualizado correctamente', 'banco' => $banco], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el registro'], 500);
        }
    }

    public function deleteBanco(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_banco' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        try {
            $banco = Bancos::find($request->input('id_banco'));
    
            if (!$banco) {
                return response()->json(['error' => 'El banco no fue encontrado'], 404);
            }
    
            $banco->delete();

            return response()->json(['message' => 'Banco eliminado correctamente'], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al eliminar el registro'], 500);
        }
    }
}
