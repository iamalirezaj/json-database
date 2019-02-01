<?php

namespace Josh\Json\Database\Driver;

use Josh\Json\Database\Model;

class JsonDriver extends Driver implements DriverInterface
{
    /**
     * Get json data from filepath
     *
     * @param $filename
     * @return array
     */
    public function getItems($filename)
    {
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * Set items to database
     *
     * @param Model $model
     * @return int|boolean
     */
    public function setItems(Model $model)
    {
        $datas = $this->addModelToDatas($model);

        return file_put_contents($this->filename, $datas);
    }
}