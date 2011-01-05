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

require_once('Form.php');
require_once('FormFieldBuilder.php');

class FormBuilder {

    /**
     * @static
     * @param string $action
     * @param string $method
     * @return FormFieldBuilder
     */
    public static function open(FormReport $formReport, $action = null, $method = 'post') {
        if ($action == null) {
            $action = $_SERVER['REQUEST_URI'];
        }

        echo '<form method="'.$method.'" action="'.$action.'">';

        // form hint
        echo '<input type="hidden" name="'.Form::$FORM_NAME.'" value="'.$formReport->getForm()->superClass()->getName().'"/>';

        return new FormFieldBuilder($formReport);
    }


}
