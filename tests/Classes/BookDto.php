<?php

namespace Tekord\Result\Tests\Classes;

/**
 * @author Cyrill Tekord
 */
class BookDto {
    /** @var int */
    public $id;

    /** @var string */
    public $title;

    /** @var \DateTime */
    public $publishedAt;

    /** @var string[] */
    public $authors;
}
