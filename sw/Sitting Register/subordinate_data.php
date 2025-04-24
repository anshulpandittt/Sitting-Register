<?php

    session_start();

	if ( ! isset($_SESSION['sessionUserId'])) 
	{
		header("Location: ../../logout.php");		
	}

    include_once('includes/hp_conn.php');
    include('includes/qfunctions.php');
    
    $rr=checkDesignation($_SESSION['sessionjocode']);

    if(!$rr)
    {
        echo '<font color="red" size="4" style="text-align:center;vertical-align:middle;">Only District & Sessions Judge is authorized to view this page!</font>'; die;
    }

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
					<h3 class="card-title"><b>DAILY SITTING MONITORING</b></h3>
                <br/>
                <div class="form-inline offset-md-1"> 
                    <div class="form-group col-sm-4" style="width:auto !important;padding-right:0px!important;">
                        <label for="estcode" style="font-weight: bold;padding-right:10px;"><span style="color: red;padding-right:5px;">*</span>Establishment :</label>
                        <?php getAllEstablishments() ?>
                    </div>
                    <div class="form-group col-sm-4" style="width:auto !important;padding-right:0px!important;">
                        <label for="courtName" id="clabel" style="font-weight: bold;padding-right:10px;"><span style="color: red;padding-right:5px;">*</span>Court :</label>
                        <select id='courtName' name='courtName' class='form-control' style="width: 350px;">
                            <option value='0'>Select</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3" style="width:auto !important;padding-right:0px!important;">
                        <label for="fdate" style="font-weight: bold;padding-right:10px;"><span style="color: red;padding-right:5px;">*</span>Month :</label>
                        <input class="form-control text-center" id="fdate" name="fdate" placeholder="Select Month" type="text" autocomplete="off" />
                    </div>
                    <div class="form-group col-sm-1" style="width:auto !important;padding-right:0px!important;">
                        <button type="button" id="viewDailyRegister" class="btn btn-sm btn-primary"><b>GO</b></button>
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
        });
    
    });

    $('#estcode').on('change', function(e)
    {
        e.preventDefault();

        $('#dtable').html('');
        var dname = this.value.split("~");

        $.ajax({ 
            url: 'includes/qfunctions.php',
            type: 'post',
            data: {funCall: 'fetch_courts',dbname: dname[1]},
            success:function(result)
            {
                $('#courtName').html(result);
            }
        });
            
    });

    $('#viewDailyRegister').on('click', function(e)
    {
        e.preventDefault();

        var ecode = $('#estcode').val();
        var cname = $('#courtName').val();
        var dataMonth = $('#fdate').val();

        $.ajax({ 
            url: 'get_data.php',
            type: 'post',
            data: {flag:'SUBORDINATE_DATA',estcode:ecode,courtname:cname,month:dataMonth},
            dataType: 'json',
            success:function(result)
            {
                if(result.includes('mandatory fields'))
                    $('#print').hide(); 
                else
                    $('#print').show();

                $('#dtable').html(result);
            }
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