@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.reports_sale')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.reports_sale')}}</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card  pb-4">

            <div class="card-body">
                <div id="data-table_processing" class="dataTables_processing panel panel-default"
                    style="display: none;">{{trans('lang.processing')}}</div>
                <div class="error_top"></div>

                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                        <fieldset>
                            <legend>{{trans('lang.reports_sale')}}</legend>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.select_driver')}}</label>
                                <div class="col-7">
                                    <select class="form-control driver">
                                        <option value="">{{trans('lang.all')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.select_user')}}</label>
                                <div class="col-7">
                                    <select class="form-control customer">
                                        <option value="">{{trans('lang.all')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.select_date')}}</label>
                                <div class="col-7">
                                    <div id="reportrange"
                                        style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.file_format')}}<span
                                        class="required-field"></span></label>
                                <div class="col-7">
                                    <select class="form-control file_format">
                                        <option value="">{{trans('lang.file_format')}}</option>
                                        <option value="xls">{{trans('lang.xls')}}</option>
                                        <option value="csv">{{trans('lang.csv')}}</option>
                                        <option value="pdf">{{trans('lang.pdf')}}</option>
                                    </select>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                </div>
                <table id="data-table" hidden>

                </table>
                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary download-sales-report"><i class="fa fa-save"></i>
                        {{ trans('lang.download')}}</button>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/objectexporter.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
    var database = firebase.firestore();
    var refCurrency = database.collection('currencies').where('isActive', '==', true).limit('1');
    var driverUserRef = database.collection('users').where('role', '==', 'driver').orderBy('createdAt').orderBy('name');
    var customerRef = database.collection('users').where('role', '==', 'customer').orderBy('createdAt').orderBy('name');

    setDate();

    function setDate() {
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
    }

    var decimal_degits = 0;
    var symbolAtRight = false;
    var currentCurrency = '';
    refCurrency.get().then(async function (snapshots) {

        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        decimal_degits = currencyData.decimal_degits;

        if (currencyData.symbolAtRight) {
            symbolAtRight = true;
        }
    });

    customerRef.get().then(function (snapShots) {

        if (snapShots.docs.length > 0) {

            snapShots.docs.forEach((listval) => {
                var data = listval.data();

                $('.customer').append('<option value="' + data.id + '">' + data.name + '</option>');
            });

        }
    });

    driverUserRef.get().then(function (snapShots) {

        if (snapShots.docs.length > 0) {

            snapShots.docs.forEach((listval) => {
                var data = listval.data();

                $('.driver').append('<option value="' + data.id + '">' + data.name + '</option>');
            });

        }
    });
    $('.customer').select2();
    $('.driver').select2();
    async function generateReport(orderData, headers, fileFormat) {

        if ((fileFormat == "pdf") ? document.title = "sales-report" : "");

        objectExporter({
            type: fileFormat,
            exportable: orderData,
            headers: headers,
            fileName: 'sales-report',
            columnSeparator: ',',
            headerStyle: 'font-weight: bold; padding: 5px; border: 1px solid #dddddd;',
            cellStyle: 'border: 1px solid lightgray; margin-bottom: -1px;',
            sheetName: 'sales-report',
            documentTitle: '',
        });

    }

    async function getReportData(orderSnapshots, fileFormat) {

        var orderData = [];

        await Promise.all(orderSnapshots.docs.map(async (order) => {

            var orderObj = order.data();
            var orderId = orderObj.id;
            var finalOrderObject = {};

            var driverData = ((orderObj.driver && orderObj.driver != null) ? orderObj.driver : '');
            var userData = ((orderObj.user && orderObj.user != null) ? orderObj.user : '');
            var vendorData = ((orderObj.vendor && orderObj.vendor != null) ? orderObj.vendor : '');
            var date = orderObj.createdAt.toDate();

            finalOrderObject['Order ID'] = orderId;

            finalOrderObject['Driver Name'] = ((driverData.name) ? driverData.name : "N/A");
            finalOrderObject['Driver Email'] = ((driverData.email) ? shortEmail(driverData.email) : "N/A");
            finalOrderObject['Driver Phone'] = ((driverData.phoneNumber) ? "(" + driverData.countryCode + ")" + EditPhoneNumber(driverData.phoneNumber) : 'N/A');

            finalOrderObject['User Name'] = ((userData.name) ? userData.name : "N/A");
            finalOrderObject['User Email'] = ((userData.email) ? shortEmail(userData.email) : "N/A");
            finalOrderObject['User Phone'] = ((userData.phoneNumber) ? "(" + userData.countryCode + ")" + EditPhoneNumber(userData.phoneNumber) : 'N/A');

            finalOrderObject['Date'] = moment(date).format('ddd MMM DD YYYY h:mm:ss A');

            finalOrderObject['Payment Method'] = orderObj.paymentMethod;

            var total_amount = getProductsTotal(orderObj);

            if (fileFormat == "csv") {

                total_amount = "(Rs) " + parseFloat(total_amount).toFixed(decimal_degits);

            } else {
                if (symbolAtRight) {
                    total_amount = parseFloat(total_amount).toFixed(decimal_degits) + "" + currentCurrency;
                } else {
                    total_amount = currentCurrency + "" + parseFloat(total_amount).toFixed(decimal_degits);
                }

            }

            finalOrderObject['Total'] = (total_amount);

            orderData.push(finalOrderObject);
        }));

        return orderData;
    }

    function getProductsTotal(snapshotsProducts) {

        var products = snapshotsProducts.products;
        var deliveryCharge = snapshotsProducts.deliveryCharge;

        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        var total_price = 0;

        if (products) {

            products.forEach((product) => {

                var val = product;

                var price_item = parseFloat(val.price);

                if (parseFloat(val.discountPrice) > 0) {
                    price_item = parseFloat(val.discountPrice);
                }
                var totalProductPrice = 0;

                if (!isNaN(price_item) && !isNaN(val.quantity)) {
                    totalProductPrice = parseFloat(price_item) * parseInt(val.quantity);
                }

                totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits);
                if (!isNaN(totalProductPrice)) {
                    total_price += parseFloat(totalProductPrice);
                }
            });
        }
        if (snapshotsProducts.hasOwnProperty('coupon') && snapshotsProducts.coupon.hasOwnProperty('discountType') && snapshotsProducts.coupon.discountType != '' && snapshotsProducts.coupon.hasOwnProperty('isEnabled') && snapshotsProducts.coupon.isEnabled) {

            var coupon = snapshotsProducts.coupon;

            var discount_amount = coupon.discount;
            var labeltype = "";

            if (coupon.discountType == "Percentage") {
                discount_amount = (coupon.discount * total_price) / 100;
                labeltype = "(" + coupon.discount + "%)";
            }
            total_price -= parseFloat(discount_amount);
        }

        var total_item_price = total_price;
        var tax = 0;

        if (snapshotsProducts.hasOwnProperty('tax') && snapshotsProducts.tax.length > 0) {

            var total_tax_amount = 0;
            for (var i = 0; i < snapshotsProducts.tax.length; i++) {
                var data = snapshotsProducts.tax[i];

                if (data.type && data.tax) {
                    if (data.type == "percentage") {
                        tax = (data.tax * total_price) / 100;
                    } else {
                        tax = data.tax;

                    }
                }
                total_tax_amount += parseFloat(tax);
            }
            total_price = parseFloat(total_price) + parseFloat(total_tax_amount);
        }
        if (intRegex.test(deliveryCharge) || floatRegex.test(deliveryCharge)) {
            deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits);
            total_price += parseFloat(deliveryCharge);
        }
       

        return total_price;
    }

    $(document).on('click', '.download-sales-report', function () {

        var driver = $(".driver :selected").val();
        var customer = $(".customer :selected").val();
        var fileFormat = $(".file_format :selected").val();
        let start_date = moment($('#reportrange').data('daterangepicker').startDate).toDate();
        let end_date = moment($('#reportrange').data('daterangepicker').endDate).toDate();

        var headerArray = ['Order ID', 'Driver Name', 'Driver Email', 'Driver Phone', 'User Name', 'User Email', 'User Phone', 'Date', 'Payment Method', 'Total'];

        var headers = [];

        $(".error_top").html("");

        if (fileFormat == 'xls' || fileFormat == 'csv') {
            headers = headerArray;
            var script = document.createElement("script");
            script.setAttribute("src", "https://unpkg.com/object-exporter@3.2.1/dist/objectexporter.min.js");

            var head = document.head;
            head.insertBefore(script, head.firstChild);
        } else {
            for (var k = 0; k < headerArray.length; k++) {
                headers.push({
                    alias: headerArray[k],
                    name: headerArray[k],
                    flex: 1,
                });
            }

            var script = document.createElement("script");
            script.setAttribute("src", "{{ asset('js/objectexporter.min.js') }}");
            script.setAttribute("async", "false");
            var head = document.head;
            head.insertBefore(script, head.firstChild);

        }

        if (fileFormat == '') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.file_format_error')}}</p>");
            window.scrollTo(0, 0);
        } else {
            jQuery("#overlay").show();

            var ordersRef = database.collection('orders').where('status', 'in', ['Delivered']).orderBy('createdAt', 'desc');


            if (driver != "") {
                ordersRef = ordersRef.where('driverID', '==', driver)
            }
            if (customer != "") {
                ordersRef = ordersRef.where('userID', '==', customer)
            }

            if (start_date != "") {
                ordersRef = ordersRef.where('createdAt', '>=', start_date)
            }

            if (end_date != "") {
                ordersRef = ordersRef.where('createdAt', '<=', end_date)
            }

            ordersRef.get().then(async function (orderSnapshots) {

                if (orderSnapshots.docs.length > 0) {
                    var reportData = await getReportData(orderSnapshots, fileFormat);


                    generateReport(reportData, headers, fileFormat);

                    jQuery("#overlay").hide();
                    setDate();
                    $('.file_format').val('').trigger('change');
                    $('.driver').val('').trigger('change');
                    $('.customer').val('').trigger('change');

                } else {
                    jQuery("#overlay").hide();
                    setDate();
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.not_found_data_error')}}</p>");
                    window.scrollTo(0, 0);

                }

            }).catch((error) => {

                jQuery("#overlay").show();

                $(".error_top").show();
                $(".error_top").html(error);
                window.scrollTo(0, 0);
            });
        }
    });


</script>

@endsection