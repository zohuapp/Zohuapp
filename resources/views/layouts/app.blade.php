<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" <?php if (str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true') { ?> dir="rtl" <?php } ?>>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->
    <title id="app_name"><?php echo @$_COOKIE['meta_title']; ?></title>
    <link rel="icon" id="favicon" type="image/x-icon" href="<?php echo str_replace('images/', 'images%2F', @$_COOKIE['favicon']); ?>">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <?php if (str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true') { ?>
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <?php } ?>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <?php if (str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true') { ?>
    <link href="{{ asset('css/style_rtl.css') }}" rel="stylesheet">
    <?php } ?>
    <link href="{{ asset('css/icons/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('css/colors/blue.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">


    <?php if (isset($_COOKIE['admin_panel_color'])) { ?>

    <style type="text/css">
        .topbar {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .left-sidebar {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav ul li a {
            border-bottom:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav ul li a:hover i {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .vendor_payout_create-inner fieldset legend {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .restaurant_payout_create-inner fieldset legend {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        a {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        a:hover,
        a:focus {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        a.link:hover,
        a.link:focus {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        html body blockquote {
            border-left: 5px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .text-warning {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?> !important;
        }

        .text-info {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?> !important;
        }

        .sidebar-nav ul li a:hover {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .btn-primary {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            border: 1px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav>ul>li.active>a {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            border-left: 3px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav>ul>li.active>a i {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .bg-info {
            background-color:
                <?php echo $_COOKIE['admin_panel_color']; ?> !important;
        }

        .bellow-text ul li>span {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>
        }

        .table tr td.redirecttopage {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>
        }

        .table-responsive {
            scrollbar-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>
        }

        ul.rating {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .nav-tabs.card-header-tabs .nav-link.active,
        .nav-tabs.card-header-tabs .nav-link:hover {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            border-color:
                <?php echo $_COOKIE['admin_panel_color']; ?> <?php echo $_COOKIE['admin_panel_color']; ?> #fff;
        }

        .btn-warning,
        .btn-warning.disabled {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            border: 1px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
            box-shadow: none;
        }

        .payment-top-tab .nav-tabs.card-header-tabs .nav-link.active,
        .payment-top-tab .nav-tabs.card-header-tabs .nav-link:hover {
            border-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .nav-tabs.card-header-tabs .nav-link span.badge-success {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .nav-tabs.card-header-tabs .nav-link.active span.badge-success,
        .nav-tabs.card-header-tabs .nav-link:hover span.badge-success,
        .sidebar-nav ul li a.active,
        .sidebar-nav ul li a.active:hover,
        .sidebar-nav ul li.active a.has-arrow:hover,
        .topbar ul.dropdown-user li a:hover {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav ul li a.has-arrow:hover::after,
        .sidebar-nav .active>.has-arrow::after,
        .sidebar-nav li>.has-arrow.active::after,
        .sidebar-nav .has-arrow[aria-expanded="true"]::after,
        .sidebar-nav ul li a:hover {
            border-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        [type="checkbox"]:checked+label::before {
            border-right: 2px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
            border-bottom: 2px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .btn-primary:hover,
        .btn-primary.disabled:hover {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            border: 1px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .btn-primary.active,
        .btn-primary:active,
        .btn-primary:focus,
        .btn-primary.disabled.active,
        .btn-primary.disabled:active,
        .btn-primary.disabled:focus,
        .btn-primary.active.focus,
        .btn-primary.active:focus,
        .btn-primary.active:hover,
        .btn-primary.focus:active,
        .btn-primary:active:focus,
        .btn-primary:active:hover,
        .open>.dropdown-toggle.btn-primary.focus,
        .open>.dropdown-toggle.btn-primary:focus,
        .open>.dropdown-toggle.btn-primary:hover,
        .btn-primary.focus,
        .btn-primary:focus,
        .btn-primary:not(:disabled):not(.disabled).active:focus,
        .btn-primary:not(:disabled):not(.disabled):active:focus,
        .show>.btn-primary.dropdown-toggle:focus,
        .btn-warning:hover,
        .btn-warning:hover,
        .btn-warning.disabled:hover,
        .btn-warning.active.focus,
        .btn-warning.active:focus,
        .btn-warning.active:hover,
        .btn-warning.focus:active,
        .btn-warning:active:focus,
        .btn-warning:active:hover,
        .open>.dropdown-toggle.btn-warning.focus,
        .open>.dropdown-toggle.btn-warning:focus,
        .open>.dropdown-toggle.btn-warning:hover,
        .btn-warning.focus,
        .btn-warning:focus {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            border-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            box-shadow: 0 0 0 0.2rem<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .language-options select option,
        .pagination>li>a.page-link:hover {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .mini-sidebar .sidebar-nav #sidebarnav>li:hover a i,
        .mini-sidebar .sidebar-nav ul li a,
        .sidebar-nav ul li a.active i,
        .sidebar-nav ul li a.active:hover i,
        .sidebar-nav ul li.active a:hover i {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .cat-slider .cat-item a.cat-link:hover,
        .cat-slider .cat-item.section-selected a.cat-link {
            border-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .cat-slider .cat-item a.cat-link {
            border-bottom-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .cat-slider .cat-item.section-selected a.cat-link:after {
            border-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .cat-slider {
            border-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .business-analytics .card-box i {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .order-status .data i,
        .order-status span.count {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .print-btn button {
            border-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        [type="radio"]:checked+label::after,
        [type="radio"].with-gap:checked+label::after {
            background-color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        [type="radio"]:checked+label::after,
        [type="radio"].with-gap:checked+label::before,
        [type="radio"].with-gap:checked+label::after {
            border: 2px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .topbar ul.dropdown-user li .dw-user-box a.mark-read,
        .topbar .notification_data .dropdown-header a.mark-read {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            border: 1px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .topbar ul.dropdown-user li .dw-user-box a.mark-read:hover,
        .topbar .notification_data .dropdown-header a.mark-read:hover {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
            background: #fff;
        }

        .topbar .notification_data .dropdown-header h6 span.text-primary {
            color:
                <?php echo $_COOKIE['admin_panel_color']; ?> !important;
        }

        .table-responsive {
            scrollbar-color:
                <?php echo $_COOKIE['admin_panel_color']; ?> transparent;
        }

        .mini-sidebar .top-navbar .navbar-header {
            background:
                <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        @media screen and (max-width: 767px) {

            .mini-sidebar .sidebar-nav ul li a:hover,
            .sidebar-nav>ul>li.active>a {
                color:
                    <?php echo $_COOKIE['admin_panel_color']; ?> !important;
            }

            .mini-sidebar .sidebar-nav #sidebarnav>li:hover a i,
            .mini-sidebar .sidebar-nav ul li a,
            .sidebar-nav ul li a.active i,
            .sidebar-nav ul li a.active:hover i,
            .sidebar-nav ul li.active a:hover i {
                color: #fff;
            }

            .sidebar-nav>ul>li.active>a,
            .sidebar-nav>ul>li.active>a i,
            .sidebar-nav>ul>li>a:hover i {
                color:
                    <?php echo $_COOKIE['admin_panel_color']; ?> !important;
            }
        }
    </style>
    <?php } ?>

</head>

<body>

    <div id="app" class="fix-header fix-sidebar card-no-border">
        <div id="main-wrapper">

            <header class="topbar">

                <nav class="navbar top-navbar navbar-expand-md navbar-light">
                    @include('layouts.header')
                </nav>

            </header>

            <aside class="left-sidebar">

                <!-- Sidebar scroll-->

                <div class="scroll-sidebar">

                    @include('layouts.menu')

                </div>

                <!-- End Sidebar scroll-->

            </aside>

        </div>


        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <span style="display: none;">
        <button type="button" class="btn btn-primary" id="order_notification_button" data-toggle="modal"
            data-target="#order_notification">{{ trans('lang.large_modal') }}</button>
    </span>
    <div class="modal fade" id="order_notification" tabindex="-1" role="dialog" aria-labelledby="order_notification"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered notification-main" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title order_subject" id="exampleModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6><span id="order_user_name"></span></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"><a href="{{ url('pending-driver-allocation') }}"
                            id="notification_penidng_driver" style="color: white">{{ trans('lang.view') }}</a>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('js/waves.js') }}"></script>
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.js') }}"></script>

    <script src="{{ asset('js/jquery.resizeImg.js') }}"></script>
    <script src="{{ asset('js/mobileBUGFix.mini.js') }}"></script>

    <script type="text/javascript">
        jQuery(window).scroll(function() {
            var scroll = jQuery(window).scrollTop();
            if (scroll <= 60) {
                jQuery("body").removeClass("sticky");
            } else {
                jQuery("body").addClass("sticky");
            }
        });
    </script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-storage.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-database.js"></script>
    <script src="https://unpkg.com/geofirestore/dist/geofirestore.js"></script>
    <script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
    <script src="{{ asset('js/chosen.jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('js/crypto-js.js') }}"></script>
    <script src="{{ asset('js/jquery.cookie.js') }}"></script>
    {{-- <script src="{{ asset('js/jquery.validate.js') }}"></script> --}}

    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js">
    </script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script src="{{ asset('js/jquery.masking.js') }}"></script>

    <script type="text/javascript">
        var firebaseConfig = {
            apiKey: "{{ config('firebase.apiKey') }}",
            authDomain: "{{ config('firebase.authDomain') }}",
            databaseURL: "{{ config('firebase.databaseURL') }}",
            projectId: "{{ config('firebase.projectId') }}",
            storageBucket: "{{ config('firebase.storageBucket') }}",
            messagingSenderId: "{{ config('firebase.messagingSenderId') }}",
            appId: "{{ config('firebase.appId') }}",
            measurementId: "{{ config('firebase.measurementId') }}"
        }

        firebase.initializeApp(firebaseConfig);

        var database = firebase.firestore();
        var geoFirestore = new GeoFirestore(database);
        var createdAtman = firebase.firestore.Timestamp.fromDate(new Date());
        var createdAt = {
            _nanoseconds: createdAtman.nanoseconds,
            _seconds: createdAtman.seconds
        };

        var ref = database.collection('settings').doc("globalSettings");
        ref.get().then(async function(snapshots) {
            try {
                var globalSettings = snapshots.data();
                $("#logo_web").attr('src', globalSettings.appLogo);

                if (getCookie('meta_title') == undefined || getCookie('meta_title') == null || getCookie(
                        'meta_title') == "") {
                    document.title = globalSettings.meta_title;

                }
                setCookie('meta_title', globalSettings.meta_title, 365);
            } catch (error) {

            }
        });


        var langcount = 0;

        var language_selected = "";

        var languages_list = database.collection('languages');

        languages_list.get().then(async function(snapshotslang) {

            if (snapshotslang.docs.length > 0) {
                languages_list_main = snapshotslang;
                snapshotslang.docs.forEach((doc) => {

                    var data = doc.data();

                    if (data.enable == true) {

                        langcount++;

                        if (data.code == "en") {

                            language_selected = data.code;


                        }

                        $('#language_dropdown').append($("<option></option>").attr("value", data.code)
                            .attr("data-isrtl", data.isRtl).text(data.name));

                    }

                });

                if (langcount > 1) {

                    $("#language_dropdown_box").css('visibility', 'visible');

                }

                <?php if (session()->get('locale')) { ?>

                $("#language_dropdown").val("<?php echo session()->get('locale'); ?>");

                <?php } else { ?>

                $("#language_dropdown").val(language_selected);



                <?php } ?>



            }

        });


        var url = "{{ route('changeLang') }}";

        $(".changeLang").change(function() {
            var slug = $(this).val();

            languages_list_main.docs.forEach((doc) => {
                var data = doc.data();

                if (slug == data.code) {
                    if (data.isRtl == undefined) {
                        setCookie('is_rtl', 'false', 365);
                    } else {
                        setCookie('is_rtl', data.isRtl.toString(), 365);
                    }
                    window.location.href = url + "?lang=" + slug;
                }
            });
        });




        $(document).ready(async function() {
            await buildNotificationHtml();
            setInterval(buildNotificationHtml, 60000);
        });

        async function buildNotificationHtml() {


            var notifications_list = database.collection('notifications').orderBy('createdAt', 'desc');
            await notifications_list.get().then(async function(snapshots) {
                var html = '';
                if (snapshots.docs.length) {

                    var data = [];

                    await Promise.all(snapshots.docs.map(async (listval) => {
                        var listval = listval.data();


                        if (listval.read == undefined) {
                            data.push(listval);
                        }
                    }));

                    if (data.length > 0) {

                        $('.notification_count').html(data.length).removeClass('d-none');
                    } else {
                        $('.notification_count').html('').addClass('d-none');
                        html +=
                            '<div class="view-all-button text-center">{{ trans('lang.no_new_notification') }}</div>';
                    }
                    if (data.length > 0) {
                        html += ' <div class="dropdown-header">\n' +
                            '                        <h6 class="mb-0 fs-12 font-weight-bold"><span id="total-notifications">' +
                            data.length +
                            '</span> <span class="text-primary">{{ trans('lang.new') }}</span> {{ trans('lang.notifications') }}</h6>\n' +
                            '                        <a href="Javascript:void(0)" onclick = "notificationMarkRead()" class="mb-1 badge badge-primary ml-auto pl-3 pr-3 mark-read" id="mark-all">{{ trans('lang.mark_all_read') }}</a>\n' +
                            '                    </div>';
                    }
                    html += '<ul class="dropdown-user">';

                    await Promise.all(data.map(async (value) => {

                        var profile = await getProfileImage(value.orderId, value.role);

                        if (!profile) {
                            profile = "{{ asset('images/notification_user.png') }}";
                        }
                        var userStatus = value.title;
                        var defaultImg = "{{ asset('images/notification_user.png') }}"
                        var notification_view = '{{ route('orders.edit', 'id') }}';
                        notification_view = notification_view.replace('id', value.orderId);
                        html += '<li>\n' +
                            '                            <div class="dw-user-box">\n' +
                            '                                <div class="u-img"><img src="' +
                            profile + '"  onerror="this.onerror=null;this.src=\'' +
                            defaultImg + '\'" alt="user"\n' +
                            '                                                        style="max-width: 45px;"></div>\n' +
                            '                                <div class="u-text">\n' +
                            '                                    <h4><a href="' +
                            notification_view + '">' + userStatus + '</a></h4>\n';

                        var date = value.createdAt.toDate().toDateString();
                        var time = value.createdAt.toDate().toLocaleTimeString('en-US');

                        if (value.orderId) {
                            html += '<p class="text-muted">OrderID : ' + value.orderId +
                                '</p>';

                        }

                        html += '<p class="text-muted">' + date + "" + time +
                            '</p></div>\n' +
                            '<a href="Javascript:void(0)" onclick = "notificationMarkRead(`' +
                        value.id +
                        '`)" class="mb-1 badge badge-primary ml-auto pl-3 pr-3 mark-read" id="mark-all">{{ trans('lang.mark_as_read') }}</a>' +
                            '                            </div>\n' +
                            ' </li>\n';


                    }));
                    html += '</ul>';
                    if (data.length > 0) {
                        html += '<div class="view-all-button text-center">\n' +
                            '                        <a href="{{ route('notifications.index') }}"\n' +
                            '                           class="fs-12 font-weight-bold">{{ trans('lang.view_all_notifications') }}</a>\n' +
                            '                    </div>';
                    }
                } else {
                    $('.notification_count').html('').addClass('d-none');
                    html +=
                        '<div class="view-all-button text-center">{{ trans('lang.no_new_notification') }}</div>';
                }
                $('.notification_data').html(html);

            });


        }
        async function getProfileImage(orderId, role) {
            var profile = '';
            await database.collection('orders').where('id', '==', orderId).get().then(async function(snapshots) {

                if (snapshots.docs.length > 0) {
                    var data = snapshots.docs[0].data();
                    if (role == 'driver') {

                        profile = data.user.image;

                    } else {
                        if (data.hasOwnProperty('driver') && data.driver != null && data.driver != '') {
                            profile = data.driver.profilePictureURL;
                        }

                    }

                }

            })

            return profile;
        }

        async function notificationMarkRead(notificationId = '') {

            if (notificationId) {


                await database.collection('notifications').doc(notificationId).update({
                    'read': true,
                    'readDate': new Date()
                }).then(async function() {
                    await buildNotificationHtml();
                });

            } else {

                await database.collection('notifications').get().then(function(querySnapshot) {
                    querySnapshot.forEach(async function(doc) {
                        doc.ref.update({
                            'read': true,
                            'readDate': new Date()
                        }).then(async function() {
                            await buildNotificationHtml();
                        });
                    });
                });
            }


        }

        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        var version = database.collection('settings').doc("Version");
        version.get().then(async function(snapshots) {
            var version_data = snapshots.data();
            if (version_data == undefined) {
                database.collection('settings').doc('Version').set({});
            }
            try {
                $('.web_version').html("V:" + version_data.web_version);
            } catch (error) {}
        });

        async function sendEmail(url, subject, message, recipients) {

            var checkFlag = false;

            await $.ajax({

                type: 'POST',
                data: {
                    subject: subject,
                    message: message,
                    recipients: recipients
                },
                url: url,
                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    checkFlag = true;
                },
                error: function(xhr, status, error) {
                    checkFlag = true;
                }
            });

            return checkFlag;

        }

        async function loadGoogleMapsScript() {

            var globalKeySnapshot = await database.collection('settings').doc("googleMapKey").get();
            var globalKeyData = globalKeySnapshot.data();
            googleMapKey = globalKeyData.key;
            const script = document.createElement('script');
            script.src = "https://maps.googleapis.com/maps/api/js?key=" + googleMapKey + "&libraries=places";
            script.onload = function() {
                navigator.geolocation.getCurrentPosition(GeolocationSuccessCallback, GeolocationErrorCallback);
            };
            document.head.appendChild(script);
        }

        const GeolocationSuccessCallback = (position) => {
            if (position.coords != undefined) {
                default_latitude = position.coords.latitude
                default_longitude = position.coords.longitude
                setCookie('default_latitude', default_latitude, 365);
                setCookie('default_longitude', default_longitude, 365);
            }
        };

        const GeolocationErrorCallback = (error) => {
            console.log('Error: You denied for your default Geolocation', error.message);
            setCookie('default_latitude', '23.022505', 365);
            setCookie('default_longitude', '72.571365', 365);
        };

        loadGoogleMapsScript();


        var route1 = '<?php echo route('orders.edit', 'id'); ?>';

        var pageloadded = 0;

        database.collection('orders').where('driverID', "==", null).where('status', '==', 'InProcess').onSnapshot(function(
            doc) {

            if (pageloadded) {

                doc.docChanges().forEach(function(change) {

                    var val = change.doc.data();
                    if (change.type == 'added') {
                        var user_name = '';
                        if (val.hasOwnProperty('user') && val.user.hasOwnProperty('name')) {
                            user_name = val.user.name;

                        }
                        $("#order_user_name").text(user_name + " has placed order, please assign driver");
                        $('.order_subject').text("Order placed");

                        if (route1) {

                            $("#notification_penidng_driver").attr("href", route1.replace('id', val.id));

                        }

                        $("#order_notification").modal('show');

                        $("#order_notification_button").trigger("click");
                    }

                });

            } else {

                pageloadded = 1;

            }

        });

        database.collection('settings').doc("notification_setting").get().then(async function(snapshots) {
            var data = snapshots.data();
            if (data != undefined) {
                serviceJson = data.serviceJson;
                if (serviceJson != '' && serviceJson != null) {
                    $.ajax({
                        type: 'POST',
                        data: {
                            serviceJson: btoa(serviceJson),
                        },
                        url: "{{ route('store-firebase-service') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {}
                    });
                }
            }
        });
    </script>

    @yield('scripts')

</body>

</html>
