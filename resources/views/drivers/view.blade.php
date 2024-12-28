@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">

        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor restaurantTitle">{{trans('lang.driver_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('drivers') !!}">{{trans('lang.driver_plural')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.driver_details')}}</li>
            </ol>
        </div>

    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="resttab-sec">
                    <div id="data-table_processing" class="dataTables_processing panel panel-default"
                         style="display: none;">
                        Processing...
                    </div>
                    <div class="menu-tab">

                        <ul>
                            <li class="active">
                                <a href="{{route('drivers.view',$id)}}">{{trans('lang.tab_basic')}}</a>
                            </li>
                            <li>
                                <a href="{{route('orders')}}?driverId={{$id}}">{{trans('lang.tab_orders')}}</a>
                            </li>
                            <li>
                                <a href="{{route('users.walletstransaction', $id)}}">{{trans('lang.wallet_transaction_plural')}}</a>
                            </li>
                        </ul>

                    </div>

                    <div class="row restaurant_payout_create driver_details">
                        <div class="restaurant_payout_create-inner">
                            <fieldset>
                                <legend>{{trans('lang.driver_details')}}</legend>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.user_name')}}</label>
                                    <div class="col-7" class="driver_name">
                                        <span class="driver_name" id="driver_name"></span>
                                    </div>
                                </div>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.email')}}</label>
                                    <div class="col-7">
                                        <span class="email"></span>
                                    </div>
                                </div>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.user_phone')}}</label>
                                    <div class="col-7">
                                        <span class="phone"></span>
                                    </div>
                                </div>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.user_zipcode')}}</label>
                                    <div class="col-7">
                                        <span class="zipCode"></span>
                                    </div>
                                </div>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.profile_image')}}</label>
                                    <div class="col-7 profile_image">
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </div>

                    <div class="row restaurant_payout_create restaurant_details">
                        <div class="restaurant_payout_create-inner">
                            <fieldset>
                                <legend>{{trans('lang.car_details')}}</legend>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.car_number')}}</label>
                                    <div class="col-7">
                                        <span class="car_number"></span>
                                    </div>
                                </div>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.car_name')}}</label>
                                    <div class="col-7">
                                        <span class="car_name"></span>
                                    </div>
                                </div>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.car_image')}}</label>
                                    <div class="col-7 car_image">

                                    </div>
                                </div>

                            </fieldset>

                        </div>
                    </div>
                </div>

            </div>
            <div class="form-group col-12 text-center btm-btn">
                <a href="{!! route('drivers') !!}" class="btn btn-default"><i
                            class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>

    var id = "<?php echo $id; ?>";
    var database = firebase.firestore();
    var ref = database.collection('users').where("id", "==", id);
    var photo = "";

    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');

    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;

    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });


    $(document).ready(async function () {
        $('.driver_menu').addClass('active');

        jQuery("#data-table_processing").show();

        ref.get().then(async function (snapshots) {
            var driver = snapshots.docs[0].data();
            $(".driver_name").text(driver.name);
            $(".email").text(shortEmail(driver.email));
            $(".phone").text((driver.hasOwnProperty('countryCode')) ? driver.countryCode + "" + EditPhoneNumber(driver.phoneNumber) : EditPhoneNumber(driver.phoneNumber));
            $(".zipCode").text(driver.pinCode);

            var image = "";
            if (driver.profilePictureURL) {
                image = '<img width="200px" id="" height="auto" src="' + driver.profilePictureURL + '">';
            } else {
                image = '<img width="200px" id="" height="auto" src="' + placeholderImage + '">';
            }

            $(".profile_image").html(image);
            $(".car_number").text(driver.carNumber);
            $(".car_name").text(driver.carName);
            var car_image = "";
            if (driver.carPictureURL) {
                car_image = '<img width="200px" id="" height="auto" src="' + driver.carPictureURL + '">';
            } else {
                car_image = '<img width="200px" id="" height="auto" src="' + placeholderImage + '">';
            }
            $(".car_image").html(car_image);

            jQuery("#data-table_processing").hide();

        });

    });
</script>
@endsection