@extends('layouts.app')

@section('content')

<div class="page-wrapper">

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.user_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.user_table')}}</li>
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
                                        class="fa fa-list mr-2"></i>{{trans('lang.user_table')}}</a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive m-t-10">
                            <table id="userTable"
                                class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        {{-- {{ dd(session('user_permissions')); }} --}}
                                        @if (in_array('users.delete', json_decode(@session('user_permissions'), true)))

                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                        class="do_not_delete" href="javascript:void(0)"><i
                                                            class="fa fa-trash"></i> {{trans('lang.all')}}</a></label>
                                            </th>
                                        @endif
                                        <th>{{trans('lang.extra_image')}}</th>
                                        <th>{{trans('lang.user_name')}}</th>
                                        <th>{{trans('lang.email')}}</th>
                                        <th>{{trans('lang.vendor_phone')}}</th>
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
    var user_permissions = '<?php echo @session('user_permissions') ?>';

    user_permissions = JSON.parse(user_permissions);

    var checkDeletePermission = false;

    if ($.inArray('users.delete', user_permissions) >= 0) {
        checkDeletePermission = true;
    }

    var database = firebase.firestore();

    var offest = 1;
    var pagesize = 10;
    var end = null;
    var endarray = [];
    var start = null;
    var user_number = [];

    var ref = database.collection('users').where("role", "in", ["customer"]).orderBy('createdAt', 'desc');

    var placeholderImage = '';
    var append_list = '';

    var placeholder = database.collection('settings').doc('placeHolderImage');

    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;

    });

    $(document.body).on('click', '.redirecttopage', function () {
        var url = $(this).attr('data-url');
        window.location.href = url;
    });

    $(document).ready(function () {

        jQuery("#data-table_processing").show();

        const table = $('#userTable').DataTable({
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

                var orderableColumns = (checkDeletePermission) ?  ['','', 'name', 'email','countryCode','createdAt','','orders',''] : [ '', 'name', 'email','countryCode','createdAt','','orders','']  // Ensure this matches the actual column names

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
                        childData.orders = orders ? orders : 0;
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
                                (childData.phoneNumber && childData.phoneNumber.toLowerCase().toString().includes(searchValue)) ||
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
                        let aValue = a[orderByField] ? a[orderByField].toString().toLowerCase().trim() : '';
                        let bValue = b[orderByField] ? b[orderByField].toString().toLowerCase().trim() : '';
                        if (orderByField === 'orders') {
                            aValue = parseInt(a[orderByField]) || 0;
                            bValue = parseInt(b[orderByField]) || 0;
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
            order: (checkDeletePermission) ? [[5,'desc']] : [[4,'desc']],
            columnDefs: [
                {
                    targets: (checkDeletePermission) ? 5 : 4,
                    type: 'date',
                    render: function (data) {
                        return data;
                    }
                },
                { orderable: false, targets: (checkDeletePermission) ? [0,1, 6,8] : [0, 5,7] }
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
        newdate = '';
        var id = val.id;
        var route1 = '{{route("users.edit", ":id")}}';
        route1 = route1.replace(':id', id);
        var user_view = '{{route("users.view", ":id")}}';
        user_view = user_view.replace(':id', id);

        <?php if (in_array('users.delete', json_decode(@session('user_permissions'), true))) { ?>
        html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
            'for="is_open_' + id + '" ></label></td>');
        <?php } ?>

        if (val.hasOwnProperty('image') && val.image != '') {
            html.push('<td><img class="rounded" style="width:50px" src="' + val.image + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="image"></td>');
        } else {
            html.push('<td><img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image"></td>');
        }
        html.push('<td data-url="' + user_view + '" class="redirecttopage">' + val.name + '</td>');

        var email = 'N/A';

        if (val.email) {
            email = shortEmail(val.email);
        }
        html.push('<td>' + email + '</td>');
        html.push('<td>' + val.countryCode + "" + EditPhoneNumber(val.phoneNumber) + '</td>');

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


        var trroute2 = '{{route("orders", ":id")}}';
        trroute2 = trroute2.replace(':id', 'userId=' + id);


        const user = await orderDetails(val.id);
        html.push('<td><a href="' + trroute2 + '">' + user + '</a></td>');

        var actionHtml = '';
        actionHtml = actionHtml + '<span class="action-btn"><a href="' + user_view + '"><i class="fa fa-eye"></i></a><a href="' + route1 + '"><i class="fa fa-edit"></i></a>';
        <?php if (in_array('users.delete', json_decode(@session('user_permissions'), true))) { ?>
            actionHtml = actionHtml + '<a id="' + val.id + '" class="delete-btn" name="user-delete" href="javascript:void(0)"><i class="fa fa-trash"></i></a>';
        <?php }?>
            actionHtml = actionHtml + '</span>';
        html.push(actionHtml);
        return html;
    }


    async function orderDetails(user) {
        var count_order_complete = 0;
        await database.collection('orders').where('userID', '==', user).get().then(async function (orderSnapshots) {
            count_order_complete = orderSnapshots.docs.length;
        });
        return count_order_complete;
    }

    $("#is_active").click(function () {
        $("#userTable .is_open").prop('checked', $(this).prop('checked'));
    });

    $("#deleteAll").click(function () {

        if ($('#userTable .is_open:checked').length) {

            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#userTable .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');

                    database.collection('users').doc(dataId).delete().then(function () {
                        const getStoreName = deleteUserData(dataId);
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

    async function deleteUserData(userId) {
        var route1 = '{{route("delete-user", ":id")}}';
        route1 = route1.replace(':id', userId);
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

        await database.collection('wallet').where('user_id', '==', userId).get().then(async function (snapshotsItem) {

            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();

                    database.collection('wallet').doc(item_data.id).delete().then(function () {

                    });
                });
            }
        });

    }
    $(document).on("click", "a[name='user-delete']", function (e) {

        var id = this.id;
        jQuery("#data-table_processing").show();

        database.collection('users').doc(id).delete().then(function (result) {
            const getStoreName = deleteUserData(id);
            setTimeout(function () {
                window.location.href = '{{ url()->current() }}';
            }, 7000);
        });

    });

    $(document).on("click", "input[name='isActive']", function (e) {

        var ischeck = $(this).is(':checked');
        var id = this.id;
        if (ischeck) {
            database.collection('users').doc(id).update({
                'active': true
            }).then(function (result) {
            });
        } else {
            database.collection('users').doc(id).update({
                'active': false
            }).then(function (result) {
            });
        }

    });
</script>

@endsection
