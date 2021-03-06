<?php $this->load->view('templates/header') ?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  	<link rel="stylesheet" type="text/css" href="/assets/css/appointment_views/appointment_views.css">
	<script type="text/javascript" src="/assets/js/input_active.js"> </script>
	<script type="text/javascript">
		$(document).ready(function(){
/******************************************** API call to Edmunds Car DB ***********************************************/				
		/******************************* Lists makes, models, & year on page load  ********************************/
		$.get("https://api.edmunds.com/api/vehicle/v2/makes?fmt=json&api_key=49kzsbfg3qem84zj35unehhs", function(res) { 
    			/********************* Set make to session or set_value if either == TRUE *******************/
    			var html_str = "html_str = <option><?= set_value('make')?></option>";
<?php 			if ($this->session->userdata("make") == TRUE && set_value("make") == FALSE): ?>
					html_str =  "<option> <?= $this->session->userdata('make')?> </option>";
<?php 			endif; ?>
				/******************************** Lists makes on page load *************************************/
				html_str += "<option> Other </option>"
                for(var i = 0; i < res.makes.length; i++) {
                    html_str += "<option>" + res.makes[i].name + "</option>";
                }
                $("#makers_list").html(html_str);
                /********************** Set model to session or set_value if either == TRUE *********************/
<?php 			if (!empty($this->session->userdata("model")) && empty(form_error('model')) && empty(set_value('model'))): ?>
					html_str =  "<option> <?= $this->session->userdata('model')?> </option>";
<?php			else: ?>
					html_str = "<option><?= set_value('model')?></option>";
<?php 			endif; ?>
				/************************************* Lists models on page load ***********************************/
				html_str += "<option> Other </option>"
				$("#models_list").html(html_str);
                for(var i = 0; i < res.makes.length; i++) {
        			if ($("#makers_list").val() == res.makes[i].name){
            			for(var j = 0; j < res.makes[i].models.length; j++) {
                        	html_str += "<option>" + res.makes[i].models[j].name + "</option>";
                        }
                    }
        		}
            	$("#models_list").html(html_str);
				html_str = "html_str = <option><?= set_value('year')?></option>";
				/********************** Set year to session or set_value if either == TRUE *********************/
<?php 			if (!empty($this->session->userdata("year")) && !is_null(set_value("year"))):?>
					html_str =  "<option> <?= $this->session->userdata('year')?> </option>";
<?php 			endif; ?>
				/************************************* Lists models on page load ***********************************/
            	html_str += "<option> Other </option>"; 	
            	for(var i = 0; i < res.makes.length; i++) {
        			if ($("#makers_list").val() == res.makes[i].name){
            			for(var j = 0; j < res.makes[i].models.length; j++) {
                        	if ($("#models_list").val() == res.makes[i].models[j].name){
                        		for(var k = 0; k < res.makes[i].models[j].years.length; k++){
                        			html_str += "<option>" + res.makes[i].models[j].years[k].year + "</option>";
                        		}
                        	}
                        }
                    }
        		}
            	$("#years_list").html(html_str);
        /******************************* Model and Year list updates on change to make value ***************************/
                $("#makers_list").change(function(){
                 html_str = "<option></option>";
                 $("#years_list").html('');
                 html_str += "<option> Other </option>"; 
                 for(var i = 0; i < res.makes.length; i++) {
                     if ($("#makers_list").val() == res.makes[i].name){
                         for(var j = 0; j < res.makes[i].models.length; j++) {
                             html_str += "<option>" + res.makes[i].models[j].name + "</option>";
                            }
                        }
                 }
                 $("#models_list").html(html_str);
                });
        /************************************ Years list updates on change to model value ******************************/
                $("#models_list").change(function(){
                 html_str = "<option> </option>";
                 html_str += "<option> Other </option>"; 
                 for(var i = 0; i < res.makes.length; i++) {
                     if ($("#makers_list").val() == res.makes[i].name){
                         for(var j = 0; j < res.makes[i].models.length; j++) {
                             if ($("#models_list").val() == res.makes[i].models[j].name){
                                 for(var k = 0; k < res.makes[i].models[j].years.length; k++){
                                     html_str += "<option>" + res.makes[i].models[j].years[k].year + "</option>";
                                 }
                             }
                            }
                        }
                 }
                 $("#years_list").html(html_str);
            	});
            }, "json");
/*************************************** jQuery UI Datepicker ****************************************/
   			$(function() {	
   				$( "#date" ).datepicker({
   					minDate: 0,
   					dateFormat: "DD, mm/dd/yy",
   				});
  			});
/**************************** Sets time value to session or set_value if either == TRUE*************************************/
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

			$(".additional-show").click(function(){
				if ($(".vehicle-add-container").is(":hidden")){
					$(".vehicle-add-container").slideDown("slow");
				}
				else{
					$(".vehicle-add-container").slideUp("slow");
				}
			});

			$(".additional-show1").click(function(){
				if ($(".appt-add-container").is(":hidden")){
					$(".appt-add-container").slideDown("slow");
				}
				else{
					$(".appt-add-container").slideUp("slow");
				}
			});
		});
	</script>
</head>
<?php $this->load->view('templates/navbar') ?>
	<div class="container-fluid location-header">
		<h3>REQUEST APPOINTMENT: SUMMARY</h3>
	</div>
	<?php $this->load->view('templates/schedule-breadcrumb')?>
	<div class="step-location">
		<table>
			<tr>
				<td><a class="glyphicon glyphicon-arrow-left" href="/request-mobile-detailing-appointment-appt-details"></a></td>
				<td><h4>Step 4 of 4</h4></td>
				<td></td>
			</tr>
		</table>
	</div>
	<span class = "validation_prompt text-center"> <?= $this->session->flashdata('email_error') ?> </span>
			<div class="row scheduling-info">
			<div class="col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4 sched-prompt">
				<p>Please review the information below and submit.</p>
				<p>We will contact you within 24 hours to confirm the date, time, and price once you have submitted your appointment request.</p>
			</div>
		</div>
	<div class="row form-container3">
	<form class = "form-horizontal form-container1" action = "/request-mobile-detailing-appointment-summary" method = "post" id = "details">
<!-- ************************************** First Name Input *************************************** -->
		<h4 class="appt-step">Contact</h4>
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> First Name: </label>
			<div class = "col-sm-7 col-md-4">
	<!--**** 1. Add class if form_error == TRUE  2. Set value to session('first_name') if == TRUE 3. Set 
			'required' attribute ****-->
				<input type="text" class="form-control 
<?php 			if (form_error('first_name') == TRUE):?>
					input_active
<?php 			endif; ?>" 			
				name ="first_name" id = "first_name"
				value="<?= set_value("first_name") ?>
<?php 			if ($this->session->userdata("first_name") == TRUE && set_value("first_name") == FALSE) {
					echo $this->session->userdata("first_name"); 
				} 
?>"				required oninvalid="this.setCustomValidity('Please include a first name.')" 
				x-moz-errormessage="Please include a first name." 
				onchange="this.setCustomValidity('')"
				autocomplete = "on">
				<span class = "validation_prompt"><?= form_error('first_name')?></span>
			</div>
		</div>
<!-- ************************************** Last Name Input *************************************** -->
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Last Name: </label>
			<div class = "col-sm-7 col-md-4">
	<!--**** 1. Add class if form_error == TRUE  2. Set value to session('last_name') if == TRUE 3. Set 
			'required' attribute ****-->
				<input type="text" class="form-control
<?php 			if (form_error('last_name') == TRUE):?>
					input_active
<?php 			endif; ?>" 	
				name = "last_name" id = "last_name"
				value="<?= set_value('last_name') ?>
<?php 
				if ($this->session->userdata("last_name") == TRUE && set_value("last_name") == FALSE) {
					echo $this->session->userdata("last_name"); 
				} 
?>"				required oninvalid="this.setCustomValidity('Please include a last name.')" 
				x-moz-errormessage="Please include a last name." 
				onchange="this.setCustomValidity('')">
				<span class = "validation_prompt"><?= form_error('last_name')?></span>	
			</div>
		</div>
<!-- ************************************** Phone Number Input *************************************** -->
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Phone Number: <br></label>
			<div class = "col-sm-7 col-md-4">
	<!--**** 1. Add class if form_error == TRUE  2. Set value to session('phone_no') if == TRUE 3. Set 
			'required' attribute ****-->
				<input type="text" class="form-control
<?php 			if (form_error('phone_no') == TRUE):?>
					input_active
<?php 			endif; ?>" 	
	 			name = "phone_no" id = "phone_no"
				value="<?= set_value('phone_no') ?>
<?php 
				if ($this->session->userdata("phone_no") == TRUE && set_value("phone_no") == FALSE) { 
					echo $this->session->userdata("phone_no"); 
				} 
?>"				required oninvalid="this.setCustomValidity('Please include a valid phone number.')" 
				x-moz-errormessage="Please include a valid phone number." 
				onchange="this.setCustomValidity('')">
				<span class = "validation_prompt"><?= form_error('phone_no')?></span>	
			</div>
		</div>
<!-- ************************************** Email Input *************************************** -->
		<div class="form-group">
    		<label for="inputEmail3" class="col-sm-3 col-md-offset-2 col-md-2 control-label">Email:</label>
   			 <div class="col-sm-7 col-md-4">
   	<!--**** 1. Add class if form_error == TRUE  2. Set value to session('email') if == TRUE 3. Set 
			'required' attribute ****-->
      			<input type="email" class="form-control
<?php 			if (form_error('email') == TRUE):?>
					input_active
<?php 			endif; ?>" 	
	 			id="inputEmail3" name = "email" id = "email"
				value="<?= set_value('email') ?> 
<?php 
				if ($this->session->userdata("email") == TRUE && set_value("email") == FALSE) {
					echo $this->session->userdata("email"); 
				} 
?>"				required oninvalid="this.setCustomValidity('Please include a valid email address.')" 
				x-moz-errormessage="Please include a valid email address." 
				onchange="this.setCustomValidity('')">
				<span class = "validation_prompt"><?= form_error('email')?> </span>
    		</div>
  		</div>
<!-- ************************************** Make Input *************************************** -->	
		<h4 class="appt-step">Vehicle</h4>	
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Make: </label>
				<div class = "col-sm-7 col-md-4">
				<!--**** 1. Add class if form_error == TRUE 2. Set 'required' attribute ****-->
					<select name = "make" class = "form-control
<?php 				if (form_error('make') == TRUE):?>
						input_active
<?php 				endif; ?>" 	
					id = "makers_list"
					required oninvalid = "this.setCustomValidity('Please choose a make.')"
					onchange = "this.setCustomValidity('')"
					x-moz-errormessage = "Please choose a make.">
					</select>
					<span class = "validation_prompt"><?= form_error('make')?> </span>
				</div>
		</div>
<!-- ************************************** Model Input *************************************** -->
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Model: </label>
				<div class = "col-sm-7 col-md-4">
				<!--**** 1. Add class if form_error == TRUE 2. Set 'required' attribute ****-->
					<select name = "model" class = "form-control
<?php 				if (form_error('model') == TRUE):?>
						input_active
<?php 				endif; ?>" 	
					id = "models_list"
					required oninvalid = "this.setCustomValidity('Please choose a model.')"
					onchange = "this.setCustomValidity('')"
					x-moz-errormessage = "Please choose a model.">
					</select>
					<span class = "validation_prompt"><?= form_error('model')?> </span>
				</div>
		</div>
<!-- ************************************** Year Input *************************************** -->
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Year: </label>
				<div class = "col-sm-7 col-md-4">
				<!--**** 1. Add class if form_error == TRUE 2. Set 'required' attribute ****-->
					<select name = "year" class = "form-control
<?php 				if (form_error('year') == TRUE):?>
						input_active
<?php 				endif; ?>" 	
					id = "years_list"
					required oninvalid = "this.setCustomValidity('Please choose a year.')"
					onchange="this.setCustomValidity('')"
					x-moz-errormessage="Please choose a year.">
					</select>
					<span class = "validation_prompt"><?= form_error('year')?> </span>
				</div>
		</div>
<!-- ***************************** Optional Additional Vehicle Info ************************** -->
<?php 	if($this->session->userdata("vehicle_additional") == TRUE): ?>
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Additional Info:</label>
			<div class = "col-sm-7 col-md-5">
				<textarea class="form-control" rows="4" name = "vehicle_additional" 
				placeholder = "1. Select 'Other' in all the dropdowns above 2. Write the make, model, and year in this text-box."><?= $this->session->userdata('vehicle_additional')?></textarea>
			</div>
		</div>
<?php 	else: ?>		
		<div class="row">
			<h5 class="additional-show-prompt">If you don't see your vehicle <span class="additional-show additional-show-link">click here</span>.</h5>
		</div>
		<div class = "form-group vehicle-add-container">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Vehicle Additional Info:</label>
			<div class = "col-sm-7 col-md-5">
				<textarea class="form-control" rows="4" name = "vehicle_additional" 
				placeholder = "1. Select 'Other' in all the dropdowns above 2. Write the make, model, and year in this text-box."><?= $this->session->userdata('vehicle_additional')?></textarea>
			</div>
		</div>
<?php 	endif; ?>
<!-- ************************************** Package Input *************************************** -->
		<h4 class="appt-step">Service</h4>
		<div class = "text-center">
			<a href="/request-mobile-detailing-appointment-vehicle-service" target="_blank">See Packages &amp; Services</a>
		</div> <br>
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Package:</label>
			<div class = "col-sm-7 col-md-4">
			<!--**** 1. Add class if form_error == TRUE 2. Set required attribute ****-->
				<select name = "package" class = "form-control
<?php 			if (form_error('package') == TRUE):?>
					input_active
<?php			endif; ?>"
				id = "package" 
				required oninvalid = "this.setCustomValidity('Please choose a package.')"
				x-moz-errormessage = "Please choose a package."
				onchange = "this.setCustomValidity('')">
				<option><?= set_value('package')?>
<?php 				if ($this->session->userdata("package") == TRUE && set_value("package") == FALSE): ?>
						<?= $this->session->userdata("package") ?> 
<?php				endif; ?>
				</option>
				<option>Classic Shine</option>
				<option>Plus Shine</option>
				<option>Premium Shine</option>
				<option>Ideal Shine</option>
				<option>Additional Service(s) Only</option>
				</select>
				<span class = "validation_prompt"><?= form_error('package')?></span>	
			</div>
		</div>
		<div class = "validation_prompt"> <?= $this->session->flashdata('add-services') ?> </div>
		<h4 class="appt-step">Additional Services</h4>
			<div class="row add_service text-center">
				<table>
					<tr>
						<td><input type="checkbox" name="deep_clean" value="Upholstery Deep Clean"
<?php 				if($this->session->userdata("deep_clean") !== FALSE):?>
						checked="checked"
<?php 				endif; ?>
						> Upholstery Deep Clean</td>
					</tr>
					<tr>
						<td><input type="checkbox" name="headlight_restoration" value="Headlight Restoration"
<?php 				if($this->session->userdata("headlight_restoration") !== FALSE):?>
						checked="checked"
<?php 				endif; ?>
						> Headlight Restoration</td>
					</tr>
					<tr>
						<td><input type="checkbox" name="pet_hair_removal" value="Pet Hair Removal"
<?php 				if($this->session->userdata("pet_hair_removal") !== FALSE):?>
						checked="checked"
<?php 				endif; ?>
						> Pet Hair Removal</td>
					</tr>
					<tr>
						<td><input type="checkbox" name="exterior_trim" value="Exterior Trim Restoration"
<?php 				if($this->session->userdata("exterior_trim") !== FALSE):?>
						checked="checked"
<?php 				endif; ?>
						> Exterior Trim Restoration</td>
					</tr>
				</table>
			</div>
<!-- ************************************** Date Input *************************************** -->
		<h4 class="appt-step">Date &amp; Time</h4>			
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
		<h4 class="appt-step">Location</h4>
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
<?php 	if($this->session->userdata("appt_additional") == TRUE):?>
		<div class = "form-group">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Additional Info:</label>
			<div class = "col-sm-7 col-md-5">
				<textarea class="form-control" rows="4" name = "appt_additional" 
				placeholder = "Ex: Gated community, on a private road, pets we should be aware of, etc."><?= $this->session->userdata('appt_additional')?></textarea>
			</div>
		</div>
<?php 	else: ?>
		<div class="row">
			<h5 class="additional-show-prompt">Additional notes about appointment and/or location <span class="additional-show1 additional-show-link">click here</span>.</h5>
		</div>
		<div class = "form-group appt-add-container">
			<label class = "col-sm-3 col-md-offset-2 col-md-2 control-label"> Additional Info: </label>
			<div class = "col-sm-7 col-md-5">
				<textarea class="form-control" rows = "4" name = "appt_additional" placeholder="Ex: Gated community, on a private road, pets we should be aware of, etc."><?= $this->session->userdata('appt_additional')?></textarea>
			</div>
		</div>
<?php   endif;?>
		<!-- ************************************** Bot/Spam Test *************************************** -->
		<div style="display: none;">
			<label for = "alt_phone"> Alt Phone </label>
			<input type = "text" id = "alt_phone" name = "alt_phone">
		</div>
		<!-- ********************************************************************************************* -->
		<div class = "form-group">
			<div class = "text-center">
				<button type="submit" class="submit-btn">Submit Appointment Request</button>
			</div>
		</div>
	</form>
	</div>
<?php $this->load->view('templates/footer') ?>