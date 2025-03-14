<?php

// Permissions to access the routes
function permissions_array(): array
{
    $permissions = array(
        'terms' => 'termsAndConditions',
        'privacy' => 'privacyPolicy',
        'refund' => 'refundPolicy',
        'about_us' => 'aboutUs',
        'support' => 'support',
        'items' => 'items',
        'items-edit' => 'items.edit',
        'items-create' => 'items.create',
        'items-delete' => "items.delete",
        'coupons' => 'coupons',
        'coupons-create' => 'coupons.create',
        'coupons-edit' => 'coupons.edit',
        'category' => 'categories',
        'category-edit' => 'categories.edit',
        'category-create' => 'categories.create',
        'users' => 'users',
        'users-edit' => 'users.edit',
        'users-view' => 'users.view',
        'users-create' => 'users.create',
        'drivers' => 'drivers',
        'drivers-edit' => 'drivers.edit',
        'drivers-create' => 'drivers.create',
        'drivers-view' => 'drivers.view',
        'orders' => "orders",
        'orders-vendors-order-print' => "vendors.orderprint",
        'orders-edit' => "orders.edit",
        'orders-delete' => "orders.delete",
        'payments' => 'payments',
        'dynamic-notifications-save' => 'dynamic-notification.save',
        'dynamic-notifications-index' => 'dynamic-notification.save',
        'god-eye' => 'map',
        'currency' => 'currencies',
        'currency-edit' => 'currencies.edit',
        'currency-create' => 'currencies.create',
        'global-setting' => 'settings.app.globals',
        'admin-commission' => 'settings.app.adminCommission',
        'delivery-charge' => 'settings.app.deliveryCharge',
        'payment-method' => 'payment-method',
        'language' => 'settings.app.languages',
        'language-save' => 'settings.app.languages.save',
        'language-create' => 'settings.app.languages.create',
        'language-edit' => 'settings.app.languages.edit',
        'general-notifications' => 'notification',
        'general-notifications-send' => 'notification.send',
        'banners' => 'banners',
        'banners-edit' => 'banners.edit',
        'banners-create' => 'banners.create',
        'cms' => 'cms',
        'cms-create' => 'cms.create',
        'cms-edit' => 'cms.edit',
        'item-attribute' => 'attributes',
        'item-attribute-edit' => 'attributes.edit',
        'item-attribute-create' => 'attributes.create',
        'footer-template' => 'footer',
        'homepage-template' => 'homepage',
        'landingpage-template' => 'landingpage',
        'header-template' => 'header',
        'reports' => 'report.index',
        'tax' => 'tax',
        'tax-edit' => 'tax.edit',
        'tax-create' => 'tax.create',
        'email-template-index' => 'email-templates.index',
        'email-template-edit' => 'email-templates.edit',
        'roles' => 'role.index',
        'roles-save' => 'role.save',
        'roles-delete' => 'role.delete',
        'roles-edit' => 'role.edit',
        'admins' => 'admin.users',
        'admins-users-create' => 'admin.users.create',
        'admins-users-delete' => 'admin.users.delete',
        'admins-users-edit' => 'admin.users.edit',
        'brands' => 'brands',
        'brands-edit' => 'brands.edit',
        'brands-create' => 'brands.create',
        'wallet-transaction' => 'wallet.transaction',
        'app-notifications' => 'app.notification',
        'app-notifications-view' => 'app.notification.view',
        'pending_orders' => 'pending.orders',
    );

    return $permissions;
}
