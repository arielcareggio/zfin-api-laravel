<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        $pruebaName = $users[0]->name;
        foreach ($users as $user) {
            // Acceder a los atributos de cada usuario
            $name = $user->name;
            $email = $user->email;
            
            // Realizar alguna operación con los datos
            // ...
        }
        


        $usersArray = $users->toArray();

        // Acceder a los datos en forma de array
        foreach ($usersArray as $user) {
            $name = $user['name'];
            $email = $user['email'];
            // ...
        }

        //return response()->json($users);

        $headers = ['Datos devueltos: '];
        return response()->json($users, 200, $headers);

        //return view('users.index', compact('users'));
    }

    public function prueba(){
        
        //Consultas utilizando el constructor de consultas (Query Builder):
        $users = DB::table('users')
            ->select('name', 'email')
            //->where('age', '>', 18)
            ->orderBy('name', 'asc')
            ->get()->toArray();
        //$usersArray = $users->toArray();
        //Consultas utilizando consultas SQL directas:
        $users = DB::select('SELECT name, email FROM users WHERE age > ?', [18]);


        //Consultas utilizando el modelo Eloquent:
        $users = User::where('age', '>', 18)
            ->orderBy('name', 'asc')
            ->get();


        return response()->json($users, 200);
    }
}
