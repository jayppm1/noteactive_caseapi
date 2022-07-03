<?php
class Controllersyndbodbc extends Controller {
	public function index() {
		
		$sql = "SELECT * FROM `" . DB_ODBCPREFIX . "user` where ";
		
		$query = $this->odbcdb->query ( $sql );
		
		$results = $query->rows;
		
		var_dump($results);
	}
	
} 
	

/*
$hostname = "166.62.28.137";
			$username = "power_bi_user";
			$password = "power_bi_user";
			$dbname = "power_bi_db";

			$connection = mysql_connect($hostname, $username, $password);
			var_dump($connection);
			echo mysql_error();
			mysql_select_db($dbname, $connection);
			
			//Setup our query
			echo $query = "SELECT * FROM ". DB_PREFIX."user ";
			 
			//Run the Query
			$result = mysql_query($query);
			 
			//If the query returned results, loop through
			// each result
			var_dump($result);
			if($result)
			{
			  while($row = mysql_fetch_array($result))
			  {
				$name = $row['username'];
				echo "Name: " . $name; 

			  }
			}
			mysql_close($connection);
*/