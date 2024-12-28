@extends('layouts.app')

@section('content')

<div class="page-wrapper">
    <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
        {{trans('lang.processing')}}
    </div>
    <div class="row page-titles">


        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor">{{trans('lang.brand_edit')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>

                <li class="breadcrumb-item"><a href="{!! route('brands') !!}">{{trans('lang.brand')}}</a></li>

                <li class="breadcrumb-item active">{{trans('lang.brand_edit')}}</li>

            </ol>

        </div>

    </div>

    <div class="card-body">


        <div class="error_top"></div>

        <div class="row restaurant_payout_create">

            <div class="restaurant_payout_create-inner">

                <fieldset>

                    <legend>{{trans('lang.brand')}}</legend>


                    <div class="form-group row width-100">

                        <label class="col-3 control-label">{{trans('lang.title')}}*</label>

                        <div class="col-7">

                            <input type="text" class="form-control title">

                        </div>

                    </div>

                    <div class="form-group row width-100">

                        <div class="form-check width-100">

                            <input type="checkbox" id="is_publish">

                            <label class="col-3 control-label" for="is_publish">{{trans('lang.is_publish')}}</label>

                        </div>

                    </div>

                    <div class="form-group row width-50">

                        <label class="col-3 control-label">{{trans('lang.photo')}}</label>

                        <input type="file" id="brand_image" class="col-7" accept="image/png,image/jpg,image/jpeg"
                            onChange="handleFileSelect(event)">
                        <div class="placeholder_img_thumb user_image"></div>
                        <div id="uploding_image" style="padding-left: 15px;"></div>


                    </div>

                </fieldset>

            </div>
        </div>

    </div>

    <div class="form-group col-12 text-center">

        <button type="button" class="btn btn-primary  edit-form-btn"><i class="fa fa-save"></i>
            {{trans('lang.save')}}
        </button>

        <a href="{!! route('brands') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{trans('lang.cancel')}}
        </a>

    </div>

</div>


@endsection

@section('scripts')

<script type="text/javascript">

    var database = firebase.firestore();

    var photo = "";
    var fileName = "";
    var oldImageFile = "";
    var storage = firebase.storage();
    var storageRef = firebase.storage().ref('images');
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })

    var id = "<?php echo $id; ?>";

    var ref = database.collection('brands').where("id", "==", id);

    var sections_list = [];

    $(document).ready(function () {

        jQuery("#data-table_processing").show();

        ref.get().then(async function (snapshots) {

            var menuItems = snapshots.docs[0].data();
            $(".title").val(menuItems.title);

            if (menuItems.is_publish) {
                $("#is_publish").prop("checked", true);
            }

            photo = menuItems.photo;
            if (photo != '') {
                oldImageFile = photo;
                $(".user_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'">');

            } else {

                $(".user_image").append('<img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image">');
            }

            jQuery("#data-table_processing").hide();

        });
    });

    function handleFileSelect(evt) {


        var f = evt.target.files[0];

        var reader = new FileReader();

        reader.onload = (function (theFile) {

            return function (e) {
                var filePayload = e.target.result;
                var hash = CryptoJS.SHA256(Math.random() + CryptoJS.SHA256(filePayload));
                var val = f.name;
                var ext = val.split('.')[1];
                var docName = val.split('fakepath')[1];
                var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                var timestamp = Number(new Date());
                var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                fileName = filename;
                photo = filePayload;
                $(".user_image").empty();
                $(".user_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'">');
                $("#brand_image").val('');
            };

        })(f);

        reader.readAsDataURL(f);

    }


    $(".edit-form-btn").click(function () {

        var title = $(".title").val();
        var is_publish = false;

        if ($("#is_publish").is(':checked')) {

            is_publish = true;

        }


        if (title == '') {

            $(".error_top").show();

            $(".error_top").html("");

            $(".error_top").append("<p>{{trans('lang.title_error')}}</p>");

            window.scrollTo(0, 0);


        } else {

            jQuery("#data-table_processing").show();
            storeImageData().then(IMG => {

                database.collection('brands').doc(id).update({
                    'title': title,
                    'photo': IMG,
                    'id': id,
                    'is_publish': is_publish,
                }).then(function (result) {
                    window.location.href = '{{ route("brands")}}';

                }).catch(function (error) {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>" + error + "</p>");

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


</script>

@endsection