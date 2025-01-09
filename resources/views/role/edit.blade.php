@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.edit_role') }}</h3>

            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ url('role') }}">{{ trans('lang.role_plural') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('lang.edit_role') }}</li>


                </ol>
            </div>

        </div>

        <div class="card-body">

            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
                {{ trans('lang.processing') }}
            </div>

            <div class="error_top" style="display:none"></div>

            <div class="success_top" style="display:none"></div>

            <form action="{{ route('role.update', $id) }}" method="post" id="submitForm">
                @csrf
                <div class="row restaurant_payout_create">

                    <div class="restaurant_payout_create-inner">

                        <fieldset>
                            <legend>{{ trans('lang.role_details') }}</legend>
                            <div class="form-group row width-100 d-flex">
                                <label class="col-3 control-label">{{ trans('lang.name') }}*</label>
                                <div class="col-6">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $roles->role_name }}" {{ $roles->id == 1 ? 'disabled' : '' }}>
                                </div>
                                <div class="col-6 text-right">
                                    <label for="permissions"
                                        class="form-label">{{ trans('lang.assign_permissions') }}</label>
                                    <div class="text-right">
                                        <input type="checkbox" name="all_permission" id="all_permission"
                                            {{ $roles->id == 1 ? 'disabled' : '' }}>
                                        <label class="control-label"
                                            for="all_permission">{{ trans('lang.all_permissions') }}</label>
                                    </div>
                                </div>

                            </div>

                            <div class="role-table row width-100">

                                <div class="col-12">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>Menu</th>
                                            <th>Permission</th>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($routesForPermissions as $routes)
                                                @foreach (permissions_array() as $permissions => $route)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ str_replace(['_', '-'], ' ', ucfirst($permissions)) }}</strong>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="{{ $permissions }}"
                                                                value="{{ $routes === $route ? $routes : '' }}" name="routes[]"
                                                                class="permission" {{ $routes === $route ? 'checked' : '' }}
                                                                {{ $roles->id == 1 ? 'disabled' : '' }}
                                                                >
                                                            <label class="contol-label2" for="{{ $permissions }}">
                                                                {{ $route }}
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach --}}
                                            @foreach (permissions_array() as $permissions => $routes)
                                                @php($permission = array_search($routes, $routesForPermissions))
                                                @if ($permission)
                                                    <tr>
                                                        <td><strong>{{ str_replace(['_', '-'], ' ', ucfirst($permissions)) }}</strong>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="{{ $permissions }}"
                                                                value="{{ $routes }}" name="routes[]"
                                                                class="permission" checked
                                                                {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                            <label class="contol-label2"
                                                                for="{{ $permissions }}">
                                                                @if (str_contains($routes, '.index') ||
                                                                        $routes === 'admin.users' ||
                                                                        $routes === 'attributes' ||
                                                                        $routes === 'notification' ||
                                                                        str_contains($routes, '.transaction') ||
                                                                        $routes === 'app.notification' ||
                                                                        str_contains($routes, 'orders') ||
                                                                        str_contains($routes, $permissions . '') ||
                                                                        str_contains($routes, 'footer') ||
                                                                        str_contains($routes, 'homepage') ||
                                                                        str_contains($routes, 'landingpage') ||
                                                                        $routes === 'categories' ||
                                                                        str_contains($routes, 'header'))
                                                                    View
                                                                @elseif (str_contains($routes, '.save') || str_contains($routes, '.create'))
                                                                    Create
                                                                @elseif (str_contains($routes, '.edit') || str_contains($routes, '.update'))
                                                                    Edit
                                                                @elseif (str_contains($routes, '.delete'))
                                                                    Delete
                                                                @elseif ($routes === 'app.notification.view')
                                                                    Show
                                                                @elseif ($routes === 'notification.send')
                                                                    Notification Send
                                                                @elseif ($routes === 'settings.app.globals')
                                                                    Global App Settings
                                                                @elseif ($routes === 'settings.app.adminCommission')
                                                                    Admin Comission
                                                                @elseif ($routes === 'settings.app.deliveryCharge')
                                                                    Delievery Charge
                                                                @elseif ($routes === 'aboutUs')
                                                                    About Us
                                                                @elseif ($routes === 'users.view')
                                                                    User Info View
                                                                @elseif ($routes === 'drivers.view')
                                                                    Drivers Info View
                                                                @elseif ($routes === 'vendors.orderprint')
                                                                    Vendors Order Print
                                                                @elseif ($routes === 'map')
                                                                Map
                                                                @elseif ($routes === 'currencies')
                                                                    View
                                                                @elseif ($permissions === 'dynamic-notifications-save')
                                                                    Dynamic Notifications Save
                                                                @endif
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td><strong>{{ str_replace(['_', '-'], ' ', ucfirst($permissions)) }}</strong>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="{{ $permissions }}"
                                                                value="{{ $routes }}" name="routes[]"
                                                                class="permission" {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                            <label class="contol-label2"
                                                                for="{{ $permissions }}">
                                                                @if (str_contains($routes, '.index') ||
                                                                        $routes === 'admin.users' ||
                                                                        $routes === 'attributes' ||
                                                                        $routes === 'notification' ||
                                                                        str_contains($routes, '.transaction') ||
                                                                        $routes === 'app.notification' ||
                                                                        str_contains($routes, 'orders') ||
                                                                        str_contains($routes, $permissions . '') ||
                                                                        str_contains($routes, 'footer') ||
                                                                        str_contains($routes, 'homepage') ||
                                                                        str_contains($routes, 'landingpage') ||
                                                                        $routes === 'categories' ||
                                                                        str_contains($routes, 'header'))
                                                                    View
                                                                @elseif (str_contains($routes, '.save') || str_contains($routes, '.create'))
                                                                    Create
                                                                @elseif (str_contains($routes, '.edit') || str_contains($routes, '.update'))
                                                                    Edit
                                                                @elseif (str_contains($routes, '.delete'))
                                                                    Delete
                                                                @elseif ($routes === 'app.notification.view')
                                                                    Show
                                                                @elseif ($routes === 'notification.send')
                                                                    Notification Send
                                                                @elseif ($routes === 'settings.app.globals')
                                                                    Global App Settings
                                                                @elseif ($routes === 'settings.app.adminCommission')
                                                                    Admin Comission
                                                                @elseif ($routes === 'settings.app.deliveryCharge')
                                                                    Delievery Charge
                                                                @elseif ($routes === 'aboutUs')
                                                                    About Us
                                                                @elseif ($routes === 'users.view')
                                                                    User Info View
                                                                @elseif ($routes === 'drivers.view')
                                                                    Drivers Info View
                                                                @elseif ($routes === 'vendors.orderprint')
                                                                    Vendors Order Print
                                                                @elseif ($routes === 'map')
                                                                Map
                                                                @elseif ($routes === 'currencies')
                                                                    View
                                                                @elseif ($permissions === 'dynamic-notifications-save')
                                                                    Dynamic Notifications Save
                                                                @endif
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        {{-- <tbody>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.god_eye') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="god-eye" value="map" name="god-eye[]"
                                                        class="permission"
                                                        {{ in_array('map', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="god-eye">{{ trans('lang.view') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.role_plural') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="role-list" value="role.index" name="roles[]"
                                                        class="permission"
                                                        {{ in_array('role.index', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="role-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="role-save" value="role.save" name="roles[]"
                                                        class="permission"
                                                        {{ in_array('role.save', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="role-save">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="role-edit" value="role.edit" name="roles[]"
                                                        class="permission"
                                                        {{ in_array('role.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" contol-label2"
                                                        for="role-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="role-delete" value="role.delete"
                                                        name="roles[]" class="permission"
                                                        {{ in_array('role.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="role-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.admin_plural') }}</strong>

                                                </td>
                                                <td>
                                                    <input type="checkbox" id="admin-list" value="admin.users"
                                                        name="admins[]" class="permission"
                                                        {{ in_array('admin.users', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="admin-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="admin-create" value="admin.users.create"
                                                        name="admins[]" class="permission"
                                                        {{ in_array('admin.users.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="admin-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="admin-edit" value="admin.users.edit"
                                                        name="admins[]" class="permission"
                                                        {{ in_array('admin.users.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="admin-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="admin-delete" value="admin.users.delete"
                                                        name="admins[]" class="permission"
                                                        {{ in_array('admin.users.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="admin-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.user_customer') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="user-list" value="users" name="users[]"
                                                        class="permission"
                                                        {{ in_array('users', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="user-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="user-edit" value="users.edit"
                                                        name="users[]" class="permission"
                                                        {{ in_array('users.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="user-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="user-view" value="users.view"
                                                        name="users[]" class="permission"
                                                        {{ in_array('users.view', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="user-view">{{ trans('lang.view') }}</label>

                                                    <input type="checkbox" id="user-delete" value="users.delete"
                                                        name="users[]" class="permission"
                                                        {{ in_array('users.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="user-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.driver_plural') }}</strong>

                                                </td>
                                                <td>
                                                    <input type="checkbox" id="drivers-list" value="drivers"
                                                        name="drivers[]" class="permission"
                                                        {{ in_array('drivers', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="drivers-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="drivers-create" value="drivers.create"
                                                        name="drivers[]" class="permission"
                                                        {{ in_array('drivers.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="drivers-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="drivers-edit" value="drivers.edit"
                                                        name="drivers[]" class="permission"
                                                        {{ in_array('drivers.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="drivers-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="drivers-view" value="drivers.view"
                                                        name="drivers[]" class="permission"
                                                        {{ in_array('drivers.view', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="drivers-view">{{ trans('lang.view') }}</label>

                                                    <input type="checkbox" id="drivers-delete" value="drivers.delete"
                                                        name="drivers[]" class="permission"
                                                        {{ in_array('drivers.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="drivers-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.reports_sale') }}</strong>

                                                </td>
                                                <td>
                                                    <input type="checkbox" id="report" value="report.index"
                                                        name="reports[]" class="permission"
                                                        {{ in_array('report.index', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="report">{{ trans('lang.create') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.category_plural') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="categories-list" value="categories"
                                                        name="category[]" class="permission"
                                                        {{ in_array('categories', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="categories-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="categories-create"
                                                        value="categories.create" name="category[]" class="permission"
                                                        {{ in_array('categories.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="categories-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="categories-edit" value="categories.edit"
                                                        name="category[]" class="permission"
                                                        {{ in_array('categories.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="categories-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="categories-delete"
                                                        value="categories.delete" name="category[]" class="permission"
                                                        {{ in_array('categories.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="categories-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.brand') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="brands" value="brands" name="brands[]"
                                                        class="permission"
                                                        {{ in_array('brands', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="brands">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="brands-create" value="brands.create"
                                                        name="brands[]" class="permission"
                                                        {{ in_array('brands.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="brands-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="brands-edit" value="brands.edit"
                                                        name="brands[]" class="permission"
                                                        {{ in_array('brands.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="brands-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="brands-delete" value="brands.delete"
                                                        name="brands[]" class="permission"
                                                        {{ in_array('brands.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="brands-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.food_plural') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="foods-list" value="items" name="items[]"
                                                        class="permission"
                                                        {{ in_array('items', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="foods-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="foods-create" value="items.create"
                                                        name="items[]" class="permission"
                                                        {{ in_array('items.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="foods-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="foods-edit" value="items.edit"
                                                        name="items[]" class="permission"
                                                        {{ in_array('items.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="foods-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="foods-delete" value="items.delete"
                                                        name="items[]" class="permission"
                                                        {{ in_array('items.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="foods-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.item_attribute_id') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="attributes-list" value="attributes"
                                                        name="item-attribute[]" class="permission"
                                                        {{ in_array('attributes', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="attributes-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="attributes-create"
                                                        value="attributes.create" name="item-attribute[]"
                                                        class="permission"
                                                        {{ in_array('attributes.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="attributes-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="attributes-edit" value="attributes.edit"
                                                        name="item-attribute[]" class="permission"
                                                        {{ in_array('attributes.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="attributes-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="attributes-delete"
                                                        value="attributes.delete" name="item-attribute[]"
                                                        class="permission"
                                                        {{ in_array('attributes.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="attributes-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.order_plural') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="orders-list" value="orders"
                                                        name="orders[]" class="permission"
                                                        {{ in_array('orders', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="orders-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="orders-print" value="vendors.orderprint"
                                                        name="orders[]" class="permission"
                                                        {{ in_array('vendors.orderprint', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="orders-print">{{ trans('lang.print') }}</label>

                                                    <input type="checkbox" id="orders-edit" value="orders.edit"
                                                        name="orders[]" class="permission"
                                                        {{ in_array('orders.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="orders-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="orders-delete" value="orders.delete"
                                                        name="orders[]" class="permission"
                                                        {{ in_array('orders.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="orders-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.driver_allocation') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="pending-orders-list"
                                                        value="pending.orders" name="pending_orders[]" class="permission"
                                                        {{ in_array('pending.orders', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="pending-orders-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="pending-orders-delete"
                                                        value="pending.orders.delete" name="pending_orders[]"
                                                        class="permission"
                                                        {{ in_array('pending.orders.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>

                                                    <label class="contol-label2"
                                                        for="pending-orders-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.coupon_plural') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="coupons" value="coupons"
                                                        name="coupons[]" class="permission"
                                                        {{ in_array('coupons', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="coupons">{{ trans('lang.list') }}</label>
                                                    <input type="checkbox" id="coupons-create" value="coupons.create"
                                                        name="coupons[]" class="permission"
                                                        {{ in_array('coupons.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="coupons-create">{{ trans('lang.create') }}</label>
                                                    <input type="checkbox" id="coupons-edit" value="coupons.edit"
                                                        name="coupons[]" class="permission"
                                                        {{ in_array('coupons.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="coupons-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="coupons-delete" value="coupons.delete"
                                                        name="coupons[]" class="permission"
                                                        {{ in_array('coupons.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="coupons-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.general_notification') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="notification-list" value="notification"
                                                        name="general-notifications[]" class="permission"
                                                        {{ in_array('notification', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="notification-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="notification-send"
                                                        value="notification.send" name="general-notifications[]"
                                                        class="permission"
                                                        {{ in_array('notification.send', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="notification-send">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="notification-delete"
                                                        value="notification.delete" name="general-notifications[]"
                                                        class="permission"
                                                        {{ in_array('notification.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="notification-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.notifications') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="app-notification-list"
                                                        value="app.notification" name="app-notifications[]"
                                                        class="permission"
                                                        {{ in_array('app.notification', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="control-label2"
                                                        for="app-notification-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="app-notification-view"
                                                        value="app.notification.view" name="app-notifications[]"
                                                        class="permission"
                                                        {{ in_array('app.notification.view', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="control-label2"
                                                        for="app-notification-view">{{ trans('lang.view') }}</label>

                                                    <input type="checkbox" id="app-notification-delete"
                                                        value="app.notification.delete" name="app-notifications[]"
                                                        class="permission"
                                                        {{ in_array('app.notification.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="app-notification-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.wallet_transaction') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="wallet-transaction"
                                                        value="wallet.transaction" name="wallet-transaction[]"
                                                        class="permission"
                                                        {{ in_array('wallet.transaction', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="control-label2"
                                                        for="wallet-transaction">{{ trans('lang.list') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.menu_items') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="banners" value="banners"
                                                        name="banners[]" class="permission"
                                                        {{ in_array('banners', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="banners">{{ trans('lang.list') }}</label>
                                                    <input type="checkbox" id="banners-create" value="banners.create"
                                                        name="banners[]" class="permission"
                                                        {{ in_array('banners.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="banners-create">{{ trans('lang.create') }}</label>
                                                    <input type="checkbox" id="banners-edit" value="banners.edit"
                                                        name="banners[]" class="permission"
                                                        {{ in_array('banners.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="banners-edit">{{ trans('lang.edit') }}</label>
                                                    <input type="checkbox" id="banners-delete" value="banners.delete"
                                                        name="banners[]" class="permission"
                                                        {{ in_array('banners.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="banners-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.email_templates') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="email-template"
                                                        value="email-templates.index" name="email-template[]"
                                                        class="permission"
                                                        {{ in_array('email-templates.index', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="email-template">{{ trans('lang.list') }}</label>
                                                    <input type="checkbox" id="email-template-edit"
                                                        value="email-templates.edit" name="email-template[]"
                                                        class="permission"
                                                        {{ in_array('email-templates.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="email-template-edit">{{ trans('lang.edit') }}</label>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.cms_plural') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="cms" value="cms" name="cms[]"
                                                        class="permission"
                                                        {{ in_array('cms', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="cms">{{ trans('lang.list') }}</label>
                                                    <input type="checkbox" id="cms-create" value="cms.create"
                                                        name="cms[]" class="permission"
                                                        {{ in_array('cms.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="cms-create">{{ trans('lang.create') }}</label>
                                                    <input type="checkbox" id="cms-edit" value="cms.edit"
                                                        name="cms[]" class="permission"
                                                        {{ in_array('cms.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="cms-edit">{{ trans('lang.edit') }}</label>
                                                    <input type="checkbox" id="cms-delete" value="cms.delete"
                                                        name="cms[]" class="permission"
                                                        {{ in_array('cms.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="cms-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.app_setting_globals') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="global-setting"
                                                        value="settings.app.globals" name="global-setting[]"
                                                        class="permission"
                                                        {{ in_array('settings.app.globals', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="global-setting">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.currency_plural') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="currency-list" value="currencies"
                                                        name="currency[]" class="permission"
                                                        {{ in_array('currencies', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="currency-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="currency-create" value="currencies.create"
                                                        name="currency[]" class="permission"
                                                        {{ in_array('currencies.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="currency-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="currency-edit" value="currencies.edit"
                                                        name="currency[]" class="permission"
                                                        {{ in_array('currencies.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="currency-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="currency-delete" value="currencies.delete"
                                                        name="currency[]" class="permission"
                                                        {{ in_array('currencies.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="currency-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.payment_methods') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="payment-method-list"
                                                        value="payment-method" name="payment-method[]" class="permission"
                                                        {{ in_array('payment-method', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="payment-method-list">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.vat_setting') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="tax-list" value="tax" name="tax[]"
                                                        class="permission"
                                                        {{ in_array('tax', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="tax-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="tax-create" value="tax.create"
                                                        name="tax[]" class="permission"
                                                        {{ in_array('tax.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="tax-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="tax-edit" value="tax.edit"
                                                        name="tax[]" class="permission"
                                                        {{ in_array('tax.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="tax-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="tax-delete" value="tax.delete"
                                                        name="tax[]" class="permission"
                                                        {{ in_array('tax.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="tax-delete">{{ trans('lang.delete') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.deliveryCharge') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="delivery-charge"
                                                        value="settings.app.deliveryCharge" name="delivery-charge[]"
                                                        class="permission"
                                                        {{ in_array('settings.app.deliveryCharge', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="delivery-charge">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.languages') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="language-list"
                                                        value="settings.app.languages" name="language[]"
                                                        class="permission"
                                                        {{ in_array('settings.app.languages', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="language-list">{{ trans('lang.list') }}</label>

                                                    <input type="checkbox" id="language-create"
                                                        value="settings.app.languages.create" name="language[]"
                                                        class="permission"
                                                        {{ in_array('settings.app.languages.create', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="language-create">{{ trans('lang.create') }}</label>

                                                    <input type="checkbox" id="language-edit"
                                                        value="settings.app.languages.edit" name="language[]"
                                                        class="permission"
                                                        {{ in_array('settings.app.languages.edit', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="language-edit">{{ trans('lang.edit') }}</label>

                                                    <input type="checkbox" id="language-delete"
                                                        value="settings.app.languages.delete" name="language[]"
                                                        class="permission"
                                                        {{ in_array('settings.app.languages.delete', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="language-delete">{{ trans('lang.delete') }}</label>

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.header_template') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="headerTemplate" value="header"
                                                        name="header-template[]" class="permission"
                                                        {{ in_array('header', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="headerTemplate">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.landingpageTemplate') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="landingpageTemplate" value="landingpage"
                                                        name="landingpage-template[]" class="permission"
                                                        {{ in_array('landingpage', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="landingpageTemplate">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.footer_template') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="footer_template" value="footer"
                                                        name="footer-template[]" class="permission"
                                                        {{ in_array('footer', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="footer_template">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.terms_and_conditions') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="terms" value="termsAndConditions"
                                                        name="terms[]" class="permission"
                                                        {{ in_array('termsAndConditions', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="terms">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.privacy_policy') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="privacy" value="privacyPolicy"
                                                        name="privacy[]" class="permission"
                                                        {{ in_array('privacyPolicy', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class="contol-label2"
                                                        for="privacy">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.refund_policy') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="refund" value="refundPolicy"
                                                        name="refund[]" class="permission"
                                                        {{ in_array('refundPolicy', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="refund">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.about_us') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="about_us" value="aboutUs"
                                                        name="about_us[]" class="permission"
                                                        {{ in_array('aboutUs', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>
                                                    <label class=" control-label2"
                                                        for="about_us">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ trans('lang.support') }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" id="support" value="support"
                                                        name="support[]" class="permission"
                                                        {{ in_array('support', $permissions) ? 'checked' : '' }}
                                                        {{ $roles->id == 1 ? 'disabled' : '' }}>

                                                    <label class=" control-label2"
                                                        for="support">{{ trans('lang.update') }}</label>
                                                </td>
                                            </tr>

                                        </tbody> --}}
                                    </table>
                                </div>


                            </div>


                        </fieldset>
                    </div>
                </div>
            </form>

        </div>
        <div class="form-group col-12 text-center btm-btn">
            @if ($roles->id != 1)
                <button type="button" class="btn btn-primary edit-form-btn"><i class="fa fa-save"></i>
                    {{ trans('lang.save') }}
                </button>
            @endif
            <a href="{{ url('role') }}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel') }}</a>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(".edit-form-btn").click(async function() {

            $(".success_top").hide();
            $(".error_top").hide();
            var name = $("#name").val();

            if (name == "") {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{ trans('lang.user_name_help') }}</p>");
                window.scrollTo(0, 0);
                return false;
            } else {
                $('form#submitForm').submit();

            }

        });

        $('#all_permission').on('click', function() {

            if ($(this).is(':checked')) {
                $.each($('.permission'), function() {
                    $(this).prop('checked', true);
                });
            } else {
                $.each($('.permission'), function() {
                    $(this).prop('checked', false);
                });
            }

        });
    </script>
@endsection
