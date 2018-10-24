additive_func
=============
```
php -q additive_func/answer.php $interger
# For example
php -q additive_func/answer.php 50
```

## Summary
Command line PHP script that determines whether the `secret()` function contained within is [additive](https://en.wikipedia.org/wiki/Additive_function).

## Features
* Has a help menu `php -q additive_func/answer.php -h`
* Will validate the number of arguments passed in

n2
==
`php -q n2/answer.php input.txt`

## Summary
Command line PHP script that will output only integers that exist in the provided range
where the sum of all the digits is evenly divisible by four. Additionally, the program will output an asterisk ("*")
next to the integer if the sum of its digits is also evenly divisible by eight.

## Features
* Has a help menu `php -q s2/answer.php -h`
* Will validate the number of arguments passed in
* Will display an error if unable to open the file path passed in
* Will check the script's directory for the input file if the relative path specified by the user fails
* Will display an error if the file passed in has more than two space separated values in it
* Will display an error if one (or both) of the values in the passed in file are not numeric
* Works with an ascending and descending range in the input file (i.e. 19 27 vs 27 19)
* The two numbers can be separated and padded by any amount of white space

## POSSIBLE IMPROVEMENTS
* I would look into the function that sums up number digits to see if that can be optimized
* Avoiding traversing every number in the range would be ideal.  I'd spend more time trying to figure out if that can be done.  I'd look deeper into binary math to see if I can find a pattern

s2
==
`php -q s2/answer.php input.txt`

## Summary
Command line PHP script that will be filter and compact the provided string as specified:
1) The output will be only alphanumeric characters (0-9, A-Z, a-z)
2) The output will remove any adjacent alphanumeric duplicates,
including duplicates separated by non- alphanumeric characters.

This program assumes that the input (both file path and contents) provided
will be from the ASCII character set.

## FEATURES
* Has a help menu `php -q s2/answer.php -h`
* Will validate the number of arguments passed in
* Will display an error if unable to open the file path passed in
* Will check the script's directory for the input file if the relative path specified by the user fails

## POSSIBLE IMPROVEMENTS
* If I had more time to work on this project I would try to figure out a way to combine the two regular expressions into one to optimize the code
* It would be a good idea to add some validation of the input file format with appropriate user feedback.  I.e. checking that the file really only contains one line (excluding empty lines).  Doing this will help eliminate possible bugs associated with faulty input.