<?php

namespace App\Http\Controllers;


class AdminPaymentsController extends Controller
{  

   public function __construct()
    {
        $this->middleware('auth');
    }
    
	public function index()
    {
       return view("payments.index");
    }

}
