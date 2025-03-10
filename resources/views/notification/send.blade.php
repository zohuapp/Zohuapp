@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.notification')}}</h3>
            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('notification') }}">{{trans('lang.notifications')}}</a>
                    </li>
                    <li class="breadcrumb-item">{{trans('lang.general_notification')}}</li>
                    <li class="breadcrumb-item active">{{trans('lang.create_notification')}}</li>
                </ol>
            </div>

        </div>
        <div>

            <div class="card-body">

                <div id="data-table_processing" class="dataTables_processing panel panel-default"
                     style="display: none;">
                    {{trans('lang.processing')}}
                </div>

                <div class="error_top" style="display:none"></div>

                <div class="success_top" style="display:none"></div>

                <div class="row restaurant_payout_create">

                    <div class="restaurant_payout_create-inner">

                        <fieldset>
                            <legend>{{trans('lang.notification')}}</legend>


                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.subject')}}*</label>
                                <div class="col-7">
                                    <input type="text" class="form-control" id="subject">
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.message')}}*</label>
                                <div class="col-7">
                                    <textarea class="form-control" id="message"></textarea>
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.send_to')}}</label>
                                <div class="col-7">
                                    <select id='role' class="form-control">
                                        <option value="customer">{{trans('lang.customer')}}</option>
                                        <option value="driver">{{trans('lang.driver')}}</option>

                                    </select>
                                </div>
                            </div>

                        </fieldset>
                    </div>

                </div>

            </div>
            <div class="form-group col-12 text-center btm-btn">
                <button type="button" class="btn btn-primary save-form-btn"><i class="fa fa-save"></i> {{
                trans('lang.send')}}
                </button>
                <a href="{{url('/notification')}}" class="btn btn-default"><i
                            class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

    <script>

        var id = "<?php echo $id;?>";
        var database = firebase.firestore();
        var ref = database.collection('send_notifications').where("id", "==", id);
        var users = database.collection('users').where("fcmToken", "!=", "");
        var pagesize = 20;
        var start = '';
        $(document).ready(function () {

            jQuery("#data-table_processing").show();
            ref.get().then(async function (snapshots) {
                if (snapshots.docs.length) {
                    var np = snapshots.docs[0].data();
                    $("#message").val(np.message);
                    $("#role").val(np.role);
                }
                jQuery("#data-table_processing").hide();

            });

            $(".save-form-btn").click(async function () {

                $(".success_top").hide();
                $(".error_top").hide();
                var message = $("#message").val();
                var subject = $("#subject").val();
                var role = $("#role").val();

                if (subject == "") {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.please_enter_subject')}}</p>");
                    window.scrollTo(0, 0);
                    return false;
                } else if (message == "") {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.please_enter_message')}}</p>");
                    window.scrollTo(0, 0);
                    return false;
                }
                jQuery("#data-table_processing").show();
                const res = await database.collection('send_notifications').add({
                    message: message,
                    subject: subject,
                    role: role,
                    createdAt: new Date(),
                });
                const res1 = await database.collection('send_notifications').doc(res.id).update({'id': res.id});
                var iscompleted = 0;
                while (iscompleted < 1) {
                    if (start) {
                        usersget = users.where("role", "==", role).startAfter(start).limit(pagesize);
                    } else {
                        usersget = users.where("role", "==", role).limit(pagesize);
                    }
                    await usersget.get().then(async function (snapshots) {
                        var all_token = [];
                        snapshots.docs.forEach((userdata) => {
                            user_data = userdata.data();
                            all_token.push(user_data.fcmToken);
                        });
                        if (all_token.length > 0) {

                            $.ajax({
                                method: 'POST',
                                url: '<?php echo route('broadcastnotification'); ?>',
                                data: {
                                    'fcm': all_token,
                                    'subject': subject,
                                    'message': message,
                                    '_token': '<?php echo csrf_token() ?>'
                                }
                            }).done(function (data) {

                            }).fail(function (xhr, textStatus, errorThrown) {

                            });

                        }
                        if (snapshots.docs.length == pagesize) {
                            start = snapshots.docs[snapshots.docs.length - 1];
                        } else {
                            iscompleted = 1;
                        }
                    });

                }

                if (iscompleted) {
                    jQuery("#data-table_processing").hide();
                    $(".success_top").show();
                    $(".success_top").html("");
                    $(".success_top").append("<p>{{trans('lang.notification_send_success')}}</p>");
                    window.scrollTo(0, 0);
                    window.location.href = '{{ route("notification")}}';
                }
            });

        });

    </script>

@endsection
