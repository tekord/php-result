<?php

namespace Tekord\Result;

use Tekord\Result\Concerns\ResultMethods;

/**
 * @template OkType
 * @template ErrorType
 *
 * @property-read OkType $ok
 * @property-read ErrorType $error
 *
 * @implements ResultInterface<OkType, ErrorType>
 *
 * @mixin ResultMethods<OkType, ErrorType>
 *
 * @author Cyrill Tekord
 */
class Result implements ResultInterface {
    use ResultMethods;

    protected const DISCRIMINANT_OK = 0;
    protected const DISCRIMINANT_ERROR = 1;

    /** @var int */
    protected $discriminant;

    /** @var OkType|ErrorType */
    protected $value;

    public static $panicExceptionClass = PanicException::class;

    /**
     * @param \Throwable|mixed $error
     *
     * @return never-returns
     *
     * @throws \Throwable
     */
    protected function panic($error) {
        if ($error instanceof \Throwable)
            throw $error;

        throw new static::$panicExceptionClass($error);
    }

    public function getOk() {
        return $this->isOk() ? $this->value : null;
    }

    public function getError() {
        return $this->isFailed() ? $this->value : null;
    }

    public function isFailed() {
        return $this->discriminant == static::DISCRIMINANT_ERROR;
    }

    public function isOk() {
        return $this->discriminant == static::DISCRIMINANT_OK;
    }

    protected function __construct() {
    }

    /**
     * Creates a succeeded result instance.
     *
     * @param OkType $value
     *
     * @return static<OkType, void>
     */
    public static function success($value = null) {
        $result = new static();

        $result->discriminant = static::DISCRIMINANT_OK;
        $result->value = $value;

        return $result;
    }

    /**
     * Creates a failed result instance.
     *
     * @param ErrorType $value
     *
     * @return static<void, ErrorType>
     */
    public static function fail($value = null) {
        $result = new static();

        $result->discriminant = static::DISCRIMINANT_ERROR;
        $result->value = $value;

        return $result;
    }
}
