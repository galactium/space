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


namespace Galactium\Space\Seo\Metas;


use Galactium\Space\Support\BaseCollection;

class Collection extends BaseCollection
{
    /**
     * @param MetaInterface $meta
     * @return Collection
     */
    public function add(MetaInterface $meta): Collection
    {
        $this->offsetSet($meta->key(), $meta);
        return $this;
    }

    /**
     * @param string $key
     * @return Collection
     */
    public function delete(string $key): Collection
    {
        $this->offsetUnset($key);
        return $this;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->setData([]);
        $this->rewind();

        return $this;
    }
}