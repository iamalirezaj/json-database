<?php

namespace Josh\Json\Database\Driver;

use Josh\Json\Database\Model;
use Josh\Json\Database\Collection;

/**
 * @method array getItems($filename)
 * @method Collection setItems(Model $model)
 */
class Driver
{
    /**
     * Database filepath
     *
     * @var string|null
     */
    protected $filename = null;

    /**
     * Collection instance
     *
     * @var Collection
     */
    protected $collection;

    /**
     * Database constructor.
     *
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->collection = Collection::make($this->getItems($filename));
    }

    /**
     * Get collection instance
     *
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Add model to datas
     *
     * @param Model $model
     * @return mixed
     */
    protected function addModelToDatas(Model $model)
    {
        $items = $this->getItems($this->getDatabaseFilePath());

        $items[$model->getKey()][] = $model->getAttributes();

        return json_encode($items, JSON_PRETTY_PRINT);
    }

    /**
     * Get database filepath
     *
     * @return null
     */
    public function getDatabaseFilePath()
    {
        return $this->filename;
    }
}