<?php
//TODO
// debugging messages, fix isconnected. try query.Clean up.

// BlaDBManager.php

// Generic database manager for access to a mysql database.
// A web.ini file should be included describing the database access 
// information.
// A web.ini.example file is included in this git repositry.

// Version information:
// 0.0.1 Created file.
// 0.0.2 Removed all the stupid comments and changed the if-hell to a 
// switch statement.
// Author Phil 'Beans' Burton. <philbeansburton@gmail.com>
class BlaDBManager
{
	// Properties
	private $version = "Bla DB Manager 0.0.2";
	private $databaseName = "";
	private $databaseUser = "";
	private $databasePassword = "";
	private $databaseHost = "";
	private $mysqli = null;
	private $sqlresult = null;

	// Constructor
	function __construct()
	{
		echo "Constructing BlaDBManager...\n";
		$this->loadINI();
		if($this->databaseName == "" || $this->databaseUser == "" || $this->databasePassword == "" || $this->databaseHost == "")
		{
			$this->returnError("web.ini file not loaded. It may contain errors. Exiting...","E");
			exit();
		}
	}

	// Methods
	private function loadINI()
	{
		echo "Loading ini file...\n";
		// Load ini into globals
		$dbConf = parse_ini_file("web.ini");
		$this->databaseName = $dbConf['database'];
		$this->databasePassword = $dbConf['password'];
		$this->databaseHost = $dbConf['hostname'];
		$this->databaseUser = $dbConf['username'];
		return true;
	}
	
	// Method to conenct to a database using the current database 
	// information
	public function connect()
	{
		echo "Trying to connect to database...\n";
		//Check if INI is loaded, Check if connected, then connect
		if(!$this->isConnected())
		{
			echo "No current Connection found...\n";
			if($this->isPHPOK())
			{
				$this->mysqli = new mysqli($this->databaseHost, $this->databaseUser, $this->databasePassword, $this->databaseName);
				if ($this->mysqli->connect_error)
				{
 			   		$this->returnError("Connect Error (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error, "E");
				}
				else
				{
					echo "Connected\n";
					return true;
				}
			}
			else
			{
				$this->retunrError("Mysqli was not found. Please install mysqli with php.", "E");
			}
		}
	}

	public function disconnect()
	{
		// If connected, disconnect
		if($this->isConnected())
		{
			$this->mysqli->close();
		}
	}
	
	// Function to get the current mysql version.
	public function getPHPVersion()
	{
		return explode("--",phpversion())[0] . "\n";
	}
	
	public function isPHPOK()
	{
		if($this->isMysqli())
		{
			echo "mysqli found!\n";
			if (strnatcmp(phpversion(),'5.3.0') >= 0)
			{
				echo "mysqlversion: " . phpversion() . " is supported\n";
				return true;
			}
			else
			{
				$this->returnError("PHP 5.3.0 is required. PHP getPHPVersion() found.","E");
			}
		}
		else
		{
			$this->returnError("MySQLi Extension is required but not found. Please check you installation of PHP");
		}
	}

	// Return the sql query result
	public function getSqlResult()
	{
		if(isset($this->sqlresult))
		{
			return $this->sqlresult;
		}
		else
		{
			$this->returnError("No Result set. Please check the query has been run.", "E");
		}
	}

	// Method to check if mysqli is installed
	public function isMysqli()
	{
		return function_exists('mysqli_connect');
	}

	public function getDatabase()
	{
		return $this->$databaseName;
	}

	public function getHost()
	{		
		return $this->$databaseHost;
	}

	public function getUser()
	{
		return $this->$databaseUser;
	}

	public function setDatabase($db)
	{
		$this->$databaseName = $db;
		echo "Database Changed.\n";
	}
	
	public function setUser($user)
	{
		$this->$databaseUser = $user;
		echo "Username Changed.\n";
	}

	public function setHost($host)
	{
		$this->$databaseHost = $host;
		echo "Hostname Changed.\n";
	}

	public function setPassword($pass)
	{
		$this->$databasePassword = $pass;
		echo "Password Changed.\n";
	}


	// Generic method for querying the database.
	public function query($query)
	{
		//if connected run query
		if($this->isConnected())
		{
			$this->sqlresult = $this->mysqli->query($query);
			
			return $this->sqlresult;
		}
		else
		{
			return false;
		}
	}
	
	// Method to check if their is a database connected.
	private function isConnected()
	{
		if(isset($this->mysqli))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function returnData($code)
	{
		//get param to lower
		$code = strtolower($code);

		switch($code)
		{
			case "row":
				return $this->sqlresult->fetch_row();
			case "count_rows":
				return $this->sqlresult->num_rows;
			case "count_cols":
				 return $this->sqlresult->field_count;
			case "field":
				return $this->sqlresult->fetch_row()[0];
			default:
				$this->returnError("No option found for return data","E");
				break;
		}
	}

	private function returnError($error, $type)
	{
		echo "Error occured in dbmanager. Type: $type Message: $error\n";
		exit;
	}

}

?>
