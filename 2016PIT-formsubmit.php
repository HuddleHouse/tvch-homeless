<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<link rel="stylesheet" type="text/css" href="./css/style.css" />
<link rel="stylesheet" type="text/css" href="./css/gridforms.css" />
<link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="./js/gridforms.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/validator.js"></script>
    <title></title>
</head>
<style>
	.button {
		text-decoration: initial;
	    border: 2px solid #111 !important;
	    color: #111;
	    padding: 8px;
	    font-size: large;
	}
</style>
<body>
	<div class="container">
		<div class="row" style="padding-top: 50px;">



<?php
	if(isset($_POST['county'])) {
		$time = date("Hi");

		$val = $_POST["hidden"];
		$isHotel = 0;
		$isTemp = 0;
		$isUnsheltered = 0;
		$isEmergency = 0;
		$isTrans = 0;


		if($val == "H"){
			$isHotel = 1;
		}
		else if ($val == "U"){
			$isUnsheltered = 1;
		}
		else if ($val == "Trans"){
			$isTrans = 1;
		}
		else if ($val == "Temp"){
			$isTemp = 1;
		}
		else if ($val == "E"){
			$isEmergency = 1;
		}

		include 'config.php';

		$county = $_POST['county'];
		$shelter = $_POST['shelter'];
		//$housing = $_POST['housing'];
		//$hotel = $_POST['hotel-paid'];

		if($shelter == "Church") {
			$hotelPaid = $_POST[$county.'-name2'];
		}
		else if($shelter == "Agency") {
			$hotelPaid = $_POST[$county.'-name1'];
		}

		//$tempStay = $_POST['temp-stay'];
		//$unsheltered = $_POST['unsheltered'];
		$notes = $_POST["notes"];
		$volunteer = $_POST["volunteer-name"];
		$location = $_POST["location"];
		$city = $_POST["city"];
		$zip = $_POST["zip"];
		$numAdults = $_POST["num-adults"];
		$numKids = $_POST["num-kids"];
		$date = date("Y-m-d");


		if($hotel == "Church" || $hotel =="Agency") {
			$sql = "insert into survey_data(county, emergency_shelter, hotel_paid_name, notes, volunteer_name, location, city, zip, num_adults, num_kids, is_trans, is_emergency, is_unsheltered, is_temp, is_hotel, date) values(:county, :emergency_shelter, :hotel_paid_name, :notes, :volunteer_name, :location, :city, :zip, :num_adults, :num_kids, :is_trans, :is_emergency, :is_unsheltered, :is_temp, :is_hotel, :date)";

			$q = $db->prepare($sql);
			$q->execute(array(':county' => $county,
								':emergency_shelter' => $shelter,
								':hotel_paid_name' => $hotelPaid,
								':notes' => $notes,
								':volunteer_name' => $volunteer,
								':location' => $location,
								':city' => $city,
								':zip' => $zip,
								':num_adults' => $numAdults,
								':num_kids' => $numKids,
								':is_trans' => $isTrans,
								':is_emergency' => $isEmergency,
								':is_unsheltered' => $isUnsheltered,
								':is_temp' => $isTemp,
								':is_hotel' => $isHotel,
								':date' => $date
						));
		}
		else {
			$sql = "insert into survey_data(county, emergency_shelter, notes, volunteer_name, location, city, zip, num_adults, num_kids, is_trans, is_emergency, is_unsheltered, is_temp, is_hotel, date) values(:county, :emergency_shelter, :notes, :volunteer_name, :location, :city, :zip, :num_adults, :num_kids, :is_trans, :is_emergency, :is_unsheltered, :is_temp, :is_hotel, :date)";

			$q = $db->prepare($sql);
			$q->execute(array(':county' => $county,
								':emergency_shelter' => $shelter,
								':notes' => $notes,
								':volunteer_name' => $volunteer,
								':location' => $location,
								':city' => $city,
								':zip' => $zip,
								':num_adults' => $numAdults,
								':num_kids' => $numKids,
								':is_trans' => $isTrans,
								':is_emergency' => $isEmergency,
								':is_unsheltered' => $isUnsheltered,
								':is_temp' => $isTemp,
								':is_hotel' => $isHotel,
								':date' => $date
						));
		}
		$count = $numAdults + $numKids;
		$lastId = $db->lastInsertId();

		$code = sprintf("%04d", $lastId);
		$confirmation = $time.'-'.$code.'-'.sprintf("%02d",$count);

		echo "<center><h1>Your entry has been submitted.</h1></center><center><h1>Please mark the following confirmation code on your paper survey:</h1></center><center><h2>$confirmation</h2></center><center><h3>For any issues or questions on the survey you submitted, please email <br>the confirmation code, along with a description of the issue to <a href='mailto:hmis@tvchomeless.org'>hmis@tvchomeless.org</a>.<h3></center>";

		for($i = 1; $i <= $count; $i++) {


			if($i > $numAdults){
				$i = $i + (5 - $numAdults);
				$iskid = 1;
			}
			else {
				$iskid = 0;
			}
			if($i == 1){
				$isHead = 1;
			}
			else {
				$isHead = 0;
			}
			$gender = $_POST['gender'.$i];
			$age = $_POST['age'.$i];
			$races = $_POST['race'.$i];
			$ethnicity = $_POST['ethnicity'.$i];
			$history = $_POST['history'.$i];
			$conditions = $_POST['condition'.$i];
			$poplulaiton = $_POST['population'.$i];
			$isMilitary = 0;
			$isViolence = 0;
			$history1 = 0;
			$history2 = 0;

			foreach($history as $his) {

				if($his == 'option1') {
					$history1 = 1;
				}
				else if($his == 'option2') {
					$history2 = 1;
				}
			}

			foreach($poplulaiton as $pop) {
				if($pop == 'military'){
					$isMilitary = 1;
				}
				else if($pop == 'violence') {
					$isViolence = 1;
				}
			}



			$sql = "insert into person_data(survey_data_id, is_head, gender, ethnicity, history_1, history_2, age, is_kid, is_military, is_violence) values(:survey_data_id, :is_head, :gender, :ethnicity, :history_1, :history_2, :age, :is_kid, :is_military, :is_violence)";
			$q = $db->prepare($sql);
			$q->execute(array(':survey_data_id' => $lastId,
				':is_head' => $isHead,
				':gender' => $gender,
				':ethnicity' => $ethnicity,
				':history_1' => $history1,
				':history_2' => $history2,
				':age' => $age,
				':is_kid' => $iskid,
				':is_military' => $isMilitary,
				':is_violence' => $isViolence
			));

			$personId = $db->lastInsertId();

			foreach($races as $race) {
				$sql = "insert into race_data(survey_data_id, person_data_id, racelist_id) values(:survey_data_id, :person_data_id, :racelist_id)";

				$q = $db->prepare($sql);
				$q->execute(array(
					':survey_data_id' => $lastId,
					':person_data_id' => $personId,
					':racelist_id' => $race
				));
			}

			foreach($conditions as $cond) {

				$sql = "insert into condition_data(survey_data_id, person_data_id, cond) values(:survey_data_id, :person_data_id, :cond)";

				$q = $db->prepare($sql);
				$q->execute(array(
					':survey_data_id' => $lastId,
					':person_data_id' => $personId,
					':cond' => $cond
				));
			}


			if($i > $numAdults){
				$i = $i - (5 - $numAdults);
			}
		}
		?>
		<br><br>
		<center><a href="/2016PIT-form.php" class="button">Submit Another Survey</a></center><br>
		<center><a href="/" class="button">View the Data</a></center>

	<?php }
	else {
		echo "<center><h2>No form data found.</h2></center><br><br>
		<center><a href='/2016PIT-form.php' class='button'>Submit Another Survey</a></center><br>
		<center><a href='/' class='button'>View the Data</a></center>";
	} ?>
			</div>
	</div>
</body>