@extends('layouts.app')

@section('content')

<div id="main-wrapper" class="page-wrapper" style="min-height: 207px;">

    <div class="container-fluid">

        <div id="data-table_processing" class="dataTables_processing panel panel-default"
            style="display: none;margin-top:20px;">{{trans('lang.processing')}}
        </div>

        <div class="card mb-3 business-analytics">

            <div class="card-body">

                <div class="row flex-between align-items-center g-2 mb-3 order_stats_header">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            {{trans('lang.dashboard_business_analytics')}}
                        </h4>
                    </div>
                </div>

                <div class="row business-analytics_list">

                    <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="card-box" onclick="location.href='{!! route('walletstransaction') !!}'">
                            <h5>{{trans('lang.dashboard_total_earnings')}}</h5>
                            <h2 id="earnings_count"></h2>
                            <i class="mdi mdi-cash-usd"></i>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="card-box" onclick="location.href='{!! route('orders') !!}'">
                            <h5>{{trans('lang.dashboard_total_orders')}}</h5>
                            <h2 id="order_count"></h2>
                            <i class="mdi mdi-cart"></i>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="card-box" onclick="location.href='{!! route('items') !!}'">
                            <h5>{{trans('lang.dashboard_total_products')}}</h5>
                            <h2 id="product_count"></h2>
                            <i class="mdi mdi-buffer"></i>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="card-box" onclick="location.href='{!! route('users') !!}'">
                            <h5>{{trans('lang.dashboard_total_clients')}}</h5>
                            <h2 id="users_count"></h2>
                            <i class="mdi mdi-account"></i>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="card-box" onclick="location.href='{!! route('drivers') !!}'">
                            <h5>{{trans('lang.dashboard_total_drivers')}}</h5>
                            <h2 id="driver_count"></h2>
                            <i class="mdi mdi-account-check"></i>
                        </div>
                    </div>


                    <div class="col-sm-6 col-lg-4 mb-3">

                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <a class="order-status pending" href="{{ route('orders', 'status=InProcess') }}">
                            <div class="data">
                                <i class="mdi mdi-lan-pending"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_inprocess')}}</h6>
                            </div>
                            <span class="count" id="placed_count"></span>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <a class="order-status packaging" href="{!! route('orders', 'status=InTransit') !!}">
                            <div class="data">
                                <i class="mdi mdi-clipboard-outline"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_intransit')}}</h6>
                            </div>
                            <span class="count" id="shipped_count"></span>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <a class="order-status delivered" href="{!! route('orders', 'status=Delivered') !!}">
                            <div class="data">
                                <i class="mdi mdi-check-circle-outline"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_delivered')}}</h6>
                            </div>
                            <span class="count" id="completed_count"></span>
                        </a>
                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header no-border">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">{{trans('lang.total_sales')}}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative">
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2"> <i class="fa fa-square" style="color:#2EC7D9"></i>
                                {{trans('lang.dashboard_this_year')}} </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row daes-sec-sec mb-3">

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header no-border d-flex justify-content-between">
                        <h3 class="card-title">{{trans('lang.recent_orders')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('orders')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive px-3">
                            <table class="table table-striped table-valign-middle" id="orderTable">
                                <thead>
                                    <tr>
                                        <th style="text-align:center">{{trans('lang.order_id')}}</th>
                                        <th>{{trans('lang.total_amount')}}</th>
                                        <th>{{trans('lang.quantity')}}</th>
                                    </tr>
                                </thead>
                                <tbody id="append_list_recent_order">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header no-border d-flex justify-content-between">
                        <h3 class="card-title">{{trans('lang.top_drivers')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('drivers')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive px-3">
                            <table class="table table-striped table-valign-middle" id="driverTable">
                                <thead>
                                    <tr>
                                        <th style="text-align:center">{{trans('lang.restaurant_image')}}</th>
                                        <th>{{trans('lang.driver')}}</th>
                                        <th>{{trans('lang.order_completed')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody id="append_list_top_drivers">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ============================================================== -->

        <!-- End Right sidebar -->

        <!-- ============================================================== -->

    </div>


</div>

@endsection

@section('scripts')
<script src="{{asset('js/chart.js')}}"></script>

<script>
    jQuery("#data-table_processing").show();

    var db = firebase.firestore();
    var currency = db.collection('settings');

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

        await db.collection('orders').get().then(
            (snapshot) => {
                jQuery("#order_count").empty();
                jQuery("#order_count").text(snapshot.docs.length);
            });

        await db.collection('vendor_products').get().then(
            (snapshot) => {
                jQuery("#product_count").empty();
                jQuery("#product_count").text(snapshot.docs.length);
            });

        await db.collection('users').where("role", "==", "customer").get().then((snapshot) => {
            jQuery("#users_count").empty();
            jQuery("#users_count").append(snapshot.docs.length);
        });

        await db.collection('users').where("role", "==", "driver").get().then((snapshot) => {
            jQuery("#driver_count").empty();
            jQuery("#driver_count").append(snapshot.docs.length);
        });


        getTotalEarnings();

        db.collection('orders').where('status', 'in', ["InProcess"]).get().then(
            (snapshot) => {
                jQuery("#placed_count").empty();
                jQuery("#placed_count").text(snapshot.docs.length);
            });

        db.collection('orders').where('status', 'in', ["Order Accepted", "Driver Accepted"]).get().then(
            (snapshot) => {
                jQuery("#confirmed_count").empty();
                jQuery("#confirmed_count").text(snapshot.docs.length);
            });

        db.collection('orders').where('status', 'in', ["InTransit"]).get().then(
            (snapshot) => {
                jQuery("#shipped_count").empty();
                jQuery("#shipped_count").text(snapshot.docs.length);
            });

        db.collection('orders').where('status', 'in', ["Delivered"]).get().then(
            (snapshot) => {
                jQuery("#completed_count").empty();
                jQuery("#completed_count").text(snapshot.docs.length);
            });

        var placeholder = db.collection('settings').doc('placeHolderImage');
        placeholder.get().then(async function (snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;

        });

        var offest = 1;
        var pagesize = 10;
        var start = null;
        var end = null;
        var endarray = [];
        var inx = parseFloat(offest) * parseFloat(pagesize);
        var append_listrecent_order = document.getElementById('append_list_recent_order');
        append_listrecent_order.innerHTML = '';

        ref = db.collection('orders');
        ref.orderBy('createdAt', 'desc').where('status', 'in', ["InProcess", "InTransit"]).limit(inx).get().then(async (snapshots) => {
            var html = '';
            html = await buildOrderHTML(snapshots);
            if (html != '') {
                append_listrecent_order.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
            }

            $('#orderTable').DataTable({
                order: [],
                "language": {
                    "zeroRecords": "{{trans("lang.no_record_found")}}",
                    "emptyTable": "{{trans("lang.no_record_found")}}"
                },
                responsive: true
            });
        });

        var offest = 1;
        var pagesize = 5;
        var start = null;
        var end = null;
        var endarray = [];
        var inx = parseFloat(offest) * parseFloat(pagesize);
        var append_listtop_drivers = document.getElementById('append_list_top_drivers');
        append_listtop_drivers.innerHTML = '';

        ref = db.collection('users');
        ref.where('role', '==', 'driver').limit(inx).get().then(async (snapshots) => {
            var html = '';
            html = await buildDriverHTML(snapshots);
            if (html != '') {
                append_listtop_drivers.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);


            }
            $('#driverTable').DataTable({
                order: [2],
                columnDefs: [
                    { orderable: false, targets: [0, 3] },
                ],
                "language": {
                    "zeroRecords": "{{trans("lang.no_record_found")}}",
                    "emptyTable": "{{trans("lang.no_record_found")}}"
                },
                responsive: true
            });

        });
    });

    async function getTotalEarnings() {
        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
        var v01 = 0;
        var v02 = 0;
        var v03 = 0;
        var v04 = 0;
        var v05 = 0;
        var v06 = 0;
        var v07 = 0;
        var v08 = 0;
        var v09 = 0;
        var v10 = 0;
        var v11 = 0;
        var v12 = 0;
        var currentYear = new Date().getFullYear();
        await db.collection('orders').where('status', 'in', ["Delivered"]).get().then(async function (orderSnapshots) {
            var data = orderSnapshots.docs;

            var totalEarning = 0;
            data.forEach((order) => {
                var paymentData = order.data();


                var products = paymentData.products;
                var deliveryCharge = paymentData.deliveryCharge;

                var intRegex = /^\d+$/;
                var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

                var price = 0;
                var total_price = 0;
                var totalProductPrice = 0;
                var total_price = 0;

                if (products) {

                    products.forEach((product) => {

                        var val = product;

                        var price_item = parseFloat(val.price);

                        if (parseFloat(val.discountPrice) > 0) {
                            price_item = parseFloat(val.discountPrice);
                        }

                        if (!isNaN(price_item) && !isNaN(val.quantity)) {
                            totalProductPrice = parseFloat(price_item) * parseFloat(val.quantity);
                        }

                        if (!isNaN(totalProductPrice)) {
                            total_price += parseFloat(totalProductPrice);
                            price = price + totalProductPrice;
                        }

                    });
                }

                if (paymentData.hasOwnProperty('coupon') && paymentData.coupon.hasOwnProperty('discountType') && paymentData.coupon.discountType != null && paymentData.coupon.discountType != '' && paymentData.coupon.hasOwnProperty('isEnabled') && paymentData.coupon.isEnabled) {

                    var coupon = paymentData.coupon;

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

                if (paymentData.hasOwnProperty('tax') && paymentData.tax.length > 0) {

                    var total_tax_amount = 0;
                    for (var i = 0; i < paymentData.tax.length; i++) {
                        var data = paymentData.tax[i];

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
                    total_price += parseFloat(deliveryCharge);
                    deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits);
                }



                total_item_price = total_price;

                if (currencyAtRight) {
                    total_price_val = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;

                } else {
                    total_price_val = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);

                }
                price = parseFloat(price).toFixed(decimal_degits);


                totalEarning += parseFloat(total_item_price);


                try {

                    if (paymentData.createdAt) {
                        var orderMonth = paymentData.createdAt.toDate().getMonth() + 1;
                        var orderYear = paymentData.createdAt.toDate().getFullYear();
                        if (currentYear == orderYear) {
                            switch (parseFloat(orderMonth)) {
                                case 1:
                                    v01 = parseFloat(parseFloat(v01).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 2:
                                    v02 = parseFloat(parseFloat(v02).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 3:
                                    v03 = parseFloat(parseFloat(v03).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 4:
                                    v04 = parseFloat(parseFloat(v04).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 5:
                                    v05 = parseFloat(parseFloat(v05).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 6:
                                    v06 = parseFloat(parseFloat(v06).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 7:
                                    v07 = parseFloat(parseFloat(v07).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 8:
                                    v08 = parseFloat(parseFloat(v08).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 9:
                                    v09 = parseFloat(parseFloat(v09).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 10:
                                    v10 = parseFloat(parseFloat(v10).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                case 11:
                                    v11 = parseFloat(parseFloat(v11).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                                default:
                                    v12 = parseFloat(parseFloat(v12).toFixed(decimal_degits)) + parseFloat(price);
                                    break;
                            }
                        }
                    }

                } catch (err) {


                    var datas = new Date(paymentData.createdAt._seconds * 1000);

                    var dates = firebase.firestore.Timestamp.fromDate(datas);

                    db.collection('orders').doc(paymentData.id).update({ 'createdAt': dates }).then(() => {

                        console.log('Provided document has been updated in Firestore');

                    }, (error) => {

                        console.log('Error: ' + error);

                    });

                }

            });

            if (currencyAtRight) {
                totalEarningAmount = parseFloat(totalEarning).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                totalEarningAmount = currentCurrency + "" + parseFloat(totalEarning).toFixed(decimal_degits);
            }

            $("#earnings_count").html(totalEarningAmount);
            var data = [v01, v02, v03, v04, v05, v06, v07, v08, v09, v10, v11, v12];
            var labels = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
            var $salesChart = $('#sales-chart');

            var salesChart = renderChart($salesChart, data, labels);
        });
        jQuery("#data-table_processing").hide();

    }

    function buildHTML(snapshots) {
        var html = '';
        var count = 1;
        var rating = 0;

        snapshots.docs.forEach((listval) => {
            val = listval.data();
            val.id = listval.id;

            html = html + '<tr>';
            if (val.photo == '') {

                html = html + '<td class="text-center"><img class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + placeholderImage + '" alt="image"></td>';
            } else {
                html = html + '<td class="text-center"><img class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + val.photo + '" alt="image"></td>';
            }

            html = html + '<td data-url="' + routeview + '" class="redirecttopage">' + val.title + '</td>';

            if (val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = Math.round(parseFloat(val.reviewsSum) / parseFloat(val.reviewsCount));
            } else {
                rating = 0;
            }

            html = html + '<td><ul class="rating" data-rating="' + rating + '">';
            html = html + '<li class="rating__item"></li>';
            html = html + '<li class="rating__item"></li>';
            html = html + '<li class="rating__item"></li>';
            html = html + '<li class="rating__item"></li>';
            html = html + '<li class="rating__item"></li>';
            html = html + '</ul></td>';
            html = html + '<td><a href="' + route + '" > <span class="fa fa-edit"></span></a></td>';
            html = html + '</tr>';

            rating = 0;
            count++;
        });
        return html;
    }

    async function buildDriverHTML(snapshots) {
        var html = '';

        await Promise.all(snapshots.docs.map(async (listval) => {

            var val = listval.data();

            var getData = await getDriverData(val);
            html += getData;
        }));

        return html;
    }


    async function getDriverData(val) {
        var html = '';

        var driverroute = '<?php echo route("drivers.edit", ":id");?>';
        driverroute = driverroute.replace(':id', val.id);

        html = html + '<tr>';
        if (val.profilePictureURL == '') {

            html = html + '<td class="text-center"><img class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + placeholderImage + '" alt="image"></td>';
        } else {
            html = html + '<td class="text-center"><img class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + val.profilePictureURL + '" alt="image"></td>';
        }
        html = html + '<td data-url="' + driverroute + '" class="redirecttopage">' + val.name + '</td>';

        var orderCompleted = await getCompletedOrders(val.id);

        html = html + '<td data-url="' + driverroute + '" class="redirecttopage">' + orderCompleted + '</td>';
        html = html + '<td data-url="' + driverroute + '" class="redirecttopage"><span class="fa fa-edit"></span></td>';
        html = html + '</tr>';

        return html;
    }

    async function getCompletedOrders(driverID) {

        var count = 0;
        await db.collection('orders').where("driverID", "==", driverID).where('status', '==', 'Delivered').get().then(function (snapshotsorder) {
            count = snapshotsorder.size;
        });
        return count;
    }

    function buildOrderHTML(snapshots) {
        var html = '';
        var count = 1;
        snapshots.docs.forEach((listval) => {
            val = listval.data();
            val.id = listval.id;
            var route = '<?php echo route("orders.edit", ":id"); ?>';
            route = route.replace(':id', val.id);

            html = html + '<tr>';

            html = html + '<td data-url="' + route + '" class="redirecttopage">' + val.id + '</td>';

            var price = buildHTMLProductstotal(val);

            if (currencyAtRight) {
                price = parseFloat(price).toFixed(decimal_degits) + "" + currentCurrency;

            } else {
                price = currentCurrency + "" + parseFloat(price).toFixed(decimal_degits);

            }

            html = html + '<td data-url="' + route + '" class="redirecttopage">' + price + '</td>';
            html = html + '<td data-url="' + route + '" class="redirecttopage"><i class="fa fa-shopping-cart"></i> ' + val.products.length + '</td>';
            html = html + '</a></tr>';
            count++;
        });
        return html;
    }


    function renderChart(chartNode, data, labels) {

        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        };

        var mode = 'index';
        var intersect = true;
        return new Chart(chartNode, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        backgroundColor: '#2EC7D9',
                        borderColor: '#2EC7D9',
                        data: data
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            callback: function (value, index, values) {
                                return currentCurrency + value.toFixed(decimal_degits);
                            }


                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })
    }

    $(document).ready(function () {
        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });
    });


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
        var deliveryCharge = snapshotsProducts.deliveryCharge;

        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        var total_price = 0;

          if (products) {

            products.forEach((product) => {

                var val = product;
                if (val.price) {
                    price_item = parseFloat(val.price);
                    if (!isNaN(price_item) || !isNaN(val.quantity)) {
                        totalProductPrice = parseFloat(price_item) * parseInt(val.quantity);
                    }
                    else
                    {
                        totalProductPrice = parseFloat(price_item);
                    }
                    
                    totalProductPrice =  parseFloat(totalProductPrice);
                    if (!isNaN(totalProductPrice)) {
                        total_price += parseFloat(totalProductPrice);
                    }
                } 

            });
        }

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
            total_price += parseFloat(deliveryCharge);
        }

        var total_item_price = total_price;

        if (currencyAtRight) {
            total_price_val = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;

        } else {
            total_price_val = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);

        }

        $('.total_amount').text(total_price_val);

        return total_price;
    }

</script>
@endsection