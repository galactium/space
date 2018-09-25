<?php
/**
 * Copyright (c) 2018. Grigoriy Ivanov
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
 * Galactium @ 2018
 * @author Grigoriy Ivanov
 */


namespace Galactium\Space\Seo\Metas;


use Phalcon\Tag;

class Title implements MetaInterface
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * Titile constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Title
     */
    public function setTitle(string $title): Title
    {
        $this->title = strip_tags($title);
        return $this;
    }

    public function key(): string
    {
        return 'title';
    }

    public function render(): string
    {
        return Tag::tagHtml('title') . $this->title . Tag::tagHtmlClose('title');
    }


}