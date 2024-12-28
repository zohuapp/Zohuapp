@extends('layouts.app')

@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.user_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('users') !!}">{{trans('lang.user_plural')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.user_edit')}}</li>
            </ol>
        </div>

    </div>
    <div>
        <div class="card-body">

            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
                {{trans('lang.processing')}}
            </div>

            <div class="row daes-top-sec mb-3">

                <div class="col-lg-12 col-md-12">
                    <a href="{{route('orders','userId='.$id)}}">

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
                            <legend>{{trans('lang.user_edit')}}</legend>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.user_name')}}*</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_name">

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
                                            onkeypress="if(this.value.length > 9){return false;}">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.email')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control user_email" id="email_address">
                                    <span id="error" style="display:none;color:red;">Wrong email</span>
                                </div>
                            </div>


                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.restaurant_image')}}</label>
                                <input type="file" onChange="handleFileSelect(event)" class="col-7"
                                    accept="image/png,image/jpg,image/jpeg">
                                <div class="placeholder_img_thumb user_image"></div>
                                <div id="uploding_image"></div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{trans('user')}} {{trans('lang.active_deactive')}}</legend>
                            <div class="form-group row">

                                <div class="form-group row width-50">
                                    <div class="form-check width-100">
                                        <input type="checkbox" class="user_active" id="user_active">
                                        <label class="col-3 control-label"
                                            for="user_active">{{trans('lang.active')}}</label>
                                    </div>
                                </div>

                            </div>
                        </fieldset>


                    </div>
                </div>
    </div>
    <div class="form-group col-12 text-center btm-btn">
        <button type="button" class="btn btn-primary  edit-form-btn "><i class="fa fa-save"></i> {{
            trans('lang.save')}}
        </button>
        <a href="{!! route('users') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
            trans('lang.cancel')}}</a>
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
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;

    var photo = "";
    var oldImageFile = "";
    var storage = firebase.storage();
    var storageRef = firebase.storage().ref('profileImage');
    var fileName = "";
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');

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


    $(document).ready(function () {

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

            var email = '';
            if (user.email != '' && user.email != null) {
                email = shortEmail(user.email)
            }
            $(".user_email").val(email);
            var phoneNumber = '';
            if (user.phoneNumber != '' && user.phoneNumber != null) {
                phoneNumber = EditPhoneNumber(user.phoneNumber)
            }
            $(".user_phone").val(phoneNumber);

            if (user.hasOwnProperty('countryCode')) {
                $('#country_selector').val(user.countryCode.split("+")[1]).trigger('change');
            }
            photo = "";

            if (user.hasOwnProperty('image') && user.image != '' && user.image != null) {

                photo = user.image;
                oldImageFile = photo;
                $(".user_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'">');

            } else {

                $(".user_image").append('<img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image">');
            }

            if (user.active) {
                $(".user_active").prop('checked', true);
            }


            var orderRef = database.collection('orders').where("userID", "==", id);
            orderRef.get().then(async function (snapshotsorder) {

                var orders = snapshotsorder.size;

                $("#total_orders").text(orders);

            });

            jQuery("#data-table_processing").hide();
        });
    });

    $(".edit-form-btn").click(function () {
        
        var user_name = $(".user_name").val();
        var email = $(".user_email").val();
        var userPhone = $(".user_phone").val();
        var active = $(".user_active").is(":checked");
        var password = $(".user_password").val();
        var country_selector = $("#country_selector").val();

        if (user_name == '') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.username_error')}}</p>");
            window.scrollTo(0, 0);
        }


        else if (country_selector == '') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.select_country')}}</p>");
            window.scrollTo(0, 0);
        } else if (userPhone == '') {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.user_phone_error')}}</p>");
            window.scrollTo(0, 0);
        } else if (userPhone.length > 10) {
            $(".error_top").show();
            $(".error_top").html("");
            $(".error_top").append("<p>{{trans('lang.valid_owners_phone')}}</p>");
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

        } else {

            storeImageData().then(IMG => {


                database.collection('users').doc(id).update
                    ({
                        'name': user_name,
                        'email': email,
                        'phoneNumber': userPhone,
                        'countryCode': "+" + country_selector,
                        'isActive': active,
                        'profilePictureURL': IMG,
                        'role': 'customer',
                        'active': active,


                    }).then(function (result) {

                        window.location.href = '{{ route("users")}}';

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
                photo = downloadURL;
                newPhoto = downloadURL;

            } else {
                newPhoto = photo;
            }
        } catch (error) {
            console.log("ERR ===", error);
        }
        return newPhoto;
    }

    function chkAlphabets(event, msg) {
        if (!(event.which >= 97 && event.which <= 122) && !(event.which >= 65 && event.which <= 90)) {
            document.getElementById(msg).innerHTML = "Accept only Alphabets";
            return false;
        } else {
            document.getElementById(msg).innerHTML = "";
            return true;
        }
    }

    function chkAlphabets2(event, msg) {
        if (!(event.which >= 48 && event.which <= 57)
        ) {
            document.getElementById(msg).innerHTML = "Accept only Number";
            return false;
        } else {
            document.getElementById(msg).innerHTML = "";
            return true;
        }
    }

    function chkAlphabets3(event, msg) {
        if (!((event.which >= 48 && event.which <= 57) || (event.which >= 97 && event.which <= 122))) {
            document.getElementById(msg).innerHTML = "Special characters not accepted ";
            return false;
        } else {
            document.getElementById(msg).innerHTML = "";
            return true;
        }
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