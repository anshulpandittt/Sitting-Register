<?php

	session_start();

	$sdbest=explode('~',$_SESSION['sessiondbestid']);

	$db_host = "localhost";
	$db_user = "postgres";
	$db_password = "";
	$db_dbname = $sdbest[1];
	
	try 
	{  
		$conn_p = new PDO("pgsql:host=$db_host;dbname=$db_dbname", $db_user, $db_password);	
	} 
	catch(PDOException $e) 
	{  
		$e = 'Connection Failed';
		$conn_p = $e;
	} 

	// if ( ! isset($_SESSION['usr_name'])) {
	// 	header("Location: ../logout.php");
	// }




	// function db_connection($dbname)
	// {
	// 	$db_host = "localhost";
	// 	$db_user = "postgres";
	// 	$db_password = "";
	// 	$db_dbname = $dbname;
		
	// 	try 
	// 	{  
	// 		$conn_p = new PDO("pgsql:host=$db_host;dbname=$db_dbname", $db_user, $db_password);	
	// 	}
	// 	catch(PDOException $e)
	// 	{
	// 		$e = 'Connection Failed';
	// 		$conn_p = $e;
	// 	}  
		
	// 	session_start();

	// 	// if ( ! isset($_SESSION['sessionUserId'])) 
	// 	// {
	// 	// 	header("Location: ../../logout.php");		
	// 	// }

	// 	return $conn_p;
	// }
?>
