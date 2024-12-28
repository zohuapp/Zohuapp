@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.landingpageTemplate')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{trans('lang.landingpageTemplate')}}
                </li>
            </ol>
        </div>
        <div>

        </div>

    </div>

    <div class="card-body">

        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
            {{trans('lang.processing')}}
        </div>
        <div class="error_top"></div>

        <div class="row restaurant_payout_create">
            <div class="restaurant_payout_create-inner">
                <fieldset>
                    <legend>
                        {{trans('lang.landingpageTemplate')}}
                    </legend>

                    <div class="form-group width-100">
                        <textarea class="form-control col-7" name="landingpageTemplate" id="landingpageTemplate"></textarea>
                    </div>

                </fieldset>

            </div>
        </div>
    </div>

    <div class="form-group col-12 text-center btm-btn">
        <button type="button" class="btn btn-primary  edit-form-btn">
            <i class="fa fa-save"></i> {{ trans('lang.save')}}
        </button>
        <a href="{!! route('dashboard') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
    </div>

</div>

@endsection

@section('scripts')

<script>

    var database = firebase.firestore();
    var photo = "";
    var ref = database.collection('settings').doc('landingpageTemplate');

    $(document).ready(function () {
        try {
            jQuery("#data-table_processing").show();
            ref.get().then(async function (snapshots) {
                var landingpageTemplateData = snapshots.data();

                if (landingpageTemplateData == undefined) {
                    database.collection('settings').doc('landingpageTemplate').set({"landingpageTemplate": ""});
                }

                if (landingpageTemplateData.landingpageTemplate) {
                    $('#landingpageTemplate').summernote("code", landingpageTemplateData.landingpageTemplate);
                }
            });
            jQuery("#data-table_processing").hide();
        } catch (error) {
            jQuery("#data-table_processing").hide();
        }

        $('#landingpageTemplate').summernote({
            height: 400,
            width: 1024,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['forecolor', ['forecolor']],
                ['backcolor', ['backcolor']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ]
        });

        $(".edit-form-btn").click(function () {

            var landingpageTemplate = $('#landingpageTemplate').summernote('code');

            $(".error_top").hide();
            $(".error_top").html("");

            if (landingpageTemplate == '') {
                $(".error_top").show();
                $(".error_top").append("<p>{{trans('lang.landingpageTemplate_error')}}</p>");
                window.scrollTo(0, 0);
            } else {

                jQuery("#data-table_processing").show();
                database.collection('settings').doc('landingpageTemplate').update({'landingpageTemplate': landingpageTemplate}).then(function (result) {
                    window.location.href = '{{ route("landingpageTemplate")}}';
                });
            }
        })
    });

</script>
@endsection
