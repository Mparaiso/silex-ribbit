<?php

namespace Ribbit\DTO {

    class Base {

        function __get($attr) {

            $method = "get" . ucwords($attr);
            if (method_exists($this, $method)) {
                return $this->$method();
            } else {
                throw new Exception("Attribute $attr has no public getter.");
            }
        }

        function __set($attr, $val) {

            $method = "set" . ucwords($attr);
            if (method_exists($this, $method)) {
                return $this->$method($val);
            } else {
                throw new Exception("Attribute $attr has no public setter.");
            }
        }

    }

}
