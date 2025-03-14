@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">

            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.deliveryCharge')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.deliveryCharge')}}</li>
                </ol>
            </div>
        </div>

        <div class="card-body">
            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
                Processing...
            </div>
            <div class="row restaurant_payout_create">
                <div class="restaurant_payout_create-inner">
                    <fieldset>
                        <legend>{{trans('lang.deliveryCharge')}}</legend>

                        <div class="form-group row width-100">
                            <label class="col-4 control-label">{{ trans('lang.delivery_charges_per_km')}}</label>
                            <div class="col-7">
                                <input type="number" class="form-control" id="delivery_charges_per_km">
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-4 control-label">{{ trans('lang.minimum_delivery_charges')}}</label>
                            <div class="col-7">
                                <input type="number" class="form-control" id="minimum_delivery_charges">
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-4 control-label">{{ trans('lang.minimum_delivery_charges_within_km')}}</label>
                            <div class="col-7">
                                <input type="number" class="form-control" id="minimum_delivery_charges_within_km">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="form-group col-12 text-center">
                <button type="button" class="btn btn-primary edit-form-btn"><i
                            class="fa fa-save"></i> {{trans('lang.save')}}</button>
                <a href="{{url('/dashboard')}}" class="btn btn-default"><i
                            class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
            </div>
        </div>

    </div>
@endsection

@section('scripts')

    <script>

        var database = firebase.firestore();
        var ref_deliverycharge = database.collection('settings').doc("DeliveryCharge");
        $(document).ready(function () {
            jQuery("#data-table_processing").show();
            ref_deliverycharge.get().then(async function (snapshots_charge) {
                var deliveryChargeSettings = snapshots_charge.data();
                if (deliveryChargeSettings == undefined) {
                    database.collection('settings').doc('DeliveryCharge').set({
                        'delivery_charges_per_km': '',
                        'minimum_delivery_charges': '',
                        'minimum_delivery_charges_within_km': ''
                    });
                }
                jQuery("#data-table_processing").hide();
                try {
                    $("#delivery_charges_per_km").val(deliveryChargeSettings.delivery_charges_per_km);
                    $("#minimum_delivery_charges").val(deliveryChargeSettings.minimum_delivery_charges);
                    $("#minimum_delivery_charges_within_km").val(deliveryChargeSettings.minimum_delivery_charges_within_km);
                } catch (error) {

                }
            });

            $(".edit-form-btn").click(function () {

                var delivery_charges_per_km = parseInt($("#delivery_charges_per_km").val());
                var minimum_delivery_charges = parseInt($("#minimum_delivery_charges").val());
                var minimum_delivery_charges_within_km = parseInt($("#minimum_delivery_charges_within_km").val());

                database.collection('settings').doc("DeliveryCharge").update({
                    'delivery_charges_per_km': delivery_charges_per_km,
                    'minimum_delivery_charges': minimum_delivery_charges,
                    'minimum_delivery_charges_within_km': minimum_delivery_charges_within_km
                }).then(function (result) {
                    window.location.href = '{{ url("settings/app/deliveryCharge")}}';
                });


            })
        })
    </script>




@endsection
