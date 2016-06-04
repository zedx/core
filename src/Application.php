<?php

namespace ZEDx;

use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Get the path to the core directory.
     *
     * @return string
     */
    public function corePath()
    {
        return realpath(__DIR__.DIRECTORY_SEPARATOR.'..');
    }

    /**
     * Get the path to the core "src" directory.
     *
     * @return string
     */
    public function coreSrcPath()
    {
        return $this->corePath().DIRECTORY_SEPARATOR.'src';
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @return string
     */
    public function path()
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'app';
    }

    /**
     * Get the base path of the Laravel installation.
     *
     * @return string
     */
    public function basePath()
    {
        return $this->basePath;
    }

    /**
     * Get the path to the bootstrap directory.
     *
     * @return string
     */
    public function bootstrapPath()
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'bootstrap';
    }

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    public function configPath()
    {
        return $this->corePath().DIRECTORY_SEPARATOR.'config';
    }

    /**
     * Get the path to the database directory.
     *
     * @return string
     */
    public function databasePath()
    {
        return $this->databasePath ?: $this->corePath().DIRECTORY_SEPARATOR.'database';
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->corePath().DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'lang';
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath()
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'public';
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath()
    {
        return $this->storagePath ?: $this->basePath.DIRECTORY_SEPARATOR.'storage';
    }

    /**
     * Get the application namespace.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace = 'ZEDx\\';
    }
}
