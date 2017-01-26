<?php

/*
    "Contact Form to Database" Copyright (C) 2013 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of Contact Form to Database.

    Contact Form to Database is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Contact Form to Database is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database.
    If not, see <http://www.gnu.org/licenses/>.
*/

class CFDBFunctionEvaluator {

    /**
     * @var CFDBValueConverter callback that can be used to pre-process values in the filter string
     * passed into parse($filterString).
     * For example, a function might take the value '$user_email' and replace it with an actual email address
     * just prior to checking it against input data in call evaluate($data)
     */
    var $compValuePreprocessor;

    /**
     * @param $compValuePreprocessor CFDBValueConverter
     */
    public function setCompValuePreprocessor($compValuePreprocessor) {
        $this->compValuePreprocessor = $compValuePreprocessor;
    }


    /**
     * @param $functionArray array ['function name', 'param1', 'param2', ...]
     * @param $data array [name => value]
     * @return mixed
     */
    public function evaluateFunction($functionArray, &$data) {
        $functionName = array_shift($functionArray);
        for ($i = 0; $i < count($functionArray); $i++) {
            $functionArray[$i] = $this->preprocessValues($functionArray[$i]);

            // See if the parameter is a field name that can be dereferenced.
            $functionArray[$i] = isset($data[$functionArray[$i]]) ?
                    $data[$functionArray[$i]] :
                    $functionArray[$i];

            // Dereference PHP Constants
            if (defined($functionArray[$i])) {
                $functionArray[$i] = constant($functionArray[$i]);
            }
        }
        if (empty($functionArray)) {
            // If function has no parameters, pass in the whole form entry associative array
            $functionArray[] = &$data;
        }
        return call_user_func_array($functionName, $functionArray);
    }

    /**
     * @param $text string
     * @return mixed
     */
    public function preprocessValues($text) {
        if ($this->compValuePreprocessor) {
            try {
                $text = $this->compValuePreprocessor->convert($text);
            } catch (Exception $ex) {
                trigger_error($ex, E_USER_NOTICE);
            }
        }
        return $text;
    }

}