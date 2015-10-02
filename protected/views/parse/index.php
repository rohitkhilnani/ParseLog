<?php
/* @var $this ParseController */

$this->breadcrumbs=array(
	'Parse',
);
?>
<h1><?php echo "Hits Per Location" ?></h1>
<h2><?php
	
	if($sqlServerError) 	// Check if there was an error while communicating with the database
	{
		echo "Error: Database not responding!";
		return;	
	}

	if((count($locationHits)===1 && $locationHits["Unknown"]===0) || $logFileError )  // Check if Log file exists and is correct. If not, display error message
	{	
			echo "Error: Log File is Empty, Corrupt or does not exist!";
	}
	else
	{	
			foreach($locationHits as $x=>$y) 	// For each (location,hits) pair, echo the location and corresponding hits
		{
			echo "$x = $y <br>";
		}

		echo "<br><br>Number of Malformed IP Addresses/Log entries encountered: ".$invalidIPCount; 		// echo number of invalid/malformed IP addresses.
	}
?> </h2>
<p>
	
</p>
