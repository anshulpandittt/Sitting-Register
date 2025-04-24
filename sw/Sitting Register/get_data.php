<?php

	date_default_timezone_set("Asia/Kolkata");

	include_once('includes/hp_conn.php');
	include('includes/qfunctions.php');

	// $sdbest=explode('~',$_SESSION['sessiondbestid']);
	$jo_code = $_SESSION['sessionjocode'];
	$tdate = date('Y-m-d');
	
	if(isset($_POST['flag']) && !empty($_POST['flag']))
	{ 
	    $flag = $_POST['flag'];

	    switch($flag)
	    {
	        case 'todays_data' : fetchtodaysdata($jo_code,$tdate); break;
			case 'all_data' : fetchSubordinateData($_SESSION['sessiondbestid'],$jo_code,$_POST['fdate']); break;
	        case 'SUBORDINATE_DATA' : fetchSubordinateData($_POST['estcode'],$_POST['courtname'],$_POST['month']); break;
		}
	}


	function fetchtodaysdata($jcode,$tdte)
	{
		global $conn_p;

		$slectqry = "SELECT * FROM uk_periphery.sitting_register_tb where todays_date=:tdate AND jo_code=:jcode ORDER BY id";

		$bindarray=array(":tdate"=>$tdte,":jcode"=>$jcode);
		$stmt = $conn_p->prepare($slectqry);
		$stmt->execute($bindarray);
		$rowchk = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if($rowchk)
		{
			$table='<table class="table table-bordered" id="srtable" style="width:100%; background:white;">
					<thead style="font-size:20px;">
						<th class="text-center">S.No.</th>
						<th class="text-center">Date</th>
						<th class="text-center">On the Bench</th>
						<th class="text-center">In Chambers</th>
						<th class="text-center">Remarks</th>
					</thead><tbody>';

			$sno=1;

			foreach ($rowchk as $val) 
			{
				$ctime[] = date('h:i:s a',strtotime($val['chamber_time']));
			}
			$cc=0;
			foreach ($rowchk as $val) 
			{
				$c_time=$val['bench_time'] ? date('h:i:s a',strtotime($val['bench_time'])) : '' ;
				$ch_time=$val['chamber_time'] ? date('h:i:s a',strtotime($val['chamber_time'])) : '' ;

				$cc = $sno++;

				$table.='<tr class="text-center" style="font-size:20px;">
							<td>'.$cc.'</td>';
				if($sno==2)
				{
					$table.='<td rowspan="'.count($rowchk).'" style="vertical-align:middle;">'.date('d-m-Y',strtotime($val['todays_date'])).'</td>';
				}
				$table.='<td>'.$c_time.' - '.$ctime[$cc].'</td>
							<td>'.$ch_time.' - '.$c_time.'</td>
							<td>'.$val['remark'].'</td>
						</tr>';
			}

			$table.='</tbody></table>';
			$final_data=array("status"=>200,"data"=>$table,"rawData"=>$rowchk);
		}
		else
		{
			$final_data=array("status"=>400);
		}

		echo json_encode($final_data);
	}


	function fetchSubordinateData($estcode,$jocode,$month)
	{
		$dname=explode('~',$estcode);
		$dataDate='01-'.$month;
		$dateMonth=date("F - Y",strtotime($dataDate));

		if($jocode && $dname && $month)
    	{
			if(date('m')==date('m',strtotime($dataDate)))
			{
				$first=$dataDate;
				$last=date('Y-m-d');
			}
			else{
				$first=$dataDate;
				$last=date('Y-m-t',strtotime($dataDate));
			}

			$allDates = range_date($first, $last);

			$conn_p=db_connection($dname[1]);

			$jo_name=getJodetails($conn_p,$jocode);

			$table='<p style="font-size:20px; font-family:sans-serif; float:left;"><b>In the Court of </b>'.$jo_name.'<p style="font-size:20px; font-family:sans-serif;float: right;"><b>Month of </b>'.$dateMonth.'</p></p>
					<table class="table table-bordered table-condensed table-hover">
					<thead>
						<tr style="font-size:20px;">
							<th class="text-center">Date</th>
							<th class="text-center">On the Bench</th>
							<th class="text-center">In Chambers</th>
							<th class="text-center">Remarks</th>
						</tr>
					</thead>
					<tbody>';

			foreach ($allDates as $tdate) 
			{
				$selQuery = "SELECT * FROM uk_periphery.sitting_register_tb where todays_date=:tdate AND jo_code=:jcode ORDER BY id";
				$bindarray=array(":tdate"=>$tdate,":jcode"=>$jocode);
				$stmt = $conn_p->prepare($selQuery);
				$stmt->execute($bindarray);
				$rowchk = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if($rowchk)
				{
					$cc=0;
					$sno=1;
					$lastDateValue ='';
					$to_time='';
					foreach ($rowchk as $val) 
					{
						$bench_time=$val['bench_time'] ? date('h:i:s a',strtotime($val['bench_time'])) : '' ;
						$chamber_time=$val['chamber_time'] ? date('h:i:s a',strtotime($val['chamber_time'])) : '' ;

						$judgeLeave=fetchJoLeaveType($jocode,$conn_p,$tdate);

						$cc = $sno++;

						if($bench_time && $chamber_time)
						{
							$table.='<tr class="text-center" style="font-size:20px;">';
							if($lastDateValue != $val['todays_date'])
							{
								$rcount=getRowCount($conn_p,$val['todays_date'],$val['jo_code']);

								$table.='<td rowspan="'.$rcount.'" style="vertical-align:middle;">'.date('d-m-Y',strtotime($val['todays_date'])).'</td>';
								$lastDateValue = $val['todays_date'];
							}
							$to_time= $rowchk[$cc]['chamber_time'] ? date('h:i:s a',strtotime($rowchk[$cc]['chamber_time'])) : '' ;

							if($to_time)
							{
								$table.='<td>'.$bench_time.' - '.$to_time.'</td>';
							}
							else
							{
								$table.='<td></td>';
							}

							$table.='<td>'.$chamber_time.' - '.$bench_time.'</td>
									<td>'.($val['remark'] ? $val['remark'].'<br>'.$judgeLeave : $judgeLeave).'</td>
								</tr>';
						}
						elseif($val['remark'] && !$bench_time && !$chamber_time)
						{
							$table.='<tr class="text-center" style="font-size:20px;">';
								if($lastDateValue != $val['todays_date'])
								{
									$rcount=getRowCount($conn_p,$val['todays_date'],$val['jo_code']);

									$table.='<td rowspan="'.$rcount.'" style="vertical-align:middle;">'.date('d-m-Y',strtotime($val['todays_date'])).'</td>';
									$lastDateValue = $val['todays_date'];
								}
							$table.='<td colspan="3">'.($val['remark'] ? $val['remark'].'<br>'.$judgeLeave : $judgeLeave).'</td>
									</tr>';
						}
					}
				}
				else
				{
					$holidayName=getHolidays($conn_p,$tdate);
					$leaveType=fetchJoLeaveType($jocode,$conn_p,$tdate);

					$showLeaveOrHoliday = $leaveType ? $leaveType : $holidayName;

					$table.='<tr class="text-center" style="font-size:20px;">
								<td>'.date('d-m-Y',strtotime($tdate)).'</td>
								<td colspan="3">'.($showLeaveOrHoliday ? $showLeaveOrHoliday : '<font color="royalblue">Not Available</font>').'</td>
							</tr>';
				}
		
			}
			$table.='</tbody></table>';
		}
		else
		{
			$table='<font color="red" size="4">Please select all mandatory fields !</font>';
		}

		echo json_encode($table);
	}



	function getRowCount($dbname,$myear,$jocode)
    {
        $selQuery = "SELECT count(todays_date)as rc FROM uk_periphery.sitting_register_tb where todays_date=:tdate AND jo_code=:jcode AND bench_time is not null GROUP BY todays_date";
        $bindarray=array(":tdate"=>$myear,":jcode"=>$jocode);
        $stmt = $dbname->prepare($selQuery);
        $stmt->execute($bindarray);
        $rowchk = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $rowchk['rc'];
    }

    function getJodetails($dbname,$jocode)
    {
        // $selQuery = "SELECT concat(jo_name,' - ',jo_desg)jodetail FROM uk_periphery.sitting_register_tb where TO_CHAR(todays_date, 'MM-YYYY')=:tdate AND jo_code=:jcode LIMIT 1";
        $selQuery = "SELECT * from
		(select tt.desg_code, tt.court_no, dt.desgname, tt.judge_name, tt.incharge from 
		(select jt.desg_code,ct.court_no, nt.judge_name, jt.incharge,nt.jocode from court_t ct
		left join judge_t jt on ct.court_no= jt.court_no
		left join judge_name_t nt on nt.jocode= jt.jocode and jt.judge_code= nt.judge_code
		where jt.to_dt is null and ct.display='Y' and nt.display= 'Y' order by ct.court_no ) tt
		left join desg_t dt on tt.desg_code= dt.desgcode where tt.jocode=:jcode) tb
		order by court_no";

        $bindarray=array(":jcode"=>$jocode);
        $stmt = $dbname->prepare($selQuery);
        $stmt->execute($bindarray);
        $rowchk = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $rowchk['judge_name'].' - '.$rowchk['desgname'];
    }


    function getHolidays($dbname,$tdate)
    {
        $selQuery = "SELECT holidayname from public.holiday_t where display='Y' and holidaydate=:tdate ";
        $bindarray=array(":tdate"=>$tdate);
        $stmt = $dbname->prepare($selQuery);
        $stmt->execute($bindarray);
        $rowchk = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $rowchk['holidayname'] ? '<font color="red">'.ucwords(strtolower($rowchk['holidayname'])).'</font>' : '' ;
    }

	function range_date($first, $last) 
	{
		$arr = array();
		$end_date=date('Y-m-d', strtotime("+1 day", strtotime($last)));

		$period = new DatePeriod(new DateTime($first),new DateInterval('P1D'),new DateTime($end_date));

		foreach ($period as $key => $value) 
		{
			$getDate = $value->format('Y-m-d');
			$get_name = date('l', strtotime($getDate)); 
        	$day_name = substr($get_name, 0, 3); 
        	
        	// if($day_name != 'Sun')
        	// {
            	$arr[] = $getDate;	
        	// }          
		}
		return $arr;
	}

	function fetchJoLeaveType($jcode,$dbname,$tdate)
	{
		// $conn_p=db_connection($dbname);

		$selQuery = "SELECT leave_type_name,halfday,leavetype from judgeleave_t jt
					left join leave_type lt ON lt.leave_type_code=jt.leave_type
					left join judge_name_t jnt ON jnt.judge_code=jt.judge_no
					where jnt.jocode=:jcode and jt.leave_date<=:tdate AND jt.to_date>=:tdate and jt.display='Y' ";
        $bindarray=array(":tdate"=>$tdate,":jcode"=>$jcode);
        $stmt = $dbname->prepare($selQuery);
        $stmt->execute($bindarray);
        $rowchk = $stmt->fetch(PDO::FETCH_ASSOC);

		$joLeave='';
		if($rowchk)
		{
			if($rowchk['leavetype']==1)
			{
				$joLeave =  $rowchk['halfday']=='Y' ? '<font color="green">Half Day '.ucwords(strtolower($rowchk['leave_type_name'])).' Leave</font>' : '<font color="green">'.ucwords(strtolower($rowchk['leave_type_name'])).' Leave</font>' ;
			}
			elseif($rowchk['leavetype']==2)
			{
				$joLeave = $rowchk['halfday']=='Y' ? '<font color="green">Half Day Not Presided</font>' : '<font color="green">Not Presided</font>' ;
			}
			
			return $joLeave;
		}
			
	}

?>