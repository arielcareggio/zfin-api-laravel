<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BancosCuentas;
use Illuminate\Support\Facades\Validator;

class BancosCuentasController extends Controller
{
    public function getBancosCuentas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_banco_cuenta' => 'nullable|string',
            'name' => 'nullable|string',
            'id_banco' => 'nullable|integer',
            'nro_cuenta' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        return BancosCuentas::getBancosCuentas($request);
    }

    public function addBancoCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'id_banco' => 'required|integer',
            'nro_cuenta' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $bancoCuenta = BancosCuentas::create([
                'name' => $request->input('name'),
                'id_banco' => $request->input('id_banco'),
                'nro_cuenta' => $request->input('nro_cuenta'),
            ]);

            return response()->json(['message' => 'Cuenta del banco registrada correctamente', 'bancoCuenta' => $bancoCuenta], 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    public function updateBancoCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_banco_cuenta' => 'required|integer',
            'id_banco' => 'required|integer',
            'nro_cuenta' => 'required|string',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $bancoCuenta = BancosCuentas::find($request->input('id_banco_cuenta'));

            if (!$bancoCuenta) {
                return response()->json(['error' => 'La Cuenta del banco no fue encontrada'], 404);
            }

            $bancoCuenta->name = $request->input('name');
            $bancoCuenta->id_banco = $request->input('id_banco');
            $bancoCuenta->nro_cuenta = $request->input('nro_cuenta');
            $bancoCuenta->save();

            return response()->json(['message' => 'Cuenta del banco actualizada correctamente', 'bancoCuenta' => $bancoCuenta], 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el registro'], 500);
        }
    }

    public function deleteBancoCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_banco_cuenta' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $bancoCuenta = BancosCuentas::find($request->input('id_banco_cuenta'));

            if (!$bancoCuenta) {
                return response()->json(['error' => 'La Cuenta del banco no fue encontrada'], 404);
            }

            $bancoCuenta->delete();

            return response()->json(['message' => 'Cuenta del banco eliminada correctamente'], 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al eliminar el registro'], 500);
        }
    }
}
