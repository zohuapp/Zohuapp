@extends('layouts.app')

@section('content')

    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.user_profile')}}</h3>
            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{!! route('users') !!}">{{trans('lang.user_profile')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.user_edit')}}</li>
                </ol>
            </div>

        </div>

        <div class="profile-form">
            @if (Session::has('message'))
                <div class="alert alert-error error_top"><p>{{Session::get('message')}}</p></div>
            @endif

            <div class="card-body">

                <div id="data-table_processing" class="dataTables_processing panel panel-default"
                     style="display: none;">Processing...
                </div>

                <div class="column">
                    <form method="post" id="submit_form" action="{{ route('users.profile.update',$user->id) }}">
                        @csrf
                        <input type="hidden" value="{{$user->id}}" name="user_id">

                        <div class="row restaurant_payout_create">
                            <div class="restaurant_payout_create-inner">
                                <fieldset>
                                    <legend>Profile Details</legend>
                                    <div class="form-group row">
                                        <label class="col-5 control-label">{{trans('lang.user_name')}}</label>
                                        <div class="col-7">
                                            <input type="text" class=" col-6 form-control" name="name" id="name"
                                                   value="<?php echo $user->name; ?>">
                                            <div class="form-text text-muted">
                                                {{ trans("lang.user_name_help") }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-5 control-label">{{trans('lang.user_email')}}</label>
                                        <div class="col-7">
                                            <input type="text" class=" col-6 form-control"
                                                   value="<?php echo $user->email; ?>" name="email"
                                                   id="email" {{$user->id == 1 ? 'disabled' : ''}}>
                                            <div class="form-text text-muted">
                                                {{ trans("lang.user_email_help") }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-5 control-label">{{trans('lang.old_password')}}</label>
                                        <div class="col-7">
                                            <input type="password" class=" col-6 form-control" name="old_password">
                                            <div class="form-text text-muted">
                                                {{ trans("lang.old_password_help") }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-5 control-label">{{trans('lang.new_password')}}</label>
                                        <div class="col-7">
                                            <input type="password" class=" col-6 form-control" name="password">
                                            <div class="form-text text-muted">
                                                {{ trans("lang.user_password_help") }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row width-50">
                                        <label class="col-5 control-label">{{trans('lang.confirm_password')}}</label>
                                        <div class="col-7">
                                            <input type="password" class=" col-6 form-control" name="confirm_password">
                                            <div class="form-text text-muted">
                                                {{ trans("lang.confirm_password_help") }}
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>
                                    <legend>{{trans('lang.vendor_details')}}</legend>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.vendor_phone')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control vendor_phone"
                                                   onkeypress="return chkAlphabets2(event,'error2')" required>
                                            <div id="error2" class="err"></div>
                                            <div class="form-text text-muted">
                                                {{ trans("lang.vendor_phone_help") }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.vendor_address')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control vendor_address" required>
                                            <div class="form-text text-muted">
                                                {{ trans("lang.vendor_address_help") }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row width-100">
                                        <div class="col-12">
                                            <h6>{{ trans("lang.know_your_cordinates") }} <a target="_blank"
                                                                                            href="https://www.latlong.net/">{{
                                            trans("lang.latitude_and_longitude_finder") }}</a></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.vendor_latitude')}}</label>
                                        <div class="col-7">
                                            <input class="form-control vendor_latitude" type="number" min="-90" max="90"
                                                   onkeypress="return chkAlphabets3(event,'error3')">
                                            <div id="error3" class="err"></div>
                                            <div class="form-text text-muted">
                                                {{ trans("lang.vendor_latitude_help") }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.vendor_longitude')}}</label>
                                        <div class="col-7">
                                            <input class="form-control vendor_longitude" type="number" min="-180"
                                                   max="180"
                                                   onkeypress="return chkAlphabets3(event,'error4')">
                                            <div id="error4" class="err"></div>
                                            <div class="form-text text-muted">
                                                {{ trans("lang.vendor_longitude_help") }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{trans('lang.vendor_image')}}</label>
                                        <div class="col-7">
                                            <input type="file" onChange="handleFileSelect(event,'photo')"
                                                   accept="image/jpeg,image/png,image/jpg">
                                            <div id="uploding_image_vendor"></div>
                                            <div class="uploaded_image" style="display:none;"><img id="uploaded_image"
                                                                                                   src=""
                                                                                                   width="150px"
                                                                                                   height="150px;">
                                            </div>
                                            <div class="form-text text-muted">
                                                {{ trans("lang.vendor_image_help") }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row width-100">
                                        <label class="col-3 control-label ">{{trans('lang.vendor_description')}}</label>
                                        <div class="col-7">
                                    <textarea rows="7" class="vendor_description form-control"
                                              id="vendor_description"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary  save_user_btn" id="save_user_btn"><i
                                class="fa fa-save"></i> {{ trans('lang.save')}}</button>
                    <a href="{!! route('dashboard') !!}" class="btn btn-default"><i
                                class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var database = firebase.firestore();
        var storageRef = firebase.storage().ref('images');
        var vendorPhoto = '';
        var vendorPhotoFileName = '';
        var photo = '';
        var placeholderImage = '';
        var placeholder = database.collection('settings').doc('placeHolderImage');
        var ref = database.collection('vendors');


        placeholder.get().then(async function (snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
        });

        $('#save_user_btn').click(function () {

            var name = $("#name").val();
            var address = $(".vendor_address").val();
            var latitude = parseFloat($(".vendor_latitude").val());
            var longitude = parseFloat($(".vendor_longitude").val());
            var description = $(".vendor_description").val();
            var phonenumber = $(".vendor_phone").val();

            if (phonenumber == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.vendor_phone_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (address == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.vendor_address_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (isNaN(latitude)) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.vendor_lattitude_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (latitude < -90 || latitude > 90) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.vendor_lattitude_limit_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (isNaN(longitude)) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.vendor_longitude_error')}}</p>");
                window.scrollTo(0, 0);

            } else if (longitude < -180 || longitude > 180) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.vendor_longitude_limit_error')}}</p>");
                window.scrollTo(0, 0);

            } else if (description == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.vendor_description_error')}}</p>");
                window.scrollTo(0, 0);

            } else {

                if ('<?php echo $user->id ?>' == 1) {

                    database.collection('users').where("role", "==", "vendor").get().then(async function (snapshots) {

                        var user = snapshots.docs[0].data();

                        await storeImageData().then(async (IMG) => {

                            database.collection('users').doc(user.id).update({
                                'name': name,
                            }).then(function (result) {

                                var coordinates = new firebase.firestore.GeoPoint(latitude, longitude);

                                geoFirestore.collection('vendors').doc(user.vendorID).update({
                                    'description': description,
                                    'latitude': latitude,
                                    'longitude': longitude,
                                    'location': address,
                                    'photo': IMG.vendorImage,
                                    'phonenumber': phonenumber,
                                    'coordinates': coordinates,
                                    'id': user.vendorID,
                                    'author': user.id,
                                    'authorName': name
                                }).then(async function (result) {

                                    $('#submit_form').submit();

                                });
                            });
                        }).catch(err => {
                            $(".error_top").show();
                            $(".error_top").html("");
                            $(".error_top").append("<p>" + err + "</p>");
                            window.scrollTo(0, 0);
                        });
                    });
                } else {
                    $('#submit_form').submit();
                }
            }

        });

        ref.get().then(async function (snapshots) {
            var vendor = snapshots.docs[0].data();

            $(".vendor_address").val(vendor.location);
            $(".vendor_latitude").val(vendor.latitude);
            $(".vendor_longitude").val(vendor.longitude);
            $(".vendor_description").val(vendor.description);

            if (vendor.photo) {
                if (vendor.photo != '') {
                    vendorPhoto = vendor.photo;
                    vendorPhotoOldImageFile = vendor.photo;
                    if (vendor.photo) {
                        photo = vendor.photo;
                    } else {
                        photo = placeholderImage;
                    }
                    $(".uploaded_image").html('<img id="uploaded_image" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" width="150px" height="150px;">');
                    $(".uploaded_image").show();
                } else {
                    $(".uploaded_image").html('<img id="uploaded_image" src="' + placeholderImage + '" width="150px" height="150px;">');
                    $(".uploaded_image").show();
                }
            } else {
                $("#uploaded_image").attr('src', placeholderImage);
                $(".uploaded_image").show();
            }

            if (vendor.hasOwnProperty('phonenumber')) {
                $(".vendor_phone").val(vendor.phonenumber);
            }

        });

        async function storeImageData() {
            var newPhoto = [];
            newPhoto['vendorImage'] = '';
            try {

                if (vendorPhoto != '') {
                    vendorPhoto = vendorPhoto.replace(/^data:image\/[a-z]+;base64,/, "")
                    var uploadTask = await storageRef.child(vendorPhotoFileName).putString(vendorPhoto, 'base64', {contentType: 'image/jpg'});
                    var downloadURL = await uploadTask.ref.getDownloadURL();
                    newPhoto['vendorImage'] = downloadURL;
                }
            } catch (error) {
                console.log("ERR ===", error);
            }
            return newPhoto;
        }

        function handleFileSelect(evt, type) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            reader.onload = (function (theFile) {
                return function (e) {

                    var filePayload = e.target.result;
                    var hash = CryptoJS.SHA256(Math.random() + CryptoJS.SHA256(filePayload));
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '');

                    var timestamp = Number(new Date());
                    var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                    photo = filePayload;
                    if (photo) {
                        if (type == 'photo') {
                            vendorPhoto = filePayload;
                            vendorPhotoFileName = filename;
                            $("#uploaded_image").attr('src', photo);
                            $(".uploaded_image").show();
                        }
                    }
                };
            })(f);
            reader.readAsDataURL(f);
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
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                document.getElementById(msg).innerHTML = "Accept only Number and Dot(.)";
                return false;
            } else {
                document.getElementById(msg).innerHTML = "";
                return true;
            }
        }

    </script>
@endsection