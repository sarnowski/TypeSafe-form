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

class FormReport {

    /**
     * @var Form
     */
    private $form;

    /**
     * @var array
     */
    private $messages = array();

    /**
     * @var bool
     */
    private $submitted = false;

    /**
     * @var array
     */
    private $globalMessages = array();


    /**
     * @param Form $form
     */
    public function __construct(Form $form) {
        $this->form = $form;
    }


    public function getForm() {
        return $this->form;
    }

    public function setMessages($messages) {
        $this->messages = $messages;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function setSubmitted($submitted) {
        $this->submitted = $submitted;
    }

    public function submitted() {
        return $this->submitted;
    }

    public function validates() {
        return $this->submitted() && empty($this->messages);
    }

    /**
     * @return array
     */
    public function getGlobalMessages() {
        return $this->globalMessages;
    }

    /**
     * @param string $message
     * @return void
     */
    public function addGlobalMessage($message) {
        $this->globalMessages[] = $message;
    }

}
