module.exports = function (elixir, fs) {
    'use strict';

    /*
     |--------------------------------------------------------------------------
     | Elixir Asset Management
     |--------------------------------------------------------------------------
     |
     | Elixir provides a clean, fluent API for defining some basic Gulp tasks
     | for your Laravel application. By default, we are compiling the Less
     | file for our application, as well as publishing vendor resources.
     |
     */

    elixir(function(mix) {

        var _root = __dirname + "/assets/src/";

        mix.styles([
            'libs/AdminLTE/bootstrap/css/bootstrap.min.css',
            'libs/font-awesome/css/font-awesome.min.css',
            'libs/AdminLTE/plugins/morris/morris.css',
            'libs/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
            'libs/AdminLTE/plugins/datepicker/datepicker3.css',
            'libs/AdminLTE/plugins/daterangepicker/daterangepicker-bs3.css',
            'libs/AdminLTE/plugins/iCheck/flat/green.css',
            'libs/AdminLTE/plugins/iCheck/square/blue.css',
            'libs/x-editable/css/bootstrap-editable.css',
            'libs/bootstrap-switch/bootstrap-switch.min.css',
            'libs/jquery-minicolors/jquery.minicolors.css',
            'libs/pnotify/pnotify.css',
            'libs/pnotify/pnotify.brighttheme.css',
            'libs/flag-icon-css/css/flag-icon.min.css',
            'libs/bootstrap-tagsinput/bootstrap-tagsinput.css',
            'libs/lazyYT/lazyYT.css',
            'libs/selectr/selectr.css',
            'libs/selectr/bs-polyfill.css',
            'libs/AdminLTE/plugins/select2/select2.css',
            'libs/AdminLTE/dist/AdminLTE.min.css',
            'libs/AdminLTE/dist/skins/_all-skins.min.css',
            'css/zedx/colorpicker.css',
            'css/zedx/dropzone.css',
            'css/zedx/helpers.css',
            'css/zedx/social-icons.css',
            'css/zedx/login.css',
            'css/zedx/editable.css',
            'css/zedx/modal.css',
            'css/zedx/nestable.css',
            'css/zedx/notification.css',
            'css/zedx/appearance.css',
            'css/zedx/page.css',
            'css/zedx/select.css',
            'css/zedx/template.css',
            'css/zedx/timeline.css',
            'css/zedx/uploader.css',
            'css/zedx/widget.css',
            'css/zedx/accordion.css',
            'css/custom.css'
        ], 'public/backend/css/styles.css', _root);

        mix.scripts([
            'libs/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js',
            'libs/jquery-ui/jquery-ui.min.js',
            'js/uibutton.js',
            'libs/AdminLTE/bootstrap/js/bootstrap.min.js',
            'libs/raphael/raphael-min.js',
            'libs/AdminLTE/plugins/morris/morris.min.js',
            'libs/AdminLTE/plugins/sparkline/jquery.sparkline.min.js',
            'libs/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
            'libs/AdminLTE/plugins/knob/jquery.knob.js',
            'libs/moment/moment.min.js',
            'libs/AdminLTE/plugins/daterangepicker/daterangepicker.js',
            'libs/AdminLTE/plugins/datepicker/bootstrap-datepicker.js',
            'libs/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js',
            'libs/AdminLTE/plugins/fastclick/fastclick.js',
            'libs/AdminLTE/plugins/iCheck/icheck.min.js',
            'libs/x-editable/js/bootstrap-editable.min.js',
            'libs/selectr/selectr.min.js',
            'libs/jquery-infinite-scroll/jquery.infinitescroll.min.js',
            'libs/nestable/jquery.nestable.js',
            'libs/AdminLTE/plugins/input-mask/jquery.inputmask.js',
            'libs/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js',
            'libs/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js',
            'libs/bootstrap-switch/bootstrap-switch.min.js',
            'libs/jquery-minicolors/jquery.minicolors.min.js',
            'libs/jquery-form/jquery.form.js',
            'libs/lazyYT/lazyYT.js',
            'libs/bootstrap.youtubepopup/bootstrap.youtubepopup.js',
            'libs/pnotify/pnotify.js',
            'libs/mustache.js/mustache.min.js',
            'libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
            'libs/AdminLTE/plugins/select2/select2.full.min.js',
            'js/reverseTable.js',
            'js/app.js',
            'js/config.js',
            'js/zedx/action.js',
            'js/zedx/widgetsList.js',
            'js/zedx/field.js',
            'js/zedx/category.js',
            'js/zedx/user.js',
            'js/zedx/ad.js',
            'js/zedx/plugin.js',
            'js/zedx/setting.js',
            'js/zedx/gateway.js',
            'js/zedx/adtype.js',
            'js/zedx/subscription.js',
            'js/zedx/components.js',
            'js/zedx/theme.js',
            'js/zedx/template.js',
            'js/zedx/page.js',
            'js/zedx/country.js',
            'js/zedx/notification.js',
            'js/zedx/dashboard.js',
            'js/zedx/menu.js',
            'js/zedx/update.js',
        ], 'public/backend/js/scripts.js', _root);


        mix.copy(_root + "libs/AdminLTE/plugins/iCheck/flat/green.png", "public/build/backend/css/")
            .copy(_root + "libs/AdminLTE/plugins/iCheck/square/blue.png", "public/build/backend/css/")
            .copy(_root + "libs/font-awesome/fonts/", "public/build/backend/fonts/")
            .copy(_root + "libs/x-editable/img/", "public/build/backend/img/")
            .copy(_root + "libs/jquery-minicolors/jquery.minicolors.png", "public/build/backend/css/")
            .copy(_root + "libs/flag-icon-css/flags/", "public/build/backend/flags")
            //.copy(_root + "libs/select2/select2.png", "public/build/backend/css/")
            .copy(_root + "img/", "public/build/backend/img/");

    });
};
