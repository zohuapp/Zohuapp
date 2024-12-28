@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.driver_plural')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('drivers') !!}">{{trans('lang.driver_plural')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.driver_edit')}}</li>
            </ol>
        </div>
        <div>

            <div class="card-body">

                <div id="data-table_processing" class="dataTables_processing panel panel-default"
                    style="display: none;">
                    {{trans('lang.processing')}}
                </div>

                <div class="row daes-top-sec mb-3">

                    <div class="col-lg-12 col-md-12">

                        <a href="{{route('orders')}}?driverId={{$id}}">

                            <div class="card">

                                <div class="flex-row">

                                    <div class="p-10 bg-info col-md-12 text-center">

                                        <h3 class="text-white box m-b-0"><i class="mdi mdi-cart"></i></h3>
                                    </div>

                                    <div class="align-self-center pt-3 col-md-12 text-center">

                                        <h3 class="m-b-0 text-info" id="total_orders">0</h3>

                                        <h5 class="text-muted m-b-0">{{trans('lang.dashboard_total_orders')}}</h5>

                                    </div>

                                </div>

                            </div>
                        </a>
                    </div>

                </div>

                <div class="error_top"></div>
                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                        <fieldset>
                            <legend>{{trans('lang.driver_details')}}</legend>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.user_name')}}*</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_name"
                                        onkeypress="return /[0-9a-zA-Z ]/i.test(event.key)">
                                    <div class="form-text text-muted">{{trans('lang.user_name_help')}}</div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.email')}}*</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_email" disabled id="email_address">
                                    <span id="error" style="display:none;color:red;">Wrong email</span>
                                </div>
                            </div>

                            <?php
                            $countries = file_get_contents(public_path('countriesdata.json'));
                            $countries = json_decode($countries);
                            $countries = (array) $countries;
                            $newcountries = array();
                            $newcountriesjs = array();
                            foreach ($countries as $keycountry => $valuecountry) {
                                $newcountries[$valuecountry->phoneCode] = $valuecountry;
                                $newcountriesjs[$valuecountry->phoneCode] = $valuecountry->code;
                            }
                            ?>

                            <div class="form-group row width-50" id="phone-box">
                                <label class="col-3 control-label">{{trans('lang.user_phone')}}*</label>

                                <div class="col-7">
                                    <div class="col-phone">
                                        <select name="country" id="country_selector">
                                            <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                                <?php $selected = ""; ?>
                                                <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>"
                                                    value="<?php echo $keycy; ?>">
                                                    +<?php echo $valuecy->phoneCode; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input placeholder="Phone" id="phone" type="text"
                                            class="form-control user_phone" name="phone" autocomplete="phone"
                                            onkeypress="if(this.value.length>9){return false;}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.user_zipcode')}}*</label>
                                <div class="col-7">
                                    <input type="text" class="form-control zipCode"
                                        onkeypress="if(this.value.length>5){return false;}">
                                    <div class="form-text text-muted">
                                        {{trans('lang.user_zipcode_help')}}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.profile_image')}}</label>
                                <div class="col-7">
                                    <input type="file" onChange="handleFileSelect(event)" class=""
                                        accept="image/png,image/jpg,image/jpeg">
                                    <div class="form-text text-muted">{{trans('lang.profile_image_help')}}</div>
                                </div>
                                <div class="placeholder_img_thumb user_image">
                                </div>

                                <div id="uploding_image"></div>
                            </div>

                        </fieldset>

                        <fieldset>
                            <legend>{{trans('driver')}} {{trans('lang.active_deactive')}}</legend>
                            <div class="form-group row">

                                <div class="form-group row width-50">
                                    <div class="form-check width-100">
                                        <input type="checkbox" id="is_active">
                                        <label class="col-3 control-label"
                                            for="is_active">{{trans('lang.active')}}</label>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" id="reset_password">
                                    <label class="col-3 control-label"
                                        for="reset_password">{{trans('lang.reset_password')}}</label>
                                    <div class="form-text text-muted w-100">
                                        {{ trans("lang.note_reset_password_email") }}
                                    </div>
                                </div>
                                <div class="form-button" style="margin-top: 16px;margin-left: 20px;">
                                    <button type="button" class="btn btn-primary"
                                        id="send_mail">{{trans('lang.send_mail')}}</button>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{trans('lang.car_details')}}</legend>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.car_number')}}*</label>
                                <div class="col-7">
                                    <input type="text" class="form-control car_number"
                                        onkeypress="return /[0-9a-zA-Z ]/i.test(event.key)">
                                    <div class="form-text text-muted">{{trans('lang.car_number_help')}}</div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.car_name')}}*</label>
                                <div class="col-7">
                                    <input type="text" class="form-control car_name"
                                        onkeypress="return /[0-9a-zA-Z ]/i.test(event.key)">
                                    <div class="form-text text-muted">{{trans('lang.car_name_help')}}</div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.car_image')}}</label>
                                <div class="col-7">
                                    <input type="file" onChange="handleFileSelectcar(event)" class=""
                                        accept="image/png,image/jpg,image/jpeg">
                                    <div class="form-text text-muted">{{trans('lang.car_image_help')}}</div>
                                </div>
                                <div class="placeholder_img_thumb car_image">
                                </div>
                                <div id="uploding_image_car"></div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="form-group col-12 text-center btm-btn">
                <button type="button" class="btn btn-primary edit-form-btn"><i class="fa fa-save"></i> {{
                    trans('lang.update')}}
                </button>
                <a href="{!! route('drivers') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
                    trans('lang.cancel')}}</a>
            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

    var email_regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    var id = "<?php echo $id; ?>";
    var database = firebase.firestore();
    var ref = database.collection('users').where("id", "==", id);
    var photo = "";
    var photocar = '';
    var carPictureFile = "";
    var carfileName = '';
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    var user_active_deactivate = false;
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var fileName = "";
    var oldImageFile = "";
    var storage = firebase.storage();
    var storageRef = firebase.storage().ref('images');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })

    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var append_list = '';

    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;

        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });


    var newcountriesjs = '<?php echo json_encode($newcountriesjs); ?>';
    var newcountriesjs = JSON.parse(newcountriesjs);

    function formatState(state) {

        if (!state.id) {
            return state.text;
        }
        var baseUrl = "<?php echo URL::to('/'); ?>/flags/120/";
        var $state = $(
            '<span><img src="' + baseUrl + '/' + newcountriesjs[state.element.value].toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
        );
        return $state;
    }

    function formatState2(state) {
        if (!state.id) {
            return state.text;
        }

        var baseUrl = "<?php echo URL::to('/'); ?>/flags/120/"
        var $state = $(
            '<span><img class="img-flag" /> <span></span></span>'
        );
        $state.find("span").text(state.text);
        $state.find("img").attr("src", baseUrl + "/" + newcountriesjs[state.element.value].toLowerCase() + ".png");

        return $state;
    }

    function handleFileSelect(evt) {
        var f = evt.target.files[0];
        var reader = new FileReader();

        reader.onload = (function (theFile) {
            return function (e) {

                var filePayload = e.target.result;
                var val = f.name;
                var ext = val.split('.')[1];
                var docName = val.split('fakepath')[1];
                var filename = (f.name).replace(/C:\\fakepath\\/i, '')

                var timestamp = Number(new Date());
                var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;

                photo = filePayload;
                fileName = filename;
                $(".user_image").empty();
                $(".user_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image">');
            };
        })(f);
        reader.readAsDataURL(f);
    }



    function handleFileSelectcar(evt) {
        var f = evt.target.files[0];
        var reader = new FileReader();

        reader.onload = (function (theFile) {
            return function (e) {

                var filePayload = e.target.result;
                var val = f.name;
                var ext = val.split('.')[1];
                var docName = val.split('fakepath')[1];
                var filename = (f.name).replace(/C:\\fakepath\\/i, '')

                var timestamp = Number(new Date());
                var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                photocar = filePayload;
                carfileName = filename;

                $(".car_image").empty();
                $(".car_image").append('<img class="rounded" style="width:50px" src="' + photocar + '" alt="image">');

            };
        })(f);
        reader.readAsDataURL(f);
    }
    async function storeImageData() {
        var newPhoto = '';
        try {
            if (oldImageFile != "" && photo != oldImageFile) {
                var OldImageUrlRef = await storage.refFromURL(oldImageFile);
                imageBucket = OldImageUrlRef.bucket;
                var envBucket = "<?php echo env('FIREBASE_STORAGE_BUCKET'); ?>";
                if (imageBucket == envBucket) {
                    await OldImageUrlRef.delete().then(() => {
                        console.log("Old file deleted!")
                    }).catch((error) => {
                        console.log("ERR File delete ===", error);
                    });
                } else {
                    console.log('Bucket not matched');
                }
            }
            if (photo != oldImageFile) {
                photo = photo.replace(/^data:image\/[a-z]+;base64,/, "")
                var uploadTask = await storageRef.child(fileName).putString(photo, 'base64', { contentType: 'image/jpg' });
                var downloadURL = await uploadTask.ref.getDownloadURL();
                newPhoto = downloadURL;
                photo = downloadURL;

            } else {
                newPhoto = photo;
            }
        } catch (error) {
            console.log("ERR ===", error);
        }
        return newPhoto;
    }
    async function storeCarImageData() {
        var newCarPhoto = '';
        try {
            if (carPictureFile != "" && photocar != carPictureFile) {
                var usercarOldImageUrlRef = await storage.refFromURL(carPictureFile);
                imageBucket = usercarOldImageUrlRef.bucket;
                var envBucket = "<?php echo env('FIREBASE_STORAGE_BUCKET'); ?>";
                if (imageBucket == envBucket) {
                    await usercarOldImageUrlRef.delete().then(() => {
                        console.log("Old file deleted!")
                    }).catch((error) => {
                        console.log("ERR File delete ===", error);
                    });
                } else {
                    console.log('Bucket not matched');
                }
            }
            if (photocar != carPictureFile) {
                photocar = photocar.replace(/^data:image\/[a-z]+;base64,/, "")
                var uploadTask = await storageRef.child(carfileName).putString(photocar, 'base64', {
                    contentType: 'image/jpg'
                });
                var downloadURL = await uploadTask.ref.getDownloadURL();
                newCarPhoto = downloadURL;
                photocar = downloadURL;

            } else {
                newCarPhoto = photocar;
            }
        } catch (error) {
            console.log("ERR ===", error);
            return;

        }
        return newCarPhoto;
    }
    $("#send_mail").click(function() {
        if ($("#reset_password").is(":checked")) {
            var email = $(".user_email").val();
            firebase.auth().sendPasswordResetEmail(email)
                .then((res) => {
                    alert('{{trans("lang.mail_sent")}}');
                })
                .catch((error) => {
                    console.log('Error password reset: ', error);
                });
        } else {
            alert('{{trans("lang.mail_send_error")}}');
        }
    });

    $(document).ready(function () {
        $('.driver_menu').addClass('active');

        jQuery("#country_selector").select2({
            templateResult: formatState,
            templateSelection: formatState2,
            placeholder: "Select Country",
            allowClear: true
        });

        jQuery("#data-table_processing").show();
        ref.get().then(async function (snapshots) {

            var user = snapshots.docs[0].data();

            $(".user_name").val(user.name);

            $(".user_email").val(shortEmail(user.email));
            $(".user_phone").val(EditPhoneNumber(user.phoneNumber));

            if (user.hasOwnProperty('countryCode')) {
                $('#country_selector').val(user.countryCode.split("+")[1]).trigger('change');
            }
            $(".zipCode").val(user.pinCode);
            $(".car_name").val(user.carName);
            $(".car_number").val(user.carNumber);

            photo = user.profilePictureURL;
            carPictureURL = user.carPictureURL;

            if (user.active) {
                $("#is_active").prop("checked", true);
                user_active_deactivate = true;
            }


            if (carPictureURL != '' && carPictureURL != null) {
                carPictureFile = carPictureURL;
                photocar=carPictureURL;
                $(".car_image").append('<img class="rounded" style="width:50px" src="' + carPictureURL + '" alt="image">');
            } else {

                $(".car_image").append('<img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image">');
            }
            if (photo != '' && photo != null) {
                oldImageFile = photo;
                $(".user_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image">');

            } else {

                $(".user_image").append('<img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image">');
            }

            var orderRef = database.collection('orders').where("driverID", "==", id);
            orderRef.get().then(async function (snapshotsorder) {
                var orders = snapshotsorder.size;
                $("#total_orders").text(orders);
            });

            jQuery("#data-table_processing").hide();
        });

        $(".edit-form-btn").click(function () {

            var userFirstName = $(".user_name").val();
            var email = $(".user_email").val();
            var userPhone = $(".user_phone").val();
            var country_selector = $("#country_selector").val();

            var zipCode = $(".zipCode").val();
            var active = $(".user_active").is(":checked");
            var user_active_deactivate = false;
            if ($("#is_active").is(':checked')) {
                user_active_deactivate = true;
            }
            var carName = $(".car_name").val();
            var carNumber = $(".car_number").val();


            if (userFirstName == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.user_name_help')}}</p>");
                window.scrollTo(0, 0);

            } else if (email == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.enter_email_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (email_regex.test(email) == false) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.valid_email_error')}}</p>");
                window.scrollTo(0, 0);

            } else if (country_selector == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.select_country')}}</p>");
                window.scrollTo(0, 0);
            } else if (userPhone == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.user_phone_help')}}</p>");
                window.scrollTo(0, 0);
            } else if (userPhone.length > 10) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.valid_owners_phone')}}</p>");
                window.scrollTo(0, 0);
            } else if (zipCode == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.enter_zipcode_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (zipCode.length > 6) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.valid_zipcode_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (carName == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.car_name_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (carNumber == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.car_number_error')}}</p>");
                window.scrollTo(0, 0);
            } else {

                storeImageData().then(IMG => {
                    storeCarImageData().then(vehIMG => {
                        database.collection('users').doc(id).update({
                            'name': userFirstName,
                            'email': email,
                            'phoneNumber': userPhone,
                            'countryCode': "+" + country_selector,
                            'profilePictureURL': IMG,
                            'carName': carName,
                            'carNumber': carNumber,
                            'carPictureURL': vehIMG,
                            'role': 'driver',
                            'active': user_active_deactivate,
                            'pinCode': zipCode,
                        }).then(function (result) {

                            window.location.href = '{{ route("drivers")}}';

                        });
                    }).catch(err => {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>" + err + "</p>");
                        window.scrollTo(0, 0);
                    });
                }).catch(err => {
                    jQuery("#data-table_processing").hide();
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>" + err + "</p>");
                    window.scrollTo(0, 0);
                });
            }

        });



        $('#email_address').on('keypress', function () {
            var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
            if (!re) {
                $('#error').show();
            } else {
                $('#error').hide();
            }
        })
    })

</script>
@endsection