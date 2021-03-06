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

/**
 * Inspired by illuminate/support package.
 * @see https://github.com/illuminate/support
 */

namespace Galactium\Space\Helpers;

use ArrayAccess;
use Phalcon\Mvc\ModelInterface;

if (!function_exists('Galactium\Space\Helpers\arrayGet')) {
    /**
     * @param array|ArrayAccess $array
     * @param string $key
     * @param null $default
     * @return mixed
     */
    function arrayGet($array, ?string $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (arrayHas($array, $key)) {
            return $array[$key];
        }
        if (strpos($key, '.') === false) {
            return $array[$key] ?? value($default);
        }
        foreach (explode('.', $key) as $segment) {
            if ((is_array($array) || $array instanceof ArrayAccess) && arrayHas($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }
        return $array;
    }
}

if (!function_exists('Galactium\Space\Helpers\arrayPluck')) {
    /**
     * @param $array
     * @param string $value
     * @param string|null $key
     * @return array
     */
    function arrayPluck($array, string $value, string $key = null): array
    {
        $results = [];

        $value = is_string($value) ? explode('.', $value) : $value;

        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);

        foreach ($array as $item) {
            $itemValue = dataGet($item, $value);
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = dataGet($item, $key);
                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string)$itemKey;
                }
                $results[$itemKey] = $itemValue;
            }
        }
        return $results;
    }
}

if (!function_exists('Galactium\Space\Helpers\arrayHas')) {
    /**
     * @param array|ArrayAccess $array
     * @param $keys
     * @return bool
     */
    function arrayHas($array, $keys): bool
    {
        if (is_null($keys)) {
            return false;
        }
        $keys = (array)$keys;
        if (!$array) {
            return false;
        }
        if ($keys === []) {
            return false;
        }
        foreach ($keys as $key) {
            $subKeyArray = $array;
            if (arrayExists($array, $key)) {
                continue;
            }
            foreach (explode('.', $key) as $segment) {
                if (arrayExists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }
        return true;

    }
}
if (!function_exists('Galactium\Space\Helpers\arrayExists')) {
    /**
     * @param array|ArrayAccess $array
     * @param string|int $key
     * @return bool
     */
    function arrayExists($array, $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }
}

if (!function_exists('Galactium\Space\Helpers\arraySet')) {
    /**
     * @param array $array
     * @param string|array $key
     * @param mixed $value
     * @return array
     */
    function arraySet(array &$array, $key, $value): array
    {
        if (is_null($key)) {
            return $array = $value;
        }

        if (is_array($key) && is_array($value)) {
            foreach ($key as $index => $item) {
                arraySet($array, $item, $value[$index]);
            }
        } else {
            $keys = explode('.', $key);

            while (count($keys) > 1) {
                $key = array_shift($keys);

                if (!isset($array[$key]) || !is_array($array[$key])) {
                    $array[$key] = [];
                }
                $array = &$array[$key];
            }

            $array[array_shift($keys)] = $value;
        }

        return $array;
    }
}
if (!function_exists('Galactium\Space\Helpers\arrayFlatten')) {
    /**
     * @param array $array
     * @return array
     */
    function arrayFlatten(array $array): array
    {
        $result = [];

        array_walk_recursive($array, function ($a) use (&$result) {
            $result[] = $a;
        });

        return $result;
    }
}

if (!function_exists('Galactium\Space\Helpers\dataGet')) {
    /**
     * @param mixed $target
     * @param string|int $key
     * @param null $default
     * @return mixed
     */
    function dataGet($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (!is_null($segment = array_shift($key))) {
            if ((is_array($target) || $target instanceof ArrayAccess) && arrayHas($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && $target instanceof ModelInterface) {
                $target = $target->readAttribute($segment);
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }
        return $target;
    }
}

if (!function_exists('Galactium\Space\Helpers\arrayDelete')) {
    /**
     * @param array $array
     * @param $keys
     */
    function arrayDelete(array &$array, $keys)
    {
        $original = &$array;
        $keys = (array)$keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {

            if (arrayHas($array, $key)) {
                unset($array[$key]);
                continue;
            }

            $parts = explode('.', $key);

            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }
}

