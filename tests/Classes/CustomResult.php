<?php

namespace Tekord\Result\Tests\Classes;

use DomainException;
use Tekord\Result\Result;

/**
 * @author Cyrill Tekord
 */
class CustomResult extends Result {
    static $panicExceptionClass = DomainException::class;
}
