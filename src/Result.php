<?php

namespace Tekord\Result;

/**
 * @template OkType
 * @template ErrorType
 *
 * @property-read OkType $ok
 * @property-read ErrorType $error
 *
 * @author Cyrill Tekord
 */
class Result {
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

    public function __get($name) {
        if ($name === 'ok') {
            /** @var OkType $returnValue */
            $returnValue = $this->getOk();
        } else if ($name === 'error') {
            /** @var ErrorType $returnValue */
            $returnValue = $this->getError();
        }
        else {
            throw new \Exception('Invalid property: ' . $name . '. Class ' . static::class . ' provides only "ok" and "error" properties');
        }

        return $returnValue;
    }

    /**
     * It is better to use the `unwrap` function instead of this one.
     *
     * @return OkType|null
     */
    public function getOk() {
        return $this->isOk() ? $this->value : null;
    }

    /**
     * @return ErrorType|null
     */
    public function getError() {
        return $this->isFailed() ? $this->value : null;
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

    /**
     * Indicates whether the result contains an error.
     *
     * @return bool
     */
    public function isFailed() {
        return $this->discriminant == static::DISCRIMINANT_ERROR;
    }

    /**
     * Indicates whether the result is OK and there is no error.
     *
     * @return bool
     */
    public function isOk() {
        return $this->discriminant == static::DISCRIMINANT_OK;
    }

    /**
     * Returns the contained OK value, or panics if there is an error.
     *
     * @return OkType
     */
    public function unwrap() {
        if ($this->isFailed())
            $this->panic($this->value);

        return $this->value;
    }

    /**
     * Returns the contained OK value, or the default value if there is an error.
     *
     * @template T
     *
     * @param T $default
     *
     * @return OkType|T
     */
    public function unwrapOrDefault($default) {
        if ($this->isFailed())
            return $default;

        return $this->value;
    }

    /**
     * Returns the contained OK value, or a value provided by the callback if there is an error.
     *
     * @param callable $valueRetriever
     *
     * @return OkType|mixed
     */
    public function unwrapOrElse(callable $valueRetriever) {
        if ($this->isFailed())
            return $valueRetriever();

        return $this->value;
    }

    /**
     * Returns the contained OK value, or null if there is an error.
     *
     * @return OkType|null
     */
    public function unwrapOrNull() {
        if ($this->isFailed())
            return null;

        return $this->value;
    }

    /**
     * Returns the contained OK value passed through the mapper, or the default value if there is an error.
     *
     * @template T
     *
     * @param callable $mapper
     * @param T $default
     *
     * @return OkType|T
     */
    public function mapOrDefault(callable $mapper, $default) {
        if ($this->isFailed())
            return $default;

        return $mapper($this->value);
    }

    /**
     * Returns the contained OK value passed through the mapper, or a value provided by the callback if there is
     * an error.
     *
     * @param callable $mapper
     * @param callable $valueRetriever
     *
     * @return OkType|mixed
     */
    public function mapOrElse(callable $mapper, callable $valueRetriever) {
        if ($this->isFailed())
            return $valueRetriever();

        return $mapper($this->value);
    }
}
