@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">

            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor restaurantTitle">{{trans('lang.notification_detail')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a
                                href="{!! route('notifications.index') !!}">{{trans('lang.notifications')}}</a>
                    </li>
                    <li class="breadcrumb-item active">{{trans('lang.notification_detail')}}</li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">
            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                 style="display: none;">Processing...
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="resttab-sec">

                        <div class="row restaurant_payout_create user_details">
                            <div class="restaurant_payout_create-inner">

                                <fieldset>

                                    <legend>{{trans('lang.notification_detail')}}</legend>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.notification_type')}}</label>
                                        <div class="col-7">
                                            <span class="notification_type" id="notification_type"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.role')}}</label>
                                        <div class="col-7">
                                            <span class="role" id="role"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.title')}}</label>
                                        <div class="col-7">
                                            <span class="title" id="title"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.notification_body')}}</label>
                                        <div class="col-7">
                                            <span class="notification_body" id="notification_body"></span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="form-group col-12 text-center btm-btn">
                    <a href="{!! route('notifications.index') !!}" class="btn btn-default"><i
                                class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        var id = "{{$id}}";
        var database = firebase.firestore();
        var ref = database.collection('notifications').where("id", "==", id);

        $(document).ready(async function () {

            jQuery("#data-table_processing").show();

            ref.get().then(async function (snapshots) {
                var notifications = snapshots.docs[0].data();

                $(".notification_type").text(notifications.notificationType);
                $(".role").text(notifications.role);
                $(".title").text(notifications.title);
                $(".notification_body").text(notifications.body);


                jQuery("#data-table_processing").hide();

            });

        });

    </script>
@endsection