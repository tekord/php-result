<?php

namespace Tekord\Result;

/**
 * @template ResultType
 *
 * @extends AbstractInstance<ResultType>
 *
 * @author Cyrill Tekord
 */
class Success extends AbstractInstance {
    public function isOk() {
        return true;
    }

    public function isFailed() {
        return false;
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
            return $this->getOk();
        } else if ($name === 'error') {
            return null;
        }

        return $this->$name;
    }
}
