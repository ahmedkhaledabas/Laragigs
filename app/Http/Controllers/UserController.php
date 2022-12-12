<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public static function create(){
       return view('users.register');
    }

    public static function login(){
        return view('users.login');
    }

    public static function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('message' , 'you have been logged out');
     }

     public static function store(Request $request){
        $formValidate = $request->validate([
            'name'=> ['required' , 'min:4'] , 
            'email' => ['email' , 'required' , Rule::unique('users', 'email')] ,
            'password' => ['required' , 'confirmed' , 'min:6']
        ]);
        $formValidate['password'] = bcrypt($formValidate['password']);
        $user = User::create($formValidate);
        auth()->login($user);
        return redirect('/')->with('message' , 'User Created Success');
     }

     public static function loginAuth(Request $request){
        $formValidate = $request->validate([
            'email' => ['email' , 'required' ] ,
            'password' => ['required' ]
        ]);
        if(auth()->attempt($formValidate)){
            $request->session()->regenerate();
            return redirect('/')->with('message' , 'You are logged in!');
        }
        return back()->withErrors(['email'=> 'Something Invalid'])->onlyInput('email');
     }
}