@extends('layouts.app')

@section('content')

<div class="page-wrapper">
    <div class="row page-titles">

        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.print_order')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>

                <li class="breadcrumb-item"><a href="{!! route('orders') !!}">{{trans('lang.order_plural')}}</a>
                </li>

                <li class="breadcrumb-item">{{trans('lang.print_order')}}</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card" id="printableArea" style="font-family: emoji;">
            <div class="col-md-12">
                <div class="print-top non-printable mt-3">
                    <div id="data-table_processing" class="dataTables_processing panel panel-default non-printable"
                        style="display: none;">{{trans('lang.processing')}}</div>
                    <div class="text-right print-btn non-printable">
                        <button type="button" class="fa fa-print non-printable"
                            onclick="printDiv('printableArea')"></button>
                    </div>
                </div>

                <hr class="non-printable">
            </div>
            <div class="col-12">

                <div class="row mt-3">
                    <div class="col-6">
                        <h5>{{trans('lang.order_id')}} : <label class="orderId"></label></h5>
                    </div>
                    <div class="col-6">
                        <h5 style="font-weight: lighter">
                            <label class="orderDate"></label>

                        </h5>
                    </div>
                    <div class="col-12">
                        <h5>
                            {{trans('lang.customer_name')}} :
                            <label class="customerName"></label>
                        </h5>
                        <h5>
                            {{trans('lang.phone')}} :

                            <label class="customerPhone"></label>
                        </h5>
                        <h5>
                            {{trans('lang.payment_method')}} :

                            <label><span id="payment_method"></span></label>
                        </h5>

                    </div>
                </div>
                <h5 class="text-uppercase"></h5>
                <span>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</span>
                <table class="table table-bordered mt-3" style="width: 92%">
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
                <span>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</span>
                <div class="row justify-content-md-end mb-3" style="width: 97%">
                    <div class="col-md-7 col-lg-7">
                        <dl class="row text-right">
                            <dt class="col-6">{{trans('lang.items_price')}} :</dt>
                            <dd class="col-6"><label class="total_item_price"></label>
                            </dd>
                            <dt class="col-6">{{trans('lang.sub_total')}} :</dt>
                            <dd class="col-6">
                                <label class="total_price"></label>
                            </dd>
                            <dt class="col-6">{{trans('lang.coupon_discount')}} :</dt>
                            <dd class="col-6">
                                -
                                <label class="total_discount_amount"></label>
                            </dd>

                            <span class="taxes row w-100 m-0"></span>

                            <dt class="col-6">{{trans('lang.deliveryFee')}} :</dt>
                            <dd class="col-6">
                                <label class="total_delivery_amount">+ $ 0</label>
                                <hr>
                            </dd>
                            <dt class="col-6" style="font-size: 20px">{{trans('lang.total')}} :
                            </dt>
                            <dd class="col-6" style="font-size: 20px">
                                <label class="total_amount"></label>
                            </dd>
                        </dl>
                    </div>
                </div>
                <span>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</span>
                <h5 class="text-center pt-3">
                    {{trans('lang.thank_you')}}
                </h5>
                <span>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</span>
            </div>
        </div>
    </div>
</div>

@endsection

@section('style')
<style type="text/css">
    #printableArea * {
        color: black !important;
    }

    @media print {
        @page {
            size: portrait;
        }

        .non-printable {
            display: none;
        }

        .printable {
            display: block;
            font-family: emoji !important;
        }

        #printableArea {
            width: 400px;
        }

        body {
            -webkit-print-color-adjust: exact !important;
            /* Chrome, Safari */
            color-adjust: exact !important;
            font-family: emoji !important;
        }

    }
</style>
<style type="text/css" media="print">
    @page {
        size: portrait;
    }

    @page {
        size: auto;
        /* auto is the initial value */
        margin: 2px;
        /* this affects the margin in the printer settings */
        font-family: emoji !important;
    }
</style>
@endsection
@section('scripts')

<script>
    var adminCommission = 0;
    var id_rendom = "<?php echo uniqid();?>";
    var id = "<?php echo $id;?>";
    var deliveryChargeVal = 0;
    var tip_amount_val = 0;
    var tip_amount = 0;
    var total_price = 0;
    var total_item_price = 0;

    var vendorname = '';
    var place_image = '';
    var database = firebase.firestore();
    var ref = database.collection('orders').where("id", "==", id);
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

    ref.get().then(async function (snapshots) {

        var order = snapshots.docs[0].data();

        $(".customerName").text(order.user.name);

        $(".orderId").text(id);

        var date = order.createdAt.toDate().toDateString();
        var time = order.createdAt.toDate().toLocaleTimeString('en-US');
        $(".orderDate").text(date + " " + time);

        if (order.user.hasOwnProperty('phoneNumber')) {
            $(".customerPhone").text((order.user.hasOwnProperty('countryCode')) ? order.user.countryCode + "" + EditPhoneNumber(order.user.phoneNumber) : EditPhoneNumber(order.user.phoneNumber));
        }

        if (order.user.hasOwnProperty('email')) {
            $("#billing_email").html('<a href="mailto:' + order.user.email + '">' + shortEmail(order.user.email) + '</a>');
        }

        if (order.createdAt) {
            var date1 = order.createdAt.toDate().toDateString();
            var date = new Date(date1);
            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = date.getFullYear();
            var createdAt_val = yyyy + '-' + mm + '-' + dd;
            var time = order.createdAt.toDate().toLocaleTimeString('en-US');
            $('#createdAt').text(createdAt_val + ' ' + time);
        }

        var paymentMethod = '';
        if (order.paymentMethod) {

            if ((order.paymentMethod).toLowerCase() == "stripe") {
                image = '{{asset("images/stripe.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';

            } else if ((order.paymentMethod).toLowerCase() == "razorpay") {
                image = '{{asset("images/razorepay.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';

            } else if ((order.paymentMethod).toLowerCase() == "paypal") {
                image = '{{asset("images/paypal.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';

            } else if ((order.paymentMethod).toLowerCase() == "payfast") {
                image = '{{asset("images/payfast.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '" width="30%" height="30%">';

            } else if ((order.paymentMethod).toLowerCase() == "paystack") {
                image = '{{asset("images/paystack.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';

            } else if ((order.paymentMethod).toLowerCase() == "flutterwave") {
                image = '{{asset("images/flutter_wave.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';

            } else if ((order.paymentMethod).toLowerCase() == "mercadopago") {
                image = '{{asset("images/marcado_pago.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';

            } else if ((order.paymentMethod).toLowerCase() == "wallet") {
                image = '{{asset("images/gromart_wallet.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%" >';

            } else if ((order.paymentMethod).toLowerCase() == "paytm") {
                image = '{{asset("images/paytm.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';

            } else if (order.paymentMethod == "cancelled order payment") {
                image = '{{asset("images/cancel_order.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';

            } else if (order.paymentMethod == "refund amount") {
                image = '{{asset("images/refund_amount.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';
            } else if (order.paymentMethod == "referral amount") {
                image = '{{asset("images/reffral_amount.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';
            } else if (order.paymentMethod == "COD" || order.paymentMethod == "Cash On Delivery") {
                image = '{{asset("images/cashondelivery.png")}}';
                paymentMethod = '<img alt="image" src="' + image + '"  width="30%" height="30%">';
            } else {
                paymentMethod = order.paymentMethod;
            }
        }
        $('#payment_method').html(paymentMethod);
        append_procucts_list = document.getElementById('order_products');
        append_procucts_list.innerHTML = '';

        var productsListHTML = buildHTMLProductsList(order.products);
        var productstotalHTML = buildHTMLProductstotal(order);

        if (productsListHTML != '') {
            append_procucts_list.innerHTML = productsListHTML;
        }

        var price = 0;

        jQuery("#data-table_processing").hide();
    });

    async function getVendorData(id) {
        return await database.collection('vendors').where("author", "==", id).get().then(async function (snapshotsorder) {

            return snapshotsorder.docs[0].data();
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

            if (product.size) {
                html = html + '<div class="type"><span>{{trans("lang.type")}} :</span><span class="ext-size">' + product.size + '</span></div>';
            }

            if (product.variant_info) {
                html += '<div class="variant-info">';
                html += '<ul>';
                $.each(product.variant_info.variant_options, function (label, value) {
                    html += '<li class="variant"><span class="label">' + label + '</span><span class="value">' + value + '</span></li>';
                });
                html += '</ul>';
                html += '</div>';
            }

            var item_price = parseFloat(parseFloat(val.discountPrice) > 0 ? parseFloat(val.discountPrice) : parseFloat(val.price));

            price_item = parseFloat(val.discountPrice).toFixed(decimal_degits);

            totalProductPrice = parseFloat(item_price) * parseInt(val.quantity);

            totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits);

            if (currencyAtRight) {
                var price_val = parseFloat(item_price).toFixed(decimal_degits) + "" + currentCurrency;
                var totalProductPrice_val = parseFloat(totalProductPrice).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                var price_val = currentCurrency + "" + parseFloat(item_price).toFixed(decimal_degits);
                var totalProductPrice_val = currentCurrency + "" + parseFloat(totalProductPrice).toFixed(decimal_degits);
            }

            html = html + '</div></div></td>';
            html = html + '<td>' + price_val + '</td><td>' + val.quantity + '</td><td>  ' + totalProductPrice_val + '</td>';

            html = html + '</tr>';
            total_price += parseFloat(totalProductPrice);
            total_item_price += parseFloat(item_price);

        });

        totalProductPrice = 0;

        if (currencyAtRight) {
            total_item_price = parseFloat(total_item_price).toFixed(decimal_degits) + "" + currentCurrency;

            $('.total_price').text(parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency);
        } else {
            total_item_price = currentCurrency + "" + parseFloat(total_item_price).toFixed(decimal_degits);

            $('.total_price').text(currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits));
        }
        $('.total_item_price').text(total_item_price);

        return html;
    }

    function buildHTMLProductstotal(snapshotsProducts) {
        var html = '';
        var alldata = [];
        var number = [];

        var adminCommission = snapshotsProducts.adminCommission;
        var adminCommissionType = snapshotsProducts.adminCommissionType;
        var discount = snapshotsProducts.discount;
        var couponCode = snapshotsProducts.couponCode;
        var rejectedByDrivers = snapshotsProducts.rejectedByDrivers;
        var takeAway = snapshotsProducts.takeAway;
        var tip_amount = snapshotsProducts.tip_amount;
        var notes = snapshotsProducts.notes;
        var tax_amount = snapshotsProducts.vendor.tax_amount;
        var status = snapshotsProducts.status;
        var products = snapshotsProducts.products;
        var deliveryCharge = snapshotsProducts.deliveryCharge;
        var specialDiscount = snapshotsProducts.specialDiscount;

        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        if (snapshotsProducts.hasOwnProperty('coupon') && snapshotsProducts.coupon.hasOwnProperty('discountType') && snapshotsProducts.coupon.discountType != '' && snapshotsProducts.coupon.discountType != null && snapshotsProducts.coupon.hasOwnProperty('isEnabled') && snapshotsProducts.coupon.isEnabled) {

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

            $('.total_discount_amount').text(discount_val);

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

                        $('.taxes').append("<dt class='col-6'> " + taxlabel + " (" + taxvalue + taxlabeltype + ")</dt><dd class='col-6'><label> +" + parseFloat(tax).toFixed(decimal_degits) + " " + currentCurrency + "</label></dt>");
                    } else {
                        $('.taxes').append("<dt class='col-6'> " + taxlabel + " (" + taxvalue + taxlabeltype + ")</dt><dd class='col-6'><label> +" + currentCurrency + " " + parseFloat(tax).toFixed(decimal_degits) + "</label></dt>");
                    }
                }
            }
            total_price = parseFloat(total_price) + parseFloat(total_tax_amount);
        }
        if (intRegex.test(deliveryCharge) || floatRegex.test(deliveryCharge)) {

            deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits);
            total_price += parseFloat(deliveryCharge);
        }

        if (currencyAtRight) {
            deliveryCharge_val = "+ " + parseFloat(deliveryCharge).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            deliveryCharge_val = "+ " + currentCurrency + "" + parseFloat(deliveryCharge).toFixed(decimal_degits);
        }

        deliveryChargeVal = deliveryCharge;
        $('.total_delivery_amount').text(deliveryCharge_val);


        var total_item_price = total_price;

        if (currencyAtRight) {
            total_price_val = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;

        } else {
            total_price_val = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);

        }

        $('.total_amount').text(total_price_val);

        return html;
    }

    function printDiv(divName) {

        var css = '@page { size: portrait; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>

@endsection