<?php

namespace NextApps\UniqueCodes;

use Countable;
use IteratorAggregate;

class UniqueCodeCollection implements IteratorAggregate
{
    /**
     * The source that is used to generate unique codes.
     *
     * @var callable
     */
    public $source;

    /**
     * Create a new collection instance.
     *
     * @param callable $source
     *
     * @return void
     */
    public function __construct(callable $source, $a = null)
    {
        $this->source = $source;
        if ($a !== null) {
            // var_dump($a);
            // die();
        }
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
     * @return static
     */
    public function filter(callable $callback = null)
    {
        if (is_null($callback)) {
            $callback = function ($value) {
                return (bool) $value;
            };
        }

        return new static(function () use ($callback) {
            foreach ($this as $key => $value) {
                if ($callback($value, $key)) {
                    yield $key => $value;
                }
            }
        }, 'test');
    }

    /**
     * Execute a callback over each item.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Get all codes in the collection.
     *
     * @return array
     */
    public function all()
    {
        return iterator_to_array($this->getIterator());
    }

    /**
     * Get the iterator.
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        $source = $this->source;

        return $source();
    }
}
