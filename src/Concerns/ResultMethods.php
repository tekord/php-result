<?php

namespace Tekord\Result\Concerns;

use Tekord\Result\ResultInterface;

/**
 * @template OkType
 * @template ErrorType
 *
 * @property-read OkType $ok
 * @property-read ErrorType $error
 *
 * @mixin ResultInterface<OkType, ErrorType>
 *
 * @author Cyrill Tekord
 */
trait ResultMethods {
    public function __get($name) {
        if ($name === 'ok') {
            /** @var OkType $returnValue */
            $returnValue = $this->getOk();
        } else if ($name === 'error') {
            /** @var ErrorType $returnValue */
            $returnValue = $this->getError();
        } else {
            throw new \Exception('Invalid property: ' . $name . '. Class ' . static::class . ' provides only "ok" and "error" properties');
        }

        return $returnValue;
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
