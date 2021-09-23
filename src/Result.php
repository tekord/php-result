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

    public static $panicCallback = [self::class, 'defaultPanic'];

    public static function defaultPanic($error) {
        if ($error instanceof \Throwable)
            throw $error;

        throw new PanicException($error);
    }

    protected static function panic($error) {
        call_user_func(static::$panicCallback, $error);
    }

    /**
     * @param string $name
     *
     * @return OkType|ErrorType|null
     */
    public function __get($name) {
        if ($name == 'ok') {
            return $this->isOk() ? $this->value : null;
        } else if ($name == 'error') {
            return $this->isFailed() ? $this->value : null;
        }

        throw new \Exception('Invalid property: ' . $name . '. Class ' . static::class . ' provides only "ok" and "error" properties');
    }

    /**
     * @param int $discriminant
     * @param OkType|ErrorType $value
     */
    protected function __construct(int $discriminant, $value) {
        $this->discriminant = $discriminant;
        $this->value = $value;
    }

    /**
     * Creates a succeeded result instance.
     *
     * @param OkType $value
     *
     * @return Result<OkType, null>
     */
    public static function success($value) {
        return new static(static::DISCRIMINANT_OK, $value);
    }

    /**
     * Creates a failed result instance.
     *
     * @param ErrorType $value
     *
     * @return Result<null, ErrorType>
     */
    public static function fail($value) {
        return new static(static::DISCRIMINANT_ERROR, $value);
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
            static::panic($this->value);

        return $this->value;
    }

    /**
     * Returns the contained OK value, or the default value if there is an error.
     *
     * @param mixed $default
     *
     * @return OkType|mixed
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
     * Returns the contained OK value passed through the mapper, or the default value if there is an error.
     *
     * @param callable $mapper
     * @param mixed $default
     *
     * @return OkType|mixed
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
