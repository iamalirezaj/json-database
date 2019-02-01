<?php

namespace Josh\Json\Database;

use Josh\Json\Database\Driver\Driver;

class Database
{
    /**
     * @var Driver
     */
    protected static $driver;

    /**
     * @return Driver
     */
    public static function getDriver()
    {
        return self::$driver;
    }

    /**
     * @param Driver $driver
     */
    public static function setDriver($driver)
    {
        self::$driver = $driver;
    }

    /**
     * @param $key
     * @return Collection
     */
    public static function from($key)
    {
        return self::$driver->getCollection()->from($key);
    }
}