<?php
	if(file_exists("dbinfo/dbinfo.php"))
	{
		include('dbinfo/dbinfo.php');
	}else
	{
		include('../dbinfo/dbinfo.php');
	}
	
	$connection = mysql_connect($host,$username,$password);
	mysql_select_db($database, $connection);
	
	
	if($connection)
	{
		if(!isset($_SESSION['adminusername']))
		{
			date_default_timezone_set("America/Los_Angeles");
			$date = date('Y.m.d H:i:s');
			$events = mysql_query("SELECT id, startDate, endDate, detail
		                            FROM upcomingappearances
		                            WHERE display = 1
		                            AND (startDate >= '$date' OR endDate >= '$date')
		                            ORDER BY startDate", $connection);
			
			
			print "<ul class='eventList'>";
						
		    while ($row = mysql_fetch_assoc($events))
		    {				
				$endDate = $row['endDate'];
				$startDate = $row['startDate'];
				$detail = stripslashes($row['detail']);
				$id = $row['id'];
		        print "<li>".date('F j', strtotime($startDate))." "
			        	.(!empty($endDate)
			        	&& $endDate != $startDate
			        	&& $endDate != '1970-01-01 00:00:00'
			        	&& $endDate != '0000-00-00 00:00:00' ? '- '.date('j', strtotime($endDate)): '')
						." : ".$detail
						."</li>";
		    }
		    
		    print "</ul>";
		}else
		{
			$date = date('m/d/Y');
			$date = strtotime($date);
			$events = mysql_query("SELECT id, startDate, endDate, detail, display
		                            FROM upcomingappearances
		                            WHERE startDate > timestampadd(day, -30, now())
		                            ORDER BY startDate", $connection);
			
			print "<ul class='eventList'>";
						
		    while ($row = mysql_fetch_assoc($events))
		    {
				$endDate = $row['endDate'];
				$startDate = $row['startDate'];
				$detail = $row['detail'];
				$id = $row['id'];
				$display = $row['display'];
		        print "<li><input name='chk' type='checkbox' id='$id' onclick='chkChanged($id)' ".($display==1 ? 'checked' : '')."/>"
		        		.date('F j', strtotime($startDate))." "
			        	.(!empty($endDate)
			        	&& $endDate != $startDate
			        	&& $endDate != '1970-01-01 00:00:00'
			        	&& $endDate != '0000-00-00 00:00:00' ? '- '.date('j', strtotime($endDate)): '')
						." : ".$detail
						."</li>";
		    }
		    
		    print "</ul>";
		}
	mysql_close($connection);
	    
	} else
	{
	    print "problems..";
	}
?>