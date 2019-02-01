<?php

namespace Josh\Json\Database;

use Countable;
use Josh\Json\Database\Support\Arr;
use Josh\Json\Database\Support\Inflect;
use Josh\Json\Database\Support\Jsonable;
use Josh\Json\Database\Support\Arrayable;

class Model implements Arrayable, Jsonable, Countable
{
    /**
     * Model items collection
     *
     * @var Collection
     */
    protected $collection;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model key in databse
     *
     * @var string
     */
    protected $key = null;

    /**
     * set hidden fields
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Model data attributes
     *
     * @var array
     */
    private $attributes = [];

    /**
     * Model constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        if (! empty($attributes)){
            $this->fill($attributes);
        }

        $this->key = ( is_null($this->key) ? $this->getClassPlurarName(): $this->key );

        $this->collection = $this->getDriver()->getCollection()->from($this->key);

        return $this;
    }

    /**
     * Get database driver
     *
     * @return Driver\Driver
     */
    protected function getDriver()
    {
        return Database::getDriver();
    }

    /**
     * Set model key
     *
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get model key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Find a object from collection
     *
     * @param $key
     * @return $this
     */
    public function find($key)
    {
        return $this->fill($this->get($key));
    }

    /**
     * Create an object
     *
     * @param $attributes
     * @return $this
     */
    public function create($attributes)
    {
        $this->fill($attributes)->insertTheAttributesToObject();

        return $this;
    }

    /**
     * Insert attribtes as array in collection
     *
     * @return void
     */
    public function insertTheAttributesToObject()
    {
        $this->getDriver()->setItems($this);
    }

    /**
     * Find a object from collection
     *
     * @param $primaryKey
     * @param $key
     * @return $this
     */
    public function findBy($primaryKey, $key)
    {
        return $this->fill($this->getBy($primaryKey, $key));
    }

    /**
     * Set attributes to model
     *
     * @param array|Collection $attributes
     * @return $this
     */
    public function fill($attributes = null)
    {
        if ($attributes instanceof Collection){
            $this->attributes = $this->hideFileds($attributes->getitems());
        }

        if (is_array($attributes)){
            $this->attributes = $this->hideFileds($attributes);
        }

        foreach ($this->attributes as $key => $attribute){
            $this->{$key} = $attribute;
        }

        return $this;
    }

    /**
     * Convert results to array
     *
     * @return array
     */
    public function toArray()
    {
        return (array)$this->attributes;
    }

    /**
     * Check existed
     *
     * @return bool
     */
    public function exists()
    {
        return count($this->attributes) > 0;
    }

    /**
     * Find all objects from collection
     *
     * @return Collection
     */
    public function all()
    {
        $items = $this->collection->all();

        return new Collection($this->newCollections($items));
    }

    /**
     * Make a new collection
     *
     * @param $attributes
     * @return Model
     */
    private function newCollection($attributes)
    {
        return new self($this->hideFileds($attributes));
    }

    /**
     * Make all from items
     *
     * @param array $items
     * @return array
     */
    private function newCollections($items)
    {
        $allItems = [];

        foreach ($items as $item){
            $allItems[] = $this->newCollection($item);
        }

        return $allItems;
    }

    /**
     * Count the collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * Find the first object from database
     *
     * @return $this
     */
    public function first()
    {
        $collection = $this->collection->first();

        return $this->fill($collection);
    }

    /**
     * Get attributes
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get an attribute
     *
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        return $this->attributes[$key];
    }

    /**
     * Get the object
     *
     * @param null $key
     * @return Collection
     */
    protected function get($key = null)
    {
        return $this->getBy($this->primaryKey, $key);
    }

    /**
     * Get the object by
     *
     * @param $primaryKey
     * @param null $key
     * @return Collection
     */
    protected function getBy($primaryKey, $key = null)
    {
        return $this->collection->findBy($primaryKey, $key);
    }

    /**
     * Convert attributes to json
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->attributes, $options);
    }

    /**
     * Hide fields
     *
     * @param $attributes
     * @return mixed
     */
    private function hideFileds($attributes)
    {
        Arr::forget($attributes, $this->hidden);

        return $attributes;
    }

    /**
     * Get class short name as key
     *
     * @return null|string|string[]
     */
    private function getClassPlurarName()
    {
        try {

            $reflection = new \ReflectionClass($this);
            $singular = strtolower($reflection->getShortName());

            return Inflect::pluralize($singular);

        } catch (\ReflectionException $e) {
        }

        return null;
    }
}