<?php

namespace Josh\Json\Database\Driver;

use Josh\Json\Database\Model;

interface DriverInterface
{
    /**
     * @param $filename
     * @return array
     */
    public function getItems($filename);

    /**
     * @param Model $model
     * @return void
     */
    public function setItems(Model $model);
}