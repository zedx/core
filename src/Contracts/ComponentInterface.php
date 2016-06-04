<?php

namespace ZEDx\Contracts;

interface ComponentInterface
{
    /**
     * Get all components.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get components as components collection instance.
     *
     * @return \Illuminate\Support\Collection
     */
    public function toCollection();

    /**
     * Get count from all components.
     *
     * @return int
     */
    public function count();

    /**
     * Find a specific component.
     *
     * @param $name
     *
     * @return mixed
     */
    public function find($name);

    /**
     * Find a specific component. If there return that, otherwise throw exception.
     *
     * @param $name
     *
     * @return mixed
     */
    public function findOrFail($name);
}
