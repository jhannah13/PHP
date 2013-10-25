<?php
	include('../dbinfo/dbinfo.php');
	$connection = mysql_connect($host,$username,$password);
	mysql_select_db($database, $connection);
	
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$detail = $_POST['details'];
	$table = $_POST['table'];
	
	$detail = mysql_real_escape_string($detail);
	
	$startDate = date("Y-m-d H:i:s", strtotime($startDate));
	$endDate = date("Y-m-d H:i:s", strtotime($endDate));
	
	if($connection)
	{
		$insertEvent = "INSERT INTO $table(startDate, endDate, detail)
							VALUES('$startDate', '$endDate', '$detail')";
		
		if(!empty($startDate) && !empty($detail))
		{
			$insertquery = mysql_query($insertEvent);
		}
	}
	
	mysql_close($connection);
?>