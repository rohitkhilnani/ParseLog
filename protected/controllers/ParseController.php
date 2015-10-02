<?php

class ParseController extends Controller
{
	
	protected $logFileError=false;    		// logFileError is set true if log file is not present in /Files directory
	protected $locationHits = array(); 		// Array to hold the hits per location for each location.
	protected $invalidIPCount = 0; 			// Count of invalid IP Addresses / log records during parsing
	protected $sqlServerError = false; 	 	// sqlServerError is set true if there is a problem accessing table from mysql database

//------------------


	public function actionIndex()
	{	
		//Get Hits Per Location
		$this->locationHits = $this->getHitsPerLocation();

		// Render View and Make class variables accessible to View
		$this->render('index', array('locationHits'=>$this->locationHits,'invalidIPCount'=>$this->invalidIPCount,'logFileError'=>$this->logFileError,'sqlServerError'=>$this->sqlServerError));
	}

		// Function to parse the log file and return array of hits per location.
		//Algorithmic Complexity: Linear in number of records in access log file.
		protected function getHitsPerLocation()
		{

				// See if log file is available. If not, set Error and return
				if(!file_exists("Files/access_log.txt"))
					{
						$this->logFileError = true;
						return -1;
					}


				// If log file is available, save number of hits per IP Address in array ipHits		

				$logfile = fopen("Files/access_log.txt",'r');		

				$ipHits = array();
				
				while($line=fgets($logfile)) 			// for each line in log file
			{		
				if(is_numeric($line[0]))   				// to ignore initial comments (log lines which do not begin with a digit)
					if(!$response404 = strpos($line, "\" 404 ") ) 		// ignore 404 responses
					{
						$firstSpaceChar = strpos($line," "); 			//	Extract the -
						$ipAddress = substr($line,0,$firstSpaceChar); 	// 	IP Address from Log
					
						// increment hits for this IP Address by 1, or set it to 1 if first occurence of this IP is encountered	
						if(array_key_exists($ipAddress, $ipHits))
							$ipHits[$ipAddress]++;
						else
							$ipHits[$ipAddress]=1;
					}

							
			}
	


		// Using the array ipHits, create a new array locationHits, which would contain number of hits per location.

			$locationHits = array();
			$locationHits["Unknown"] = 0;   // Set 'Unknown' = 0, for locations whose ip address is not known/found in the database


				foreach($ipHits as $x => $y)	// Iterate over all (IP address,hits) pairs
				{
					$x_parts = explode(".",$x);	// split IP address into four components	

					if(count($x_parts) < 4)	  	// If there are less than 4 components (IP address is invalid)
					{
						$this->invalidIPCount++;   // increment invalid IP Addresses count
						continue;				   // move on to next IP address
					}	
				

					
					
					try{
						// Get the location name for current IP address.
						$location = $this->getLocationForIP($x_parts);
					}
					catch(CDbException $e)
					{
						// If there is a problem communicating with the database, set Error = true and return
						$this->sqlServerError = true;
						return -1;
					}

					// increment the hits for the location detected.	
					if(array_key_exists($location, $locationHits))
								$locationHits[$location]+=$y;
							else
								$locationHits[$location]=$y;		
				}

			
				return $locationHits;		
		}


		// This function queries the database for location of a given IP address. First the most generic match is searched, following next most generic -
		// match, and so on, searching for the exact IP address in the end. 
		// For example, for IP = 124.10.20.14, we first look for 124.*.*.* in the database, followed by 124.10.*.*, followed by 124.10.20.* and then 
		// 124.10.20.14
		//Algorithmic Complexity: Linear in number of records in table.
		protected function getLocationForIP($ip_parts)
		{
			// For IP address of type AAA.BBB.CCC.DDD , see if database has a record for AAA.*.*.*
			$ip = "$ip_parts[0]".".*.*.*"; 
			$result = Location::model()->find("IP=\"$ip\""); //	
					if(count($result)>0) 		// If a record is found					
						{
							return $result->Location;							
						}

			// For IP address of type AAA.BBB.CCC.DDD , see if database has a record for AAA.BBB.*.*
			$ip = "$ip_parts[0].$ip_parts[1]".".*.*";
			$result = Location::model()->find("IP=\"$ip\""); //	
					if(count($result)>0)
						{
							return $result->Location;							
						}
			
			// For IP address of type AAA.BBB.CCC.DDD , see if database has a record for AAA.BBB.CCC.*
			$ip = "$ip_parts[0].$ip_parts[1].$ip_parts[2]".".*";
			$result = Location::model()->find("IP=\"$ip\""); //	
					if(count($result)>0)
						{
							return $result->Location;							
						}

			// For IP address of type AAA.BBB.CCC.DDD , see if database has a record for AAA.BBB.CCC.*
			$ip = "$ip_parts[0].$ip_parts[1].$ip_parts[2].$ip_parts[3]";
			$result = Location::model()->find("IP=\"$ip\""); //	
					if(count($result)>0)
						{
							return $result->Location;							
						}						


			// If there is not match for the current IP address in the database, count this hit for 'Unknown' location. 
			// This helps keep track of hits which could not be resolved, although this is a rare case.				
			return "Unknown";		


		}

		// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}