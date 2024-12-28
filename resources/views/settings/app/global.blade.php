@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">

            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.app_setting_global') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.app_setting_global') }}</li>
                </ol>
            </div>
        </div>

        <div class="card-body">
            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
                {{ trans('lang.processing') }}
            </div>
            <div class="error_top" style="display:none"></div>
            <div class="row restaurant_payout_create">
                <div class="restaurant_payout_create-inner">
                    <fieldset>
                        <legend><i class="mr-3 mdi mdi-settings"></i>{{ trans('lang.app_setting_global') }}</legend>

                        <div class="form-group row width-100">
                            <label class="col-5 control-label">{{ trans('lang.app_setting_app_name') }}*</label>
                            <div class="col-7">
                                <input type="text" class="form-control application_name">
                                <div class="form-text text-muted">
                                    {{ trans('lang.app_setting_app_name_help') }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row width-100">
                            <label class="col-5 control-label">{{ trans('lang.app_setting_meta_title') }}</label>
                            <div class="col-7">
                                <input type="text" class="form-control meta_title">
                                <div class="form-text text-muted">
                                    {{ trans('lang.app_setting_meta_title_help') }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row width-50">
                            <label class="col-3 control-label">{{ trans('lang.upload_app_logo') }}*</label>
                            <input type="file" class="col-7" onChange="handleFileSelect(event)"
                                accept="image/png,image/jpg,image/jpeg">
                            <div id="uploding_image"></div>
                            <div class="logo_img_thumb"></div>
                        </div>

                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.menu_placeholder_image') }}*</label>
                            <input type="file" class="col-7" onChange="handleFileSelectplaceholder(event)"
                                accept="image/png,image/jpg,image/jpeg">
                            <div id="uploading_placeholder"></div>
                            <div class="placeholder_img_thumb"></div>
                        </div>

                        <div class="form-group row width-50">
                            <label class="col-3 control-label">{{ trans('lang.upload_favicon') }}*</label>
                            <input type="file" class="col-7" onChange="handleFileSelectFavicon(event)"
                                accept="image/png,image/jpg,image/jpeg">
                            <div id="uploding_favicon"></div>
                            <div class="favicon_img_thumb"></div>
                        </div>

                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.admin_panel_color_settings') }}</label>
                            <input type="color" class="ml-3" name="admin_color" id="admin_color">
                        </div>
                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.web_panel_color_settings') }}</label>
                            <input type="color" class="ml-3" name="web_color" id="web_color">
                        </div>
                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.driver_color_settings') }}</label>
                            <input type="color" class="ml-3" name="colour_driver" id="colour_driver">
                        </div>
                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.customer_color_settings') }}</label>
                            <input type="color" class="ml-3" name="colour_customer" id="colour_customer">
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{{ trans('lang.google_map_api_key_title') }}</legend>

                        <div class="form-group row width-100">
                            <label class="col-3 control-label">{{ trans('lang.google_map_api_key') }}</label>
                            <div class="col-7">
                                <input type="password" class="form-control address_line1" name="map_key" id="map_key">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{{ trans('lang.map_redirection') }}</legend>
                        <div class="form-group row width-100">
                            <label class="col-4 control-label">{{ trans('lang.select_map_type_for_application') }}</label>
                            <div class="col-7">
                                <select name="selectedMapType" id="selectedMapType" class="form-control selectedMapType">
                                    <option value="google">{{ trans('lang.google_maps') }}</option>
                                    <option value="osm">{{ trans('lang.open_street_map') }}</option>
                                </select>
                            </div>
                            <div class="form-text pl-3 text-muted">
                                <span><strong>{{ trans('lang.note') }} :</strong>
                                    {{ trans('lang.google_map_note') }}<br>
                                    {{ trans('lang.open_street_map_note') }}<br>
                                    <strong>{{ trans('lang.recommended_note') }}</strong></span>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{{ trans('lang.radius_configuration') }}</legend>

                        <div class="form-group row width-100">
                            <label class="col-3 control-label">{{ trans('lang.vendor_radius') }}</label>
                            <div class="col-7">
                                <input type="number" class="form-control vendor_radius" name="vendor_radius"
                                    id="vendor_radius">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend><i class="mr-3 mdi mdi-cash-100"></i>{{ trans('lang.order_settings_for_user') }}</legend>
                        <div class="form-group row width-100">
                            <label class="col-4 control-label">{{ trans('lang.minimum_placed_order_amount') }}*</label>
                            <div class="col-7">
                                <div class="control-inner">
                                    <div class="currentCurrency" style="left: 3px"></div>
                                    <input type="number" class="form-control min_order_amount">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-4 control-label">{{ trans('lang.minimum_deposit_amount') }}*</label>
                            <div class="col-7">
                                <div class="control-inner">
                                    <div class="currentCurrency" style="left: 3px"></div>
                                    <input type="number" class="form-control minimum_deposit_amount">
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend><i class="mr-3 fa fa-solid fa-address-book"></i>{{ trans('lang.contact_us') }}</legend>

                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.contact_us_address') }}</label>
                            <div class="col-7">
                                <textarea class="form-control contact_us_address" rows="3"></textarea>
                                <div class="form-text text-muted">
                                    {{ trans('lang.contact_us_address_help') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.contact_us_email') }}</label>
                            <div class="col-7">
                                <input type="text" class="form-control contact_us_email">
                                <div class="form-text text-muted">
                                    {{ trans('lang.contact_us_email_help') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.contact_us_phone') }}</label>
                            <div class="col-7">
                                <input type="text" class="form-control contact_us_phone">
                                <div class="form-text text-muted">
                                    {{ trans('lang.contact_us_phone_help') }}
                                </div>
                            </div>
                        </div>

                    </fieldset>


                    <fieldset>
                        <legend>{{ trans('lang.email_setting') }}</legend>


                        <div class="form-group row width-50">

                            <label class="col-3 control-label">{{ trans('lang.smtp') }}
                                {{ trans('lang.from_name') }}</label>

                            <div class="col-7">

                                <input type="text" class="form-control from_name">

                            </div>

                        </div>

                        <div class="form-group row width-50">

                            <label class="col-3 control-label">{{ trans('lang.smtp') }} {{ trans('lang.host') }}*</label>

                            <div class="col-7">

                                <input type="text" class="form-control host">

                            </div>

                        </div>

                        <div class="form-group row width-50">

                            <label class="col-3 control-label">{{ trans('lang.smtp') }} {{ trans('lang.port') }}*</label>

                            <div class="col-7">

                                <input type="text" class="form-control port">

                            </div>

                        </div>

                        <div class="form-group row width-50">

                            <label class="col-3 control-label">{{ trans('lang.smtp_user_name') }}*</label>

                            <div class="col-7">

                                <input type="text" class="form-control user_name">

                            </div>

                        </div>

                        <div class="form-group row width-50">

                            <label class="col-3 control-label">{{ trans('lang.smtp') }} {{ trans('lang.password') }}
                                *</label>

                            <div class="col-7">

                                <input type="password" class="form-control password">

                            </div>

                        </div>

                    </fieldset>
                    <fieldset>

                        <legend><i class="mr-3 mdi mdi-comment-alert"></i>{{ trans('lang.notification_setting') }}
                        </legend>

                        <div class="form-group row width-100">
                            <label class="col-5 control-label">{{ trans('lang.sender_id') }}</label>
                            <div class="col-7">
                                <input type="password" class="form-control" id="sender_id">
                            </div>
                            <div class="form-text pl-3 text-muted">
                                {{ trans('lang.notification_sender_id_help') }}
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-3 control-label">{{ trans('lang.upload_json_file') }}</label>
                            <input type="file" class="col-7 pb-2" onChange="handleUploadJsonFile(event)">
                            <div id="uploding_json_file"></div>
                            <div id="uploded_json_file"></div>
                            <div class="form-text pl-3 text-muted">
                                {{ trans('lang.notification_json_file_help') }}
                            </div>
                        </div>

                    </fieldset>

                    <fieldset>
                        <legend><i class="mr-3 fa fa-solid fa fa-android"></i>{{ trans('lang.version') }}</legend>

                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.app_version') }}</label>
                            <div class="col-7">
                                <input type="text" class="form-control app_version">

                            </div>
                        </div>

                        <div class="form-group row width-50">
                            <label class="col-5 control-label">{{ trans('lang.web_version') }}</label>
                            <div class="col-7">
                                <input type="text" class="form-control" id="web_version">

                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
        </div>

        <div class="form-group col-12 text-center btm-btn">
            <button type="button" class="btn btn-primary edit-form-btn"><i class="fa fa-save"></i>
                {{ trans('lang.save') }}</button>
            <a href="{{ url('/dashboard') }}" class="btn btn-default"><i
                    class="fa fa-undo"></i>{{ trans('lang.cancel') }}
            </a>
        </div>
    </div>

    <div class="modal fade" id="themeModal" tabindex="-1" role="dialog" aria-labelledby="themeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <img id="themeImage" src="" width="630">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var database = firebase.firestore();
        var ref = database.collection('settings').doc("globalSettings");
        var mapKey = database.collection('settings').doc("googleMapKey");
        var refPlaceholderImage = database.collection('settings').doc("placeHolderImage");
        var contactUs = database.collection('settings').doc("ContactUs");
        var version = database.collection('settings').doc("Version");
        var refEmailSetting = database.collection('settings').doc("emailSetting");
        var refNotificationSetting = database.collection('settings').doc("notification_setting");

        var photo = "";
        var placeholderphoto = '';
        var serviceJsonFile = '';

        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        refCurrency.get().then(async function(snapshots) {
            var currencyData = snapshots.docs[0].data();
            $(".currentCurrency").text(currencyData.symbol);
        });

        var favicon = '';

        $(document).ready(function() {

            jQuery("#data-table_processing").show();

            ref.get().then(async function(snapshots) {

                var globalSettings = snapshots.data();

                if (globalSettings == undefined) {
                    database.collection('settings').doc('globalSettings').set({});
                }

                try {
                    $(".application_name").val(globalSettings.applicationName);
                    $(".meta_title").val(globalSettings.meta_title);
                    $("#admin_color").val(globalSettings.colour_admin);
                    $("#web_color").val(globalSettings.color_website);
                    $("#colour_customer").val(globalSettings.colour_customer);
                    $("#colour_driver").val(globalSettings.colour_driver);
                    $(".min_order_amount").val(globalSettings.min_order_amount);
                    $(".minimum_deposit_amount").val(globalSettings.minimumAmountToDeposit);
                    $(".vendor_radius").val(globalSettings.vendorRadius);
                    if (globalSettings.selectedMapType) {
                        $('#selectedMapType').val(globalSettings.selectedMapType).trigger('change');
                    }
                    photo = globalSettings.appLogo;
                    $(".logo_img_thumb").append('<img class="rounded" style="width:50px" src="' +
                        photo + '" alt="image">');

                    favicon = globalSettings.favicon;
                    $(".favicon_img_thumb").append('<img class="rounded" style="width:50px" src="' +
                        favicon + '" alt="image">');
                } catch (error) {

                }

                jQuery("#data-table_processing").hide();
            });

            refPlaceholderImage.get().then(async function(snapshots) {
                var placeholderImage = snapshots.data();
                jQuery("#data-table_processing").hide();
                placeholderphoto = placeholderImage.image;
                $(".placeholder_img_thumb").append('<img class="rounded" style="width:50px" src="' +
                    placeholderphoto + '" alt="image">');
            })
            refNotificationSetting.get().then(async function(snapshots) {
                var notificationData = snapshots.data();
                if (notificationData == undefined) {
                    database.collection('settings').doc('notification_setting').set({});
                } else {
                    if (notificationData.senderId != '' && notificationData.senderId != null) {
                        $('#sender_id').val(notificationData.senderId);
                    }
                    if (notificationData.serviceJson != '' && notificationData.serviceJson != null) {
                        $('#uploded_json_file').html("<a href='" + notificationData.serviceJson +
                            "' class='btn-link pl-3' target='_blank'>See Uploaded File</a>");
                        serviceJsonFile = notificationData.serviceJson;
                    }
                }
            });
            version.get().then(async function(snapshots) {
                var version_data = snapshots.data();

                if (version_data == undefined) {
                    database.collection('settings').doc('Version').set({});
                }
                try {
                    $('.app_version').val(version_data.app_version);
                    $('#web_version').val(version_data.web_version);

                } catch (error) {

                }

            });

            contactUs.get().then(async function(snapshots) {
                var contactUsData = snapshots.data();

                if (contactUsData == undefined) {
                    database.collection('settings').doc('ContactUs').set({});
                }

                try {
                    $('.contact_us_address').val(contactUsData.Address);
                    $('.contact_us_email').val(contactUsData.Email);
                    $('.contact_us_phone').val(contactUsData.Phone);

                } catch (error) {

                }
            })

            mapKey.get().then(async function(snapshots) {
                var key = snapshots.data();

                if (key == undefined) {
                    database.collection('settings').doc('googleMapKey').set({});
                }
                try {
                    $('#map_key').val(key.key);

                } catch (error) {

                }

            });

            refEmailSetting.get().then(async function(snapshots) {
                var emailSettingData = snapshots.data();

                if (emailSettingData == undefined) {
                    database.collection('settings').doc('emailSetting').set({});
                }

                try {

                    if (emailSettingData.fromName) {
                        $('.from_name').val(emailSettingData.fromName);

                    }
                    if (emailSettingData.host) {
                        $('.host').val(emailSettingData.host);

                    }

                    if (emailSettingData.port) {
                        $('.port').val(emailSettingData.port);

                    }

                    if (emailSettingData.userName) {
                        $('.user_name').val(emailSettingData.userName);

                    }
                    if (emailSettingData.password) {
                        $('.password').val(emailSettingData.password);

                    }

                } catch (error) {

                }

                jQuery("#data-table_processing").hide();

            });
        });

        $(".edit-form-btn").click(function() {

            var admin_color = $("#admin_color").val();
            var web_color = $("#web_color").val();
            var colour_driver = $("#colour_driver").val();
            var colour_customer = $("#colour_customer").val();
            var googleApiKey = $("#map_key").val();
            var vendorRadius = $('.vendor_radius').val();
            var contact_us_address = $('.contact_us_address').val();
            var contact_us_email = $('.contact_us_email').val();
            var contact_us_phone = $('.contact_us_phone').val();
            var app_version = $('.app_version').val();
            var web_version = $('#web_version').val();
            var min_order_amount = $(".min_order_amount").val();
            var minimum_deposit_amount = $(".minimum_deposit_amount").val();
            var selectedMapType = $("#selectedMapType").val();

            var fromName = $('.from_name').val();
            var host = $('.host').val();
            var port = $('.port').val();
            var userName = $('.user_name').val();
            var password = $('.password').val();
            var senderId = $("#sender_id").val();

            if (admin_color != null) {
                setCookie('admin_panel_color', admin_color, 365);
            }

            var applicationName = $(".application_name").val();
            var meta_title = $(".meta_title").val();

            if (applicationName == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.enter_app_name_error') }}</p>");
                window.scrollTo(0, 0);
            } else if (photo == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.upload_app_logo_error') }}</p>");
                window.scrollTo(0, 0);
            } else if (placeholderphoto == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.menu_placeholder_image_error') }}</p>");
                window.scrollTo(0, 0);
            } else if (favicon == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.upload_favicon_error') }}</p>");
                window.scrollTo(0, 0);
            } else if (min_order_amount == "") {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.minimum_placed_order_amount_error') }}</p>");
                window.scrollTo(0, 0);
            } else if (minimum_deposit_amount == "") {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.minimum_deposit_amount_error') }}</p>");
                window.scrollTo(0, 0);
            } else if (vendorRadius == "") {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.enter_vendor_radius') }}</p>");
                window.scrollTo(0, 0);
            } else if (senderId == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.notification_sender_id_error') }}</p>");
                window.scrollTo(0, 0);
            } else if (serviceJsonFile == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.notification_service_json_error') }}</p>");
                window.scrollTo(0, 0);
            } else {

                jQuery("#data-table_processing").show();

                database.collection('settings').doc("globalSettings").update({
                    'colour_admin': admin_color,
                    'color_website': web_color,
                    'colour_customer': colour_customer,
                    'colour_driver': colour_driver,
                    'applicationName': applicationName,
                    'min_order_amount': min_order_amount,
                    'minimumAmountToDeposit': minimum_deposit_amount,
                    'meta_title': meta_title,
                    'appLogo': photo,
                    'favicon': favicon,
                    'vendorRadius': vendorRadius,
                    'selectedMapType': selectedMapType,

                });

                database.collection('settings').doc('placeHolderImage').update({
                    'image': placeholderphoto
                });

                database.collection('settings').doc("ContactUs").update({
                    'Address': contact_us_address,
                    'Email': contact_us_email,
                    'Phone': contact_us_phone
                });

                database.collection('settings').doc("Version").update({
                    'app_version': app_version,
                    'web_version': web_version,
                });

                database.collection('settings').doc("googleMapKey").update({
                    'key': googleApiKey
                });

                database.collection('settings').doc("emailSetting").update({
                    'fromName': fromName,
                    'host': host,
                    'port': port,
                    'userName': userName,
                    'password': password,
                    'mailMethod': "smtp",
                    'mailEncryptionType': "ssl",
                }).then(function(result) {
                    window.location.href = '{{ url('settings/app/globals') }}';
                });
                database.collection('settings').doc("notification_setting").update({
                    'senderId': senderId,
                    'serviceJson': serviceJsonFile,
                });
            }

        });


        $("#restaurant_can_upload_story").click(function() {
            if ($(this).is(':checked')) {
                $("#story_upload_time_div").show();
            } else {
                $("#story_upload_time_div").hide();
            }
        });

        $('#themeModal').on('hide.bs.modal', function(event) {
            var modal = $(this);
            modal.find('#themeImage').attr('src', '');
        });

        var storageRef = firebase.storage().ref('images');

        function handleFileSelect(evt) {

            var f = evt.target.files[0];
            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {

                    var filePayload = e.target.result;
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')

                    var timestamp = Number(new Date());
                    var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                    var uploadTask = storageRef.child(filename).put(theFile);
                    uploadTask.on('state_changed', function(snapshot) {
                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                        jQuery("#uploding_image").text("Image is uploading...");

                    }, function(error) {

                    }, function() {
                        uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                            jQuery("#uploding_image").text("Upload is completed");
                            photo = downloadURL;

                        });
                    });

                };
            })(f);
            reader.readAsDataURL(f);
        }


        function handleFileSelectplaceholder(evt) {

            var f = evt.target.files[0];
            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {

                    var filePayload = e.target.result;
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')

                    var timestamp = Number(new Date());
                    var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                    var uploadTask = storageRef.child(filename).put(theFile);
                    uploadTask.on('state_changed', function(snapshot) {
                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                        jQuery("#uploading_placeholder").text("Image is uploading...");

                    }, function(error) {

                    }, function() {
                        uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                            jQuery("#uploading_placeholder").text("Upload is completed");
                            placeholderphoto = downloadURL;

                        });
                    });

                };
            })(f);
            reader.readAsDataURL(f);
        }

        function handleFileSelectFavicon(evt) {

            var f = evt.target.files[0];
            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {

                    var filePayload = e.target.result;
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')

                    var timestamp = Number(new Date());
                    var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                    var uploadTask = storageRef.child(filename).put(theFile);
                    uploadTask.on('state_changed', function(snapshot) {
                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                        jQuery("#uploding_favicon").text("Image is uploading...");

                    }, function(error) {

                    }, function() {
                        uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                            jQuery("#uploding_favicon").text("Upload is completed");
                            favicon = downloadURL;

                        });
                    });

                };
            })(f);
            reader.readAsDataURL(f);
        }
        // function handleUploadJsonFile(evt) {

        //     var f = evt.target.files[0];
        //     var reader = new FileReader();
        //     reader.onload = (function (theFile) {
        //         return function (e) {
        //             var filePayload = e.target.result;
        //             var hash = CryptoJS.SHA256(Math.random() + CryptoJS.SHA256(filePayload));
        //             var val = f.name;
        //             var ext = val.split('.')[1];
        //             var docName = val.split('fakepath')[1];
        //             var filename = (f.name).replace(/C:\\fakepath\\/i, '')
        //             var timestamp = Number(new Date());
        //             var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
        //             var uploadTask = firebase.storage().ref('/').child(filename).put(theFile);
        //             uploadTask.on('state_changed', function (snapshot) {
        //                 var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        //                 jQuery("#uploding_json_file").text("File is uploading...");
        //             }, function (error) {
        //                 console.log(error);
        //             }, function () {
        //                 uploadTask.snapshot.ref.getDownloadURL().then(function (downloadURL) {
        //                     jQuery("#uploding_json_file").text("Upload is completed");
        //                     serviceJsonFile = downloadURL;
        //                     setTimeout(function () {
        //                         jQuery("#uploding_json_file").hide();
        //                     }, 3000);
        //                 });
        //             });
        //         };
        //     })(f);
        //     reader.readAsDataURL(f);
        // }
        function handleUploadJsonFile(evt) {
            const file = evt.target.files[0];
            const reader = new FileReader();

            reader.onload = (e) => {
                const fileData = e.target.result;
                const filename = file.name.replace(/C:\\fakepath\\/i, '');
                const timestamp = Number(new Date());
                const newFilename = `${filename.split('.')[0]}_${timestamp}.${filename.split('.')[1]}`;

                const uploadTask = firebase.storage().ref('/').child(newFilename).put(file);

                uploadTask.on('state_changed', (snapshot) => {
                    const progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                    jQuery("#uploding_json_file").text("File is uploading...");
                }, (error) => {
                    console.error('Error uploading file:', error);
                    // Add user-friendly error message here, e.g., using jQuery to display an error message on the UI.
                }, () => {
                    uploadTask.snapshot.ref.getDownloadURL().then((downloadURL) => {
                        jQuery("#uploding_json_file").text("Upload is completed");
                        serviceJsonFile = downloadURL;
                        setTimeout(() => {
                            jQuery("#uploding_json_file").hide();
                        }, 3000);
                    });
                });
            };

            reader.readAsDataURL(file);
        }
    </script>
@endsection
