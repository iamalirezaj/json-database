<?php

namespace Josh\Json\Database;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Josh\Json\Database\Support\Arr;
use Josh\Json\Database\Support\Jsonable;
use Josh\Json\Database\Support\Arrayable;

class Collection implements Countable, Arrayable, Jsonable, ArrayAccess, IteratorAggregate
{
    /**
     * Collection items
     *
     * @param array $items
     */
    protected $items = [];

    /**
     * Make the collection
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Make the collection
     *
     * @param array $items
     * @return Collection
     */
    public static function make(array $items = [])
    {
        return new Collection($items);
    }

    /**
     * Find the array
     *
     * @param $key
     * @return $this
     */
    public function find($key)
    {
        return $this->findBy("id", $key);
    }

    /**
     * Find the array by
     *
     * @param $primaryKey
     * @param $key
     * @return $this
     */
    public function findBy($primaryKey, $key)
    {
        if (empty($this->all())){

            $this->items = [];
        } else {

            foreach ($this->all() as $attribute){

                if(Arr::get($attribute, $primaryKey) == $key){

                    $this->items = $attribute;
                    break;
                } else {

                    $this->items = [];
                }
            }
        }

        return $this;
    }

    /**
     * Get all items
     *
     * @return array
     */
    public function all()
    {
        return $this->getItems();
    }

    /**
     * Count collection items
     *
     * @return int
     */
    public function count()
    {
        return count($this->getItems());
    }

    /**
     * Check key exists in items
     *
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        $items = ( is_null($this->{'from'}) ? $this->items : Arr::get($this->items, $this->{'from'}) );

        if (is_null($items)){
            return Arr::exists($this->items, $key);
        }

        return Arr::exists($items, $key);
    }

    /**
     * Get attribute item from collection
     *
     * @return $this
     */
    public function first()
    {
        $this->items = $this->getItem(0);

        return $this;
    }

    /**
     * Get from collection
     *
     * @param $key
     * @return mixed
     */
    public function get($key = null)
    {
        return ( is_null($key) ? $this->get($this->{'from'}) : $this->getItem($key) );
    }

    /**
     * Get attribute from collection
     *
     * @param $key
     * @return mixed
     */
    public function getItem($key)
    {
        if (is_int($key) || is_array($this->items)){
            return ( Arr::has($this->items, $key) ? Arr::get($this->items, $key) : []);
        }

        if ($this->items instanceof ArrayAccess){
            return ( $this->items->offsetExists($key) ? $this->items->offsetGet($key) : [] );
        }

        return $this->items;
    }

    /**
     * Get collection from
     *
     * @param $from
     * @return $this
     */
    public function from($from)
    {
        $this->{'from'} = $from;

        if (Arr::exists($this->items, $from)){
            $this->items = $this->items[$from];
        }

        return $this;
    }

    /**
     * @return null
     */
    public function getitems()
    {
        return $this->items;
    }

    /**
     * Get attribute from items
     *
     * @param $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        return $this->items[$attribute];
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}