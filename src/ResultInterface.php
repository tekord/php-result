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
interface ResultInterface {
    /**
     * @return OkType|null
     */
    public function getOk();

    /**
     * @return ErrorType|null
     */
    public function getError();

    /**
     * Indicates whether the result is OK and there is no error.
     *
     * @return bool
     */
    public function isOk();

    /**
     * Indicates whether the result contains an error.
     *
     * @return bool
     */
    public function isFailed();
}
