@extends('layouts.app')

@section('content')

    <div class="page-wrapper">


        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.dynamic_notification')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>

                    <li class="breadcrumb-item">{{trans('lang.dynamic_notification')}}</li>

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
                        <div class="card-header">
                            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{!! url()->current() !!}"><i
                                                class="fa fa-list mr-2"></i>{{trans('lang.notificaions_table')}}</a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive m-t-10">


                                <table id="notificationTable"
                                       class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                       cellspacing="0" width="100%">

                                    <thead>

                                    <tr>
                                        <th>{{trans('lang.order_id')}}</th>
                                        <th>{{trans('lang.role')}}</th>
                                        <th>{{trans('lang.name')}}</th>
                                        <th>{{trans('lang.subject')}}</th>
                                        <th>{{trans('lang.date_created')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
                                    </tr>

                                    </thead>

                                    <tbody id="append_restaurants">


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

        var ref = database.collection('notifications').orderBy('createdAt', 'desc');

        var append_list = '';

        $(document).ready(function () {

            jQuery("#data-table_processing").show();

            const table = $('#notificationTable').DataTable({
                pageLength: 10, // Number of rows per page
                processing: false, // Show processing indicator
                serverSide: true, // Enable server-side processing
                responsive: true,
                ajax: async function (data, callback, settings) {
                    jQuery("#data-table_processing").show();

                    const start = data.start;
                    const length = data.length;
                    const searchValue = data.search.value.toLowerCase();
                    const orderColumnIndex = data.order[0].column;
                    const orderDirection = data.order[0].dir;

                    var orderableColumns = ['orderId','role', 'name', 'title', 'createdAt','']// Ensure this matches the actual column names

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
                            var userName = await getUserName(childData.userId);
                            childData.name = userName ? userName : "";
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
                                    (userName && userName.toLowerCase().toString().includes(searchValue)) ||
                                    (childData.role && childData.role.toLowerCase().toString().includes(searchValue)) ||
                                    (childData.title && childData.title.toLowerCase().toString().includes(searchValue)) ||
                                    (childData.orderId && childData.orderId.toLowerCase().toString().includes(searchValue)) ||
                                    (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1)


                                ) {
                                    filteredRecords.push(childData);
                                }
                            } else {
                                filteredRecords.push(childData);
                            }
                        }));

                        filteredRecords.sort((a, b) => {
                            let aValue = a[orderByField] /* ? a[orderByField].toString().toLowerCase().trim() : '' */;
                            let bValue = b[orderByField] /* ? b[orderByField].toString().toLowerCase().trim() : '' */;
                            if (orderByField === 'createdAt') {                                
                                aValue = a[orderByField] ? new Date(a[orderByField].toDate()).getTime() : 0;
                                bValue = b[orderByField] ? new Date(b[orderByField].toDate()).getTime() : 0;
                            }else{
                                aValue = a[orderByField] ? a[orderByField].toString().toLowerCase().trim() : '';
                                bValue = b[orderByField] ? b[orderByField].toString().toLowerCase().trim() : '';
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
                order:  [[4,'desc']],
                columnDefs: [
                    { orderable: false, targets: [5] }
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
        $("#is_active").click(function () {
            $("#notificationTable .is_open").prop('checked', $(this).prop('checked'));
        });

        $("#deleteAll").click(function () {

            if ($('#notificationTable .is_open:checked').length) {
                if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                    jQuery("#data-table_processing").show();
                    $('#notificationTable .is_open:checked').each(function () {
                        var dataId = $(this).attr('dataId');

                        database.collection('notifications').doc(dataId).delete().then(function () {

                            window.location.reload();
                        });

                    });

                }
            } else {
                alert("{{trans('lang.select_delete_alert')}}");
            }
        });
       

        async function buildHTML(val) {

            var html = [];
            var id = val.id;

            var notification_view = '{{route('notifications.view','id')}}';
            notification_view = notification_view.replace('id', val.id);

            html.push(val.orderId);
            html.push(val.role );
            html.push(val.name );
            html.push(val.title );

            if (val.hasOwnProperty("createdAt")) {
                try {
                    var date = val.createdAt.toDate().toDateString();
                    var time = val.createdAt.toDate().toLocaleTimeString('en-US');
                } catch (err) {
                }
                html.push('<td class="dt-time">' + date + ' ' + time + '</td>');
            } else {
                html.push('<td></td>');
            }

            html.push('<span class="action-btn"><a href="' + notification_view + '"><i class="fa fa-eye"></i></a><a id="' + val.id + '" name="notifications-delete" class="delete-btn" href="javascript:void(0)"><i class="fa fa-trash"></i></a></span>');

            return html;
        }

        async function getUserName(userId) {
            var name = '';

            await database.collection('users').where('id', '==', userId).get().then(function (snapshots) {

                if (snapshots.docs.length) {
                    var data = snapshots.docs[0].data();

                    name = data.name;
                } else {
                    name = '{{trans('lang.unknown')}}';
                }
            });


            return name;
        }


        $(document).on("click", "a[name='notifications-delete']", function (e) {
            var id = this.id;
            database.collection('notifications').doc(id).delete().then(function () {
                window.location.reload();
            });
        });
    </script>


@endsection
