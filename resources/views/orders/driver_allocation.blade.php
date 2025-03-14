@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor orderTitle">{{trans('lang.order_plural')}} </h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.order_plural')}}</li>
            </ol>
        </div>
        <div>
        </div>
    </div>

    <div class="container-fluid">
        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
            Processing...
        </div>

        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive m-t-10">
                            <table id="orderTable"
                                class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <?php if (in_array('pending.orders.delete', json_decode(@session('user_permissions'), true))) { ?>

                                        <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                class="col-3 control-label" for="is_active">
                                                <a id="deleteAll" class="do_not_delete" href="javascript:void(0)">
                                                    <i class="fa fa-trash"></i> {{trans('lang.all')}}</a></label></th>
                                        <?php } ?>

                                        <th>{{trans('lang.order_id')}}</th>
                                        <th>{{trans('lang.order_user_id')}}</th>

                                        <th>{{trans('lang.date')}}</th>
                                        <th>{{trans('lang.restaurants_payout_amount')}}</th>
                                        <th>{{trans('lang.order_order_status_id')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody id="append_list1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

    var database = firebase.firestore();

    var redData = ref;
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

    var order_status = jQuery('#order_status').val();
    var search = jQuery("#search").val();

    var ref = database.collection('orders').where('driverID', '==', null).orderBy('createdAt', 'desc');


    var user_permissions = '<?php echo @session('user_permissions') ?>';

    user_permissions = JSON.parse(user_permissions);

    var checkDeletePermission = false;

    if ($.inArray('pending.orders.delete', user_permissions) >= 0) {
        checkDeletePermission = true;
    }
    var checkPrintPermission = false;

    if ($.inArray('vendors.orderprint', user_permissions) >= 0) {
        checkPrintPermission = true;
    }

    $(document.body).on('change', '#order_status', function () {
        order_status = jQuery(this).val();
    });

    $(document.body).on('keyup', '#search', function () {
        search = jQuery(this).val();
    });

    $(document).ready(function () {


        jQuery('#search').hide();

        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });

        $(document.body).on('change', '#selected_search', function () {

            if (jQuery(this).val() == 'status') {
                jQuery('#order_status').show();
                jQuery('#search').hide();
            } else {

                jQuery('#order_status').hide();
                jQuery('#search').show();

            }
        });


        jQuery("#data-table_processing").show();

        const table = $('#orderTable').DataTable({
            pageLength: 10, // Number of rows per page
            processing: false, // Show processing indicator
            serverSide: true, // Enable server-side processing
            responsive: true,
            ajax: async function (data, callback, settings) {

                const start = data.start;
                const length = data.length;
                const searchValue = data.search.value.toLowerCase();
                const orderColumnIndex = data.order[0].column;
                const orderDirection = data.order[0].dir;

                var orderableColumns = (checkDeletePermission == true) ? ['', 'id', 'user', 'createdAt','productprice','status',''] : ['id', 'user', 'createdAt','productprice','status',''] // Ensure this matches the actual column names

                const orderByField = orderableColumns[orderColumnIndex]; // Adjust the index to match your table
                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $('#data-table_processing').show();
                }
                await ref.get().then(async function (querySnapshot) {
                    if (querySnapshot.empty) {
                        $('#data-table_processing').hide(); // Hide loader
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            data: [] // No data
                        });
                        return;
                    }
                    let records = [];
                    let filteredRecords = [];

                    await Promise.all(querySnapshot.docs.map(async (doc) => {
                        let childData = doc.data();
                        childData.id = doc.id; // Ensure the document ID is included in the data
                        var user = await getuserName(childData.userID);
                        var price = await buildHTMLProductstotal(childData);
                        childData.productprice = price ? price : "$0.00";
                        childData.user = user ? user : "" ;
                        if (searchValue) {
                            var date = '';
                            var time = '';
                            if (childData.hasOwnProperty("createdAt")) {
                                try {
                                    date = childData.createdAt.toDate().toDateString();
                                    time = childData.createdAt.toDate().toLocaleTimeString('en-US');
                                } catch (err) {
                                }
                            }
                            var createdAt = date + ' ' + time;
                            if (
                                (user && user.toLowerCase().toString().includes(searchValue)) ||
                                (childData.id && childData.id.toLowerCase().toString().includes(searchValue)) ||
                                (childData.status && childData.status.toLowerCase().toString().includes(searchValue)) || (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1)
                            ) {
                                filteredRecords.push(childData);
                            }
                        } else {
                            filteredRecords.push(childData);
                        }
                    }));

                    filteredRecords.sort((a, b) => {
                        let aValue = a[orderByField] ? a[orderByField].toString().toLowerCase() : '';
                        let bValue = b[orderByField] ? b[orderByField].toString().toLowerCase() : '';
                        if (orderByField === 'createdAt') {
                            try {
                                aValue = a[orderByField] ? new Date(a[orderByField].toDate()).getTime() : 0;
                                bValue = b[orderByField] ? new Date(b[orderByField].toDate()).getTime() : 0;
                            } catch (err) {
                            }
                        }
                        if (orderByField === 'productprice') {
                            aValue = a[orderByField].slice(1) ? parseInt(a[orderByField].slice(1)) : 0;
                            bValue = b[orderByField].slice(1) ? parseInt(b[orderByField].slice(1)) : 0;
                        }
                        if (orderDirection === 'asc') {
                            return (aValue > bValue) ? 1 : -1;
                        } else {
                            return (aValue < bValue) ? 1 : -1;
                        }
                    });

                    const totalRecords = filteredRecords.length;

                    const paginatedRecords = filteredRecords.slice(start, start + length);

                    await Promise.all(paginatedRecords.map(async (childData) => {
                        var getData = await buildHTML(childData);
                        records.push(getData);
                    }));

                    $('#data-table_processing').hide(); // Hide loader
                    callback({
                        draw: data.draw,
                        recordsTotal: totalRecords, // Total number of records in Firestore
                        recordsFiltered: totalRecords, // Number of records after filtering (if any)
                        data: records // The actual data to display in the table
                    });
                }).catch(function (error) {
                    console.error("Error fetching data from Firestore:", error);
                    $('#data-table_processing').hide(); // Hide loader
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: [] // No data due to error
                    });
                });
            },
            order: (checkDeletePermission == true) ? [[3,'desc']] : [[2,'desc']],
            columnDefs: [
                {
                    targets: (checkDeletePermission == true) ? 3 : 2,
                    type: 'date',
                    render: function (data) {
                        return data;
                    }
                },{
                    targets: (checkDeletePermission == true) ? 4 : 3,
                    type: "html-num-fmt",
                },
                { orderable: false, targets: (checkDeletePermission == true) ? [0, 5, 6] : [0, 5, 6] }
            ],
            "language": {
                "zeroRecords": "{{trans("lang.no_record_found")}}",
                "emptyTable": "{{trans("lang.no_record_found")}}",
                "processing": "" // Remove default loader
            },

        });
        function debounce(func, wait) {
            let timeout;
            const context = this;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
        $('#search-input').on('input', debounce(function () {
            const searchValue = $(this).val();
            if (searchValue.length >= 3) {
                $('#data-table_processing').show();
                table.search(searchValue).draw();
            } else if (searchValue.length === 0) {
                $('#data-table_processing').show();
                table.search('').draw();
            }
        }, 300));


    });

    async function buildHTML(val) {
        var html = [];

        var id = val.id;
        var vendorID = val.vendorID;

        var user_id = val.userID;
        var route1 = '{{route("orders.edit",":id")}}';
        route1 = route1.replace(':id', id);

        var printRoute = '{{route("vendors.orderprint",":id")}}';
        printRoute = printRoute.replace(':id', id);

        var customer_view = '{{route("users.edit",":id")}}';
        customer_view = customer_view.replace(':id', user_id);
        if (checkDeletePermission) {
            html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>');
        }
        html.push('<a data-url="' + route1 + '" href="'+ route1 +'" class="redirecttopage">' + val.id + '</a>');

        if (val.hasOwnProperty("user")) {
            if (val.user) {
                html.push('<a  data-url="' + customer_view + '" href="'+customer_view+'" class="redirecttopage">' + val.user + '</a>');

            } else {
                html.push('<td>{{trans("lang.unknown")}}</td>');
            }

        } else {
            html.push('<td></td>');
        }


        var date = '';
        var time = '';
        if (val.hasOwnProperty("createdAt")) {

            try {
                date = val.createdAt.toDate().toDateString();
                time = val.createdAt.toDate().toLocaleTimeString('en-US');
            } catch (err) {

            }
            html.push('<td class="dt-time">' + date + ' ' + time + '</td>');
        } else {
            html.push('<td></td>');
        }
        var price = 0;

        var price = await buildHTMLProductstotal(val);
        html.push('<span class="text-green">' + price + '</span>');

        if (val.status == 'InProcess') {
            html.push('<span class="order_placed"><span>' + val.status + '</span></span>');

        } else if (val.status == 'InTransit') {
            html.push('<span class="in_transit"><span>' + val.status + '</span></span>');

        } else if (val.status == 'Delivered') {
            html.push('<span class="order_completed"><span>' + val.status + '</span></span>');

        } else {
            html.push('<span class="order_completed"><span>' + val.status + '</span></span>');

        }
        var actionHtml = '';
        actionHtml = actionHtml + '<span class="action-btn">';

        actionHtml = actionHtml + '<a href="' + route1 + '"><i class="fa fa-eye"></i></a>';

        if (checkPrintPermission) {
            actionHtml = actionHtml + '<a href="' + printRoute + '"><i class="fa fa-print" style="font-size:20px;"></i></a>';
        }

        if (checkDeletePermission) {
            actionHtml = actionHtml + '<a id="' + val.id + '" class="delete-btn" name="order-delete" href="javascript:void(0)"><i class="fa fa-trash"></i></a>';
        }
        actionHtml = actionHtml + '</span>';
        html.push(actionHtml);

        return html;
    }

    $("#is_active").click(function () {
        $("#orderTable .is_open").prop('checked', $(this).prop('checked'));

    });

    async function getuserName(id) {
        var name = '';
        await database.collection('users').where("id", "==", id).get().then(async function (snapshotsorder) {

            if (snapshotsorder.docs.length) {
                var user = snapshotsorder.docs[0].data();
                name = user.name;
            }
        });
        return name;
    }

    $("#deleteAll").click(function () {

        if ($('#orderTable .is_open:checked').length) {

            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#orderTable .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');

                    database.collection('orders').doc(dataId).delete().then(function () {

                        setTimeout(function () {
                            window.location.reload();
                        }, 7000);

                    });

                });

            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });

    $(document).on("click", "a[name='order-delete']", function (e) {

        if (confirm("{{trans('lang.delete_alert')}}")) {

            var id = this.id;
            database.collection('orders').doc(id).delete().then(function (result) {
                window.location.href = '{{ url()->current() }}';
            });
        }
    });


    async function getStoreNameFunction(vendorId) {
        var vendorName = '';
        await database.collection('vendors').where('id', '==', vendorId).get().then(async function (snapshots) {
            if (!snapshots.empty) {
                var vendorData = snapshots.docs[0].data();

                vendorName = vendorData.title;
                $('.orderTitle').html('{{trans("lang.order_plural")}} - ' + vendorName);

                if (vendorData.dine_in_active == true) {
                    $(".dine_in_future").show();
                }
                walletRoute = "{{route('users.walletstransaction', ':id')}}";
                walletRoute = walletRoute.replace(":id", vendorData.author);
                $('#restaurant_wallet').append('<a href="' + walletRoute + '">{{trans("lang.wallet_transaction")}}</a>');

            }
        });

        return vendorName;

    }


    async function getUserNameFunction(userId) {
        var userName = '';
        await database.collection('users').where('id', '==', userId).get().then(async function (snapshots) {
            var user = snapshots.docs[0].data();

            userName = user.name;
            $('.orderTitle').html('{{trans("lang.order_plural")}} - ' + userName + "(" + user.role + ")");
        });

        return userName;

    }

    function buildHTMLProductstotal(snapshotsProducts) {
        var html = '';
        var alldata = [];
        var number = [];

        var couponCode = snapshotsProducts.couponCode;
        var couponCode = snapshotsProducts.couponCode;
        var extras = snapshotsProducts.extras;
        var extras_price = snapshotsProducts.extras_price;
        var rejectedByDrivers = snapshotsProducts.rejectedByDrivers;
        var takeAway = snapshotsProducts.takeAway;
        var tip_amount = snapshotsProducts.tip_amount;
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

                var price_item = parseFloat(parseFloat(val.discountPrice) > 0 ? parseFloat(val.discountPrice) : parseFloat(val.price));
                    if (!isNaN(price_item) && !isNaN(val.quantity)) {
                        totalProductPrice = parseFloat(price_item) * parseInt(val.quantity);
                    }
                    totalProductPrice = parseFloat(totalProductPrice).toFixed(2);
                    if (!isNaN(totalProductPrice)) {
                        total_price += parseFloat(totalProductPrice);
                    }




            });
        }

        if (snapshotsProducts.hasOwnProperty('coupon') && snapshotsProducts.coupon.hasOwnProperty('discountType') && snapshotsProducts.coupon.discountType != '' && snapshotsProducts.coupon.discountType != null && snapshotsProducts.coupon.hasOwnProperty('isEnabled') && snapshotsProducts.coupon.isEnabled) {

            var coupon = snapshotsProducts.coupon;
            html = html + '<tr><td class="seprater" colspan="2"><hr><span>{{trans("lang.discount")}}</span></td></tr>';

            var discount_amount = parseFloat(coupon.discount);
            var labeltype = "";

            if (coupon.discountType == "Percentage") {
                discount_amount = (parseFloat(coupon.discount) * total_price) / 100;
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

        if (intRegex.test(tip_amount) || floatRegex.test(tip_amount)) {

            tip_amount = parseFloat(tip_amount).toFixed(decimal_degits);
            total_price += parseFloat(tip_amount);
            total_price = parseFloat(total_price).toFixed(decimal_degits);

        }

        var total_price_val = 0;

        if (currencyAtRight) {
            total_price_val = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            total_price_val = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);
        }

        return total_price_val;
    }

</script>

@endsection
