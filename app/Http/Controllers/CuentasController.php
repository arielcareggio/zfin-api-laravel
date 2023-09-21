<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuentas;
use Illuminate\Support\Facades\Validator;

class CuentasController extends Controller
{

    public function getCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        return Cuentas::getCuentas($request);
    }

    public function addCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'id_user' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $cuenta = Cuentas::create([
                'name' => $request->input('name'),
                'id_user' => $request->input('id_user'),
            ]);

            return response()->json(['message' => 'Cuenta registrada correctamente', 'cuenta' => $cuenta], 200);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    public function updateCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_cuenta' => 'required|integer',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $cuenta = Cuentas::find($request->input('id_cuenta'));

            if (!$cuenta) {
                return response()->json(['error' => 'La cuenta no fue encontrada'], 404);
            }

            $cuenta->name = $request->input('name');
            $cuenta->save();

            return response()->json(['message' => 'Cuenta actualizada correctamente', 'cuenta' => $cuenta], 200);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el registro'], 500);
        }
    }

    public function deleteCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_cuenta' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        try {
            $cuenta = Cuentas::find($request->input('id_cuenta'));
    
            if (!$cuenta) {
                return response()->json(['error' => 'La cuenta no fue encontrada'], 404);
            }
    
            $cuenta->delete();

            return response()->json(['message' => 'Cuenta eliminada correctamente'], 200);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al eliminar el registro'], 500);
        }
    }
}
