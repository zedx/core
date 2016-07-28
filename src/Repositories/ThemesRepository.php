<?php

namespace ZEDx\Repositories;

use Artisan;
use File;
use ZEDx\Models\Template;
use ZEDx\Models\Themepartial;
use ZEDx\Utils\TemplateHelper;

class ThemesRepository
{
    /**
     * @var string
     */
    protected $type = '';

    public function frontend()
    {
        $this->type = 'frontend';

        return $this;
    }

    public function backend()
    {
        $this->type = 'backend';

        return $this;
    }

    /**
     * Get all themes.
     *
     * @return Collection
     */
    public function all()
    {
        $themes = [];
        $paths = File::directories(base_path('themes'));
        foreach ($paths as $path) {
            $name = basename($path);
            $themes[$name] = [
                'is_active' => $this->isActive($name),
                'manifest'  => $this->getManifest($name),
            ];
        }

        return collect($themes);
    }

    /**
     * Check if given theme exists.
     *
     * @param string $theme
     *
     * @return bool
     */
    public function has($theme)
    {
        return $this->exists($theme);
    }

    /**
     * Check if given theme exists.
     *
     * @param string $theme
     *
     * @return bool
     */
    public function exists($theme)
    {
        $themePath = 'themes'.DIRECTORY_SEPARATOR.$theme;

        return File::isDirectory(base_path($themePath));
    }

    /**
     * Get theme JSON content as an array.
     *
     * @param string $theme
     *
     * @return array|mixed
     */
    public function getManifest($theme = null)
    {
        $default = [];
        if ($theme == null) {
            $theme = $this->getActive();
        }

        if (!$this->exists($theme)) {
            return $default;
        }

        $path = base_path('themes/'.$theme.'/zedx.json');

        if (File::exists($path)) {
            $contents = File::get($path);

            return json_decode($contents, true);
        } else {
            $message = "Theme [{$theme}] must have a valid zedx.json manifest file.";
            throw new \Exception($message);
        }
    }

    /**
     * Set theme manifest JSON content property value.
     *
     * @param string $theme
     * @param array  $content
     *
     * @return int
     */
    public function setManifest($theme, array $content)
    {
        $content = json_encode($content, JSON_PRETTY_PRINT);
        $path = base_path('themes/'.$theme.'/zedx.json');

        return File::put($path, $content);
    }

    /**
     * Get a theme manifest property value.
     *
     * @param string      $property
     * @param null|string $default
     *
     * @return mixed
     */
    public function getProperty($property, $default = null)
    {
        list($theme, $key) = explode('::', $property);

        return array_get($this->getManifest($theme), $key, $default);
    }

    /**
     * Set a theme manifest property value.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return bool
     */
    public function setProperty($property, $value)
    {
        list($theme, $key) = explode('::', $property);
        $content = $this->getManifest($theme);

        if (empty($content)) {
            return false;
        }

        $content[$key] = $value;

        return $this->setManifest($theme, $content);
    }

    /**
     * Get theme slug.
     *
     * @param string $theme
     *
     * @return string
     */
    public function getSlug($theme = '')
    {
        return $this->getProperty($theme.'::slug');
    }

    /**
     * Get theme name.
     *
     * @param string $theme
     *
     * @return string
     */
    public function getName($theme = '')
    {
        return $this->getProperty($theme.'::name');
    }

    /**
     * Get theme author.
     *
     * @param string $theme
     *
     * @return string
     */
    public function getAuthor($theme = '')
    {
        return $this->getProperty($theme.'::author');
    }

    /**
     * Get theme description.
     *
     * @param string $theme
     *
     * @return string
     */
    public function getDescription($theme = '')
    {
        return $this->getProperty($theme.'::description');
    }

    /**
     * Get theme version.
     *
     * @param string $theme
     *
     * @return string
     */
    public function getVersion($theme = '')
    {
        return $this->getProperty($theme.'::version');
    }

    /**
     * Get active theme.
     *
     * @return string
     */
    public function getActive()
    {
        return env('APP_FRONTEND_THEME');
    }

    /**
     * Get a theme file path.
     *
     * This function checks if the user has a custom file or not,
     * if so, it returns the path to the customized file
     * otherwise it returns a path to the original.
     *
     * @return string
     */
    public function getFilePath($additionalPath = null)
    {
        $path = $additionalPath ?: '';

        $defaultThemePath = base_path('themes/'.$this->getActive().'/views/'.$path);
        $userThemePath = base_path('resources/views/frontend/'.$path);

        if (File::exists($userThemePath)) {
            return $userThemePath;
        }

        return $defaultThemePath;
    }

    /**
     * Check if given theme is the active one.
     *
     * @param string $theme
     *
     * @return bool
     */
    public function isActive($theme)
    {
        return $theme == $this->getActive();
    }

    /**
     * set a new active Theme.
     *
     * @param string $theme
     *
     * @return bool
     */
    public function setActive($theme)
    {
        if (!$this->exists($theme)) {
            throw new Exception("Theme $theme doesn't exist!");
        }

        umask(0);

        $this->cleanActiveTheme();

        $themePath = base_path('themes'.DIRECTORY_SEPARATOR.$theme);

        @symlink($themePath.'/task.js', base_path('tasks/frontend.js'));
        @symlink($themePath.'/widgets', base_path('widgets/Frontend/Theme'));

        env_replace('APP_FRONTEND_THEME', $theme);

        $this->saveThemePartials();

        if (Template::all()->isEmpty()) {
            TemplateHelper::saveTemplates($theme);
        }

        Artisan::call('theme:publish', ['--force' => true]);
    }

    /**
     * Clean Active theme, delete all symlinks.
     *
     * @return void
     */
    protected function cleanActiveTheme()
    {
        @unlink(base_path('tasks/frontend.js'));
        @unlink(base_path('widgets/Frontend/Theme'));
    }

    /**
     * Save theme partials.
     *
     * @return void
     */
    public function saveThemePartials()
    {
        $ids = [];
        $manifest = $this->getManifest();
        $partials = isset($manifest['partials']) ? $manifest['partials'] : [];

        foreach ($partials as $partial) {
            $ids[] = Themepartial::firstOrCreate([
                'name'  => $partial['file'],
                'title' => $partial['title'],
            ])->id;
        }

        $partialsToRemove = Themepartial::whereNotIn('id', $ids)->get();
        foreach ($partialsToRemove as $partial) {
            $partial->delete();
        }
    }
}
