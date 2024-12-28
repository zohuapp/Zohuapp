@extends('layouts.app')

@section('content')

<div class="page-wrapper">


    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.wallet_transaction_plural')}} <span class="userTitle"></span>
            </h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.wallet_transaction_plural')}}</li>
            </ol>
        </div>

        <div>

        </div>

    </div>


    <div class="container-fluid">
        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
            {{trans('lang.processing')}}
        </div>

        <div class="row">

            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="{!! url()->current() !!}"><i
                                        class="fa fa-list mr-2"></i>{{trans('lang.wallet_transaction_table')}}
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive m-t-10">


                            <table id="walletTransactionTable"
                                class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                cellspacing="0" width="100%">

                                <thead>

                                    <tr>
                                        <?php if ($id == '') { ?>
                                            <th>{{ trans('lang.users')}}</th>
                                            <th>{{ trans('lang.role')}}</th>
                                        <?php } ?>
                                        <th>{{trans('lang.amount')}}</th>
                                        <th>{{trans('lang.date')}}</th>
                                        <th>{{trans('lang.wallet_transaction_note')}}</th>
                                        <th>{{trans('lang.payment_method')}}</th>
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
<script>

    var database = firebase.firestore();


    var refData = database.collection('wallet_transaction');
    var search = jQuery("#search").val();

    $(document.body).on('keyup', '#search', function () {
        search = jQuery(this).val();
    });

    <?php if ($id != '') { ?>
        var idUnavailabe = false;
        ref = refData.where('userId', '==', '<?php echo $id; ?>').orderBy('createdDate', 'desc');
    <?php } else { ?>
        var idUnavailabe = true;
        ref = refData.orderBy('createdDate', 'desc');

    <?php } ?>

    var append_list = '';

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

    $(document).ready(function () {

        if ('{{$id}}') {
            var username = database.collection('users').where('id', '==', '{{$id}}');
            username.get().then(async function (snapshots) {
                var username = snapshots.docs[0].data();
                $(".userTitle").text(' of ' + username.name);
            });
        }

        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });

        jQuery("#data-table_processing").show();
        const table = $('#walletTransactionTable').DataTable({
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

                var orderableColumns = (idUnavailabe) ? ['name','role', 'amount', 'createdDate','note','',''] : ['amount', 'createdDate','note','','']; // Ensure this matches the actual column names

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
                        var payoutuser = await payoutuserfunction(childData.userId);
                        childData.name = payoutuser.name;
                        childData.role = payoutuser.role;
                        if (searchValue) {
                            var date = '';
                            var time = '';
                            if (childData.hasOwnProperty("createdDate")) {
                                try {
                                    date = childData.createdDate.toDate().toDateString();
                                    time = childData.createdDate.toDate().toLocaleTimeString('en-US');
                                } catch (err) {
                                }
                            }
                            var createdAt = date + ' ' + time;
                            if (
                                (payoutuser && payoutuser.name.toLowerCase().toString().includes(searchValue)) ||
                                (payoutuser && payoutuser.role.toLowerCase().toString().includes(searchValue)) ||
                                (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1) ||
                                (childData.note && childData.note.toString().toLowerCase().includes(searchValue)) ||
                                (childData.amount && childData.amount.toString().includes(searchValue))
                            ) {
                                filteredRecords.push(childData);
                            }
                        } else {
                            filteredRecords.push(childData);
                        }
                    }));

                    filteredRecords.sort((a, b) => {
                        let aValue = a[orderByField] ? a[orderByField].toString().toLowerCase().trim() : '';
                        let bValue = b[orderByField] ? b[orderByField].toString().toLowerCase().trim() : '';
                        if (orderByField === 'createdDate') {
                            try {
                                aValue = a[orderByField] ? new Date(a[orderByField].toDate()).getTime() : 0;
                                bValue = b[orderByField] ? new Date(b[orderByField].toDate()).getTime() : 0;
                            } catch (err) {

                            }
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
            order: (idUnavailabe) ? [[3,'desc']] : [[1,'asc']],
            columnDefs: [
                { orderable: false, targets: (idUnavailabe) ? [4,5,6] : [2,3,4] }
                ,{
                    targets: (idUnavailabe == true) ? 3 : 1,
                    type: 'date',
                    render: function (data) {
                        return data;
                    }
                },
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

        <?php if ($id == '') { ?>
        if (val.name && val.role) {
            var user_role = val.role;
            var user_name = val.name;

            var routeuser = "Javascript:void(0)";
            if (user_role == "customer") {
                routeuser = '{{route("users.view",":id")}}';
                routeuser = routeuser.replace(':id', val.userId);
            } else if (user_role == "driver") {
                routeuser = '{{route("drivers.view",":id")}}';
                routeuser = routeuser.replace(':id', val.userId);
            }
            html.push('<td class="user_' + val.userId + '"><a href="' + routeuser + '">' + user_name + '</a></td>');
            html.push('<td class="user_role_' + val.userId + '" >' + user_role + '</td>');

        } else {
            html.push('<td>{{trans("lang.unknown/deleted")}}</td>');
            // html.push('<td>{{trans("lang.N/A")}}</td>');
            html.push('');
        }
        <?php } ?>
        amount = val.amount;
        if (!isNaN(amount)) {
            amount = parseFloat(amount).toFixed(decimal_degits);
        }
        if ((val.hasOwnProperty('isCredit') && val.isCredit) || (val.payment_method == "Cancelled Order Payment")) {
            if (currencyAtRight) {
                html.push('<span class="text-green" data-order="' + amount + '">' + parseFloat(amount).toFixed(decimal_degits) + '' + currentCurrency + '</span>');
            } else {
                html.push('<span class="text-green" data-order="' + amount + '">' + currentCurrency + '' + parseFloat(amount).toFixed(decimal_degits) + '</span>');
            }
        } else if (val.hasOwnProperty('isCredit') && !val.isCredit) {
             amount=amount.slice(1);
            if (currencyAtRight) {
                html.push('<span class="text-red" data-order="' + val.amount + '">(-' + parseFloat(amount).toFixed(decimal_degits) + '' + currentCurrency + ')</span>');
            } else {
                html.push('<span class="text-red" data-order="' + val.amount + '">(-' + currentCurrency + '' + parseFloat(amount).toFixed(decimal_degits) + ')</span>');
            }
        } else {
            if (currencyAtRight) {
                html.push('<span class="" data-order="' + amount + '">' + parseFloat(amount).toFixed(decimal_degits) + '' + currentCurrency + '</span>');
            } else {
                html.push('<span class="" data-order="' + amount + '">' + currentCurrency + '' + parseFloat(amount).toFixed(decimal_degits) + '</span>');
            }
        }


        var date = "";
        var time = "";
        try {
            if (val.hasOwnProperty("createdDate")) {
                date = val.createdDate.toDate().toDateString();
                time = val.createdDate.toDate().toLocaleTimeString('en-US');
            }
        } catch (err) {

        }
        html.push( date + ' ' + time );
        if (val.note != undefined && val.note != '') {
            html.push( val.note );
        } else {
            html.push('<td></td>');
        }

        var paymentType = '';
        if (val.paymentType) {

            if ((val.paymentType).toLowerCase() == "stripe") {
                image = '{{asset("images/stripe.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if ((val.paymentType).toLowerCase() == "xendit") {
                image = '{{asset("images/xendit.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';


            } else if ((val.paymentType).toLowerCase() == "midtrans") {
                image = '{{asset("images/midtrans.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';


            } else if ((val.paymentType).toLowerCase() == "orangepay") {
                image = '{{asset("images/orangepay.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';


            } else if ((val.paymentType).toLowerCase() == "razorpay") {
                image = '{{asset("images/razorepay.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';


            } else if ((val.paymentType).toLowerCase() == "paypal") {
                image = '{{asset("images/paypal.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if ((val.paymentType).toLowerCase() == "payfast") {
                image = '{{asset("images/payfast.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if ((val.paymentType).toLowerCase() == "paystack") {
                image = '{{asset("images/paystack.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if ((val.paymentType).toLowerCase() == "flutterwave") {
                image = '{{asset("images/flutter_wave.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if ((val.paymentType).toLowerCase() == "mercadopago") {
                image = '{{asset("images/marcado_pago.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if ((val.paymentType).toLowerCase() == "wallet") {
                image = '{{asset("images/wallet.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if ((val.paymentType).toLowerCase() == "paytm") {
                image = '{{asset("images/paytm.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if ((val.paymentType).toLowerCase() == "cash on delivery ") {
                image = '{{asset("images/cash.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            }
            else if (val.paymentType == "Cancelled Order Payment") {
                image = '{{asset("images/cancel_order.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';

            } else if (val.paymentType == "Refund Amount") {
                image = '{{asset("images/refund_amount.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';
            } else if (val.paymentType == "Referral Amount") {
                image = '{{asset("images/reffral_amount.png")}}';
                paymentType = '<img alt="image" src="' + image + '" >';
            } else {
                paymentType = val.paymentType;
            }
        }
        html.push('<td class="payment_images">' + paymentType + '</td>');
        if(val.hasOwnProperty('orderId') && val.orderId!='' && val.orderId!=null){
            var orderRoute="{{route('orders.edit',':id')}}";
            orderRoute=orderRoute.replace(':id',val.orderId);
            html.push('<span class="action-btn"><a href="' + orderRoute + '"><i class="fa fa-eye"></i></a></span>');
        }else{
            html.push('<td></td>');
        }
        return html;
    }

    async function payoutuserfunction(user) {
        var payoutuser = '';

        await database.collection('users').where("id", "==", user).get().then(async function (snapshotss) {

            if (snapshotss.docs[0]) {
                payoutuser = snapshotss.docs[0].data();
            }
        });
        return payoutuser;
    }
</script>


@endsection
