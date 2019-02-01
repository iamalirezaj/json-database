<?php

use Josh\Json\Database\Database;
use Josh\Json\Database\Driver\JsonDriver;
use Josh\Json\Database\Model;

class ModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * TestCase constructor.
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        Database::setDriver(new JsonDriver(base_path("database.json")));

        $this->testRemoveDatasManually();
    }

    /**
     * Test find a object from model
     *
     * @return void
     */
    public function testFind()
    {
        $test = (new TestModel())->first();

        $this->assertArrayHasKey("id", $test->getAttributes());
        $this->assertArrayHasKey("name", $test->getAttributes());
        $this->assertEquals("1", $test->id);
        $this->assertEquals("test 1", $test->name);
        $this->assertEquals("test 1", $test->getAttribute("name"));
        $this->assertEquals("1", $test->getAttribute("id"));
        $this->assertNotEmpty($test->getAttributes());
    }

    /**
     * Create an object and insert it into database file
     *
     * @return void
     */
    public function testCreate()
    {
        $test = new TestModel();

        $test->create([ "id" => 2, "name" => "test 2" ]);

        $this->assertArrayHasKey("id", $test->getAttributes());
        $this->assertArrayHasKey("name", $test->getAttributes());
        $this->assertEquals("2", $test->id);
        $this->assertEquals("test 2", $test->name);
        $this->assertEquals("test 2", $test->getAttribute("name"));
        $this->assertEquals("2", $test->getAttribute("id"));
        $this->assertNotEmpty($test->getAttributes());
    }

    /**
     * Removed datas from database manually
     *
     * @return void
     */
    public function testRemoveDatasManually()
    {
        $data = json_encode([
            "tests" => [
                [
                    "id" => 1,
                    "name" => "test 1"
                ]
            ]
        ], JSON_PRETTY_PRINT);

        file_put_contents(base_path("database.json"), $data);
    }
}

class TestModel extends Model {

    protected $key = "tests";
}
