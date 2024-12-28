@extends('layouts.app')

@section('content')
<div class="page-wrapper">


    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.currency_table')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.currency_table')}}</li>
            </ol>
        </div>

        <div>

        </div>

    </div>


    <div class="container-fluid">
        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">{{trans('lang.processing')}}
        </div>

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="{!! url()->current() !!}"><i
                                            class="fa fa-list mr-2"></i>{{trans('lang.currency_table')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! route('currencies.create') !!}"><i
                                            class="fa fa-plus mr-2"></i>{{trans('lang.currency_create')}}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive m-t-10">


                            <table id="currenciesTable"
                                   class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                   cellspacing="0" width="100%">

                                <thead>

                                <tr>
                                <?php if (in_array('currencies.delete', json_decode(@session('user_permissions'), true))) { ?>

                                    <th class="delete-all"><input type="checkbox" id="is_active">
                                        <label class="col-3 control-label" for="is_active">
                                            <a id="deleteAll" class="" href="javascript:void(0)"><i
                                                        class="fa fa-trash"></i> {{trans('lang.all')}}</a>
                                        </label>
                                    </th>
                                <?php }?>

                                    <th>{{trans('lang.currency_name')}}</th>

                                    <th>{{trans('lang.currency_symbol')}}</th>

                                    <th>{{trans('lang.currency_code')}}</th>
                                    <th>{{trans('lang.symbole_at_right')}}</th>
                                    <th>{{trans('lang.active')}}</th>
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

    if ($.inArray('currencies.delete', user_permissions) >= 0) {
            checkDeletePermission = true;
        }

    var database = firebase.firestore();
    var offest = 1;
    var pagesize = 10;
    var end = null;
    var endarray = [];
    var start = null;
    var ref = database.collection('currencies').orderBy('name');

    var append_list = '';

    $(document).ready(function () {

        var inx = parseInt(offest) * parseInt(pagesize);
        jQuery("#data-table_processing").show();

        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.get().then(async function (snapshots) {
            html = '';

            html = await buildHTML(snapshots);
            jQuery("#data-table_processing").hide();
            if (html != '') {
                append_list.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
                if (snapshots.docs.length < pagesize) {
                    jQuery("#data-table_paginate").hide();
                }
            }
            $('#currenciesTable').DataTable({
                order: (checkDeletePermission==true) ? ['1', 'asc']: ['0','asc'],
                columnDefs: [

                    {orderable: false, targets: (checkDeletePermission==true) ? [0, 4, 5, 6] : [3,4,5]},
                ],

                "language": {
                    "zeroRecords": "{{trans("lang.no_record_found")}}",
                    "emptyTable": "{{trans("lang.no_record_found")}}"
                },
                responsive: true
            });

        });

    });


    function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });

        var count = 0;
        alldata.forEach((listval) => {

            var val = listval;

            html = html + '<tr>';

            var id = val.id;
            var route1 = '{{route("currencies.edit", ":id")}}';
            route1 = route1.replace(':id', id);
            if(checkDeletePermission){
            html = html + '<td class="delete-all" class="do_not_delete"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>';
            }
            html = html + '<td><a href="' + route1 + '">' + val.name + '</a></td>';
            html = html + '<td>' + val.symbol + '</td>';
            html = html + '<td>' + val.code + '</td>';


            if (val.symbolAtRight) {
                html = html + '<td><span class="badge badge-success">Yes</span></td>';
            } else {
                html = html + '<td><span class="badge badge-danger">No</span></td>';
            }

            if (val.isActive) {
                html = html + '<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>';
            } else {
                html = html + '<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>';
            }

            html = html + '<td class="action-btn"><a href="' + route1 + '"><i class="fa fa-edit"></i></a>';
            if(checkDeletePermission){
            html=html+'<a id="' + val.id + '" name="category-delete" class="delete-btn" href="javascript:void(0)"><i class="fa fa-trash"></i></a>';
            }
            html=html+'</td>';

            html = html + '</tr>';
        });
        return html;
    }

    $("#is_active").click(function () {
        $("#currenciesTable .is_open").prop('checked', $(this).prop('checked'));

    });

    $("#deleteAll").click(function () {

        if ($('#currenciesTable .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#currenciesTable .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    database.collection('currencies').doc(dataId).delete().then(function () {
                        window.location.reload();
                    });

                });
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });

    $(document).on("click", "input[name='isActive']", function (e) {


        var ischeck = $(this).is(':checked');
        var id = this.id;
        if (ischeck) {
            database.collection('currencies').doc(id).update({'isActive': true}).then(function (result) {

            });
            //only 1 currency should active at a time
            database.collection('currencies').where('isActive', "==", true).get().then(function (snapshots) {
                var activeCurrency = snapshots.docs[0].data();
                var activeCurrencyId = activeCurrency.id;
                database.collection('currencies').doc(activeCurrencyId).update({'isActive': false});

                $("#append_list1 tr").each(function () {
                    $(this).find(".switch #" + activeCurrencyId).prop('checked', false);
                });
            });
        } else {
            database.collection('currencies').where('isActive', "==", true).get().then(function (snapshots) {
                var activeCurrency = snapshots.docs[0].data();
                var activeCurrencyId = activeCurrency.id;
                if (snapshots.docs.length == 1 && activeCurrencyId == id) {
                    alert('Can not disable all currency');
                    $("#" + id).prop('checked', true);
                    return false;
                } else {
                    database.collection('currencies').doc(id).update({'isActive': false}).then(function (result) {
                    });
                }
            });
        }

    });


    $(document).on("click", "a[name='category-delete']", function (e) {


        var id = this.id;
        database.collection('currencies').doc(id).delete().then(function (result) {
            window.location.reload();
        });

    });

</script>

@endsection
