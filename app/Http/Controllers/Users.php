<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Users extends Controller
{
   public function index(){
        return view('welcome');
    }
    public function show(){
        return "ajib";
    }


}
