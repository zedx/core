<?php

namespace ZEDx\Providers;

use Auth;
use Crypt;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use ZEDx\Models\Ad;
use ZEDx\Models\Adstatus;
use ZEDx\Models\Adtype;
use ZEDx\Models\Field;
use ZEDx\Models\Order;
use ZEDx\Models\Subscription;
use ZEDx\Models\Template;
use ZEDx\Models\Templateblock;
use ZEDx\Models\User;
use ZEDx\Models\Category;
use ZEDx\Models\Widgetnode;

class RouteServiceProvider extends ServiceProvider
{
    /**
   * This namespace is applied to the controller routes in your routes file.
   *
   * In addition, it is set as the URL generator's root namespace.
   *
   * @var string
   */
  protected $namespace = 'ZEDx\Http\Controllers';

  /**
   * Define your route model bindings, pattern filters, etc.
   *
   * @param  \Illuminate\Routing\Router  $router
   *
   * @return void
   */
  public function boot(Router $router)
  {
      $router->bind('adWithTrashed', function ($id) {
          return Ad::with('content')->withTrashed()->findOrFail($id);
      });

      $router->bind('ad', function ($id) {
          return Ad::with('content')->findOrFail($id);
      });

      $router->bind('adCollection', function ($ids) {
          $ids = explode(',', $ids);

          return Ad::with('content')->withTrashed()->findMany($ids);
      });

      $router->bind('adstatus', function ($title) {
          return Adstatus::whereTitle($title)->firstOrFail();
      });

      $router->bind('adValidated', function ($id) {
          return Ad::with('content')->validate()->findOrFail($id);
      });

      $router->bind('adPreview', function ($id) {
          if (Auth::guard('admin')->check()) {
              return Ad::with('content')->withTrashed()->findOrFail($id);
          }

          if (Auth::guard('user')->check()) {
              return Auth::user()->ads()->with('content')->findOrFail($id);
          }

          abort(404);
      });

      $router->bind('adtypeNotCustomized', function ($id) {
          return Adtype::whereIsCustomized(0)->findOrFail($id);
      });

      $router->bind('adtypeCollection', function ($ids) {
          $ids = explode(',', $ids);

          return Adtype::findMany($ids);
      });

      $router->bind('templateCollection', function ($ids) {
          $ids = explode(',', $ids);

          return Template::findMany($ids);
      });

      $router->bind('encryptedOrderId', function ($enc) {
          $orderId = Crypt::decrypt($enc);

          return Order::findOrFail($orderId);
      });

      $router->bind('fieldCollection', function ($ids) {
          $ids = explode(',', $ids);

          return Field::findMany($ids);
      });

      $router->bind('subscriptionCollection', function ($ids) {
          $ids = explode(',', $ids);

          return Subscription::findMany($ids);
      });

      $router->bind('userCollection', function ($ids) {
          $ids = explode(',', $ids);

          return User::findMany($ids);
      });

      $router->bind('adUser', function ($id) {
          if (Auth::check()) {
              return Auth::user()->ads()->with('content')->findOrFail($id);
          } else {
              abort(404);
          }
      });

      $router->bind('field', function ($id) {
          return Field::with('search')->findOrFail($id);
      });

      $router->bind('visibleCategory', function ($id) {
          return Category::visible()->findOrFail($id);
      });

      $router->bind('templateblock', function ($identifier) use ($router) {
          $page = $router->input('page');

          return Templateblock::whereIdentifier($identifier)
        ->whereTemplateId($page->template->id)
        ->firstOrFail();
      });

      $router->bind('widgetnode', function ($id) use ($router) {
          $pageId = $router->input('page')->id;
          $templateblockId = $router->input('templateblock')->id;

          return Widgetnode::whereTemplateblockId($templateblockId)->wherePageId($pageId)->findOrFail($id);
      });

      $router->model('adtype', 'ZEDx\Models\Adtype');
      $router->model('template', 'ZEDx\Models\Template');
      $router->model('category', 'ZEDx\Models\Category');
      $router->model('country', 'ZEDx\Models\Country');
      $router->model('page', 'ZEDx\Models\Page');
      $router->model('menu', 'ZEDx\Models\Menu');
      $router->model('gateway', 'ZEDx\Models\Gateway');
      $router->model('themepartial', 'ZEDx\Models\Themepartial');
      $router->model('dashboardWidget', 'ZEDx\Models\Dashboardwidget');
      $router->model('searchfield', 'ZEDx\Models\SearchField');
      $router->model('selectfield', 'ZEDx\Models\SelectField');
      $router->model('subscription', 'ZEDx\Models\Subscription');
      $router->model('user', 'ZEDx\Models\User');

      parent::boot($router);
  }

  /**
   * Define the routes for the application.
   *
   * @param  \Illuminate\Routing\Router  $router
   *
   * @return void
   */
  public function map(Router $router)
  {
      $router->group(['namespace' => $this->namespace], function ($router) {
          require core_src_path('Http/routes.php');
      });
  }
}
