@extends('layouts.app')

@section('content')

<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.driver_plural')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>

                <li class="breadcrumb-item active">{{trans('lang.driver_table')}}</li>

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
                                <a class="nav-link active" href="{!! route('drivers') !!}"><i
                                        class="fa fa-list mr-2"></i>{{trans('lang.driver_table')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! route('drivers.create') !!}"><i
                                        class="fa fa-plus mr-2"></i>{{trans('lang.drivers_create')}}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive m-t-10">

                            <table id="driverTable"
                                class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                cellspacing="0" width="100%">

                                <thead>

                                    <tr>
                                        <?php if (in_array('drivers.delete', json_decode(@session('user_permissions'), true))) { ?>

                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                        class="do_not_delete" href="javascript:void(0)"><i
                                                            class="fa fa-trash"></i> {{trans('lang.all')}}</a></label>
                                            </th>
                                        <?php } ?>

                                        <th>{{trans('lang.extra_image')}}</th>

                                        <th>{{trans('lang.user_name')}}</th>
                                        <th>{{trans('lang.email')}}</th>
                                        <th>{{trans('lang.date')}}</th>

                                        <th>{{trans('lang.active')}}</th>

                                        <th>{{trans('lang.dashboard_total_orders')}}</th>


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

    var offest = 1;
    var pagesize = 10;
    var end = null;
    var endarray = [];
    var start = null;
    var user_number = [];
    var ref = database.collection('users').where("role", "==", "driver").orderBy('createdAt', 'desc');
    var alldriver = database.collection('users').where("role", "==", "driver");
    var append_list = '';


    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');

    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    });
    var user_permissions = '<?php echo @session('user_permissions') ?>';

    user_permissions = JSON.parse(user_permissions);

    var checkDeletePermission = false;

    if ($.inArray('drivers.delete', user_permissions) >= 0) {
        checkDeletePermission = true;
    }
    $(document).ready(function () {
        jQuery("#data-table_processing").show();
        const table = $('#driverTable').DataTable({
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

                var orderableColumns = (checkDeletePermission == true) ? ['', '', 'name', 'email','createdAt','','orders',''] : [ '', 'name', 'email','createdAt','','orders',''] // Ensure this matches the actual column names

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
                        const orders = await orderDetails(childData.id);
                        childData['orders'] = orders ? orders : "";
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
                                (childData.name && childData.name.toLowerCase().toString().includes(searchValue)) ||
                                (childData.email && childData.email.toLowerCase().toString().includes(searchValue)) ||
                                (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1)

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
                        if (orderByField === 'orders') {
                            aValue = parseInt(a[orderByField]) || 0;
                            bValue = parseInt(b[orderByField]) || 0;
                        }
                        if (orderByField === 'createdAt') {
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
            order: (checkDeletePermission == true) ? [4, "desc"] : [3, "desc"],
            columnDefs: [
                {
                    targets: (checkDeletePermission == true) ? 4 : 3,
                    type: 'date',
                    render: function (data) {

                        return data;
                    }
                },
                { orderable: false, targets: (checkDeletePermission == true) ? [0, 1, 5, 7] : [0, 4, 6] }
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

        alldriver.get().then(async function (snapshotsdriver) {

            snapshotsdriver.docs.forEach((listval) => {
                database.collection('orders').where('driverID', '==', listval.id).where("status", "in", ["Order Completed"]).get().then(async function (orderSnapshots) {
                    var count_order_complete = orderSnapshots.docs.length;
                    database.collection('users').doc(listval.id).update({ 'orderCompleted': count_order_complete }).then(function (result) {

                    });

                });

            });
        });

    });

    async function buildHTML(val) {
        var html = [];
        newdate = '';
        var id = val.id;

        var route1 = '{{route("drivers.view",":id")}}';
        route1 = route1.replace(':id', id);
        var route2 = '{{route("drivers.edit",":id")}}';
        route2 = route2.replace(':id', id);
        if (checkDeletePermission) {
            html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>');
        }
        if (val.profilePictureURL) {
            html.push('<td><img class="rounded" style="width:50px" src="' + val.profilePictureURL + '" alt="image"></td>');
        } else {
            html.push('<td><img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image"></td>');
        }
        html.push('<td data-url="' + route1 + '" class="redirecttopage">' + val.name + '</td>');
        html.push('<td>  ' + shortEmail(val.email) + '</td>');

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

        if (val.active) {
            html.push('<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>');
        } else {
            html.push('<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>');
        }

        var trroute2 = '{{route("orders",":id")}}';
        trroute2 = trroute2.replace(':id', 'driverId=' + id);

        const driver = await orderDetails(val.id);
        html.push('<td><a href="' + trroute2 + '">' + driver + '</a></td>');

        var driverView = '{{route("drivers.view",":id")}}';
        driverView = driverView.replace(':id', id);
        var actionHtml = '';
        actionHtml = actionHtml + '<span class="action-btn"><a href="' + driverView + '"><i class="fa fa-eye"></i></a><a href="' + route2 + '"><i class="fa fa-edit"></i></a>';
        if (checkDeletePermission) {
            actionHtml = actionHtml + '<a id="' + val.id + '" name="driver-delete" class="delete-btn" href="javascript:void(0)"><i class="fa fa-trash"></i></a>';
        }
        actionHtml = actionHtml + '</span>';
        html.push(actionHtml);
        return html;
    }

    async function orderDetails(driver) {
        var count_order_complete = 0;
        await database.collection('orders').where('driverID', '==', driver).get().then(async function (orderSnapshots) {
            count_order_complete = orderSnapshots.docs.length;
        });
        return count_order_complete;
    }

    $(document).on("click", "input[name='isActive']", function (e) {

        var ischeck = $(this).is(':checked');
        var id = this.id;
        if (ischeck) {
            database.collection('users').doc(id).update({ 'active': true }).then(function (result) {
            });
        } else {
            database.collection('users').doc(id).update({ 'active': false }).then(function (result) {
            });
        }
    });

    $("#is_active").click(function () {
        $("#driverTable .is_open").prop('checked', $(this).prop('checked'));

    });

    $("#deleteAll").click(function () {

        if ($('#driverTable .is_open:checked').length) {

            if (confirm("{{trans('lang.selected_delete_alert')}}")) {

                jQuery("#data-table_processing").show();

                $('#driverTable .is_open:checked').each(function () {

                    var dataId = $(this).attr('dataId');

                    database.collection('users').doc(dataId).delete().then(function () {

                        const getStoreName = deleteDriverData(dataId);

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

    async function deleteDriverData(driverId) {
        if (confirm("{{trans('lang.delete_alert')}}")) {
            await database.collection('order_transactions').where('driverId', '==', driverId).get().then(async function (snapshotsOrderTransacation) {
                if (snapshotsOrderTransacation.docs.length > 0) {
                    snapshotsOrderTransacation.docs.forEach((temData) => {
                        var item_data = temData.data();

                        database.collection('order_transactions').doc(item_data.id).delete().then(function () {

                        });
                    });
                }

            });

            await database.collection('driver_payouts').where('driverID', '==', driverId).get().then(async function (snapshotsItem) {

                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();

                        database.collection('driver_payouts').doc(item_data.id).delete().then(function () {

                        });
                    });
                }

            });

            //delete user from authentication
            var route1 = '{{route("delete-user", ":id")}}';
            route1 = route1.replace(':id', driverId);
            jQuery.ajax({
                url: route1,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token() ?>'
                },
                success: function (data) {
                    console.log('Delete user success:', data);
                },
            });


        }
    }

    $(document.body).on('click', '.redirecttopage', function () {
        var url = $(this).attr('data-url');
        window.location.href = url;
    });

    $(document).on("click", "a[name='driver-delete']", function (e) {


        var id = this.id;
        database.collection('users').doc(id).delete().then(function () {
            deleteDriverData(id).then(function () {
                setTimeout(function () {
                    window.location.reload();
                }, 9000);
            });
        });


    });


</script>

@endsection
