<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personas;
use Illuminate\Support\Facades\Validator;

class PersonasController extends Controller
{
    public function addPersona(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'id_cuenta' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $persona = Personas::create([
                'name' => $request->input('name'),
                'id_cuenta' => $request->input('id_cuenta'),
            ]);

            return response()->json(['message' => 'Persona registrada correctamente', 'persona' => $persona], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            //, 'error_message' => $e->getMessage()
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    public function updatePersona(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_persona' => 'required|integer',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $persona = Personas::find($request->input('id_persona'));

            if (!$persona) {
                return response()->json(['error' => 'La persona no fue encontrada'], 404);
            }

            $persona->name = $request->input('name');
            $persona->save();

            return response()->json(['message' => 'Persona actualizada correctamente', 'persona' => $persona], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el registro'], 500);
        }
    }

    public function deletePersona(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_persona' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        try {
            $persona = Personas::find($request->input('id_persona'));
    
            if (!$persona) {
                return response()->json(['error' => 'La persona no fue encontrada'], 404);
            }
    
            $persona->delete();

            return response()->json(['message' => 'Persona eliminada correctamente'], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al eliminar el registro'], 500);
        }
    }
}
