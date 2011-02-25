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

require_once('FormReport.php');

class FormFieldBuilder {

    /**
     * @var FormReport
     */
    private $formReport;


    /**
     * @param FormReport $formReport
     */
    public function __construct(FormReport $formReport) {
        $this->formReport = $formReport;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $attr additional html attributes
     * @return FormFieldBuilder
     */
    public function field($name, $type = 'text', $attr = array()) {
        $messages = $this->formReport->getMessages();

        $default = array(
            'name' => $name,
            'id' => $name,
        );

        if ($type != 'submit') {

            // check validation
            if (isset($messages[$name])) {

                // set class
                if (isset($attr['class'])) {
                    $attr['class'] .= ' error';
                } else {
                    $attr['class']  = 'error';
                }

                $default['value'] = $messages[$name]['value'];

            } else {
                $default['value'] = $this->formReport->getForm()->get($name);
            }

        }

        if ($type == 'password' || $type == 'textarea') {
            $value = $default['value']; //persevering value for later access
            unset($default['value']);
        }

        $attr = array_merge($default, $attr);

        // build attributes
        $attrStr = $this->prepareAttributes($attr);

        // generate
        switch($type) {

            case 'submit':
                echo '<input type="submit"'.$attrStr.' />';
                break;

            case 'textarea':
                echo '<textarea'.$attrStr.'>'.$value.'</textarea>';
                break;

            case 'password':
            case 'input':
            default:
                echo '<input type="'.$type.'"'.$attrStr.' />';
                break;
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $attr additional html attributes
     * @return FormFieldBuilder
     */
    public function label($name, $label = null, $attr = array()) {
        if ($label == null) {
            $label = $name;
        }

        $attr = $this->prepareAttributes($attr);

        echo '<label for="'.$name.'"'.$attr.'>'.$label.'</label>';

        return $this;
    }

    /**
     * @param string $name
     * @param string $wrap
     * @return FormFieldBuilder
     */
    public function message($name, $wrap = '<p class="error">%message%</p>') {
        $messages = $this->formReport->getMessages();

        if (!empty($messages[$name])) {
            echo str_replace('%message%', $messages[$name]['message'], $wrap);
        }

        return $this;
    }

    /**
     * @param string $value
     * @param array $attr
     * @return FormFieldBuilder
     */
    public function submit($value, $attr = array()) {
        $attr['value'] = $value;

        return $this->field($value, 'submit', $attr);
    }

    /**
     * @param string $string
     * @return FormFieldBuilder
     */
    public function html($string) {
        echo $string;

        return $this;
    }

    /**
     * @param array $attr additional html attributes
     * @return FormFieldBuilder
     */
    public function globalMessages($attr = array()) {
        $messages = $this->formReport->getGlobalMessages();

        if (isset($attr['class'])) $attr['class'] .= ' messages';
        else $attr['class'] = 'messages';
        $attr = $this->prepareAttributes($attr);

        if (!empty($messages)) {
            echo '<ul '.$attr.'>';
            foreach ($messages as $message) {
                echo '<li>'.$message.'</li>';
            }
            echo '</ul>';
        }

        return $this;
    }

    /**
     * @param boolean $submit
     * @return void
     */
    public function close($submit = true) {
        if ($submit) $this->submit(Form::$SUBMIT_VALUE);
        echo '</form>';
    }

    /**
     * Prepare a key-value attribute array. the keys and values will be escaped.
     *
     * @param array $attr key-value attribute array
     * @return string
     */
    private function prepareAttributes($attr = array()) {
        if (empty($attr)) return '';

        $attrString = '';
        foreach ($attr as $name => $value) {
            $attrString .= sprintf(' %s="%s"', htmlspecialchars($name), htmlspecialchars($value));
        }
        return $attrString;
    }
}
