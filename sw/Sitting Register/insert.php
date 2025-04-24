<?php
	include_once('includes/hp_conn.php');
	include('includes/qfunctions.php');

	// $user_id = $_SESSION['sessionUserId'];
	$flag = $_POST['flag'];
	$dsrRemark = $_POST['dsrRemark'];

	$jname = $_SESSION['sessionJudge_name'];
    $jdesg = $_SESSION['sessiondesg_name'];
    $jcode = $_SESSION['sessionjocode'];
	$userFullName = $_SESSION['full_name'];
    $userId = $_SESSION['sessionUserId'];
	$userName = $_SESSION['sessionUser'];

	$recordcheck = checktodaysentry($jcode);
	$data_id=$recordcheck['id'];

if($jcode)
{
	if($flag=='CHAMBER')
	{
		if($recordcheck['chamber_time'] || !$recordcheck)
		{
			$st_insert="INSERT INTO uk_periphery.sitting_register_tb (jo_code,jo_desg,jo_name,userid,user_name,user_full_name,todays_date,chamber_time) VALUES (:jcode,:jdesg,:jname,:userid,:uname,:ufullname,:todays_date,'now()')";
			$bindarray=array(":userid"=>$userId,":todays_date"=>date('Y-m-d'),":jcode"=>$jcode,":jdesg"=>$jdesg,":jname"=>$jname,":uname"=>$userName,":ufullname"=>$userFullName);
		}
		else
		{
			$st_insert="UPDATE uk_periphery.sitting_register_tb SET chamber_time='now()',create_modify='now()' WHERE jo_code=:jcode AND todays_date=:todays_date AND id=$data_id ";
			$bindarray=array(":todays_date"=>date('Y-m-d'),":jcode"=>$jcode);
		}
	}
	elseif($flag=='BENCH')
	{	
		if($recordcheck['bench_time'] || !$recordcheck)
		{
			$st_insert="INSERT INTO uk_periphery.sitting_register_tb (jo_code,jo_desg,jo_name,userid,user_name,user_full_name,todays_date,bench_time) VALUES (:jcode,:jdesg,:jname,:userid,:uname,:ufullname,:todays_date,'now()')";
			$bindarray=array(":userid"=>$userId,":todays_date"=>date('Y-m-d'),":jcode"=>$jcode,":jdesg"=>$jdesg,":jname"=>$jname,":uname"=>$userName,":ufullname"=>$userFullName);
		}
		else
		{
			$st_insert="UPDATE uk_periphery.sitting_register_tb SET bench_time='now()',create_modify='now()' WHERE jo_code=:jcode AND todays_date=:todays_date AND id=$data_id ";
			$bindarray=array(":todays_date"=>date('Y-m-d'),":jcode"=>$jcode);
		}
	}
	elseif($flag=='REMARK')
	{	
		if(!$recordcheck)
		{
			$st_insert="INSERT INTO uk_periphery.sitting_register_tb (jo_code,jo_desg,jo_name,userid,user_name,user_full_name,todays_date,remark) VALUES (:jcode,:jdesg,:jname,:userid,:uname,:ufullname,:todays_date,'$dsrRemark')";
			$bindarray=array(":userid"=>$userId,":todays_date"=>date('Y-m-d'),":jcode"=>$jcode,":jdesg"=>$jdesg,":jname"=>$jname,":uname"=>$userName,":ufullname"=>$userFullName);
		}
		else
		{
			$st_insert="UPDATE uk_periphery.sitting_register_tb SET remark='$dsrRemark',create_modify='now()' WHERE jo_code=:jcode AND todays_date=:todays_date AND id=$data_id ";
			$bindarray=array(":todays_date"=>date('Y-m-d'),":jcode"=>$jcode);
		}
	}


	// $bindarray=array(":userid"=>$userId,":todays_date"=>date('Y-m-d'),":jcode"=>$jcode,":jdesg"=>$jdesg,":jname"=>$jname,":uname"=>$userName,":ufullname"=>$userFullName);
	$stmt = $conn_p->prepare($st_insert);
	$insCheck = $stmt->execute($bindarray);

	if($insCheck)
		$arry= array("status"=>200);
	else
		$arry= array("status"=>400);

}
else
{
	$arry= array("status"=>200,"msg"=>'<font color="red">Please login first !</font>');
}

echo json_encode($arry);


?>