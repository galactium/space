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

namespace Galactium\Space\Events;


use Galactium\Space\Di\ServiceProvider;
use Phalcon\DiInterface;

abstract class EventProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        foreach ($this->listeners as $type => $listener) {
            $di->get('eventsManager')->attach($type, new $listener($di));
        }
    }


}