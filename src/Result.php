<?php

namespace Tekord\Result;

/**
 * @property-read mixed $ok
 * @property-read mixed $error
 *
 * @author Cyrill Tekord
 */
class Result {
    /** @var mixed */
    protected $ok;

    /** @var mixed */
    protected $error;

    public static $panicCallback = [self::class, 'defaultPanic'];

    public static function defaultPanic($error) {
        if ($error instanceof \Exception)
            throw $error;

        throw new PanicException($error);
    }

    protected static function panic($error) {
        call_user_func(static::$panicCallback, $error);
    }

    public function __get($name) {
        return $this->$name;
    }

    protected function __construct($ok, $error) {
        $this->ok = $ok;
        $this->error = $error;
    }

    /**
     * Creates a succeeded result instance.
     *
     * @param $ok
     *
     * @return static
     */
    public static function success($ok) {
        return new static($ok, null);
    }

    /**
     * Creates a failed result instance.
     *
     * @param $error
     *
     * @return static
     */
    public static function fail($error) {
        return new static(null, $error);
    }

    /**
     * Indicates whether the result contains an error.
     *
     * @return bool
     */
    public function isFailed() {
        return $this->error !== null;
    }

    /**
     * Indicates whether the result is OK and there is no error.
     *
     * @return bool
     */
    public function isOk() {
        return $this->error === null;
    }

    /**
     * Returns the contained OK value, or panics if there is an error.
     *
     * @return mixed
     */
    public function unwrap() {
        if ($this->isFailed())
            static::panic($this->error);

        return $this->ok;
    }

    /**
     * Returns the contained OK value, or the default value if there is an error.
     *
     * @param $default
     *
     * @return mixed
     */
    public function unwrapOrDefault($default) {
        if ($this->isFailed())
            return $default;

        return $this->ok;
    }

    /**
     * Returns the contained OK value, or a value provided by the callback if there is an error.
     *
     * @param callable $valueRetriever
     *
     * @return mixed
     */
    public function unwrapOrElse(callable $valueRetriever) {
        if ($this->isFailed())
            return $valueRetriever();

        return $this->ok;
    }

    /**
     * Returns the contained OK value passed through the mapper, or the default value if there is an error.
     *
     * @param callable $mapper
     * @param $default
     *
     * @return mixed
     */
    public function mapOrDefault(callable $mapper, $default) {
        if ($this->isFailed())
            return $default;

        return $mapper($this->ok);
    }

    /**
     * Returns the contained OK value passed through the mapper, or a value provided by the callback if there is
     * an error.
     *
     * @param callable $mapper
     * @param callable $valueRetriever
     *
     * @return mixed
     */
    public function mapOrElse(callable $mapper, callable $valueRetriever) {
        if ($this->isFailed())
            return $valueRetriever();

        return $mapper($this->ok);
    }
}
