@extends('layouts.app')


@section('content')
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.languages')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.languages')}}</li>
            </ol>
        </div>
        <div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="{!! url()->current() !!}"><i
                                        class="fa fa-list mr-2"></i>{{trans('lang.languages')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! route('settings.languages.save') !!}"><i
                                        class="fa fa-plus mr-2"></i>{{trans('lang.language_create')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive m-t-10">
                            <table id="languageTable"
                                class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <?php if (in_array('settings.app.languages.delete', json_decode(@session('user_permissions'), true))) { ?>

                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                        class="do_not_delete" href="javascript:void(0)"><i
                                                            class="fa fa-trash"></i> {{trans('lang.all')}}</a></label>
                                            </th>
                                        <?php } ?>

                                        <th>{{trans('lang.image')}}</th>
                                        <th>{{trans('lang.name')}}</th>
                                        <th>{{trans('lang.code')}}</th>
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
    @endsection


    @section('scripts')

    <script type="text/javascript">
        var user_permissions = '<?php echo @session('user_permissions') ?>';

        user_permissions = JSON.parse(user_permissions);

        var checkDeletePermission = false;

        if ($.inArray('settings.app.languages.delete', user_permissions) >= 0) {
            checkDeletePermission = true;
        }

        var database = firebase.firestore();

        var ref = database.collection('languages');

        var placeholderImage = '';
        var placeholder = database.collection('settings').doc('placeHolderImage');



        var append_list = '';
        var deleteMsg = "{{trans('lang.delete_alert')}}";
        var deleteSelectedRecordMsg = "{{trans('lang.selected_delete_alert')}}";
        var languageSwitchAlert = "{{trans('lang.language_switch_alert')}}";
        $(document).ready(async function () {
            await placeholder.get().then(async function (snapshotsimage) {

                var placeholderImageData = snapshotsimage.data();

                placeholderImage = placeholderImageData.image;

            });
            jQuery("#overlay").show();

            append_list = document.getElementById('append_list1');
            append_list.innerHTML = '';

            ref.get().then(async function (snapshots) {

                var html = '';
                html = await buildHTML(snapshots);
                if (html != '') {
                    append_list.innerHTML = html;
                }
                $('#languageTable').DataTable({
                    order: (checkDeletePermission) ? [[2, 'asc']] : [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, targets: (checkDeletePermission) ? [0, 1, 4, 5] : [0, 3, 4] },
                    ],
                    "language": {
                        "zeroRecords": "{{trans('lang.no_record_found')}}",
                        "emptyTable": "{{trans('lang.no_record_found')}}"
                    }
                });

                jQuery("#overlay").hide();
            });
        });

        async function buildHTML(snapshots) {
            var html = '';
            await Promise.all(snapshots.docs.map(async (listval) => {
                var val = listval.data();
                var getData = await getListData(val);
                html += getData;
            }));
            return html;
        }

        function getListData(val) {
            var html = '';

            html = html + '<tr>';
            newdate = '';
            var id = val.id;
            var route1 = '{{route("settings.languages.save", ":id")}}';
            route1 = route1.replace(':id', id);
            if (checkDeletePermission) {
                html = html + '<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                    'for="is_open_' + id + '" ></label></td>';
            }

            if (val.image == '' || val.image == undefined) {

                html = html + '<td><img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'"></td>';

            } else {

                if (val.image) {
                    photo = val.image;
                } else {
                    photo = placeholderImage;
                }
                html = html + '<td><img class="rounded" style="width:50px" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="image"></td>';

            }



            // html = html + '<td><a href="' + route1 + '"><img src="' + image + '" class="rounded" style="width:50px" alt="image" ></a></td>';

            html = html + '<td><a href="' + route1 + '">' + val.name + '</a></td>';

            html = html + '<td>' + val.code + '</td>';

            if (val.enable) {
                html = html + '<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>';
            } else {
                html = html + '<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>';
            }

            html = html + '<td class="action-btn"><a href="' + route1 + '"><i class="fa fa-edit"></i></a>';
            if (checkDeletePermission) {
                html = html + '<a id="' + val.id + '" class="delete-btn" name="lang-delete" href="javascript:void(0)"><i class="fa fa-trash"></i></a>';
            }
            html = html + '</td>';

            html = html + '</tr>';

            return html;
        }

        $("#is_active").click(function () {
            $("#languageTable .is_open").prop('checked', $(this).prop('checked'));
        });

        $("#deleteAll").click(function () {

            if ($('#languageTable .is_open:checked').length) {
                if (confirm(deleteSelectedRecordMsg)) {
                    jQuery("#overlay").show();
                    $('#languageTable .is_open:checked').each(function () {
                        var dataId = $(this).attr('dataId');
                        database.collection('languages').doc(dataId).delete().then(function () {
                            window.location.reload();
                        });
                    });
                } else {
                    return false;
                }
            } else {
                alert("{{trans('lang.select_delete_alert')}}");
            }
        });

        $(document).on("click", "input[name='isActive']", function (e) {

            var ischeck = $(this).is(':checked');
            var id = this.id;
            if (ischeck) {
                database.collection('languages').doc(id).update({
                    'enable': true
                }).then(function (result) {
                });
            } else {
                database.collection('languages').doc(id).update({ 'enable': false });

                database.collection('languages').where('enable', "==", true).get().then(function (snapshots) {
                    if (snapshots.docs.length > 0) {
                    } else {
                        database.collection('languages').doc(id).update({ 'enable': true });
                        alert(languageSwitchAlert);
                        $("#" + id).prop('checked', true);

                    }
                });

            }

        });

        $(document).on("click", "a[name='lang-delete']", function (e) {

            var id = this.id;
            if (confirm(deleteMsg)) {
                jQuery("#overlay").show();
                database.collection('languages').doc(id).delete().then(function (result) {

                    window.location.href = '{{ url()->current() }}';
                });
            } else {
                return false;
            }

        });
    </script>

    @endsection
