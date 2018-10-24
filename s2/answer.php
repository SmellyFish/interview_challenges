#!/usr/bin/php
<?php

//Check if the program is being misused (wrong number of parameters passed in) or the user is requesting help
if((2 != $argc) || in_array($argv[1], array('--help', '-help', '-h', '-?'))){ ?>
This is a command line PHP script that expects one option

	Usage: 
	answer.php <option>
	<option> can be a path to a valid file that contains a single line of text
	which [the text] will be filtered and compacted as specified:
	1) the output will be only alphanumeric characters (0-9, A-Z, a-z)
	2) the output will remove any adjacent alphanumeric duplicates,
	including duplicates separated by non- alphanumeric characters.
	This program assumes that the input (both file path and contents) provided
	will be from the ASCII character set.
<?php } else {
	//The program expects two input parameters, the second one being a path to the file we need to use
	$path = $argv[1];
	
	//let's make it a little easier on the user.  If they're working directory is different from the one this
	//script is located in, don't make them type the relative path for the input file option.  Instead check if
	//it's located in the same directory as this script.
	//I.e. if our working directory is /Users/nikita/Sites/MakeyevN and this script and the text file with the
	//input string are located in /Users/nikita/Sites/MakeyevN/s2 give the user the convenience of running our
	//script as $ php -q s2/answer.php input.txt instead of $ php -q s2/answer.php s2/input.txt
	//note that we didn't have to prepend s2/ to input.txt.  I know it's not much when you first look at it, but
	//it's the small things that count.  Plus having to use a relative path on an option may be confusing to
	//people who aren't that familiar with CLI PHP and how scripts work with options.
	if(!file_exists($path)){
        $filePathArray = explode('/', $path);
		$potentialPath = dirname(__FILE__) . '/' . array_pop($filePathArray);
		if(file_exists($potentialPath)){
			$path = $potentialPath;
		}
	}
	//get a file handle
	$handle = @fopen($path, "r");

	if($handle){
		//by omitting the second parameter to fgets, the function will keep reading until it hits an EOL or EOF.
		//This will help us avoid truncating the input string if it's humongous and exceeds the limit we pass.
		$line = fgets($handle);
		
		//\d is the same as 0-9. We could use \w instead of a-zA-Z below but we don't want underscores and in
		//some rare cases the shorthand code may encompass some other unwanted unicode characters.  Be careful
		//to NOT use [\W\D] - that reg exp is misleading because it yields all characters that are either not
		//alpha OR not numeric, so it matches all characters
		$line = preg_replace('/[^a-zA-Z\d]/', '', $line);
		//use a positive lookahead to find and strip out repeated characters.  It's OK to match any character
		//in the first part since we already stripped out all unwanted candidates above 
		$line = preg_replace('/(.)(?=\1+)/', '', $line);
		print $line . "\n";
	} else {
		//If unable to open the file, alert the user and echo back the input so they can easily troubleshoot
		//the file path
		print 'ERROR: Could not open input file of ' . $path . "\n";
	}
}