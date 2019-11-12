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

namespace Galactium\Space\Identifier;


class Parser
{
    /**
     * @var array
     */
    protected $parsed = [];

    /**
     * @param  string $key
     * @return array
     */
    public function parse(string $key)
    {
        if (isset($this->parsed[$key])) {
            return $this->parsed[$key];
        }

        $parsed = $this->parseSegments($key);

        return $this->parsed[$key] = $parsed;
    }

    /**
     * @param $key
     * @return array
     */
    protected function parseSegments($key)
    {
        [$module, $segments] = explode('::', $key);

        $items = explode('.', $segments, 3);
        $params = [];
        if (count($items) > 2) {
            $params = explode('.', array_splice($items, -1)[0]);
        }
        return array_merge([$module], $items, [$params]);
    }


}