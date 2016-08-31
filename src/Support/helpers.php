<?php

use ZEDx\Models\Country;
use ZEDx\Models\Menu;
use ZEDx\Models\Page;
use ZEDx\Models\Setting;
use ZEDx\Utils\Translation\Translator;

if (!function_exists('setting')) {
    /**
     * Get Settings.
     *
     * @param string $key
     *
     * @return string/Setting
     */
    function setting($key = null)
    {
        static $setting = null;

        if (!$setting) {
            $setting = Setting::firstOrFail();
        }

        if (!$key) {
            return $setting;
        }

        return $setting->{$key};
    }
}

if (!function_exists('zedx_cache')) {
    /**
     * Cache a closure forever.
     *
     * @param string   $key
     * @param \Closure $next
     *
     * @return \Closure
     */
    function zedx_cache($key, Closure $next)
    {
        if (!env('APP_CACHE', true)) {
            return $next();
        }

        return Cache::rememberForever("zx-{$key}", function () use ($next) {
            \Log::info('yes');

            return $next();
        });
    }
}

if (!function_exists('elixir_frontend')) {
    /**
     * Get the path to a versioned Elixir frontend file.
     *
     * @param string $file
     * @param string $buildDirectory
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    function elixir_frontend($file, $buildDirectory = 'build')
    {
        $file = 'frontend/'.$file;

        return elixir($file, $buildDirectory);
    }
}

if (!function_exists('elixir_backend')) {
    /**
     * Get the path to a versioned Elixir backend file.
     *
     * @param string $file
     * @param string $buildDirectory
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    function elixir_backend($file, $buildDirectory = 'build')
    {
        $file = 'backend/'.$file;

        return elixir($file, $buildDirectory);
    }
}

if (!function_exists('make_array')) {
    /**
     * Make array.
     *
     * @param string/array $data
     *
     * @return array
     */
    function make_array($data)
    {
        return is_array($data) ? $data : [$data];
    }
}

if (!function_exists('view_widget')) {
    /**
     * Get the evaluated view widget contents for the given view.
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function view_widget($view = null, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }
}

if (!function_exists('view_module')) {
    /**
     * Get the evaluated view module contents for the given view.
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function view_module($view = null, $data = [], $mergeData = [])
    {
        $view = $view ? 'module_'.$view : $view;

        return view($view, $data, $mergeData);
    }
}

if (!function_exists('view_backend')) {
    /**
     * Get the evaluated view backend contents for the given view.
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function view_backend($view = null, $data = [], $mergeData = [])
    {
        $view = $view ? 'backend.'.$view : $view;

        return view($view, $data, $mergeData);
    }
}

if (!function_exists('merge_trans')) {
    /**
     * Merge default with the custom translations.
     *
     * @return string
     */
    function merge_trans($type, array $trans = [])
    {
        return Translator::merge($type, $trans);
    }
}

if (!function_exists('core_path')) {
    /**
     * Get the path to the zedx core directory.
     *
     * @return string
     */
    function core_path($path = '')
    {
        $root = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..');

        return $path ? $root.DIRECTORY_SEPARATOR.$path : $root;
    }
}

if (!function_exists('core_src_path')) {
    /**
     * Get the path to the zedx core directory.
     *
     * @return string
     */
    function core_src_path($path = '')
    {
        $root = core_path('src');

        return $path ? $root.DIRECTORY_SEPARATOR.$path : $root;
    }
}

if (!function_exists('public_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool   $secure
     *
     * @return string
     */
    function public_asset($path, $secure = null)
    {
        $path = starts_with($path, '/') ? $path : '/'.$path;

        return app('url')->asset('/public'.$path, $secure);
    }
}

if (!function_exists('rchmod')) {
    /**
     * Recursive chmod.
     *
     * @param string $path
     * @param int    $filePerm
     * @param int    $dirPerm
     *
     * @return bool
     */
    function rchmod($path, $filePerm = 0644, $dirPerm = 0755)
    {
        if (!file_exists($path)) {
            return false;
        }

        if (is_file($path)) {
            chmod($path, $filePerm);
        } elseif (is_dir($path)) {
            $foldersAndFiles = scandir($path);

            $entries = array_slice($foldersAndFiles, 2);

            foreach ($entries as $entry) {
                rchmod($path.DIRECTORY_SEPARATOR.$entry, $filePerm, $dirPerm);
            }
            chmod($path, $dirPerm);
        }

        return true;
    }
}

if (!function_exists('env_replace')) {
    /**
     * Replace an environement content.
     *
     * @param string $keyToRepace
     * @param string $value
     *
     * @return string
     */
    function env_replace($keyToRepace, $value)
    {
        $keyExist = false;
        $value = str_contains($value, ' ') ? '"'.$value.'"' : $value;

        $envPath = base_path('.env');
        $envContent = file($envPath);

        $newContent = array_map(function ($content) use ($keyToRepace, $value) {
            $element = explode('=', $content);
            $key = $element[0];

            if ($keyToRepace == $key) {
                $keyExist = true;

                return $key.'='.$value."\n";
            } else {
                return $content;
            }
        }, $envContent);

        if (!$keyExist) {
            array_push($newContent, $keyToRepace.'='.$value."\n");
        }

        file_put_contents($envPath, implode('', $newContent));
        putenv($keyToRepace.'='.$value);
    }
}

if (!function_exists('is_numeric_array')) {
    /**
     * Check whether an array is composed only with numeric values or not.
     *
     * @param array $array
     *
     * @return bool
     */
    function is_numeric_array($array)
    {
        foreach ($array as $element) {
            if (!is_numeric($element)) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('image_route')) {
    /**
     * Get image route.
     *
     * @param string $type
     * @param string $image
     *
     * @return string
     */
    function image_route($type, $image)
    {
        if ($image) {
            $image = '/'.$image;
        }

        return public_asset(config("zedx.images.{$type}.path").$image);
    }
}


if (!function_exists('getAdFields')) {
    /**
     * Get Ad fields.
     *
     * @param Ad $ad
     *
     * @return Collection
     */
    function getAdFields($ad)
    {
        $mergedFields = [];

        if (!$ad->has('fields')) {
            return [];
        }

        $fields = $ad->fields()->orderBy('is_price', 'desc')->with('select')->whereIsInAd(true)->get();

        foreach ($fields as $field) {
            $value = $field->type != 5 ? $field->pivot->value : $field->pivot->string;
            if (isset($mergedFields[$field->id])) {
                $oldValue = $mergedFields[$field->id];
                $mergedFields[$field->id] = is_array($oldValue) ? array_merge($oldValue, [$value]) : [$oldValue, $value];
            } else {
                $mergedFields[$field->id] = $value;
            }
        }

        return collect($mergedFields);
    }
}

if (!function_exists('getAdCurrency')) {
    /**
     * Get ad currency.
     *
     * @param Ad     $ad
     * @param string $unit
     *
     * @return string
     */
    function getAdCurrency($ad, $unit)
    {
        if ($unit != '{currency}') {
            return $unit;
        }

        $default = setting('default_ad_currency');

        $countryCode = $ad->geolocation->country;

        if (!$countryCode) {
            return $default;
        }

        $country = Country::whereCode($countryCode)->first();
        if ($country) {
            return $country->currency_symbole ?: $country->currency;
        }

        return $default;
    }
}

if (!function_exists('in_arrayi')) {
    /**
     * Insensitive in_array.
     *
     * @param string $needle
     * @param array  $haystack
     *
     * @return bool
     */
    function in_arrayi($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }
}

if (!function_exists('string_between')) {
    /**
     * Get string between two strings.
     *
     * @param string $string
     * @param string $start
     * @param string $end
     *
     * @return string
     */
    function string_between($string, $start, $end)
    {
        $string = ' '.$string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }

        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }
}

if (!function_exists('renderMenu')) {
    /**
     * Render Menu.
     *
     * @param string/Menu $groupName
     * @param array       $config
     *
     * @return void
     */
    function renderMenu($groupName, $config = [])
    {
        static $first = true;
        $render = '';

        if (is_string($groupName)) {
            $nested = Menu::whereGroupName($groupName)->orderBy('lft')->get();
            if (!$nested) {
                return;
            }
            $menus = $nested->toHierarchy();
        } else {
            $menus = $groupName;
        }

        foreach ($menus as $menu) {
            if ($menu->type == 'page') {
                $page = Page::find($menu->link);
                $url = $page ? route('page.show', $page->shortcut) : '#';
            } elseif ($menu->type == 'route') {
                $url = Route::has($menu->link) ? route($menu->link) : '#';
            } else {
                $url = starts_with($menu->link, '/') ? url($menu->link) : $menu->link;
            }

            $active = url()->current() == $url ? 'active' : '';
            $hasChildren = $menu->children()->count() > 0;
            $element = $first ? 'parent' : 'children';
            $attrType = $hasChildren ? 'withChildren' : 'withoutChildren';

            $li = str_replace('{active}', $active, array_get($config, $element.'.li.'.$attrType, 'class="{active}"'));
            $liChildren = array_get($config, $element.'.liChildren');
            $link = array_get($config, $element.'.link.'.$attrType);
            $ul = array_get($config, $element.'.ul');

            $caret = $hasChildren ? array_get($config, $element.'.angle', '<span class="caret"></span>') : '';

            $render .= '<li '.$li.'>'
            .'  <a href="'.$url.'" '.$link.'>'
            .'    <i class="'.$menu->icon.'"></i> '.$menu->name.' '.$caret
                .'  </a>';

            if ($hasChildren) {
                $first = false;
                $render .= '<ul '.$ul.'>';
                foreach ($menu->children as $child) {
                    $render .= renderMenu([$child], $config);
                }

                $render .= '</ul>';
            }
            $render .= '</li>';
        }

        return $render;
    }
}

if (!function_exists('renderNode')) {
    /**
     * Render Node.
     *
     * @param Node   $node
     * @param string $type
     *
     * @return void
     */
    function renderNode($node, $type)
    {
        switch ($type) {
            case 'menu':
                $label = '<span class="label label-info">'.trans('backend.menu.'.$node->type.'.'.$node->type).'</span>';
                $name = '<i class="'.$node->icon.'"></i> '.$node->name;
                $route = '#';
                $modal = 'data-toggle="modal" data-menu="'.e($node->toJson()).'" data-target="#confirmEditAction" data-title="'.$node->name.'"';
                break;
            default:
                $label = '';
                $name = $node->name;
                $route = route('zxadmin.'.$type.'.edit', $node->id);
                $modal = '';
                break;
        }

        $text = trans('backend.'.$type.'.deleted_'.$type);
        $message = trans('backend.'.$type.'.delete_'.$type.'_confirmation');

        echo '<li class="dd-item dd3-item" data-element-parent-action data-id="'.$node->id.'" data-parent-id="'.$node->parent_id.'">'
        .'<div class="dd-handle fa fa-arrows dd3-handle"></div>'
        .'<div class="dd3-content">'.$name
        .' <span class="pull-right">'.$label
        .'   <a href="'.$route.'" class="btn-edit-'.$type.' btn btn-xs btn-primary" '.$modal.'>'
        .'     <i class="fa fa-edit"></i> '.trans('backend.'.$type.'.edit')
        .'   </a>'
        .' <span><a href="#" class="btn btn-xs btn-danger" data-element-action data-element-action-text=\''.$text.'\' data-element-action-route = \''.route('zxadmin.'.$type.'.destroy', [$node->id]).'\' data-toggle="modal" data-target="#confirmDeleteAction" data-title="'.$node->name.'" data-message=\''.$message.'\'><i class="fa fa-remove"></i> '.trans('backend.'.$type.'.delete').'</a></span>'
            .' </span>'
            .'</div>';

        if ($node->children()->count() > 0) {
            echo '<ol class="dd-list">';
            foreach ($node->children as $child) {
                renderNode($child, $type);
            }

            echo '</ol>';
        }
        echo '</li>';
    }
}

if (!function_exists('constructRouteNames')) {
    /**
     * Construct routes.
     *
     * @param string $base
     *
     * @return array
     */
    function constructRouteNames($base)
    {
        return [
            'index'   => $base.'.index',
            'show'    => $base.'.show',
            'create'  => $base.'.create',
            'store'   => $base.'.store',
            'edit'    => $base.'.edit',
            'update'  => $base.'.update',
            'destroy' => $base.'.destroy',
        ];
    }
}
