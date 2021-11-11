<?php

namespace Bataille\View;

/**
 * @name \Bataille\View\abstractView
 */
abstract class abstractView implements abstractViewInterface
{
    /**
     * @var array
     */
    private array $arrOutput;

    /**
     * @var array [ref => ['question' => string, 'answer' => string], 'defaultAnswer' => string]
     */
    private array $arrInput;

    /**
     * @return array
     */
    public function getArrOutput()
    {
        return $this->arrOutput;
    }

    /**
     * @param array $arrOutput
     * @return $this
     */
    public function setArrOutput($arrOutput)
    {
        $this->arrOutput = $arrOutput;
        return $this;
    }

    /**
     * @return array
     */
    public function getArrInput()
    {
        return $this->arrInput;
    }

    /**
     * @param array $arrInput
     * @return $this
     */
    public function setArrInput($arrInput)
    {
        $this->arrInput = $arrInput;
        return $this;
    }

    public function resolveInputs()
    {
        if(!$this->arrInput) {
            echo PHP_EOL;
            return;
        }
        $tmpArrInput = [];
        foreach ($this->arrInput as $key => $input) {
            $question = $input['question'];
            if($input['defaultAnswer']) {
                $question .= ' [default = '.$input['defaultAnswer'].']';
            }
            echo $question.' : ';

            $answer = fgets(STDIN);
            $answer = substr($answer, 0, -1); //fgets(STDIN) leave an EOL at the end of string.

            if(!$answer) {
                $answer = $input['defaultAnswer'];
            }
            $input['answer'] = $answer;
            $tmpArrInput[$key] = $input;
        }
        $this->arrInput = $tmpArrInput;
        echo PHP_EOL;
    }

    public function displayOutputs()
    {
        if(!$this->arrOutput) {
            return;
        }
        $this->autoHandleOutput($this->arrOutput);
        echo PHP_EOL;
    }

    private function autoHandleOutput(mixed $var, bool $showKey = false, string|int|null $key = null)
    {
        if ($var) {
            if (is_string($var) || is_numeric($var) || is_bool($var)) {
                echo $var;
            } else {
                if (is_object($var)) {
                    $var = get_object_vars($var);
                }
                if (is_array($var)) {
                    echo PHP_EOL;
                    foreach ($var as $skey => $sub_var) {
                        $this->autoHandleOutput($sub_var, true, $skey);
                    }
                    echo PHP_EOL;
                }
            }
        }
    }
}