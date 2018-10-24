#!/usr/bin/php
<?php

//Check if the program is being misused (wrong number of parameters passed in) or the user is requesting help
if((2 != $argc) || in_array($argv[1], array('--help', '-help', '-h', '-?'))){
	include('help.tem.txt');
} else {
	//The program expects two input parameters, the second one being a path to the file we need to use
	$path = $argv[1];
	
	//let's make it a little easier on the user.  If they're working directory is different from the one this script is located in, don't make them type the relative path for the input file option.  Instead check if it's located in the same directory as this script.
	//I.e. if our working directory is /Users/nikita/Sites/MakeyevN and this script and the text file with the input string are located in /Users/nikita/Sites/MakeyevN/s2 give the user the convenience of running our script as $ php -q s2/answer.php input.txt instead of $ php -q s2/answer.php s2/input.txt
	//note that we didn't have to prepend s2/ to input.txt.  I know it's not much when you first look at it, but it's the small things that count.  Plus having to use a relative path on an option may be confusing to people who aren't that familiar with CLI PHP and how scripts work with options.
	if(!file_exists($path)){
	    $filePathArray = explode('/', $path);
		$potentialPath = __DIR__ . '/' . array_pop($filePathArray);
		if(file_exists($potentialPath)){
			$path = $potentialPath;
		}
	}
	//get a file handle
	$handle = @fopen($path, "r");

	if($handle){
		//by omitting the second parameter to fgets, the function will keep reading until it hits an EOL or EOF.  This will help us avoid truncating the input string if it's humongous and exceeds the limit we pass.
		$line = trim(fgets($handle));
		$numbers = preg_split('/\s+/', $line); //be mindful of the two number being separated by more than one space
		$errorFound = false;
		
		//do a little validation
		if(2 != count($numbers)){
			$errorFound = true;
			//the input file did not have two space separated strings.  Notify the user.
			print 'ERROR: Input file provided did not have two elements as expected.  See help (--help) for more info';
		} elseif(!is_numeric($numbers[0]) || !is_numeric($numbers[1])){
			$errorFound = true;
			//the input file the user provided contains two elements but one (or both) of them is not numeric.  Notify the user.
			print 'ERROR: One (or both) of the elements in the input file provided were not numeric.  See help (--help) for more info';
		}
		
		
		if(!$errorFound){
			//make sure the passed in range can be in ascending and descending orders (i.e. 19 27 as well as 27 19)
			//record the low and high numbers we'll be working with.  Note that the range is non inclusive (that's why we're adding 1 to the low)
			if($numbers[1] > $numbers[0]){
				$low = $numbers[0];
				$high = $numbers[1];
			} else {
				$low = $numbers[1];
				$high = $numbers[0];
			}
			$low++;
			
			
			for($low; $low<$high; $low++){
				//get the sum of all digits in the current number
				$digitSum = getSumOfDigits($low);
				//use bitwise and to see if the number is divisible by four (and later 8).  In binary code every number above a certain one is always divisible by its predecessor.  I.e. if you look at 4 in 1 2 4 8 16 32 ... you'll notice that all numbers higher (8, 16, 32 ...) are divisible by it.
				//Additionally all bits lower than a certain one make any number containing that digit non divisible by that bit if they're on.  I.e. any number with the 4 bit on is not divisible by 4 if the 1 or 2 bits are also on.  So all we need to do to check if a number is
				//divisible by 4 is to check if the 1 or 2 bits are on.  We can do that easily using the bitwise and operator.  1 & 2 = 3 - used to check for divisiblity by 4.  1 & 2 & 4 = 7 - used to check for divisibilty by 8.
				if(!(3 & $digitSum)){
					print "\n" . $low;
				}
				//use bitwise and to see if the number is divisible by eight.
				if(!(7 & $digitSum)){
					print '*';
				}
			}
		}
		
	} else {
		//If unable to open the file, alert the user and echo back the input so they can easily troubleshoot the file path
		print 'ERROR: Could not open input file of ' . $path . "\n";
	}
	print "\n";
}

/**
 * Get a sum of all digits in the passed in number
 * 
 * @param int $number
 * @return int
 */
function getSumOfDigits($number){
	//take care of negative numbers
	$number = intval(abs($number));
	//create a string version of the number so we can access each digit through an array
	$string = $number . "";
	$sum = 0;
	$length = strlen($string);
	for($i=0; $i<$length; $i++){
		$sum += $string[$i];
	}
	return $sum;
}