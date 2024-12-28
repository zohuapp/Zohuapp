@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">

        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.category_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('categories') !!}">{{trans('lang.category_plural')}}</a>
                </li>
                <li class="breadcrumb-item active">{{trans('lang.category_edit')}}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="cat-edite-page max-width-box">
            <div class="card  pb-4">

                <div class="card-header">
                    <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                        <li role="presentation" class="nav-item">
                            <a href="#category_information" aria-controls="description" role="tab" data-toggle="tab"
                                class="nav-link active">{{trans('lang.category_information')}}</a>
                        </li>

                    </ul>
                </div>
                <div class="card-body">

                    <div id="data-table_processing" class="dataTables_processing panel panel-default"
                        style="display: none;">{{trans('lang.processing')}}
                    </div>
                    <div class="error_top" style="display:none"></div>
                    <div class="row restaurant_payout_create" role="tabpanel">

                        <div class="restaurant_payout_create-inner tab-content">
                            <div role="tabpanel" class="tab-pane active" id="category_information">
                                <fieldset>
                                    <legend>{{trans('lang.category_edit')}}</legend>
                                    <div class="form-group row width-100">
                                        <label class="col-3 control-label">{{trans('lang.category_name')}}*</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control cat-name">
                                            <div class="form-text text-muted">{{ trans("lang.category_name_help") }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row width-100">
                                        <label class="col-3 control-label">{{trans('lang.category_image')}}*</label>
                                        <div class="col-7">
                                            <input type="file" id="category_image" onChange="handleFileSelect(event)">
                                            <div class="placeholder_img_thumb cat_image"></div>
                                            <div id="uploding_image"></div>
                                            <div class="form-text text-muted w-50">{{ trans("lang.category_image_help")
                                                }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check row width-100">
                                        <input type="checkbox" class="item_publish" id="item_publish">
                                        <label class="col-3 control-label"
                                            for="item_publish">{{trans('lang.item_publish')}}</label>
                                    </div>
                                </fieldset>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="review_attributes">

                            </div>
                        </div>

                    </div>

                </div>
                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary edit-form-btn"><i class="fa fa-save"></i>
                        {{trans('lang.save')}}
                    </button>
                    <a href="{!! route('categories') !!}" class="btn btn-default"><i
                            class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<script>

    var id = "<?php echo $id; ?>";
    var database = firebase.firestore();
    var fileName = "";
    var oldImageFile = "";
    var ref = database.collection('vendor_categories').where("id", "==", id);
    var photo = "";
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    var ref_review_attributes = database.collection('review_attributes');
    var category = '';
    var storage = firebase.storage();
    var storageRef = firebase.storage().ref('images');


    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })


    $(document).ready(function () {
        jQuery("#data-table_processing").show();
        ref.get().then(async function (snapshots) {
            category = snapshots.docs[0].data();
            $(".cat-name").val(category.title);


            if (category.photo != '' && category.photo != null) {

                photo = category.photo;
                oldImageFile = photo;
                $(".cat_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'">');
            } else {

                $(".cat_image").append('<img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image">');
            }

            if (category.publish) {
                $("#item_publish").prop('checked', true);
            }

            jQuery("#data-table_processing").hide();
        });

        ref_review_attributes.get().then(async function (snapshots) {
            var ra_html = '';
            snapshots.docs.forEach((listval) => {
                var data = listval.data();
                ra_html += '<div class="form-check width-100" >';
                var checked = $.inArray(data.id, category.review_attributes) !== -1 ? 'checked' : '';
                ra_html += '<input type="checkbox" id="review_attribute_' + data.id + '" value="' + data.id + '" ' + checked + '>';
                ra_html += '<label class="col-3 control-label" for="review_attribute_' + data.id + '">' + data.title + '</label>';
                ra_html += '</div>';
            })
            $('#review_attributes').html(ra_html);
        })


        $(".edit-form-btn").click(async function () {

            var title = $(".cat-name").val();
            var item_publish = $("#item_publish").is(":checked");

            var review_attributes = [];
            $('#review_attributes input').each(function () {
                if ($(this).is(':checked')) {
                    review_attributes.push($(this).val());
                }
            });

            if (title == '') {

                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.enter_cat_title_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (photo == '') {

                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.please_enter_image')}}</p>");
                window.scrollTo(0, 0);
            } else {

                storeImageData().then(IMG => {
                    database.collection('vendor_categories').doc(id).update({
                        'title': title,
                        'photo': IMG,
                        'publish': item_publish,
                    }).then(function (result) {
                        window.location.href = '{{ route("categories")}}';
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


    var storageRef = firebase.storage().ref('images');

    //upload image with compression

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
                $(".cat_image").empty();
                $(".cat_image").append('<img class="rounded" style="width:50px" src="' + photo + '" alt="image">');
                $("#category_image").val('');
            }
        })(f);
        reader.readAsDataURL(f);
    }



</script>
@endsection