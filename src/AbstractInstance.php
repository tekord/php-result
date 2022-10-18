<?php

namespace Tekord\Result;

/**
 * @template ResultType
 *
 * @author Cyrill Tekord
 */
abstract class AbstractInstance {
    /** @var ResultType */
    public $value;

    /** @var Result|string */
    private $resultClass;

    /**
     * @param ResultType $value
     * @param class-string<Result> $resultClass
     */
    public function __construct($value, $resultClass) {
        $this->value = $value;
        $this->resultClass = $resultClass;
    }

    /**
     * Indicates whether the result contains an error.
     *
     * @return bool
     */
    public abstract function isFailed();

    /**
     * Indicates whether the result is OK and there is no error.
     *
     * @return bool
     */
    public abstract  function isOk();

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

        $exceptionClass = $this->resultClass::$panicExceptionClass;

        throw new $exceptionClass($error);
    }

    /**
     * It is better to use the `unwrap` function instead of this one.
     *
     * @return ResultType|null
     */
    public function getOk() {
        return $this->isOk() ? $this->value : null;
    }

    /**
     * @return ResultType|null
     */
    public function getError() {
        return $this->isFailed() ? $this->value : null;
    }

    /**
     * Returns the contained OK value, or panics if there is an error.
     *
     * @return ResultType
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
     * @return ResultType|T
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
     * @return ResultType|mixed
     */
    public function unwrapOrElse(callable $valueRetriever) {
        if ($this->isFailed())
            return $valueRetriever();

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
     * @return ResultType|T
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
     * @return ResultType|mixed
     */
    public function mapOrElse(callable $mapper, callable $valueRetriever) {
        if ($this->isFailed())
            return $valueRetriever();

        return $mapper($this->value);
    }
}
