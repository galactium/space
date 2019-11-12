<?php
/**
 * Copyright (c) 2020. Grigoriy Ivanov
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

/**
 * Galactium @ 2020
 * @author Grigoriy Ivanov
 */


namespace Galactium\Space\Support;


use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;

class BaseCollection implements JsonSerializable, ArrayAccess, Countable, Iterator
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Json constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return BaseCollection
     */
    public function setData(array $data): BaseCollection
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return int
     */
    public final function count()
    {
        return count($this->data);
    }

    public final function next()
    {
        next($this->data);
    }

    public final function key()
    {
        return key($this->data);
    }

    public final function rewind()
    {
        reset($this->data);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return key($this->data) !== null;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * @param $key
     * @return mixed
     */
    public final function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public final function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public final function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public final function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public final function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public final function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param $key
     */
    public final function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @param mixed $offset
     */
    public final function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}