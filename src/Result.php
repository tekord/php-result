<?php

namespace Tekord\Result;

/**
 * @author Cyrill Tekord
 */
abstract class Result {
    public static $successInstanceClass = Success::class;
    public static $failInstanceClass = Fail::class;

    public static $panicExceptionClass = PanicException::class;

    private function __construct() {
    }

    /**
     * Creates a succeeded result instance.
     *
     * @template T
     * @psalm-template T
     *
     * @param T $value
     *
     * @return Success<T>
     */
    public static function success($value = null) {
        return new static::$successInstanceClass($value, static::class);
    }

    /**
     * Creates a failed result instance.
     *
     * @template T
     *
     * @param T $value
     *
     * @return Fail<T>
     */
    public static function fail($value = null) {
        return new static::$failInstanceClass($value, static::class);
    }
}
