<?php

include_once('includes/hp_conn.php');
include('includes/qfunctions.php');

?>

<!DOCTYPE html>
<html>
<head>
	<title>Daily Sitting Record</title>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-datepicker.min.css"/>

	<style>
		.mp{
		    align-items: center;
			background: #e9ecef;
			/* display: -ms-flexbox; */
			margin-top: 30px;
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
		<div class="card shadow-lg p-3 mb-5 bg-white rounded" style="width: 90%;">
			<div class="card-body" style="padding: 0rem !important;">
					<h3 class="card-title"><b>REGISTER OF DAILY SITTINGS</b></h3>
				<div>
					<div class="form-group">
						<!-- <label class="control-label" for="date">Date</label> -->
						<input class="form-control text-center" id="fdate" name="fdate" placeholder="Select Month" type="text" autocomplete="off" style="width: 20%;" />
					</div>
				</div>
				<div id="print" style="display:none; float:right;">
					<button type="button" class="btn btn-md btn-link" onclick="printDiv('dtable')">Print</button>
				</div>
				<br/>

				<div id="dtable"></div>
			</div>
		</div>
	</center>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datepicker.min.js"></script>
    <iframe id="ifrmPrint" style="display: none;"></iframe>


</body>
</html>

<script>
    $(document).ready(function(){

        $('input[name="fdate"]').datepicker({
            format: 'mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startView: "months", 
            minViewMode: "months",
            endDate: '+0d',
			orientation: "auto"
        }).on('changeDate', function(ev){

        		// var cdate=$('#fdate').val();

		        $.ajax({
				url: 'get_data.php',
				method: "post",
				data: {flag: 'all_data',fdate: this.value},
				dataType: 'json',
				beforeSend: function() 
				{
			       $('#dtable').html('<img src="loader.gif" >');
			    },
				success: function(response) 
				{
					if(response.includes('No record found !'))
						$('#print').hide();
					else
						$('#print').show();
						
					$('#dtable').html(response);
				}
			});

	    });
    
    });


	function printDiv(view) 
    {
        try
        {
            var oIframe = document.getElementById('ifrmPrint');
            var oContent = document.getElementById(view).innerHTML;
            var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
            if (oDoc.document) oDoc = oDoc.document;
            oDoc.write('<html><head><link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">');
            oDoc.write('</head><body onload="this.focus(); this.print();"><center><h4><b><u>REGISTER OF DAILY SITTINGS</u></b></h4></center><br>');
            oDoc.write(oContent + '</body>');
            oDoc.close();
        } 
        catch(e)
        {
            self.print();
        }
    };

</script>