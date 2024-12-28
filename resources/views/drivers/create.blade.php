@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.add_driver')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('drivers') !!}">{{trans('lang.driver_plural')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.drivers_create')}}</li>
            </ol>
        </div>
        <div>

            <div class="card-body">

                <div id="data-table_processing" class="dataTables_processing panel panel-default"
                    style="display: none;">
                    {{trans('lang.processing')}}
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
                                    <input type="text" class="form-control user_email" id="email_address">
                                    <span id="error" style="display:none;color:red;">Wrong email</span>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.password')}}</label>
                                <div class="col-7">
                                    <input type="password" class="form-control user_password">
                                    <div class="form-text text-muted">{{trans('lang.user_password_help')}}</div>
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
                                                <?php $selected = "selected"; ?>
                                                <option <?php if ($keycy == 91) {
                                                    echo $selected;
                                                } ?> code="<?php echo $valuecy->code; ?>" value="<?php echo $keycy; ?>">
                                                    +<?php echo $valuecy->phoneCode; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input placeholder="Phone" id="phone" type="number"
                                            class="form-control user_phone" name="phone" autocomplete="phone"
                                            onkeypress="if(this.value.length>9){return false;}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.user_zipcode')}}*</label>
                                <div class="col-7">
                                    <input type="number" class="form-control zipCode"
                                        onkeypress="if(this.value.length>5){return false;}">
                                    <div class="form-text text-muted">
                                        {{trans('lang.user_zipcode_help')}}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.profile_image')}}</label>
                                <div class="col-7">
                                    <input type="file" onChange="handleFileSelect(event)" class=""
                                        accept="image/png,image/jpg,image/jpeg">
                                    <div class="form-text text-muted">{{trans('lang.profile_image_help')}}</div>
                                </div>
                                <div class="placeholder_img_thumb user_image"></div>
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
                <button type="button" class="btn btn-primary save-form-btn"><i class="fa fa-save"></i> {{
                    trans('lang.save')}}
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

    var database = firebase.firestore();

    var photo = "";
    var carPictureURL = "";
    var fileName = '';
    var photocar = "";
    var carfileName = '';
    var rentalImages = [];
    var rentalImagesFileName = [];
    var user_active_deactivate = false;
    var createdAt = firebase.firestore.FieldValue.serverTimestamp();

    var email_templates = database.collection('email_templates').where('type', '==', 'new_driver_signup');

    var emailTemplatesData = null;


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

  
    $(document).ready(async function () {

        $('.driver_menu').addClass('active');
        await email_templates.get().then(async function (snapshots) {
            emailTemplatesData = snapshots.docs[0].data();

        });

        jQuery("#country_selector").select2({
            templateResult: formatState,
            templateSelection: formatState2,
            placeholder: "Select Country",
            allowClear: true
        });


    });

    $(".save-form-btn").click(async function () {

       
        var userFirstName = $(".user_name").val();
        var zipCode = $(".zipCode").val();
        var email = $(".user_email").val();
        var password = $(".user_password").val();
        var userPhone = $(".user_phone").val();
        var country_selector = $("#country_selector").val();

        user_active_deactivate = false;
        if ($("#is_active").is(':checked')) {
            user_active_deactivate = true;
        }
        var carName = $(".car_name").val();
        var carNumber = $(".car_number").val();

        var id = "<?php echo uniqid(); ?>";



        $(".error_top").html("");

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

            var checkPincode = true;

            await database.collection('users').where('role', '==', 'driver').get().then(function (snapShots) {

                if (snapShots.docs.length) {

                    snapShots.docs.forEach((listval) => {

                        var val = listval.data();

                        if (val.pinCode == zipCode) {

                            checkPincode = false;
                        }
                    });
                }
            });

            if (checkPincode) {

                firebase.auth().createUserWithEmailAndPassword(email, password)
                    .then(function (firebaseUser) {
                        id = firebaseUser.user.uid;
                        storeImageData().then(IMG => {
                            storeCarImageData().then(IMGVEH => {
                                database.collection('users').doc(id).set({
                                    'id': id,
                                    'name': userFirstName,
                                    'email': email,
                                    'phoneNumber': userPhone,
                                    'countryCode': "+" + country_selector,
                                    'profilePictureURL': IMG,
                                    'carName': carName,
                                    'carNumber': carNumber,
                                    'carPictureURL': IMGVEH,
                                    'role': 'driver',
                                    'active': user_active_deactivate,
                                    'createdAt': createdAt,
                                    'pinCode': zipCode,
                                }).then(async function (result) {

                                    var formattedDate = new Date();
                                    var month = formattedDate.getMonth() + 1;
                                    var day = formattedDate.getDate();
                                    var year = formattedDate.getFullYear();

                                    month = month < 10 ? '0' + month : month;
                                    day = day < 10 ? '0' + day : day;

                                    formattedDate = day + '-' + month + '-' + year;

                                    var message = emailTemplatesData.message;
                                    message = message.replace(/{username}/g, userFirstName);
                                    message = message.replace(/{useremail}/g, email);
                                    message = message.replace(/{password}/g, password);
                                    message = message.replace(/{date}/g, formattedDate);

                                    emailTemplatesData.message = message;
                                   
                                    var url = "{{url('send-email')}}";

                                    var sendEmailStatus = await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [email]);

                                    if (sendEmailStatus) {
                                        window.location.href = '{{ route("drivers")}}';
                                    }

                                });
                            }).catch(function (error) {

                                $(".error_top").show();
                                $(".error_top").html("");
                                $(".error_top").append("<p>" + error + "</p>");
                                window.scrollTo(0, 0);
                            })
                        }).catch(err => {
                            jQuery("#data-table_processing").hide();
                            $(".error_top").show();
                            $(".error_top").html("");
                            $(".error_top").append("<p>" + err + "</p>");
                            window.scrollTo(0, 0);
                        });

                    }).catch(function (error) {

                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>" + error + "</p>");
                        window.scrollTo(0, 0);
                    });

            } else {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.duplicate_pincode')}}</p>");
                window.scrollTo(0, 0);

            }

        }

    });

    var storageRef = firebase.storage().ref('images');
    async function storeImageData() {
        var newPhoto = '';
        try {
            if (photo != "") {
                photo = photo.replace(/^data:image\/[a-z]+;base64,/, "")
                var uploadTask = await storageRef.child(fileName).putString(photo, 'base64', { contentType: 'image/jpg' });
                var downloadURL = await uploadTask.ref.getDownloadURL();
                newPhoto = downloadURL;
                photo = downloadURL;
            }
        } catch (error) {
            console.log("ERR ===", error);
            return;
        }
        return newPhoto;
    }
    async function storeCarImageData() {
        var newCarPhoto = '';
        try {
            if (photocar != "") {
                photocar = photocar.replace(/^data:image\/[a-z]+;base64,/, "")
                var uploadTask = await storageRef.child(carfileName).putString(photocar, 'base64', { contentType: 'image/jpg' });
                var downloadURL = await uploadTask.ref.getDownloadURL();
                newCarPhoto = downloadURL;
                photocar = downloadURL;
            }
        } catch (error) {
            console.log("ERR ===", error);
            return;

        }
        return newCarPhoto;
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

    var storageRefcar = firebase.storage().ref('images');

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


    $('#email_address').on('keypress', function () {
        var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
        if (!re) {
            $('#error').show();
        } else {
            $('#error').hide();
        }
    })

</script>
@endsection