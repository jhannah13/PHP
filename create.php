<?php
	ob_start();
	include "../dbinfo/dbinfo.php";
	$connection = mysql_connect($host,$username,$password);
	mysql_select_db($database, $connection);
	
	require 'PasswordHash.php';
	
	function fail($pub, $pvt = '')
	{
		$msg = $pub;
		if ($pvt !== '')
			$msg .= ": $pvt";
		exit("An error occurred ($msg).\n");
	}
	
	
	$hash_cost_log2 = 8;
	$hash_portable = FALSE;
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	
	$op = $_POST['op'];
	if ($op !== 'new' && $op !== 'login')
		fail('Unknown request');
	
		$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
		$hash = $hasher->HashPassword($pass);
		if (strlen($hash) < 20){
			fail('Failed to hash new password');
			unset($hasher);}
		
		$db = new mysqli($host, $username, $password, $database);
		if (mysqli_connect_errno())
			fail('MySQL connect', mysqli_connect_error());
		
		if ($op === 'new')
		{
			($stmt = $db->prepare('insert into adminusers (username, password) values (?, ?)'))
				|| fail('MySQL prepare', $db->error);
			$stmt->bind_param('ss', $user, $hash)
				|| fail('MySQL bind_param', $db->error);
			if (!$stmt->execute())
			{
				if ($db->errno === 1062 /* ER_DUP_ENTRY */)
					fail('This username is already taken');
				else
					fail('MySQL execute', $db->error);
			}
			
			header("Location: ../index.php");
			$what = 'User created';
			$stmt->close();
			$db->close();
		}else
		{
			$hash = '*'; // In case the user is not found
			($stmt = $db->prepare('select password from adminusers where username=?'))
				|| fail('MySQL prepare', $db->error);
			$stmt->bind_param('s', $user)
				|| fail('MySQL bind_param', $db->error);
			$stmt->execute()
				|| fail('MySQL execute', $db->error);
			$stmt->bind_result($hash)
				|| fail('MySQL bind_result', $db->error);
			if (!$stmt->fetch() && $db->errno)
				fail('MySQL fetch', $db->error);
		
			if ($hasher->CheckPassword($pass, $hash)) {
				$what = 'Authentication succeeded';
				$adminUsername = $user;
				$adminPassword = $pass;
				
				session_register($adminUsername);
				session_register($adminPassword);
				$_SESSION['adminusername'] = $adminUsername;
				$_SESSION['adminpassword'] = $adminPassword;
				header("Location: ../PHPscripts/loginSuccess.php");
			} else {
				$what = 'Authentication failed';
				echo $what;
			}
			unset($hasher);
			
			
		}
?>