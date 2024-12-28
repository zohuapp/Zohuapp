@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">

        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor restaurantTitle">{{trans('lang.user_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('users') !!}">{{trans('lang.user_plural')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.user_details')}}</li>
            </ol>
        </div>

    </div>

    <div class="container-fluid">
        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
            Processing...
        </div>

        <div class="row">
            <div class="col-12">

                <div class="resttab-sec">
                    <div class="menu-tab">

                        <ul>
                            <li class="active">
                                <a href="{{route('users.view', $id)}}">{{trans('lang.tab_basic')}}</a>
                            </li>
                            <li>
                                <a href="{{route('orders', 'userId=' . $id)}}">{{trans('lang.tab_orders')}}</a>
                            </li>
                            <li>
                                <a href="{{route('users.walletstransaction', $id)}}">{{trans('lang.wallet_transaction_plural')}}</a>
                            </li>
                        </ul>

                    </div>

                    <div class="row restaurant_payout_create user_details">
                        <div class="restaurant_payout_create-inner">

                            <fieldset>

                                <legend>{{trans('lang.user_details')}}</legend>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.first_name')}}</label>
                                    <div class="col-7">
                                        <span class="user_name" id="user_name"></span>
                                    </div>
                                </div>


                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.user_phone')}}</label>
                                    <div class="col-7">
                                        <span class="phone"></span>
                                    </div>
                                </div>

                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.email')}}</label>
                                    <div class="col-7">
                                        <span class="email"></span>
                                    </div>
                                </div>


                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.profile_image')}}</label>
                                    <div class="col-7 profile_image">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-3 control-label">{{trans('lang.address')}}</label>
                                    <div class="col-7">
                                        <span class="business_name" id="business_address"></span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                </div>

            </div>
            <div class="form-group col-12 text-center btm-btn">
                <a href="{!! route('users') !!}" class="btn btn-default"><i
                        class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    var id = "{{$id}}";
    var database = firebase.firestore();
    var ref = database.collection('users').where("id", "==", id);
    var photo = "";

    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');

    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    });

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
        $(".currentCurrency").text(currencyData.symbol);

    });

    $(document).ready(async function () {

        jQuery("#data-table_processing").show();

        ref.get().then(async function (snapshots) {
            var user = snapshots.docs[0].data();

            $(".user_name").text(user.name);

            if (user.hasOwnProperty('email') && user.email) {
                $(".email").text(shortEmail(user.email));

            } else {
                $('.email').html("{{trans('lang.not_mentioned')}}");

            }

            if (user.hasOwnProperty('phoneNumber') && user.phoneNumber) {
                $(".phone").text(EditPhoneNumber(user.phoneNumber));

            } else {
                $('.phone').html("{{trans('lang.not_mentioned')}}");

            }


            var address = '';
            if (user.hasOwnProperty('shippingAddress') && Array.isArray(user.shippingAddress)) {
                shippingAddress = user.shippingAddress;
                address += '<div id="append_list1" class="res-search-list row">';
                shippingAddress.forEach((listval) => {
                    var defaultBtnHtml = '';

                    if (listval.isDefault == true) {
                        defaultBtnHtml = '<span class="badge badge-success ml-2 py-2 px-3" type="button" >Default</span>';
                    }

                    address = address + '<div class="transactions-list-wrap mt-4 col-md-6">';
                    address += '<div class="bg-white rounded-lg mb-3 transactions-list-view shadow-sm">';
                    address += '<div class="gold-members d-flex align-items-start transactions-list">';

                    address = address + '<div class="media transactions-list-left w-100">';
                    address = address + '<div class="media-body"><h6 class="date">' + listval.address + "," + listval.locality + " " + listval.landmark + '</h6>';

                    address = address + '<span class="badge badge-info py-2 px-3">' + listval.addressAs + '</span>' + defaultBtnHtml;
                    address += '</div></div>';
                    address = address + '</div> </div></div>';
                });
                address += '</div>';

            }
            if (address != "") {
                $('#business_address').html(address);
            } else {
                $('#business_address').html("<h5>{{trans('lang.not_mentioned')}}</h5>");
            }


            if (user.pinCode) {
                $("#user_zipcode").text(user.pinCode);
            } else {
                $("#user_zipcode").text("{{trans('lang.not_mentioned')}}");
            }


            if (user.gstinNumber) {
                $("#gstin_number").text(user.gstinNumber);
            } else {
                $('#gstin_number').html("{{trans('lang.not_mentioned')}}");
            }
            if (user.fssaiNumber) {
                $("#fssai_number").text(user.fssaiNumber);

            } else {
                $("#fssai_number").text("{{trans('lang.not_mentioned')}}");

            }

            var image = "";
            if (user.image) {
                image = '<img width="100px" id="" height="auto" src="' + user.image + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'">';
            } else {
                image = '<img width="100px" id="" height="auto" src="' + placeholderImage + '">';
            }

            $('.profile_image').html(image);

            jQuery("#data-table_processing").hide();

        });

    });

</script>
@endsection