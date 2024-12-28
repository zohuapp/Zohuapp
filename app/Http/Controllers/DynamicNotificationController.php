<?php

namespace App\Http\Controllers;

class DynamicNotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("dynamic_notifications.index");
    }

    public function view($id)
    {
        return view("dynamic_notifications.view")->with('id', $id);
    }


}