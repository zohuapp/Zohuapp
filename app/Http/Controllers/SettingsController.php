<?php

namespace App\Http\Controllers;


class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function globals()
    {

        return view("settings.app.global");
    }

    public function notifications()
    {
        return view("settings.app.notification");
    }


    public function deliveryCharge()
    {
        return view("settings.app.deliveryCharge");
    }

    public function languages()
    {
        return view('settings.languages.index');
    }

    public function saveLanguage($id=null)
    {
        return view('settings.languages.save')->with('id',$id);
    }

    public function emailTemplatesIndex()
    {
        return view('email_templates.index');
    }

    public function emailTemplatesSave($id = '')
    {

        return view('email_templates.save')->with('id', $id);
    }

    public function headerTemplate()
    {
        return view('header_template.index');
    }

    public function landingpageTemplate()
    {
        return view('landingpage_template.index');
    }
    public function homepageTemplate()
    {
        return view('homepage_Template.index');
    }
    public function footerTemplate()
    {
        return view('footerTemplate.index');
    }

    public function cod()
    {
        return view('settings.payments.cod');
    }

    public function applePay()
    {
        return view('settings.payments.applepay');
    }

    public function stripe()
    {
        return view('settings.payments.stripe');
    }

    public function razorpay()
    {
        return view('settings.payments.razorpay');
    }

    public function payfast()
    {
        return view('settings.payments.payfast');
    }

    public function paypal()
    {
        return view('settings.payments.paypal');
    }

    public function paystack()
    {
        return view('settings.payments.paystack');
    }

    public function flutterwave()
    {
        return view('settings.payments.flutterwave');
    }

    public function mercadopago()
    {
        return view('settings.payments.mercadopago');
    }

    public function wallet()
    {
        return view('settings.payments.wallet');
    }
    public function xendit()
    {
        return view('settings.payments.xendit');
    }
    public function orangepay()
    {
        return view('settings.payments.orangepay');
    }
    public function midtrans()
    {
        return view('settings.payments.midtrans');
    }


}
