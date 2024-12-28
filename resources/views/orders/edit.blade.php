@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">

        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.order_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>

                <li class="breadcrumb-item"><a href="{!! route('orders') !!}">{{trans('lang.order_plural')}}</a>
                </li>
                <li class="breadcrumb-item">{{trans('lang.order_edit')}}</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid no_data_found">

        <div class="card-body pb-5 p-0">
            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
                {{trans('lang.processing')}}
            </div>

            <div class="text-right print-btn pb-3">
                <?php if (in_array('vendors.orderprint', json_decode(@session('user_permissions'), true))) { ?>
                <a href="{{route('vendors.orderprint', $id)}}">
                    <button type="button" class="fa fa-print"></button>
                </a>

                <?php } ?>

                <button type="button" id="" onclick="downloadExcel()" class="btn btn-success">
                    <i class="fa fa-arrow-down"></i> {{trans('lang.invoice')}}
                </button>
                <div id="data-table" hidden>

                </div>

            </div>


            <div class="order_detail" id="order_detail">
                <div class="order_detail-top">
                    <div class="row">
                        <div class="order_edit-genrl col-lg-7 col-md-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h3>{{trans('lang.general_details')}}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="order_detail-top-box">

                                        <div class="form-group row widt-100 gendetail-col">
                                            <label class="col-12 control-label"><strong>{{trans('lang.order_id')}}
                                                    : </strong><span id="order_id"></span></label>
                                        </div>

                                        <div class="form-group row widt-100 gendetail-col">
                                            <label class="col-12 control-label"><strong>{{trans('lang.date_created')}}
                                                    : </strong><span id="createdAt"></span></label>
                                        </div>

                                        <div class="form-group row widt-100 gendetail-col payment_method">
                                            <label class="col-12 control-label"><strong>{{trans('lang.payment_methods')}}
                                                    : </strong><span id="payment_method"></span></label>
                                        </div>

                                        <div class="form-group row widt-100 gendetail-col">
                                            <label class="col-12 control-label"><strong>{{trans('lang.order_type')}}
                                                    :</strong>
                                                <span id="order_type"></span></label>
                                        </div>

                                        <div class="form-group row widt-100 gendetail-col schedule_date">

                                        </div>
                                        <div class="form-group row widt-100 gendetail-col prepare_time">

                                        </div>

                                        <div class="form-group row widt-100 gendetail-col">
                                            <label class="col-12 control-label"><strong>{{trans('lang.status')}}
                                                    :</strong>
                                                <span id="order_status"></span></label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="order-items-list mt-4">

                                <div class="card">
                                    <div class="card-body">
                                        <table cellpadding="0" cellspacing="0"
                                            class="table table-striped table-valign-middle">

                                            <thead>
                                                <tr>
                                                    <th>{{trans('lang.item')}}</th>
                                                    <th>{{trans('lang.price')}}</th>
                                                    <th>{{trans('lang.qty')}}</th>
                                                    <th>{{trans('lang.total')}}</th>
                                                </tr>

                                            </thead>

                                            <tbody id="order_products">

                                            </tbody>
                                        </table>
                                        <div class="order-data-row order-totals-items">
                                            <div class="card">

                                                <div class="card-body">
                                                    <div class="table-responsive bk-summary-table">
                                                        <table class="order-totals">

                                                            <tbody id="order_products_total">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="order_addre-edit col-lg-5 col-md-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h3>{{ trans('lang.billing_details')}}</h3>
                                </div>
                                <div class="card-body">
                                    <h4 class="user-title" style="text-align: center;"></h4>
                                    <div class="address order_detail-top-box user-box">
                                        <p>
                                            <strong>{{trans('lang.name')}}: </strong><span id="billing_name"></span>
                                        </p>
                                        <p><strong>{{trans('lang.email_address')}}:</strong>
                                            <span id="billing_email"></span>
                                        </p>
                                        <p><strong>{{trans('lang.phone')}}:</strong>
                                            <span id="billing_phone"></span>
                                        </p>
                                        <p><strong>{{trans('lang.delivery_address')}}:</strong>
                                            <span id="delivery_address"></span>
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <div class="order_addre-edit mt-4">
                                <div class="card ">
                                    <div class="card-header bg-white">
                                        <h3>{{ trans('lang.driver_detail')}}</h3>
                                        <div class="assign-driver d-none"><a href="javascript:void(0)"
                                                data-toggle="modal" data-target="#assignDriver"
                                                class="btn btn-success"><i class="fa fa-plus"></i>
                                                {{trans('lang.assign_another_driver')}}
                                            </a></div>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="driver-title" style="text-align: center;"></h4>
                                        <div class="address order_detail-top-box driver-box">
                                            <p>
                                                <strong>{{trans('lang.name')}}: </strong><span
                                                    id="driver_firstName"></span> <br>
                                            </p>
                                            <p><strong>{{trans('lang.email_address')}}:</strong>
                                                <span id="driver_email"></span>
                                            </p>
                                            <p><strong>{{trans('lang.phone')}}:</strong>
                                                <span id="driver_phone"></span>
                                            </p>
                                            <p><strong>{{trans('lang.car_name')}}:</strong>
                                                <span id="driver_carName"></span>
                                            </p>
                                            <p><strong>{{trans('lang.car_number')}}:</strong>
                                                <span id="driver_carNumber"></span>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>
                </div>

            </div>


        </div>
        <div class="form-group col-12 text-center btm-btn">

            <a href="{!! route('orders') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{trans('lang.cancel')}}
            </a>

        </div>

    </div>


</div>

<div class="modal fade" id="assignDriver" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered location_modal">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">{{trans('lang.assign_another_driver')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form class="">

                    <div class="form-row">

                        <div class="form-group row">

                            <div class="form-group row width-100">
                                <label class="col-12 control-label">{{
    trans('lang.driver')}}</label>
                                <div class="col-12">
                                    <select class="form-control" id="driver"></select>
                                    <div id="driver_error" style="color:red"></div>
                                </div>
                            </div>

                        </div>

                    </div>

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary assign_driver_btn"
                        id="assign-driver-btn">{{trans('submit')}}
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                        {{trans('close')}}
                    </button>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection

@section('style')

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>

<script>

    var id_rendom = "<?php echo uniqid(); ?>";
    var id = "<?php echo $id; ?>";
    var driverId = '';
    var old_order_status = '';
    var deliveryChargeVal = 0;
    var deliveryCharge = 0;
    var fcmToken = '';
    var manfcmTokenVendor = '';
    var fcmTokenVendor = '';
    var customername = '';
    var vendorname = '';
    var page_size = 5;
    var database = firebase.firestore();
    var ref = database.collection('orders').where("id", "==", id);
    var ref_review_attributes = database.collection('review_attributes');
    var selected_review_attributes = '';
    var refUserReview = database.collection('foods_review').where('orderid', '==', id);
    var append_procucts_list = '';
    var append_procucts_total = '';
    var total_price = 0;
    var currentCurrency = '';
    var currencyAtRight = false;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var orderPreviousStatus = '';
    var orderTakeAwayOption = false;


    var manname = '';

    var decimal_degits = 0;
    var orderCustomerId = '';
    var orderPaytableAmount = 0;
    var vendorAuthor = '';

    var refDrivers = database.collection('users').where('role', '==', "driver").where('active', '==', true);

    var user_permissions = '<?php echo @session('user_permissions') ?>';

    user_permissions = JSON.parse(user_permissions);

    var checkPrintPermission = false;

    if ($.inArray('vendors.orderprint', user_permissions) >= 0) {
        checkPrintPermission = true;
    }
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;

        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    refDrivers.get().then(async function (snapshots) {
        $('#driver').html('');

        $('#driver').append('<option value="" selected>{{trans("lang.select_driver")}}</option>');

        snapshots.docs.forEach((listval) => {
            var data = listval.data();

            $('#driver').append('<option value="' + data.id + '">' + data.name + '</option>');
        });
    });

    async function getDriverData(id) {
        return await database.collection('users').where("id", "==", id).get().then(async function (snapshotsorder) {

            return snapshotsorder.docs[0].data();
        });
    }


    $('.assign_driver_btn').click(async function () {

        var driverID = $('#driver option:selected').val();


        if (driverID == "") {

            $('#driver_error').html("{{trans('lang.select_driver_error')}}");
            return false;
        }
        var driverData = await getDriverData(driverID);
        database.collection('orders').doc(id).update({
            'driver': driverData,
            'driverID': driverID,
        }).then(function (result) {

            window.location.reload();

        });

    });

    var geoFirestore = new GeoFirestore(database);
    var place_image = '';
    var placeHolderImage = database.collection('settings').doc("placeHolderImage");
    placeHolderImage.get().then(async function (snapshots) {
        var placeHolderImage = snapshots.data();
        place_image = placeHolderImage.image;
    });

    async function downloadExcel() {

        var ordersRef = database.collection('orders').where("id", "==", id);

        ordersRef.get().then(async function (orderSnapshots) {

            var reportData = await getReportData(orderSnapshots);

        }).catch((error) => {

            $(".error_top").show();
            $(".error_top").html(error);
            window.scrollTo(0, 0);
        });
    }

    $(document).ready(function () {

        $('.time-picker').timepicker({
            timeFormat: "HH:mm",
            showMeridian: false,
            format24: true
        });

        $('.time-picker').timepicker().on('changeTime.timepicker', function (e) {
            var hours = e.time.hours,
                min = e.time.minutes;
            if (hours < 10) {
                $(e.currentTarget).val('0' + hours + ':' + min);
            }

        });

        var alovelaceDocumentRef = database.collection('orders').doc();
        if (alovelaceDocumentRef.id) {
            id_rendom = alovelaceDocumentRef.id;
        }
        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });

        jQuery("#data-table_processing").show();

        ref.get().then(async function (snapshots) {
            if (snapshots.docs.length > 0) {
                var order = snapshots.docs[0].data();

                append_procucts_list = document.getElementById('order_products');
                append_procucts_list.innerHTML = '';

                append_procucts_total = document.getElementById('order_products_total');
                append_procucts_total.innerHTML = '';

                var user = await getuser(order.userID);
                $("#trackng_number").text(id);

                if (user > 0) {
                    $("#billing_name").text(order.user.name);

                    if (order.hasOwnProperty('address')) {
                        $("#delivery_address").text(order.address.locality);
                    }

                    if (order.user.hasOwnProperty('phoneNumber')) {
                        $("#billing_phone").text((order.user.hasOwnProperty('countryCode')) ? order.user.countryCode + "" + EditPhoneNumber(order.user.phoneNumber) : EditPhoneNumber(order.user.phoneNumber));
                    }

                    if (order.user.hasOwnProperty('email') && order.user.email != "") {
                        $("#billing_email").html('<a href="mailto:' + order.user.email + '">' + shortEmail(order.user.email) + '</a>');
                    } else {
                        $("#billing_email").html('N/A');
                    }
                } else {
                    $('.user-title').html('{{trans("lang.unknown")}}');
                    $('.user-box').hide();
                }

                $('#order_id').text(order.id);
                if (order.createdAt) {
                    var date1 = order.createdAt.toDate().toDateString();
                    var date = new Date(date1);
                    var dd = String(date.getDate()).padStart(2, '0');
                    var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = date.getFullYear();
                    var createdAt_val = dd + '-' + mm + '-' + yyyy;
                    var time = order.createdAt.toDate().toLocaleTimeString('en-US');

                    $('#createdAt').text(createdAt_val + ' ' + time);
                }

                if (order.paymentMethod) {
                    getPaymentImage(order.paymentMethod);
                } else {
                    $('#payment_method').html("-");
                }

                if (order.hasOwnProperty('takeAway') && order.takeAway) {
                    $('#order_type').text('{{trans("lang.order_takeaway")}}');

                } else {
                    $('#order_type').text('{{trans("lang.order_delivery")}}');

                }

                if (order.hasOwnProperty('driver') && order.driver != null && order.driver != "") {
                    var user = await getuser(order.driverID);

                    if (user > 0) {
                        $('#driver_carName').text(order.driver.carName);
                        $('#driver_carNumber').text(order.driver.carNumber);
                        $('#driver_email').html('<a href="mailto:' + order.driver.email + '">' + shortEmail(order.driver.email) + '</a>');
                        $('#driver_firstName').text(order.driver.name);
                        $('#driver_phone').text((order.driver.hasOwnProperty('countryCode')) ? order.driver.countryCode + "" + EditPhoneNumber(order.driver.phoneNumber) : EditPhoneNumber(order.driver.phoneNumber));

                    } else {
                        $('.driver-title').html('{{trans("lang.unknown")}}');
                        $('.driver-box').hide();
                    }

                } else {
                    $('.order_edit-genrl').removeClass('col-md-7').addClass('col-md-7');
                    $('.order_addre-edit').removeClass('col-md-5');
                    $('.driver-box').hide();

                }
                if (order.hasOwnProperty('driverID') && order.driverID != null && order.driverID != "") {
                    driverId = order.driverID;
                }
                if (order.vendor && order.vendor.author != '' && order.vendor.author != undefined) {
                    vendorAuthor = order.vendor.author;
                }
                var scheduleTime = '';
                if (order.hasOwnProperty('scheduleTime') && order.scheduleTime != null) {
                    scheduleTime = order.scheduleTime;
                    var scheduleDate = scheduleTime.toDate().toDateString();
                    var time = order.scheduleTime.toDate().toLocaleTimeString('en-US');
                    var scheduleDate = new Date(scheduleDate);
                    var dd = String(scheduleDate.getDate()).padStart(2, '0');
                    var mm = String(scheduleDate.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = scheduleDate.getFullYear();
                    var scheduleDate = yyyy + '-' + mm + '-' + dd;
                    var scheduleDateTime = scheduleDate + ' ' + time;
                    $('.schedule_date').append('<label class="col-12 control-label"><strong>{{trans("lang.schedule_date_time")}}:</strong><span id=""> ' + scheduleDateTime + '</span></label>')

                }
                if (order.hasOwnProperty('estimatedTimeToPrepare') && order.estimatedTimeToPrepare != null && order.estimatedTimeToPrepare != '') {

                    $('.prepare_time').append('<label class="col-12 control-label "><strong>{{trans("lang.prepare_time")}}:</strong><span id=""> ' + order.estimatedTimeToPrepare + '</span></label>')

                }

                fcmToken = order.user.fcmToken;
                vendorname = order.vendor.title;

                fcmTokenVendor = order.vendor.fcmToken;
                customername = order.user.name;

                vendorId = order.vendor.id;

                var productsListHTML = buildHTMLProductsList(order.products);
                var productstotalHTML = await buildHTMLProductstotal(order);

                if (productsListHTML != '') {
                    append_procucts_list.innerHTML = productsListHTML;
                }

                if (productstotalHTML != '') {
                    append_procucts_total.innerHTML = productstotalHTML;
                }


                if (order.status == 'InProcess') {
                    $('#order_status').html('<span class="order_placed">' + order.status + '</span>');
                } else if (order.status == 'InTransit') {
                    $('#order_status').html('<span class="in_transit">' + order.status + '</span>');
                } else if (order.status == 'Delivered') {
                    $('#order_status').html('<span class="order_completed">' + order.status + '</span>');
                }
                if (order.status != "InProcess") {
                    $('.assign-driver').html("");
                } else {
                    $('.assign-driver').removeClass('d-none');
                }

                var price = 0;

                if (order.userID) {
                    orderCustomerId = order.userID;
                }
            }else{
                $('.no_data_found').html('<p class="font-weight-bold text-center">{{trans("lang.not_found_data_error")}}</p>')
            }
            jQuery("#data-table_processing").hide();
        });


        async function getuser(id) {
            return await database.collection('users').where("id", "==", id).get().then(async function (snapshotsorder) {

                return snapshotsorder.docs.length;
            });
        }

        function getTwentyFourFormat(h, timeslot) {
            if (h < 10 && timeslot == "PM") {
                h = parseInt(h) + 12;
            } else if (h < 10 && timeslot == "AM") {
                h = '0' + h;
            }
            return h;
        }


    });

    async function getPaymentImage(paymentType) {

        await database.collection('settings').doc('payment').get().then(async function (snapshots) {
            var payment = snapshots.data();
            var payamentData = Object.values(payment).filter((data) => data.name.toLowerCase() == paymentType.toLowerCase()).map((filterData) => filterData);

            if (payamentData != null && payamentData != undefined && payamentData != "") {
                if (payamentData[0].image && payamentData[0].image != null) {
                    $('#payment_method').html('<img src="' + payamentData[0].image + '" alt="image" style="width: 80px">');
                } else {
                    $('#payment_method').html(paymentType);
                }
            } else {
                $('#payment_method').html(paymentType);
            }

        });
    }

    function buildHTMLProductsList(snapshotsProducts) {
        var html = '';
        var alldata = [];
        var number = [];
        var totalProductPrice = 0;

        snapshotsProducts.forEach((product) => {

            var val = product;

            html = html + '<tr>';

            html = html + '<td class="order-product"><div class="order-product-box">';

            if (val.photo != '') {
                html = html + '<img class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + val.photo + '" alt="image">';
            } else {
                html = html + '<img class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + place_image + '" alt="image">';
            }


            html = html + '</div><div class="orders-tracking"><h6>' + val.name + '</h6><div class="orders-tracking-item-details">';

            if (val.variant_info) {
                html = html + '<div class="variant-info">';
                html = html + '<ul>';
                $.each(val.variant_info.variant_options, function (label, value) {
                    html = html + '<li class="variant"><span class="label">' + label + '</span><span class="value">' + value + '</span></li>';
                });
                html = html + '</ul>';
                html = html + '</div>';
            }

            if (product.size) {
                html = html + '<div class="type"><span>{{trans("lang.type")}} :</span><span class="ext-size">' + product.size + '</span></div>';
            }

            var item_price = parseFloat(parseFloat(val.discountPrice) > 0 ? parseFloat(val.discountPrice) : parseFloat(val.price));

            price_item = parseFloat(item_price).toFixed(decimal_degits);

            totalProductPrice = parseFloat(price_item) * parseInt(val.quantity);

            totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits);

            if (currencyAtRight) {
                var price_val = parseFloat(price_item).toFixed(decimal_degits) + "" + currentCurrency;
                var totalProductPrice_val = parseFloat(totalProductPrice).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                var price_val = currentCurrency + "" + parseFloat(price_item).toFixed(decimal_degits);
                var totalProductPrice_val = currentCurrency + "" + parseFloat(totalProductPrice).toFixed(decimal_degits);
            }

            html = html + '</div></div></td>';
            html = html + '<td class="text-green">' + price_val + '</td><td> × ' + val.quantity + '</td><td class="text-green">  ' + totalProductPrice_val + '</td>';

            html = html + '</tr>';
            total_price += parseFloat(totalProductPrice);

        });
        totalProductPrice = 0;

        return html;
    }


    function buildHTMLProductstotal(snapshotsProducts) {
        var html = '';
        var alldata = [];
        var number = [];

        var couponCode = snapshotsProducts.couponCode;
        var rejectedByDrivers = snapshotsProducts.rejectedByDrivers;
        var takeAway = snapshotsProducts.takeAway;

        var notes = snapshotsProducts.notes;
        var tax_amount = snapshotsProducts.vendor.tax_amount;
        var status = snapshotsProducts.status;
        var products = snapshotsProducts.products;
        deliveryCharge = snapshotsProducts.deliveryCharge;

        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        if (products) {

            products.forEach((product) => {
                var val = product;
            });
        }
        if (currencyAtRight) {
            var sub_total = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            var sub_total = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);
        }
        html = html + '<tr><td class="seprater" colspan="2"><hr><span>{{trans("lang.sub_total")}}</span></td></tr>';

        html = html + '<tr class="final-rate"><td class="label">{{trans("lang.sub_total")}}</td><td class="sub_total" style="color:green">(' + sub_total + ')</td></tr>';

        if (snapshotsProducts.hasOwnProperty('coupon') && snapshotsProducts.coupon.hasOwnProperty('discountType') && snapshotsProducts.coupon.discountType != '' &&  snapshotsProducts.coupon.discountType != null && snapshotsProducts.coupon.hasOwnProperty('isEnabled') && snapshotsProducts.coupon.isEnabled) {

            var coupon = snapshotsProducts.coupon;
            html = html + '<tr><td class="seprater" colspan="2"><hr><span>{{trans("lang.discount")}}</span></td></tr>';

            var discount_amount = coupon.discount;
            var labeltype = "";

            if (coupon.discountType == "Percentage") {
                discount_amount = (coupon.discount * total_price) / 100;
                labeltype = "(" + coupon.discount + "%)";
            }
            total_price -= parseFloat(discount_amount);


            if (currencyAtRight) {
                discount_val = parseFloat(discount_amount).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                discount_val = currentCurrency + "" + parseFloat(discount_amount).toFixed(decimal_degits);
            }


            couponCode_html = '</br><small>{{trans("lang.coupon_codes")}} :' + coupon.code + '</small>';

            html = html + '<tr><td class="label">{{trans("lang.discount")}}' + labeltype + couponCode_html + '</td><td class="discount" style="color:red">(-' + discount_val + ')</td></tr>';
        }


        var total_item_price = total_price;
        var tax = 0;
        taxlabel = '';
        taxlabeltype = '';

        if (snapshotsProducts.hasOwnProperty('tax') && snapshotsProducts.tax.length > 0) {
            html = html + '<tr><td class="seprater" colspan="2"><hr><span>{{trans("lang.tax_calculation")}}</span></td></tr>';

            var total_tax_amount = 0;
            for (var i = 0; i < snapshotsProducts.tax.length; i++) {
                var data = snapshotsProducts.tax[i];

                if (data.type && data.tax) {
                    if (data.type == "percentage") {
                        tax = (data.tax * total_price) / 100;
                        var taxvalue = data.tax;
                        taxlabeltype = "%";
                    } else {
                        tax = data.tax;
                        taxlabeltype = "";
                        if (currencyAtRight) {
                            var taxvalue = parseFloat(data.tax).toFixed(decimal_degits) + "" + currentCurrency;
                        } else {
                            var taxvalue = currentCurrency + "" + parseFloat(data.tax).toFixed(decimal_degits);

                        }

                    }
                    taxlabel = data.title;
                }
                total_tax_amount += parseFloat(tax);
                if (!isNaN(tax) && tax != 0) {
                    if (currencyAtRight) {
                        html = html + '<tr><td class="label">' + taxlabel + " (" + taxvalue + taxlabeltype + ')</td><td class="tax_amount" id="greenColor" style="color:green">+' + parseFloat(tax).toFixed(decimal_degits) + '' + currentCurrency + '</td></tr>';
                    } else {
                        html = html + '<tr><td class="label">' + taxlabel + " (" + taxvalue + taxlabeltype + ')</td><td class="tax_amount" id="greenColor" style="color:green">+' + currentCurrency + parseFloat(tax).toFixed(decimal_degits) + '</td></tr>';
                    }


                }
            }
            total_price = parseFloat(total_price) + parseFloat(total_tax_amount);
        }

        if (intRegex.test(deliveryCharge) || floatRegex.test(deliveryCharge)) {
            html = html + '<tr><td class="seprater" colspan="2"><hr><span>{{trans("lang.delivery_charge")}}</span></td></tr>';

            deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits);
            total_price += parseFloat(deliveryCharge);

            if (currencyAtRight) {
                deliveryCharge_val = parseFloat(deliveryCharge).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                deliveryCharge_val = currentCurrency + "" + parseFloat(deliveryCharge).toFixed(decimal_degits);
            }

            deliveryChargeVal = deliveryCharge;
            html = html + '<tr><td class="label">{{trans("lang.deliveryCharge")}}</td><td class="deliveryCharge" style="color:green">+' + deliveryCharge_val + '</td></tr>';
        }
        html += '<tr><td class="seprater" colspan="2"><hr></td></tr>';

        orderPaytableAmount = total_price;

        if (currencyAtRight) {
            total_price_val = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            total_price_val = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);
        }

        html = html + '<tr class="grand-total"><td class="label"><strong>{{trans("lang.total_amount")}}</strong></td><td class="total_price_val" style="color:green" ><strong>' + total_price_val + '</strong></td></tr>';

        if (notes) {

            html = html + '<tr><td class="label">{{trans("lang.notes")}}</td><td class="adminCommission_val">' + notes + '</td></tr>';
        }


        return html;
    }

    function PrintElem(elem) {

        jQuery('#' + elem).printThis({
            debug: false,
            importStyle: true,
            loadCSS: [
                '<?php echo asset('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>',
                '<?php echo asset('css/style.css'); ?>',
                '<?php echo asset('css/colors/blue.css'); ?>',
                '<?php echo asset('css/icons/font-awesome/css/font-awesome.css'); ?>',
                '<?php echo asset('assets/plugins/toast-master/css/jquery.toast.css'); ?>',
            ],

        });

    }


    $(document).on('click', '#download-invoice', function () {

        downloadExcel();
    });


    var count = 0;

    function getReportData(orderSnapshots) {
        var table = '<style>' +
            '    table {' +
            '        border-collapse: collapse;' +
            '        width: 100%;' +
            '    }' +
            '   .border-class{' +
            '        border: 1px solid black;' +
            '    }' +
            '   table tr.border-color{' +
            '       background-color: #C4D79B;' +
            '       text-align: center;' +
            '   }' +
            '</style>';
        table += '<table class="border-class">';

        table += '<tr><td></td><td></td><td></td><td colspan="10" align="center" style="font-size: 26px;font-family:Bookman Old Style"><b>E-Basket</b></td><td colspan="5" style="font-size: 20px;font-family:Bookman Old Style;text-align: center"><b>Origional</b></td><td class="border-class" style="background-color: black"></td></tr>';
        table += '<tr><td colspan="2" rowspan="2" align="center">';

        table += '</td><td></td><td></td><td colspan="9" align="center" style="font-size: 22px;font-family:Bookman Old Style"><b>Ahmedabad. PIN: 380060</b></td><td colspan="5" style="font-size: 20px;font-family:Bookman Old Style;text-align: center"><b>Duplicate</b></td><td class="border-class"></td></tr>';
        table += '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td colspan="5" style="font-size: 20px;font-family:Bookman Old Style;text-align: center"><b>Triplicate</b></td><td class="border-class"></td></tr>';
        table += '<tr><td></td><td></td><td></td><td></td><td></td><td colspan="6" align="center" style="font-size: 20px;font-family:Bookman Old Style"><b>GSTIN:-N/A</b></td><td></td><td></td><td colspan="5" style="font-size: 20px;font-family:Bookman Old Style;text-align: center"><b>Extra OC</b></td><td class="border-class"></td></tr>';
        table += '<tr><td class="border-class" colspan="19"></td></tr>';

        var invoice_date = new Date();

        var date1 = invoice_date.toDateString();
        var date = new Date(date1);
        var dd = String(date.getDate()).padStart(2, '0');
        var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = date.getFullYear();
        var createdAt_val = dd + '-' + mm + '-' + yyyy;
        var time = invoice_date.toLocaleTimeString('en-US');

        var invoiceDate = createdAt_val + ' ' + time;

        table += '<tr class="border-color"><td class="border-class" colspan="19" align="center" style="background-color:#C4D79B;font-size: 36px;font-family:Bookman Old Style"><b>Tax Invoice</b></td></tr>';
        table += '<tr><td class="border-class" colspan="8"><b>Invoice No: </b> ' + id + '</td><td class="border-class" colspan="11"></td></tr>';
        table += '<tr><td class="border-class" colspan="8"><b>Invoice date:</b> ' + invoiceDate + '</td><td class="border-class" colspan="11"></td></tr>';
        table += '<tr><td class="border-class" colspan="7"><b>Reverse Charge (Y/N):</b></td><td class="border-class"></td><td class="border-class" colspan="11"><b>Date :</b></td></tr>';
        table += '<tr><td class="border-class" colspan="6"><b>State:</b></td><td class="border-class"><b>Code</b></td><td class="border-class"></td><td class="border-class" colspan="11"><b>Place</b></td></tr>';

        var orderObj = orderSnapshots.docs[0].data();

        table += '<tr><td class="border-class"  style="background-color:#C4D79B;" colspan="8"><b>Detail of Receiver (Billed to)</b></td><td style="background-color:#C4D79B;" class="border-class" colspan="11"><b>Detail of Consignee (Shipped to)</td></tr>';
        table += '<tr><td class="border-class" colspan="8"><b>Name: </b>' + orderObj.user.name + '</td><td class="border-class" colspan="11"><b>Name: </b>' + orderObj.user.name + '</td></tr>';
        table += '<tr><td class="border-class" colspan="8"><b>Address: </b>' + orderObj.address.locality + '</td><td class="border-class" colspan="11"><b>Address: </b>' + orderObj.address.locality + '</td></tr>';
        table += '<tr><td class="border-class" colspan="6"><b>State:</b></td><td class="border-class"><b>Code</b></td><td class="border-class"></td><td class="border-class" colspan="6"><b>State:</b></td><td class="border-class"></td><td class="border-class"></td><td class="border-class"></td><td class="border-class"><b>Code</b></td><td class="border-class"></td></tr>';
        table += '<tr><td class="border-class" colspan="19"></td></tr>';

        var taxData = orderObj.tax[0];
        var productsData = orderObj.products;

        var totalQty = 0;
        var totalAmount = 0;
        var totalDiscount = 0;
        var totalTax = 0;
        var totalFinalAmount = 0;
        var totalAmountBeforeTax = 0;
        var taxAmount = 0;
        var count = 0;
        for (var i = 0; i < productsData.length; i++) {

            var products = productsData[i];

            var item_price = parseFloat(parseFloat(products.discountPrice) > 0 ? parseFloat(products.discountPrice) : parseFloat(products.price));

            totalAmountBeforeTax += parseFloat(item_price);
            totalQty += parseFloat(products.quantity);
            // totalDiscount += parseFloat(products.discountPrice);
            totalTax += parseFloat(item_price);
            totalFinalAmount += parseFloat(item_price);
            totalAmount += parseFloat(parseFloat(products.quantity) * parseFloat(item_price));

            if (count == 0) {

                table += '<tr class="border-color"><td class="border-class" rowspan="2"><b>S. No.</b></td><td class="border-class"  rowspan="2"><b>Product Description</b></td><td class="border-class"  rowspan="2"><b>HSN code</b></td><td class="border-class"  rowspan="2"><b>UOM</b></td><td class="border-class"  rowspan="2"><b>Qty</b></td><td class="border-class"  rowspan="2"><b>Rate</b></td><td class="border-class"  rowspan="2"><b>Amount</b></td><td class="border-class"  rowspan="2"><b>Discount</b></td><td class="border-class" colspan="2" rowspan="2"><b>Total</b></td></tr>';
                table += '<tr class="border-color"><td class="border-class" rowspan="2" colspan="3"><b></b></td><td rowspan="2" class="border-class" colspan="4"><b></b></td><td rowspan="2" class="border-class" colspan="2"></td></tr>';

            }


            table += '<tr><td class="border-class">' + (i + 1) + '</td><td class="border-class">' + products.name + '</td><td class="border-class">' + products.hsn_code + '</td><td class="border-class">' + products.unit + '</td><td class="border-class">' + products.quantity + '</td><td class="border-class">' + item_price + '</td><td class="border-class">' + parseFloat(products.quantity) * parseFloat(item_price) + '</td><td class="border-class">-</td><td class="border-class" colspan="2">' + parseFloat(products.quantity) * parseFloat(item_price) + '</td></tr>';
            count++;
        }

        var totalDiscountAmount = 0;

        if (orderObj.hasOwnProperty('coupon') && orderObj.coupon.hasOwnProperty('isEnabled') && orderObj.coupon.isEnabled && orderObj.coupon.discountType != '' && orderObj.coupon.discountType != null) {

            totalDiscountAmount = (orderObj.coupon.discountType == "Percentage") ? parseFloat(parseFloat(orderObj.coupon.discount) * totalAmount) / 100 : parseFloat(orderObj.coupon.discount);
            totalDiscount += parseFloat(totalDiscountAmount);
            table += '<tr><td class="border-class"></td><td class="border-class">Coupon Applied(' + orderObj.coupon.code + ')</td><td class="border-class"></td><td class="border-class"></td><td class="border-class"></td><td class="border-class"></td><td class="border-class"></td><td class="border-class">' + totalDiscountAmount + '</td><td class="border-class" colspan="2">-' + totalDiscountAmount + '</td><td class="border-class" colspan="3"></td><td class="border-class" colspan="4"></td><td class="border-class" colspan="2">-' + totalDiscountAmount + '</td></tr>';

        }
        totalAfterDiscount = totalAmount - totalDiscount;



        table += '<tr><td class="border-class" colspan="4" style="background-color:#C4D79B;font-size: 30px;font-family:Bookman Old Style"><b>Total</b></td><td class="border-class">' + totalQty + '</td><td class="border-class"></td><td class="border-class">' + totalAmount + '</td><td class="border-class">' + totalDiscountAmount + '</td><td class="border-class" colspan="2">' + totalAfterDiscount + '</td><td class="border-class" colspan="3"></td><td class="border-class" colspan="4"></td><td class="border-class" colspan="2">' + totalAfterDiscount + '</td></tr>';
        table += '<tr><td class="border-class" colspan="9" style="background-color:#C4D79B;"><b>Total Invoice amount in words</b></td><td class="border-class" colspan="8"><b>Total Amount before Tax</b></td><td class="border-class" colspan="2">' + totalAfterDiscount + '</td></tr>';
        var taxHtml = '';
        var total_tax_amount = 0;
        for (var i = 0; i < orderObj.tax.length; i++) {
            var data = orderObj.tax[i];
            if (data.type && data.tax) {
                if (data.type == "percentage") {
                    tax = (data.tax * totalAfterDiscount) / 100;
                    var taxvalue = data.tax;
                    taxlabeltype = "%";
                } else {
                    tax = data.tax;
                    taxlabeltype = "";


                }
                taxlabel = data.title;
                taxHtml += '<tr><td class="border-class" colspan="8"><b>Add: ' + taxlabel + '(' + data.tax + ' ' + taxlabeltype + ')' + '</b></td><td class="border-class" colspan="2">' + tax + '</td></tr>';
                total_tax_amount += parseFloat(tax);
            }

        }

        var finalAmount = parseFloat(parseFloat(totalAfterDiscount) + parseFloat(total_tax_amount));
        if (orderObj.deliveryCharge != '' && orderObj.deliveryCharge != null) {
            finalAmount += parseFloat(orderObj.deliveryCharge);
        }

        table += '<tr><td class="border-class" colspan="9" rowspan="10">' + convertNumberToWords(finalAmount) + '</td></tr>';
        table += taxHtml;
        table += '<tr><td class="border-class" colspan="8"><b>Delivery Charges</b></td><td class="border-class" colspan="2">' + orderObj.deliveryCharge + '</td></tr>';
        table += '<tr><td class="border-class" colspan="8"><b>Sales Return</b></td><td class="border-class" colspan="2">0</td></tr>';
        table += '<tr><td class="border-class" colspan="8"><b>Total Amount</b></td><td class="border-class" colspan="2">' + finalAmount + '</td></tr>';

        table += '<tr><td class="border-class" style="background-color:#C4D79B;" colspan="9"><b>Bank Details</b></td><td style="background-color:#C4D79B;" class="border-class" colspan="8"><b>GST on Reverse Charge</b></td><td class="border-class" colspan="2">0</td></tr>';
        table += '<tr><td class="border-class" colspan="9"><b>Bank Name:</b></td><td class="border-class" colspan="10" style="text-align: center;font-size: 15px;">Ceritified that the particulars given above are true and correct</td></tr>';
        table += '<tr><td class="border-class" colspan="9"><b>Bank A/C:</b></td><td class="border-class" colspan="10" style="text-align: center"><b>For XYZ & Co</b></td></tr>';
        table += '<tr><td class="border-class" colspan="9"><b>Bank IFSC: </b></td><td class="border-class" rowspan="5" colspan="10"></td></tr>';
        table += '<tr><td class="border-class" rowspan="5" colspan="9" style="vertical-align: top"><b>Terms & conditions:-</b></td></tr>';
        table += '<tr></tr>';
        table += '<tr></tr>';
        table += '<tr></tr>';
        table += '<tr><td class="border-class" colspan="10" style="text-align: center"><b>Authorised signatory<b></td></tr>';

        table += '<tr><td colspan="19"></td></tr>';
        table += '<tr><td colspan="19"></td></tr>';

        table += '</table>';

        $('#data-table').html(table);

        const table_data = document.getElementById('data-table');

        const htmlTable = table_data.outerHTML;

        // Create a Blob object containing the HTML table
        const blob = new Blob([htmlTable], { type: 'application/vnd.ms-excel' });

        // Create a URL for the Blob object
        const url = window.URL.createObjectURL(blob);

        // Create a download link
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', 'invoice_' + id + '.xls');

        // Trigger a click event on the link to initiate download
        link.click();

        // Cleanup: remove the link and revoke the URL
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);

    }

    function convertNumberToWords(amount) {
        var words = new Array();
        words[0] = '';
        words[1] = 'One';
        words[2] = 'Two';
        words[3] = 'Three';
        words[4] = 'Four';
        words[5] = 'Five';
        words[6] = 'Six';
        words[7] = 'Seven';
        words[8] = 'Eight';
        words[9] = 'Nine';
        words[10] = 'Ten';
        words[11] = 'Eleven';
        words[12] = 'Twelve';
        words[13] = 'Thirteen';
        words[14] = 'Fourteen';
        words[15] = 'Fifteen';
        words[16] = 'Sixteen';
        words[17] = 'Seventeen';
        words[18] = 'Eighteen';
        words[19] = 'Nineteen';
        words[20] = 'Twenty';
        words[30] = 'Thirty';
        words[40] = 'Forty';
        words[50] = 'Fifty';
        words[60] = 'Sixty';
        words[70] = 'Seventy';
        words[80] = 'Eighty';
        words[90] = 'Ninety';
        amount = amount.toString();
        var atemp = amount.split(".");
        var number = atemp[0].split(",").join("");
        var n_length = number.length;
        var words_string = "";
        if (n_length <= 9) {
            var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
            var received_n_array = new Array();
            for (var i = 0; i < n_length; i++) {
                received_n_array[i] = number.substr(i, 1);
            }
            for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                n_array[i] = received_n_array[j];
            }
            for (var i = 0, j = 1; i < 9; i++, j++) {
                if (i == 0 || i == 2 || i == 4 || i == 7) {
                    if (n_array[i] == 1) {
                        n_array[j] = 10 + parseInt(n_array[j]);
                        n_array[i] = 0;
                    }
                }
            }
            value = "";
            for (var i = 0; i < 9; i++) {
                if (i == 0 || i == 2 || i == 4 || i == 7) {
                    value = n_array[i] * 10;
                } else {
                    value = n_array[i];
                }
                if (value != 0) {
                    words_string += words[value] + " ";
                }
                if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Crores ";
                }
                if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Lakhs ";
                }
                if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Thousand ";
                }
                if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                    words_string += "Hundred and ";
                } else if (i == 6 && value != 0) {
                    words_string += "Hundred ";
                }
            }
            words_string = words_string.split("  ").join(" ");
        }
        return words_string;
    }

</script>

@endsection
