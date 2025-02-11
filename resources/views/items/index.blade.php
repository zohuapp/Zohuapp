@extends('layouts.app')

@section('content')
    <div class="page-wrapper">


        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor restaurantTitle">{{ trans('lang.item_plural') }}</h3>

            </div>

            <div class="col-md-7 align-self-center">

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.item_plural') }}</li>
                </ol>

            </div>

            <div>

            </div>

        </div>


        <div class="container-fluid">
            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
                Processing...
            </div>

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{!! url()->current() !!}"><i
                                            class="fa fa-list mr-2"></i>{{ trans('lang.item_table') }}</a>
                                </li>
                                <?php if ($id != '') { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! route('items.create') !!}/{{ $id }}"><i
                                            class="fa fa-plus mr-2"></i>{{ trans('lang.item_create') }}</a>
                                </li>
                                <?php } else { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! route('items.create') !!}"><i
                                            class="fa fa-plus mr-2"></i>{{ trans('lang.item_create') }}</a>
                                </li>
                                <?php } ?>

                            </ul>
                        </div>
                        <div class="card-body">


                            <div class="table-responsive m-t-10">


                                <table id="itemTable"
                                    class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                    cellspacing="0" width="100%">

                                    <thead>

                                        <tr>
                                            <?php if (in_array('items.delete', json_decode(@session('user_permissions'), true))) { ?>

                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active">
                                                    <a id="deleteAll" class="do_not_delete" href="javascript:void(0)"><i
                                                            class="fa fa-trash"></i> {{ trans('lang.all') }}</a></label>
                                            </th>
                                            <?php } ?>
                                            <th>{{ trans('lang.item_image') }}</th>
                                            <th>{{ trans('lang.item_name') }}</th>
                                            <th>{{ trans('lang.item_price') }}</th>

                                            <th>{{ trans('lang.item_category_id') }}</th>
                                            <th>{{ trans('lang.brand') }}</th>

                                            <th>{{ trans('lang.item_publish') }}</th>
                                            <th>{{ trans('lang.actions') }}</th>
                                        </tr>

                                    </thead>

                                    <tbody id="append_list1">


                                    </tbody>

                                </table>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        const urlParams = new URLSearchParams(location.search);
        for (const [key, value] of urlParams) {

            if (key == 'brandID') {
                var brandID = value;
            } else {
                var brandID = '';
            }

            if (key == 'categoryID') {
                var categoryID = value;
            } else {
                var categoryID = '';
            }

        }
        var database = firebase.firestore();

        var currentCurrency = '';
        var currencyAtRight = false;
        var decimal_degits = 0;
        var id = "<?php echo $id; ?>";
        if (brandID != '' && brandID != undefined) {
            var ref = database.collection('vendor_products').where('brandID', '==', brandID);

        } else if (categoryID != '' && categoryID != undefined) {
            var ref = database.collection('vendor_products').where('categoryID', '==', categoryID);
        } else {
            var ref = database.collection('vendor_products');
        }
        ref = ref.orderBy('name');

        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        var append_list = '';

        refCurrency.get().then(async function(snapshots) {
            var currencyData = snapshots.docs[0].data();
            currentCurrency = currencyData.symbol;
            currencyAtRight = currencyData.symbolAtRight;

            if (currencyData.decimal_degits) {
                decimal_degits = currencyData.decimal_degits;
            }
        });

        var placeholderImage = '';
        var placeholder = database.collection('settings').doc('placeHolderImage');
        placeholder.get().then(async function(snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
        })

        var user_permissions = '<?php echo json_encode(@session('user_permissions')); ?>';

        const hasDeletePermission = user_permissions.includes('items.delete');

        var checkDeletePermission = false;

        if (hasDeletePermission) {
            checkDeletePermission = true;
        }

        $(document).ready(function() {
            $('#category_search_dropdown').hide();

            $(document.body).on('click', '.redirecttopage', function() {
                var url = $(this).attr('data-url');
                window.location.href = url;
            });

            jQuery("#data-table_processing").show();

            const table = $('#itemTable').DataTable({
                pageLength: 10, // Number of rows per page
                processing: false, // Show processing indicator
                serverSide: true, // Enable server-side processing
                responsive: true,
                ajax: async function(data, callback, settings) {
                    const start = data.start;
                    const length = data.length;
                    const searchValue = data.search.value.toLowerCase();
                    const orderColumnIndex = data.order[0].column;
                    const orderDirection = data.order[0].dir;

                    var orderableColumns = (checkDeletePermission) ? ['', '', 'name', 'finalPrice',
                            'category', 'brand', '', ''
                        ] : ['', 'name', 'finalPrice', 'category', 'brand', '',
                        ''] // Ensure this matches the actual column names

                    const orderByField = orderableColumns[
                    orderColumnIndex]; // Adjust the index to match your table
                    if (searchValue.length >= 3 || searchValue.length === 0) {
                        $('#data-table_processing').show();
                    }
                    await ref.get().then(async function(querySnapshot) {
                        if (querySnapshot.empty) {
                            $('#data-table_processing').hide(); // Hide loader
                            callback({
                                draw: data.draw,
                                recordsTotal: 0,
                                recordsFiltered: 0,
                                data: [] // No data
                            });
                            return;
                        }
                        let records = [];
                        let filteredRecords = [];

                        await Promise.all(querySnapshot.docs.map(async (doc) => {
                            let childData = doc.data();
                            childData.id = doc
                            .id; // Ensure the document ID is included in the data
                            const category = await productCategory(childData
                                .categoryID);
                            var brand = await productBrand(childData
                                .brandID);
                            childData.category = category ? category : "";
                            childData.brand = brand ? brand : "";

                            var dis_price = parseFloat(parseFloat(childData
                                .price) * parseFloat(childData
                                .discount)) / 100;
                            var finalPrice = parseFloat(childData.price) -
                                dis_price;
                            childData.finalPrice = parseInt(finalPrice);
                            if (searchValue) {
                                if (
                                    (childData.name && childData.name
                                        .toLowerCase().toString().includes(
                                            searchValue)) ||
                                    (category && category.toLowerCase()
                                        .toString().includes(searchValue)
                                        ) ||
                                    (brand && brand.toLowerCase().toString()
                                        .includes(searchValue)) ||
                                    (childData.finalPrice && childData
                                        .finalPrice.toString().includes(
                                            searchValue))
                                ) {
                                    filteredRecords.push(childData);
                                }
                            } else {
                                filteredRecords.push(childData);
                            }
                        }));

                        filteredRecords.sort((a, b) => {
                            let aValue = a[orderByField];
                            let bValue = b[orderByField];

                            if (orderByField === 'finalPrice') {

                                aValue = a[orderByField] ? parseInt(a[
                                    orderByField]) : 0;
                                bValue = b[orderByField] ? parseInt(b[
                                    orderByField]) : 0;
                            } else {

                                aValue = a[orderByField] ? a[orderByField]
                                .toString().toLowerCase().trim() : '';
                                bValue = b[orderByField] ? b[orderByField]
                                .toString().toLowerCase().trim() : ''

                            }

                            if (orderDirection === 'asc') {
                                return (aValue > bValue) ? 1 : -1;
                            } else {
                                return (aValue < bValue) ? 1 : -1;
                            }
                        });

                        const totalRecords = filteredRecords.length;

                        const paginatedRecords = filteredRecords.slice(start, start +
                            length);

                        await Promise.all(paginatedRecords.map(async (childData) => {
                            var getData = await buildHTML(childData);
                            records.push(getData);
                        }));

                        $('#data-table_processing').hide(); // Hide loader
                        callback({
                            draw: data.draw,
                            recordsTotal: totalRecords, // Total number of records in Firestore
                            recordsFiltered: totalRecords, // Number of records after filtering (if any)
                            data: records // The actual data to display in the table
                        });
                    }).catch(function(error) {
                        console.error("Error fetching data from Firestore:", error);
                        $('#data-table_processing').hide(); // Hide loader
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            data: [] // No data due to error
                        });
                    });
                },
                order: (checkDeletePermission) ? [
                    [2, 'asc']
                ] : [
                    [1, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: (checkDeletePermission) ? [0, 1, 6, 7] : [0, 5, 6]
                }, {
                    targets: (checkDeletePermission == true) ? 3 : 2,
                    type: "html-num-fmt",
                }, ],
                "language": {
                    "zeroRecords": "{{ trans('lang.no_record_found') }}",
                    "emptyTable": "{{ trans('lang.no_record_found') }}",
                    "processing": "" // Remove default loader
                },

            });

            function debounce(func, wait) {
                let timeout;
                const context = this;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
            $('#search-input').on('input', debounce(function() {
                const searchValue = $(this).val();
                if (searchValue.length >= 3) {
                    $('#data-table_processing').show();
                    table.search(searchValue).draw();
                } else if (searchValue.length === 0) {
                    $('#data-table_processing').show();
                    table.search('').draw();
                }
            }, 300));

        });

        async function buildHTML(val) {
            var html = [];
            newdate = '';

            var id = val.id;
            var route1 = '{{ route('items.edit', ':id') }}';
            route1 = route1.replace(':id', id);

            <?php if ($id != '') { ?>
            route1 = route1 + '?eid={{ $id }}';
            <?php } ?>
            if (checkDeletePermission) {
                html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id +
                    '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                    'for="is_open_' + id + '" ></label></td>');
            }
            if (val.photos != '') {
                html.push('<td><img class="rounded" style="width:50px" src="' + val.photo + '" alt="image"></td>');

            } else if (val.photo != '') {
                html.push('<td><img class="rounded" style="width:50px" src="' + val.photo + '" alt="image"></td>');
            } else {
                html.push('<td><img class="rounded" style="width:50px" src="' + placeholderImage +
                    '" alt="image"></td>');
            }
            html.push('<a data-url="' + route1 + '" href="' + route1 + '" class="redirecttopage">' + val.name + '</a>');

            var dis_price = '';
            val.disPrice = parseFloat(val.discount);
            var dis_price = parseFloat(parseFloat(val.price) * parseFloat(val.disPrice)) / 100;
            dis_price = val.price - dis_price;

            if (val.hasOwnProperty('discount') && val.discount != '' && val.discount != '0') {
                if (currencyAtRight) {
                    html.push('<span class="text-green" data-html="true" data-order="' + dis_price + '">' + parseFloat(
                        dis_price).toFixed(decimal_degits) + '' + currentCurrency + '  <s>' + parseFloat(val
                        .price).toFixed(decimal_degits) + '' + currentCurrency + '</s></span>');
                } else {
                    html.push('<span class="text-green" data-html="true" data-order="' + dis_price + '">' + '' +
                        currentCurrency + parseFloat(dis_price).toFixed(decimal_degits) + '  <s>' +
                        currentCurrency + '' + parseFloat(val.price).toFixed(decimal_degits) + '</s></span>');
                }
            } else {
                if (currencyAtRight) {
                    html.push('<span class="text-green" data-html="true" data-order="' + val.price + '">' + parseFloat(
                        val.price).toFixed(decimal_degits) + '' + currentCurrency + '</span>');
                } else {
                    html.push('<span class="text-green" data-html="true" data-order="' + val.price + '">' +
                        currentCurrency + '' + parseFloat(val.price).toFixed(decimal_degits) + '</span>');
                }
            }

            //const category = await productCategory(val.categoryID);
            var caregoryroute = '{{ route('categories.edit', ':id') }}';
            caregoryroute = caregoryroute.replace(':id', val.categoryID);
            html.push('<td><a href="' + caregoryroute + '">' + val.category + '</a></td>');

            var brandroute = "Javascript:void(0)";
            if (val.hasOwnProperty('brandID')) {
                // var brand = await productBrand(val.brandID);
                var brand = val.brand;
                brandroute = '{{ route('brands.edit', ':id') }}';
                brandroute = brandroute.replace(':id', val.brandID);
            } else {
                var brand = '';
            }

            html.push('<td><a href="' + brandroute + '">' + brand + '</a></td>');
            if (val.publish) {
                html.push('<td><label class="switch"><input type="checkbox" checked id="' + val.id +
                    '" name="isActive"><span class="slider round"></span></label></td>');
            } else {
                html.push('<td><label class="switch"><input type="checkbox" id="' + val.id +
                    '" name="isActive"><span class="slider round"></span></label></td>');
            }
            var actionHtml = '';
            actionHtml = actionHtml + '<span class="action-btn"><a href="' + route1 +
                '" class="link-td"><i class="fa fa-edit"></i></a>';
            if (checkDeletePermission) {
                actionHtml = actionHtml + '<a id="' + val.id +
                    '" name="item-delete" href="javascript:void(0)" class="delete-btn"><i class="fa fa-trash"></i></a>';
            }
            actionHtml = actionHtml + '</span>';
            html.push(actionHtml);

            return html;
        }

        $(document).on("click", "input[name='isActive']", function(e) {

            var ischeck = $(this).is(':checked');
            var id = this.id;
            if (ischeck) {
                database.collection('vendor_products').doc(id).update({
                    'publish': true
                }).then(function(result) {

                });
            } else {
                database.collection('vendor_products').doc(id).update({
                    'publish': false
                }).then(function(result) {

                });
            }

        });

        async function productRestaurant(restaurant) {
            var productRestaurant = '';
            await database.collection('vendors').where("id", "==", restaurant).get().then(async function(snapshotss) {


                if (snapshotss.docs[0]) {
                    var restaurant_data = snapshotss.docs[0].data();
                    productRestaurant = restaurant_data.title;

                }
            });
            return productRestaurant;
        }

        async function productCategory(category) {
            var productCategory = '';
            await database.collection('vendor_categories').where("id", "==", category).get().then(async function(
                snapshotss) {

                if (snapshotss.docs[0]) {
                    var category_data = snapshotss.docs[0].data();
                    productCategory = category_data.title;
                }
            });
            return productCategory;
        }

        async function productBrand(brand) {
            var productBrand = '';
            await database.collection('brands').where("id", "==", brand).get().then(async function(snapshotss) {

                if (snapshotss.docs[0]) {
                    var brand_data = snapshotss.docs[0].data();
                    productBrand = brand_data.title;

                }
            });
            return productBrand;
        }

        $(document).on("click", "a[name='item-delete']", function(e) {

            if (confirm("{{ trans('lang.delete_alert') }}")) {
                var id = this.id;
                database.collection('vendor_products').doc(id).delete().then(function(result) {
                    window.location.href = '{{ url()->current() }}';
                });
            }
        });

        $(document.body).on('change', '#selected_search', function() {

            if (jQuery(this).val() == 'category') {

                var ref_category = database.collection('vendor_categories');

                ref_category.get().then(async function(snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var data = listval.data();
                        $('#category_search_dropdown').append($("<option></option").attr(
                            "value", data.id).text(data.title));

                    });

                });
                jQuery('#search').hide();
                jQuery('#category_search_dropdown').show();
            } else {
                jQuery('#search').show();
                jQuery('#category_search_dropdown').hide();

            }
        });

        $("#is_active").click(function() {
            $("#itemTable .is_open").prop('checked', $(this).prop('checked'));
        });

        $("#deleteAll").click(function() {

            if ($('#itemTable .is_open:checked').length) {

                if (confirm("{{ trans('lang.selected_delete_alert') }}")) {
                    jQuery("#data-table_processing").show();
                    $('#itemTable .is_open:checked').each(function() {
                        var dataId = $(this).attr('dataId');

                        database.collection('vendor_products').doc(dataId).delete().then(function() {
                            setTimeout(function() {
                                window.location.reload();
                            }, 7000);

                        });
                    });
                }
            } else {
                alert("{{ trans('lang.select_delete_alert') }}");
            }
        });
    </script>
@endsection
