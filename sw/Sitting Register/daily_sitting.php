<?php
	session_start();

	if ( ! isset($_SESSION['sessionUserId'])) 
	{
		header("Location: ../../logout.php");		
	}
	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// $username = $_SESSION['est_id'];
	// echo $username.'--uname';

	date_default_timezone_set('Asia/Kolkata');
	$time = date("h:i:s a");
	$today = date("D - d F, Y");
	$date = date("Y-m-d");

	
	$judgeName=$_SESSION['sessionJudge_name'];
	$desgName=$_SESSION['sessiondesg_name'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sitting Register</title>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

	<style>
		.mp{
		    align-items: center;
			background: #e9ecef;
			/* display: -ms-flexbox; */
			margin-top: 10px;
			/* display: flex; */
			/* -ms-flex-direction: column; */
			flex-direction: column;
			/* height: 100vh; */
			/* -ms-flex-pack: center; */
			justify-content: center;
		}

		.login-logo {
			font-size: 2.1rem;
			font-weight: 300;
			margin-bottom: 0.9rem;
			text-align: center;
		}
		.shadow {
			box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
		}

		.shadow-lg {
			box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
		}
	</style>


</head>
<body class="mp">
	<center>

		<div class="login-logo">
            <p id="date"><?php echo $today; ?></p>
            <p id="time" style="font-weight: bold;"><?php echo $time; ?></p>
        </div>

		<div class="card shadow-lg p-3 mb-5 bg-white rounded" style="width: 90%;">
			<div class="card-body" style="padding: 0rem !important;">
				<h3 class="card-title"><b>REGISTER OF DAILY SITTINGS</b></h3>
				<h6 class="card-subtitle mb-2 text-muted"><b>IN THE COURT OF -</b> <?php echo strtoupper($judgeName.' ('.$desgName.')'); ?></h6>
				<div style="margin-top: 20px;">
					<input class="btn btn-success btn-lg" type="button" id="in-chamber" value="In Chambers" style="font-weight: bold;">
					<!-- <input class="btn btn-primary btn-lg" type="button" id="in-chamber" value="In Chamber" onclick="disableButton('in-chamber','on-bench')"> -->

					<input class="btn btn-danger btn-lg" type="button" id="on-bench" value="On the Bench" style="font-weight: bold;">
					<input class="btn btn-info btn-lg" type="button" id="dsr_remark" value="Remarks" style="font-weight: bold;">
				</div>
				<br/>
				<div id="dtable"></div>

			</div>
		</div>

	</center>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/sweetalert2.min.js"></script>
    <script src="js/moment.min.js"></script>

	
</body>
</html>


<script type="text/javascript">

	// var interval = setInterval(function() {
    //     var momentNow = moment();
    //     $('#date').html(momentNow.format('dddd').substring(0, 3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));
    //     $('#time').html(momentNow.format('hh:mm:ss A'));
    // }, 100);


	$(document).ready(function () {

		setInterval(runningTime,1000);

		$.ajax({
			url: 'get_data.php',
			method: "post",
			data: {flag: 'todays_data'},
			dataType: 'json',
			success: function(response) 
			{//console.log(response);
				if(response.status==200)
				{
					$('#dtable').html(response.data);
					if(response.rawData[response.rawData.length-1].bench_time==null && response.rawData[response.rawData.length-1].chamber_time)
					{
						document.getElementById('in-chamber').disabled = true;
						document.getElementById('on-bench').disabled = false;
					}
					else if(response.rawData[response.rawData.length-1].chamber_time==null && response.rawData[response.rawData.length-1].bench_time)
					{
						document.getElementById('in-chamber').disabled = false;
						document.getElementById('on-bench').disabled = true;
					}
					else if(response.rawData[response.rawData.length-1].remark && response.rawData[response.rawData.length-1].chamber_time==null && response.rawData[response.rawData.length-1].bench_time==null)
					{
						document.getElementById('in-chamber').disabled = false;
						document.getElementById('on-bench').disabled = true;
					}
					else if(response.rawData[response.rawData.length-1].chamber_time && response.rawData[response.rawData.length-1].bench_time)
					{
						document.getElementById('in-chamber').disabled = false;
						document.getElementById('on-bench').disabled = true;
					}
				}
				else if(response.status==400)
				{
					document.getElementById('in-chamber').disabled = false;
					document.getElementById('on-bench').disabled = true;
				}
			}
		});

		// document.getElementById(localStorage.BtnId).disabled = true;

	});

	// function disableButton(first,second) 
	// {
	// 	document.getElementById(first).disabled = true;
	// 	document.getElementById(second).disabled = false;

	// 	localStorage.BtnId = first;
	// }

	function runningTime() {
		$.ajax({
			url: 'timeScript.php',
			success: function(data) {
				$('#time').html(data);
			},
		});
	}

	function gettabledata()
	{
		$.ajax({
			url: 'get_data.php',
			method: "post",
			data: {flag: 'todays_data'},
			dataType: 'json',

			success: function(response) 
			{
			   $('#dtable').html(response.data);
			}
		});
	}


	
	$('#in-chamber').on('click', function(e)
    {
        e.preventDefault();

        // var chamber_time = new Date().toLocaleTimeString(navigator.language, {hour: '2-digit', minute:'2-digit'});

        $.ajax({ 

        	url: "insert.php",
            type: "post",
            data: {flag: 'CHAMBER'},
         	dataType: 'json',

            success:function(result)
            {
				document.getElementById('in-chamber').disabled = true;
				document.getElementById('on-bench').disabled = false;
                gettabledata();
            }

        });
            
    });
		


	$('#on-bench').on('click', function(e)
	{
	    e.preventDefault();

	    // var bench_time = new Date().toLocaleTimeString(navigator.language, {hour: '2-digit', minute:'2-digit'});

	    $.ajax({ url: 'insert.php',
	             type: 'post',
	             data: {flag: 'BENCH'},
	             dataType: 'json',
	             success:function(result)
	             {
					document.getElementById('on-bench').disabled = true;
					document.getElementById('in-chamber').disabled = false;
	                gettabledata();
	             }
	        });
	        
	});

	$('#dsr_remark').on('click', function(e)
	{
	    e.preventDefault();

		Swal.fire({
			title: 'Enter remark',
			input: 'text',
			inputAttributes: {
				autocapitalize: 'off'
			},
			showCancelButton: true,
			confirmButtonText: 'Submit',
			showLoaderOnConfirm: true,
			inputValidator: (value) => {
				if (!value) {
					return 'You need to write something!'
				}
			},
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({ 
						url: 'insert.php',
						type: 'post',
						data: {flag: 'REMARK',dsrRemark: result.value},
						dataType: 'json',
						success:function(result)
						{
							if(result.status==200)
							{
								// document.getElementById('on-bench').disabled = true;
								// document.getElementById('in-chamber').disabled = true;
								Swal.fire({
									icon: 'success',
									title: 'Done !',
									showConfirmButton: false,
									timer: 1500
								});
							}
							else if(result.status==400)
							{
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Something went wrong!',
								})
							}
								
							gettabledata();
						}
					});
				}
			});

	    // $.ajax({ url: 'insert.php',
	    //          type: 'post',
	    //          data: {flag: 'REMARK'},
	    //          dataType: 'json',
	    //          success:function(result)
	    //          {
	    //             gettabledata();
	    //          }
	    //     });
	        
	});

</script>