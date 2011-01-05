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

require_once('FormService.php');
require_once('FormReport.php');


class FormServiceImpl implements FormService {

    /**
     * @var Kernel
     */
    private $kernel;


    /**
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel) {
        $this->kernel = $kernel;
    }


    public function createForm($formClassName, $data = null) {
        if (is_null($data)) {
            $data = $_POST;
        }

        $form = $this->kernel->createInstance($formClassName);
        $formReport = new FormReport($form);

        if (!empty($data)) {
            if (!isset($data[Form::$FORM_NAME])) {
                throw new InternalServerErrorException("No valid formular given.");
            } else if ($data[Form::$FORM_NAME] == $formClassName) {
                $messages = $form->_setData($data);
                $formReport->setMessages($messages);
                $formReport->setSubmitted(true);
            }
        }

        return $formReport;
    }
}
