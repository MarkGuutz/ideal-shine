<?php $this->load->view('templates/header') ?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  	<link rel="stylesheet" type="text/css" href="/assets/css/appointment_views/appointment_views.css">
  	<script type="text/javascript" src="/assets/js/input_active.js"> </script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(function() {
/*************************************** jQuery UI Datepicker ****************************************/
   				$( "#date" ).datepicker({
   					minDate: 0,
   					dateFormat: "DD, mm/dd/yy",
   				});
  			});
/**************************** Sets value to session or set_value if either == TRUE*************************************/
<?php 		if (!empty($this->session->userdata("time")) && empty(set_value('time')) && empty(form_error('time'))):?>
				html_str =  "<option> <?= $this->session->userdata('time')?> </option>";
<?php  		else: ?>
				html_str = "<option><?= set_value('time')?></option>";
<?php 		endif; ?>
/********************************** Function to choose appt. time based on day ****************************************/
			var date = function(html_str){
				if($("#date").val().substring(0, 3) == "Thu" || $("#date").val().substring(0, 3) == "Fri"){
					for (var i = 8; i < 12; i++){
						html_str += "<option>" + i + ":00am</option>";
					}
					html_str += "<option> 12:00 pm </option>"
					for (var i = 1; i < 9; i++){
						html_str += "<option>" + i + ":00pm</option>";
					}
					$("#time").html(html_str);
				}
				else if($("#date").val().substring(0, 3) !== ""){
					for (var i = 3; i < 9; i++){
						html_str += "<option>" + i + ":00pm</option>";
					}
						$("#time").html(html_str);
					}
			}
			date(html_str);
/********************************** When date is changed, time is set to emtpy ****************************************/
			$("#date").change(function(){
				html_str = "<option> </option>";
				date(html_str);
			});
		});
	</script>
</head>
<?php $this->load->view('templates/navbar') ?>
	<div class="container-fluid location-header">
		<h3>Schedule Appointment</h3>
	</div>
	<?php $this->load->view('templates/schedule-breadcrumb')?>
	<a class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 glyphicon glyphicon-arrow-left" href="/appointment/vehicle"></a>
<?php 	if($this->session->userdata("appt_info")=="complete"):?>
	<a class="col-xs-offset-8 col-sm-offset-9 col-md-offset-9 glyphicon glyphicon-arrow-right" href="/appointment/details"></a>
<?php	endif;?>
	<form class = "form-horizontal" action = "/appointment/appt" id = "apptForm" method = "post">
		<h3 class="col-md-offset-2">Date &amp; Time</h3>
<!-- ************************************** Date Input *************************************** -->			
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Date: </label>
			<div class = "col-sm-7 col-md-4">
			<!--**** 1. Add class if form_error == TRUE 2. Set required attribute ****-->
				<input type = "text" class = "form-control 
<?php 			if (form_error('date') == TRUE):?>
					input_active 
<?php 			endif; ?>" 
				name = "date" id = "date"
				value = "<?= set_value("date")?>
<?php 
				if ($this->session->userdata("date") == TRUE && set_value("date") == FALSE) {
					echo $this->session->userdata("date"); 
				} 
?>"				required oninvalid = "this.setCustomValidity('Please choose a date.')" 
				x-moz-errormessage = "Please choose a date." 
				onchange="this.setCustomValidity('')">
				<span class = "validation_prompt"><?= form_error('date')?></span>
			</div>
		</div>
<!-- ************************************** Time Input *************************************** -->
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Time: </label>
			<div class = "col-sm-7 col-md-4">
			<!--**** 1. Add class if form_error == TRUE 2. Set required attribute ****-->
				<select name = "time" class = "form-control
<?php 			if (form_error('time') == TRUE):?>
					input_active
<?php 			endif; ?>"
				id = "time" 
				required oninvalid = "this.setCustomValidity('Please choose a time.')"
				x-moz-errormessage = "Please choose a time."
				onchange = "this.setCustomValidity('')">
				</select>
				<span class = "validation_prompt"><?= form_error('time')?></span>	
			</div>
		</div>
<!-- ************************************** Street Input *************************************** -->
		<h3 class="col-md-offset-2">Location</h3>
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Street: </label>
			<div class = "col-sm-7 col-md-4">
			<!--**** 1. Add class if form_error == TRUE 2. Set required attribute ****-->
				<input type = "text" class = "form-control
<?php 			if (form_error('street') == TRUE):?>
					input_active
<?php			endif; ?>"
				name = "street" id = "street"
				value="<?= set_value('street')?>
<?php 
				if ($this->session->userdata("street") == TRUE && set_value("street") == FALSE) {
					echo $this->session->userdata("street"); 
				} 
?>" 			required oninvalid = "this.setCustomValidity('Please include a street.')" 
				x-moz-errormessage = "Please include a street." 
				onchange="this.setCustomValidity('')">
				<span class = "validation_prompt"><?= form_error('street')?></span>	
			</div>
		</div>
<!-- ************************************** City Input *************************************** -->
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> City:</label>
			<div class = "col-sm-7 col-md-4">
			<!--**** 1. Add class if form_error == TRUE 2. Set required attribute ****-->
				<input type="text" class="form-control
<?php 			if (form_error('city') == TRUE):?>
					input_active
<?php			endif; ?>" 
				name = "city" id = "city"
				value="<?= set_value('city') ?>
<?php 
				if ($this->session->userdata("city") == TRUE && set_value("city") == FALSE) { 
					echo $this->session->userdata("city"); 
				} 
?>"				required oninvalid = "this.setCustomValidity('Please include a city.')" 
				x-moz-errormessage = "Please include a city." 
				onchange = "this.setCustomValidity('')">
				<span class = "validation_prompt"><?= form_error('city')?></span>	
			</div>
		</div>
<!-- ************************************** Zip Input *************************************** -->
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Zip:</label>
			<div class = "col-sm-7 col-md-4">
			<!--**** 1. Add class if form_error == TRUE 2. Set required attribute ****-->
				<input type="text" class="form-control
<?php 			if (form_error('zip') == TRUE):?>
					input_active
<?php			endif; ?>"
				name = "zip" id = "zip"
				value="<?= set_value('zip') ?>
<?php 
				if ($this->session->userdata("zip") == TRUE && set_value("zip") == FALSE) { 
					echo $this->session->userdata("zip"); 
				} 
?>"				required oninvalid = "this.setCustomValidity('Please include a zip.')" 
				x-moz-errormessage = "Please include a zip." 
				onchange = "this.setCustomValidity('')">
				<span class = "validation_prompt"><?= form_error('zip')?></span>	
			</div>
		</div>
<!-- ************************************** Optional Additional Appointment Input *************************************** -->
  		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Additional Info: </label>
			<div class = "col-sm-7 col-md-5">
				<textarea class="form-control" rows = "4" name = "appt_additional" placeholder="Ex: Gated community, on a private road, pets we should be aware of, etc."><?= $this->session->userdata('appt_additional')?></textarea>
			</div>
		</div>
		<div class = "form-group">
			<div class = "col-xs-offset-4 col-sm-offset-5 col-md-offset-5">
				<button type = "submit" class = "submit-btn">Continue to Vehicle Info</button>
			</div>
		</div>
	</form>
<?php $this->load->view('templates/footer') ?>