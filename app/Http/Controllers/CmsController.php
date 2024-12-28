<?php

namespace App\Http\Controllers;


class CmsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('cms.index');
    }
    public function edit($id)
    {
        return view('cms.edit')->with('id',$id);
    }

    public function create()
    {
        return view('cms.create');
    }

}