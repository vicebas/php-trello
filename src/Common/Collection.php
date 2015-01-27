<?php

namespace Trello\Common;

class Collection implements \IteratorAggregate , \ArrayAccess , \Countable
{
    protected $items = [];

    /**
     * @param array $items
     */
    function __construct($items = [])
    {
        $this->setArray($items);
    }

    /**
     * @param $offset
     * @param $value
     */
    public function set($offset, $value)
    {
        $this->offsetSet($offset, $value);
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function get($offset)
    {
        return $this->offsetGet($offset);
    }

    /**
     * @param $value
     */
    public function append($value)
    {
        $this->items[] = $value;
    }

    /**
     * @param int $flags
     */
    public function sort($flags = SORT_REGULAR)
    {
        sort($this->items, $flags);
    }

    /**
     * @param int $flags
     */
    public function rsort($flags = SORT_REGULAR)
    {
        rsort($this->items, $flags);
    }

    /**
     * @param callable $callback
     */
    public function usort(callable $callback)
    {
        usort($this->items, $callback);
    }

    /**
     * @param int $flags
     */
    public function asort($flags = SORT_REGULAR)
    {
        asort($this->items, $flags);
    }

    /**
     * @param int $flags
     */
    public function arsort($flags = SORT_REGULAR)
    {
        arsort($this->items, $flags);
    }

    /**
     * @param callable $callback
     */
    public function uasort(callable $callback)
    {
        uasort($this->items, $callback);
    }

    /**
     * @param int $flags
     */
    public function ksort($flags = SORT_REGULAR)
    {
        ksort($this->items, $flags);
    }

    /**
     * @param int $flags
     */
    public function krsort($flags = SORT_REGULAR)
    {
        krsort($this->items, $flags);
    }

    /**
     * @param callable $callable
     */
    public function uksort(callable $callable)
    {
        uksort($this->items, $callable);
    }

    /**
     * @param callable $callback
     * @param int $flag
     * @return Collection
     */
    public function filter(callable $callback = null, $flag = 0)
    {
        $array = array_filter($this->items, $callback, $flag);

        return new Collection($array);
    }

    /**
     * @param callable $callback
     * @return Collection
     */
    public function map(callable $callback)
    {
        $array = array_map($callback, $this->items);

        return new Collection($array);
    }

    /**
     * @param callable $callback
     * @param null $userdata
     */
    public function walk(callable $callback, $userdata = null)
    {
        array_walk($this->items, $callback, $userdata);
    }

    /**
     * @param $items
     */
    public function setArray($items)
    {
        if ($items instanceof Collection) {
            $this->items = $items->toArray();
        } elseif (is_array($items)) {
            $this->items = $items;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
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
        if (isset($offset)) {
            $this->items[$offset] = $value;
        } else {
            $this->items[] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @param $name
     * @return mixed
     */
    function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param $name
     * @param $value
     */
    function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }
}
