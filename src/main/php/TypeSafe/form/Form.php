<?php
/*
 * Copyright 2011 Tobias Sarnowski, Florian Purchess
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once('pinjector/DocParser.php');
require_once('TypeSafe/validation/ValidationException.php');

abstract class Form {

    // unset them below
    public static $FORM_NAME = '_formName';
    public static $SUBMIT_NAME = '_submitButton';
    public static $SUBMIT_VALUE = 'submit';

    /**
     * @param array $data
     * @return void
     */
    public function _setData($data) {
        // container for messages
        $messages = array();

        // ourself
        $class = $this->superClass();

        // unset protected fields
        unset($data[self::$FORM_NAME]);
        unset($data[self::$SUBMIT_NAME]);


        //$weavedClass = new ReflectionClass(get_class($this));
        //$class = $weavedClass->getMethod("superClass")->invoke(null);

        $requiredFields = array();
        foreach ($class->getProperties() as $property) {
            if ($property->getName() == 'SUBMIT_NAME') {
                continue;

            } else if (count(DocParser::parseSettings($property->getDocComment(), 'requiredField'))) {
                $requiredFields[$property->getName()] = true;
            }

        }

        foreach($data as $key => $value) {
            if (isset($requiredFields[$key])) {
                if (empty($value) && $value !== "0") {
                    $messages[$key] = array('message' => 'Field is required.', 'value' => $value);
                    continue;
                } else {
                    unset($requiredFields[$key]);
                }
            }

            try {
                $this->set($key, $value);
            } catch(ValidationException $e) {
                $messages[$key] = array('message' => $e->getMessage(), 'value' => $value);
            }
        }

        return $messages;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        $method = "get".ucfirst($name);
        return $this->$method();
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function set($name, $value) {
        $method = "set".ucfirst($name);
        return $this->$method($value);
    }
}
