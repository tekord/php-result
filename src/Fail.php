<?php

namespace Tekord\Result;

/**
 * @template ResultType
 *
 * @extends AbstractInstance<ResultType>
 *
 * @author Cyrill Tekord
 */
class Fail extends AbstractInstance {
    public function isOk() {
        return false;
    }

    public function isFailed() {
        return true;
    }

    /**
     * @param $name
     *
     * @return ResultType|null
     *
     * @throws \Exception
     */
    public function __get($name) {
        if ($name === 'ok') {
            return null;
        } else if ($name === 'error') {
            return $this->getError();
        }

        return $this->$name;
    }
}
