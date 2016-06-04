<?php

Route::group(['middleware' => 'api'], function () {

});

Route::group(['middleware' => 'web'], function () {
    /*
     * Ajax Routes
     */
    Route::group([], function () {
        Route::group(['prefix' => '_zxajax', 'namespace' => 'ZxAjax'], function () {
            Route::get('map/{map}', ['as' => 'zxajax.map.show', 'uses' => 'MapController@show']);
            Route::get('category/{category}/adFields', ['as' => 'zxajax.category.adFields', 'uses' => 'CategoryController@adFields']);
            Route::get('category/{category}/searchFields', ['as' => 'zxajax.category.searchFields', 'uses' => 'CategoryController@searchFields']);
        });
    });

    /*
     * Backend Routes
     */
    Route::group(['prefix' => 'zxadmin', 'namespace' => 'Backend'], function () {
        /* Auth Backend */

        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
            Route::get('login', ['as' => 'zxadmin.login', 'uses' => 'AuthController@getLogin']);
            Route::post('login', ['as' => 'zxadmin.login', 'uses' => 'AuthController@login']);
            Route::get('logout', ['as' => 'zxadmin.logout', 'uses' => 'AuthController@getLogout']);

            Route::get('password/reset/{token?}', ['as' => 'zxadmin.password.resetrequest', 'uses' => 'PasswordController@getReset']);
            Route::get('password/email', ['as' => 'zxadmin.password.email', 'uses' => 'PasswordController@getEmail']);
            Route::post('password/email', ['as' => 'zxadmin.password.email', 'uses' => 'PasswordController@postEmail']);
            Route::post('password/reset', ['as' => 'zxadmin.password.reset', 'uses' => 'PasswordController@postReset']);
        });

        Route::controller('password', 'Auth\PasswordController');

        /* Administrator : /zxadmin */
        Route::group(['middleware' => 'auth:admin'], function () {
            /* Home : /zxadmin/ */
            Route::get('/', ['as' => 'zxadmin.dashboard.index', 'uses' => 'DashBoardController@index']);

            /* Edit : /zxadmin/edit */
            Route::group(['prefix' => 'edit'], function () {
                Route::get('/', ['as' => 'zxadmin.edit', 'uses' => 'AdminController@edit']);
                Route::patch('/', ['as' => 'zxadmin.update', 'uses' => 'AdminController@update']);
            });

            /* Home : /zxadmin/ */
            Route::group(['prefix' => 'dashboard'], function () {
                Route::resource('dashboardWidget', 'DashBoardController', ['except' => ['index', 'show', 'create'], 'names' => constructRouteNames('zxadmin.dashboard')]);
            });

            /* Home : /zxadmin/notification */
            Route::get('notification', ['as' => 'zxadmin.notification.index', 'uses' => 'NotificationController@index']);
            Route::put('notification/readall', ['as' => 'zxadmin.notification.readall', 'uses' => 'NotificationController@readall']);

            /* Sort : /zxadmin/sort */
            Route::post('sort', ['as' => 'zxadmin.sort', 'uses' => '\Rutorika\Sortable\SortableController@sort']);

            /* Menu : /zxadmin/menu */
            Route::resource('menu', 'MenuController', ['except' => ['edit', 'show', 'create'], 'names' => constructRouteNames('zxadmin.menu')]);
            Route::get('menu/{groupName}', ['as' => 'zxadmin.menu.group', 'uses' => 'MenuController@filterByGroupName']);
            Route::put('menu/{menu}/order', ['as' => 'zxadmin.menu.order', 'uses' => 'MenuController@order']);

            /* Firewall : /zxadmin/firewall */
            //Route::get('firewall', ['as' => 'zxadmin.firewall.index', 'uses' => 'FirewallController@index']);
            //Route::post('firewall', ['as' => 'zxadmin.firewall.store', 'uses' => 'FirewallController@store']);
            //Route::delete('firewall/{encodedIp}', ['as' => 'zxadmin.firewall.destroy', 'uses' => 'FirewallController@destroy']);

            /* Gateway : /zxadmin/gateway */
            Route::put('gateway/setCurrency', ['as' => 'zxadmin.gateway.setCurrency', 'uses' => 'GatewayController@setCurrency']);
            Route::resource('gateway', 'GatewayController', ['only' => ['index', 'update', 'edit'], 'names' => constructRouteNames('zxadmin.gateway')]);
            Route::put('gateway/{gateway}/switchStatus', ['as' => 'zxadmin.gateway.switchStatus', 'uses' => 'GatewayController@switchStatus']);

            /* Ad : /zxadmin/ad */
            Route::resource('ad', 'AdController', ['except' => ['show', 'create', 'store', 'destroy'], 'names' => constructRouteNames('zxadmin.ad')]);
            Route::group(['prefix' => 'ad'], function () {
                Route::patch('{adWithTrashed}/adtype/{adtype}', ['as' => 'zxadmin.ad.adtype', 'uses' => 'AdController@updateAdtype']);
                Route::get('adtype/{adtype}', ['as' => 'zxadmin.ad.create', 'uses' => 'AdController@create']);
                Route::post('adtype/{adtype}', ['as' => 'zxadmin.ad.store', 'uses' => 'AdController@store']);
                Route::get('choose', ['as' => 'zxadmin.ad.choose', 'uses' => 'AdController@choose']);
                Route::get('status/{adstatus}', ['as' => 'zxadmin.ad.status', 'uses' => 'AdController@filterByStatus']);
                Route::put('{adCollection}/moderate/{adstatus}', ['as' => 'zxadmin.ad.moderate', 'uses' => 'AdController@moderateAdsCollection']);
                Route::delete('{adCollection}', ['as' => 'zxadmin.ad.destroy', 'uses' => 'AdController@destroyAdsCollection']);
            });

            /* Adtype : /zxadmin/adtype */
            Route::resource('adtype', 'AdtypeController', ['except' => ['show', 'destroy'], 'names' => constructRouteNames('zxadmin.adtype')]);
            Route::delete('adtype/{adtypeCollection}', ['as' => 'zxadmin.adtype.destroy', 'uses' => 'AdtypeController@destroyAdtypesCollection']);

            /* Categories : /zxadmin/category */
            Route::resource('category', 'CategoryController', ['except' => ['show'], 'names' => constructRouteNames('zxadmin.category')]);
            Route::group(['prefix' => 'category'], function () {
                Route::put('{category}/order', ['as' => 'zxadmin.category.order', 'uses' => 'CategoryController@order']);
            });

            /* Country : /zxadmin/country */
            Route::group(['prefix' => 'country'], function () {
                Route::get('/', ['as' => 'zxadmin.country.index', 'uses' => 'CountryController@index']);
                Route::put('{country}/swap', ['as' => 'zxadmin.country.swap', 'uses' => 'CountryController@swap']);
                Route::put('{country}/personalize', ['as' => 'zxadmin.country.personalize', 'uses' => 'CountryController@personalize']);
                //Route::post('{country}/upload', ['as' => 'zxadmin.country.upload', 'uses' => 'CountryController@upload']);
            });

            /* Field : /zxadmin/field */
            Route::resource('field', 'FieldController', ['except' => ['show', 'destroy'], 'names' => constructRouteNames('zxadmin.field')]);
            Route::delete('field/{fieldCollection}', ['as' => 'zxadmin.field.destroy', 'uses' => 'FieldController@destroyFieldsCollection']);

            /* Page : /zxadmin/page */
            Route::get('page/core', ['as' => 'zxadmin.page.core', 'uses' => 'PageController@core']);
            Route::resource('page', 'PageController', ['except' => ['edit', 'show'], 'names' => constructRouteNames('zxadmin.page')]);
            Route::group(['prefix' => 'page'], function () {
                Route::put('{page}/homepage', ['as' => 'zxadmin.page.beHomepage', 'uses' => 'PageController@beHomepage']);
                Route::put('{page}/template/switch', ['as' => 'zxadmin.page.switchTemplate', 'uses' => 'PageController@switchTemplate']);
                Route::get('{page}/edit/block/{templateblock}', ['as' => 'zxadmin.page.edit', 'uses' => 'PageController@edit']);
                Route::post('{page}/edit/themepartial/{themepartial}', ['as' => 'zxadmin.page.attachthemepartial', 'uses' => 'PageController@attachThemePartial']);
                Route::delete('{page}/edit/themepartial/{themepartial}', ['as' => 'zxadmin.page.detachthemepartial', 'uses' => 'PageController@detachThemePartial']);
                Route::post('{page}/edit/block/{templateblock}/widgetnode/{widgetnode}/swap', ['as' => 'zxadmin.widget.swap', 'uses' => 'WidgetnodeController@swap']);
                Route::resource('{page}/edit/block/{templateblock}/widgetnode', 'WidgetnodeController', ['except' => ['index', 'show', 'create'], 'names' => constructRouteNames('zxadmin.widgetnode')]);
            });

            /* Module : /zxadmin/module */
            Route::group(['prefix' => 'module'], function () {
                Route::get('/', ['as' => 'zxadmin.module.index', 'uses' => 'ModuleController@index']);

                Route::post('download/{module}', ['as' => 'zxadmin.module.download', 'uses' => 'ModuleController@download']);
                Route::put('{module}/switchStatus', ['as' => 'zxadmin.module.switchStatus', 'uses' => 'ModuleController@switchStatus']);
                Route::get('status/{status}', ['as' => 'zxadmin.module.status', 'uses' => 'ModuleController@filterByStatus']);
                Route::get('add', ['as' => 'zxadmin.module.add', 'uses' => 'ModuleController@add']);
                Route::get('add/tab/{tab}', ['as' => 'zxadmin.module.addWithTab', 'uses' => 'ModuleController@addWithTab']);
                //Route::post('add/tab/upload', ['as' => 'zxadmin.module.upload', 'uses' => 'ModuleController@upload']);
            });

            /* Widget : /zxadmin/widget */
            Route::group(['prefix' => 'widget'], function () {
                Route::get('/', ['as' => 'zxadmin.widget.index', 'uses' => 'WidgetController@index']);
                Route::post('download/{widgetType}/{widgetNamespace}/{widget}', ['as' => 'zxadmin.widget.download', 'uses' => 'WidgetController@download']);
                Route::get('add/{type}/tab/{tab}', ['as' => 'zxadmin.widget.addWithTab', 'uses' => 'WidgetController@addWithTab']);
                //Route::post('add/{type}/tab/upload', ['as' => 'zxadmin.widget.upload', 'uses' => 'WidgetController@upload']);
            });

            /* Theme : /zxadmin/theme */
            Route::group(['prefix' => 'theme'], function () {
                Route::get('/', ['as' => 'zxadmin.theme.index', 'uses' => 'ThemeController@index']);
                Route::put('/', ['as' => 'zxadmin.theme.set', 'uses' => 'ThemeController@set']);
                Route::post('download/{theme}', ['as' => 'zxadmin.theme.download', 'uses' => 'ThemeController@download']);
                Route::get('add', ['as' => 'zxadmin.theme.add', 'uses' => 'ThemeController@add']);
                Route::get('add/tab/{tab}', ['as' => 'zxadmin.theme.addWithTab', 'uses' => 'ThemeController@addWithTab']);
                //Route::post('add/tab/upload', ['as' => 'zxadmin.theme.upload', 'uses' => 'ThemeController@upload']);
                Route::post('recompile', ['as' => 'zxadmin.theme.recompile', 'uses' => 'ThemeController@recompile']);
            });

            Route::resource('template', 'TemplateController', ['except' => ['show', 'destroy'], 'names' => constructRouteNames('zxadmin.template')]);
            Route::delete('template/{templateCollection}', ['as' => 'zxadmin.template.destroy', 'uses' => 'TemplateController@destroyTemplatesCollection']);

            /* Setting : /zxadmin/setting */
            Route::group(['prefix' => 'setting'], function () {
                Route::get('/', ['as' => 'zxadmin.setting.index', 'uses' => 'SettingController@index']);
                Route::patch('/', ['as' => 'zxadmin.setting.update', 'uses' => 'SettingController@update']);
            });

            /* Subscription : /zxadmin/subscription */
            Route::resource('subscription', 'SubscriptionController', ['except' => ['show', 'destroy'], 'names' => constructRouteNames('zxadmin.subscription')]);
            Route::delete('subscription/{subscriptionCollection}', ['as' => 'zxadmin.subscription.destroy', 'uses' => 'SubscriptionController@destroySubscriptionsCollection']);

            /* User : /zxadmin/user */
            Route::resource('user', 'UserController', ['except' => ['destory'], 'names' => constructRouteNames('zxadmin.user')]);
            Route::delete('user/{userCollection}', ['as' => 'zxadmin.user.destroy', 'uses' => 'UserController@destroyUsersCollection']);

            /* User : /zxadmin/admin */
            Route::resource('admin', 'AdminController', ['names' => constructRouteNames('zxadmin.admin')]);

            /* User : /zxadmin/update */
            Route::get('update', ['as' => 'zxadmin.update.index', 'uses' => 'UpdateController@index']);
            Route::get('update/{type?}/{group?}/{name?}', ['as' => 'zxadmin.update.show', 'uses' => 'UpdateController@show']);
        });
    });

    /*
     * Frontend Routes
     */
    Route::group(['namespace' => 'Frontend'], function () {
        Route::any('/', ['as' => 'page.home', 'uses' => 'PageController@index']);

        /* Auth User */
        Route::group(['prefix' => 'user/auth', 'namespace' => 'User\Auth'], function () {
            Route::get('auth/provider/{provider}', ['as' => 'auth.provider', 'uses' => 'SocialProviderController@redirectToProvider']);
            Route::get('auth/provider/{provider}/callback', ['as' => 'auth.provider.callback', 'uses' => 'SocialProviderController@handleProviderCallback']);

            Route::get('login', ['as' => 'user.login', 'uses' => 'AuthController@getLogin']);
            Route::post('login', ['as' => 'user.login', 'uses' => 'AuthController@login']);
            Route::get('logout', ['as' => 'user.logout', 'uses' => 'AuthController@getLogout']);
            Route::get('register', ['as' => 'user.register', 'uses' => 'AuthController@getRegister']);
            Route::post('register', ['as' => 'user.register', 'uses' => 'AuthController@register']);

            Route::get('password/reset/{token?}', ['as' => 'auth.password.resetrequest', 'uses' => 'PasswordController@getReset']);
            Route::get('password/email', ['as' => 'auth.password.email', 'uses' => 'PasswordController@getEmail']);
            Route::post('password/email', ['as' => 'auth.password.email', 'uses' => 'PasswordController@postEmail']);
            Route::post('password/reset', ['as' => 'auth.password.reset', 'uses' => 'PasswordController@postReset']);
        });

        /* User : /user */
        Route::group(['middleware' => 'auth', 'prefix' => 'user', 'namespace' => 'User'], function () {
            /* Home : /user/ */
            Route::get('/', ['as' => 'user.index', 'uses' => 'AdController@index']);

            /* Ad : /user/ad */
            Route::group(['prefix' => 'ad'], function () {
                Route::get('/', ['as' => 'user.ad.index', 'uses' => 'AdController@index']);
                Route::get('status/{adstatus}', ['as' => 'user.ad.status', 'uses' => 'AdController@filterByStatus']);
                Route::get('adtype/{adtypeNotCustomized}', ['as' => 'user.ad.create', 'uses' => 'AdController@create']);
                Route::post('adtype/{adtypeNotCustomized}', ['as' => 'user.ad.store', 'uses' => 'AdController@store']);
                Route::put('{adUser}/renew', ['as' => 'user.ad.renew', 'uses' => 'AdController@renew']);
                Route::get('{adUser}/edit', ['as' => 'user.ad.edit', 'uses' => 'AdController@edit']);
                Route::patch('{adUser}', ['as' => 'user.ad.update', 'uses' => 'AdController@update']);
                Route::delete('{adUser}', ['as' => 'user.ad.destroy', 'uses' => 'AdController@destroy']);
            });

            /* Ad : /user/adtype */
            Route::group(['prefix' => 'adtype'], function () {
                Route::get('/', ['as' => 'user.adtype.index', 'uses' => 'AdtypeController@index']);
                Route::get('cart/{adtypeNotCustomized}', ['as' => 'user.adtype.cart', 'uses' => 'AdtypeController@cart']);
                Route::post('checkout/{adtypeNotCustomized}', ['as' => 'user.adtype.checkout', 'uses' => 'AdtypeController@checkout']);
            });

            /* Subscription : /user/subscription */
            Route::group(['prefix' => 'subscription'], function () {
                Route::get('/', ['as' => 'user.subscription.index', 'uses' => 'SubscriptionController@index']);
                Route::get('cart/{subscription}', ['as' => 'user.subscription.cart', 'uses' => 'SubscriptionController@cart']);
                Route::post('checkout/{subscription}', ['as' => 'user.subscription.checkout', 'uses' => 'SubscriptionController@checkout']);
                Route::patch('{subscription}', ['as' => 'user.subscription.swapForFree', 'uses' => 'SubscriptionController@swapForFree']);
            });

            /* Edit : /user/edit */
            Route::group(['prefix' => 'edit'], function () {
                Route::get('/', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
                Route::patch('/', ['as' => 'user.update', 'uses' => 'UserController@update']);
            });
        });

        /* Ad : /ad */
        Route::group(['prefix' => 'ad', 'namespace' => 'Ad'], function () {
            Route::get('/', ['as' => 'ad.search.all', 'uses' => 'AdController@search']);
            Route::get('search/{params?}', ['as' => 'ad.search', 'uses' => 'AdController@search'])->where('params', '.+');
            Route::get('show/{adValidated}/{title?}', ['as' => 'ad.show', 'uses' => 'AdController@show']);
            Route::get('preview/{adPreview}/{title?}', ['as' => 'ad.preview', 'uses' => 'AdController@preview']);
            Route::post('contact/{adValidated}', ['as' => 'ad.contact', 'uses' => 'AdController@contact']);
            Route::post('phone/{adValidated}', ['as' => 'ad.phone', 'uses' => 'AdController@phone']);
        });

        /* Page : /page */
        Route::group(['prefix' => 'page'], function () {
            Route::any('{shortcut?}', ['as' => 'page.show', 'uses' => 'PageController@show'])->where('shortcut', '.+');
        });

        /* Payment : /payment */
        Route::group(['prefix' => 'payment'], function () {
            Route::get('accepted', ['as' => 'payment.accepted', 'uses' => 'PaymentController@accepted']);
            Route::get('cancelled', ['as' => 'payment.cancelled', 'uses' => 'PaymentController@cancelled']);

            Route::get('cancel/{encryptedOrderId}', ['as' => 'payment.cancel', 'uses' => 'PaymentController@cancelPayment']);
            Route::get('return/{encryptedOrderId}', ['as' => 'payment.return', 'uses' => 'PaymentController@returnPayment']);
            Route::any('notify/{encryptedOrderId}', ['as' => 'payment.notify', 'uses' => 'PaymentController@notifyPayment']);
        });

    });
});
