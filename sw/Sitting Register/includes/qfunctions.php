<?php

	include_once('includes/hp_conn.php');

	if(isset($_POST['funCall']) && !empty($_POST['funCall']))
	{ 
	    $funCall = $_POST['funCall'];
	    
	    switch($funCall)
	    {
	        case 'fetch_courts' : getAllCourts($_POST['dbname']);break;
		}
	}

	function db_connection($dbname)
	{
		$db_host = "localhost";
		$db_user = "postgres";
		$db_password = "";
		$db_dbname = $dbname;
		
		try 
		{  
			$conn_p = new PDO("pgsql:host=$db_host;dbname=$db_dbname", $db_user, $db_password);	
		}
		catch(PDOException $e)
		{
			$e = 'Connection Failed';
			$conn_p = $e;
		}  
		
		return $conn_p;
	}


	function checktodaysentry($jcode)
	{
		global $conn_p;

		$st_insert="SELECT id,chamber_time,bench_time FROM uk_periphery.sitting_register_tb WHERE jo_code=:jcode AND todays_date=:tdate ORDER BY id DESC limit 1";

		$bindarray=array(":tdate"=>date('Y-m-d'),":jcode"=>$jcode);
		$stmt = $conn_p->prepare($st_insert);
		$stmt->execute($bindarray);
		$rowchk = $stmt->fetch(PDO::FETCH_ASSOC);

		return $rowchk;
	}

	function getAllEstablishments()
	{
		$conn_p=db_connection('ecourtisuserdb');

		$st_insert="SELECT est_code,est_dbname,estname FROM public.establishment WHERE display='Y' ";

		$stmt = $conn_p->prepare($st_insert);
		$stmt->execute();
		$rowchk = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$selectbox="<select id='estcode' name='estcode' class='form-control'>
		<option value='0'>Select</option>";

		foreach($rowchk as $val)
		{
			$selectbox.="<option value='".$val['est_code'].'~'.$val['est_dbname']."'>".$val['estname']."</option>";
		}

		$selectbox.="</select>";

		echo $selectbox;	
	}

	function getAllCourts($dbname)
	{
		$conn_p=db_connection($dbname);

		$query = "SELECT * from
		(SELECT tt.desg_code, tt.court_no, dt.desgname, tt.judge_name, tt.jocode, tt.judge_priority from 
		(SELECT jt.desg_code,ct.court_no, nt.judge_name, nt.jocode, nt.judge_priority from court_t ct
		left join judge_t jt on ct.court_no= jt.court_no
		left join judge_name_t nt on nt.jocode= jt.jocode and jt.judge_code= nt.judge_code
		where jt.to_dt is null and ct.display='Y' and nt.display= 'Y' order by ct.court_no ) tt
		left join desg_t dt on tt.desg_code= dt.desgcode) tb
		order by judge_priority,jocode,court_no";
		
		$sqlchk = $conn_p->prepare($query);
		$sqlchk->execute();
		$rowchk = $sqlchk->fetchAll(PDO::FETCH_ASSOC);
		
		$selectbox="<option value='0'>Select</option>";	
		foreach($rowchk as $row)
		{
			$selectbox.="<option value='".$row['jocode']."'>".$row['judge_name'].', '.$row['desgname']."</option>";
		}

		echo $selectbox;
	}

	function checkDesignation($jcode)
	{
		global $conn_p;

		// $query="SELECT * from judge_name_t jt
		// left join desg_t d ON jt.desg_code=d.desgcode 
		// where jocode=:jcode and d.national_code='1001' ";
		$query="SELECT * from judge_t j
				left join judge_name_t jt ON j.judge_code=jt.judge_code 
				left join desg_t d ON j.desg_code=d.desgcode 
				where jt.jocode=:jcode and d.national_code='1001' AND j.to_dt is null";

		$bindarray=array(":jcode"=>$jcode);
		$stmt = $conn_p->prepare($query);
		$stmt->execute($bindarray);
		$rowchk = $stmt->fetch(PDO::FETCH_ASSOC);

		return $rowchk;
	}

?>