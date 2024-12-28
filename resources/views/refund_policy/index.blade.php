@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.refund_policy')}}</h3>
            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.refund_policy')}}</li>
                </ol>
            </div>
            <div>

            </div>

        </div>

        <div class="card-body">

            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                 style="display: none;">{{trans('lang.processing')}}</div>
            <div class="error_top"></div>

            <div class="terms-cond restaurant_payout_create row">
                <div class="restaurant_payout_create-inner">
                    <fieldset>
                        <legend>{{trans('lang.refund_policy')}}</legend>

                        <div class="form-group width-100">
                            <textarea class="form-control col-7" name="refund_policy" id="refund_policy"></textarea>
                        </div>


                    </fieldset>

                </div>
            </div>
        </div>

        <div class="form-group col-12 text-center btm-btn">
            <button type="button" class="btn btn-primary  edit-form-btn"><i
                        class="fa fa-save"></i> {{ trans('lang.save')}}</button>
            <a href="{!! route('refund-policy') !!}" class="btn btn-default"><i
                        class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
        </div>

    </div>

@endsection

@section('scripts')
    <script>

        var database = firebase.firestore();
        var photo = "";
        var ref = database.collection('settings').doc('refundPolicy');
        $(document).ready(function () {
            try {
                jQuery("#data-table_processing").show();
                ref.get().then(async function (snapshots) {
                    var user = snapshots.data();

                    if (user.refund_policy) {
                        $('#refund_policy').summernote("code", user.refund_policy);
                    }
                });
                jQuery("#data-table_processing").hide();
            } catch (error) {
                jQuery("#data-table_processing").hide();
            }
            $('#refund_policy').summernote({
                height: 400,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['forecolor', ['forecolor']],
                    ['backcolor', ['backcolor']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ]
            });

            $(".edit-form-btn").click(function () {

                var refund_policy = $('#refund_policy').summernote('code');

                if (refund_policy == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.refund_policy_error')}}</p>");
                    window.scrollTo(0, 0);
                } else {
                    database.collection('settings').doc('refundPolicy').update({'refund_policy': refund_policy}).then(function (result) {
                        window.location.href = '{{ route("refund-policy")}}';
                    })
                }
            })
        });

    </script>
@endsection
