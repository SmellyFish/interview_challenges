#!/usr/bin/php
<?php

try {
    //Check if the program is being misused (wrong number of parameters passed in) or the user is requesting help
    if(array_key_exists(1, $argv) && in_array($argv[1], array('--help', '-help', '-h', '-?'))){
        printHelpOutput();
    } else {
        validateScriptArguments($argc, $argv);
        $maxNumber = $argv[1];
        echo sprintf(
            'The secret() function %s additive.' . "\n",
            isSecretFunctionAdditive($maxNumber) ? 'is' : 'is NOT'
        );
    }

} catch (Exception $e) {
    echo sprintf(
        'There was a problem running the script: %s' . "\n",
        $e->getMessage()
    );
}

/**
 * Use the sieve of Eratosthenes to find all prime numbers below the limit
 *
 * @param int $maxNumber
 * @return array
 */
function getPrimeNumbers($maxNumber)
{
    $range = range(2, $maxNumber - 1);
    $range = array_combine($range, $range);

    for ($i = current($range); $i * $i < $maxNumber; $i = next($range)) {
        for ($j = $i * $i; $j < $maxNumber; $j += $i) {
            unset($range[$j]);
        }

    }

    return $range;
}

/**
 * @param int $maxNumber The max number to use in our calculations
 * @return bool
 */
function isSecretFunctionAdditive($maxNumber)
{
    $primes = getPrimeNumbers($maxNumber);
    $resultCache = array();
    $functionIsAdditive = true;

    foreach ($primes as $i) {
        foreach($primes as $j) {
            $k = $i + $j;

            $input = array('i' => $i, 'j' => $j, 'k' => $k);
            $output = array();

            foreach($input as $key => $value) {
                if (array_key_exists($value, $resultCache)) {
                    $result = $resultCache[$value];
                } else {
                    $result = $resultCache[$value] = secret($value);
                }
                $output[$key] = $result;
            }

            if (($output['i'] + $output['j']) != $output['k']) {
                $functionIsAdditive = false;
                break 2;
            }
        }
    }
    return $functionIsAdditive;
}

/**
 * @param int $value The value to perform an operation on
 * @return int
 */
function secret($value)
{
    // Additive
//    return $value;
    // Not additive
    return $value > 15 ? 100 - $value : $value;
}

/**
 * @param int $argc Number of arguments passed to PHP
 * @param array $argv Array of arguments passed to PHP
 * @throws InvalidArgumentException
 */
function validateScriptArguments($argc, $argv)
{
    if ($argc > 2) {
        throw new \InvalidArgumentException('This script only accepts one parameter.');
    } elseif ($argc < 2) {
        throw new \InvalidArgumentException('This script requires one parameter.');
    }

    $maxNumber = filter_var(
        $argv[1],
        FILTER_VALIDATE_INT,
        array(
            'options' => array(
                'min_range' => '3'
            )
        )
    );

    if (false === $maxNumber) {
        throw new \InvalidArgumentException('Parameter "' . $argv[1] . '" is invalid. Expecting a number greater than 2');
    }
}

/**
 * Print out the help menu output to screen
 */
function printHelpOutput()
{
    $string = <<<STRING
This is a command line PHP script that determines whether the secret() function contained within is additive.

Usage:
  answer.php <max_number>
  <max_number>  Any integer greater than two.
STRING;
    echo $string . "\n";
}