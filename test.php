<?php

	// test.php this will eventually be replaced by
	// examples for various things.

	// Author: Phil 'Beans' Burton <philbeansburton@gmail.com>
	
	// For version information see the BlaDBManager.php file.

	// test.php a file to create an instance of the database manager
	// and test the functionality

	include_once("BlaDBManager.php");
	$dbman = new BlaDBManager();
	$dbman->connect();
	// Dont get a local variable
	// for this, we don't need it.
	$dbman->query("SHOW TABLES;");

//	echo "array: \n";
//	$array = $dbman->returnData("array");
//	while($row = $dbman->returnData("array"))
//	{
//		print_r($row);
//	}
	//get the field data
	echo "field: " . $dbman->returnData("field") . "\n";

	//get a row count
	echo "row count: " . $dbman->returnData("count_rows") . "\n";
	
	// get a field count
	echo "field count: " . $dbman->returnData("count_cols") . "\n";

	// get a row
	echo "row:\n";
	print_r($dbman->returnData("row"));
	print_r($dbman->returnData("row"));
	
	// Ben wants me checking my mysql errors. 
	//$sError = mysql_error(bla bla bla) // fuck ill just google this bit.	
	
	// Disconnect from the database	
	$dbman->disconnect();
	echo "disconnected\n";	
	// Good practice to run $dbman->clear() here, incase you use it again.
	// However, I have written clear() yet. But ... Just so you know...
?>

