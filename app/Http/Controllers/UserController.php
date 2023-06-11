<?php

namespace App\Http\Controllers;
use Dompdf\Dompdf;

use App\Exports\ExportData;

use Maatwebsite\Excel\Facades\Excel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    //

    public function self()
    {
        $user = User::find(auth()->user()->id);
        $token = $user->createToken('authToken')->accessToken;
        return response(['user' => $user, 'access_token' => $token]);
    }

    public function index()
    {
        return view('users');
    }
    public function login(Request $request)
    {
        // return $request;
        $login = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->where('is_active', 1)->first();

        // return $user;
        if (!Auth::attempt($login)) {
            return response(['message' => 'login Credentials are incorrect'], 401);
        }
        $token = $user->createToken('authToken')->accessToken;
        return response(['user' => Auth::user(), 'access_token' => $token]);
    }

    public function addRegister(Request $request) {
        $data = new User;

        $data->last_name = $request->last_name;
        $data->first_name = $request->first_name;
        $data->position = 'STAFF/VET Assistant';
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        
        $data->save();

        return $data;
    }
    public function editRegister($id ,Request $request) {
        $data = User::find($id);

        $data->last_name = $request->last_name;
        $data->first_name = $request->first_name;
        $data->position = 'STAFF/VET Assistant';
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        
        $data->save();

        return $data;
    }

    public function getAll(){
        return User::get();
    }

    public function status($id) {
        $data = User::where('id', $id)->first();

        if ($data->is_active == 1) {
            $data->update(['is_active' => 0]);
        }else {
            $data->update(['is_active' => 1]);
        };

    }

  
   


}
