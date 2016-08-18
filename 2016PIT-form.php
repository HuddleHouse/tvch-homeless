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
<style type="text/css">
.grid-form fieldset legend {
    border: none;
    border-bottom: none;
    padding: 5px;
}

.highlight {
    padding: 9px 14px;
    margin-bottom: 14px;
    background-color: #f7f7f9;
    border: 1px solid #e1e1e8;
    border-radius: 4px;
}
.radio {
    color: #747474;
    font: 13px/20px "PTSansRegular",Arial,Helvetica,sans-serif;
    font-weight: bold;
}
.highlight3 {
    padding: 9px 14px;
    margin-bottom: 220px;
    background-color: #f7f7f9;
    border: 1px solid #e1e1e8;
    border-radius: 4px;
}
.house {
    background-color: #f7f7f9;
    border: 1px solid #000;
    border-radius: 4px;
    padding-bottom: 15px;
}
.info-label {
    color: #fff;
    background-color: #000;
    padding-top: 10px;
    padding-bottom: 10px;
    margin-top: 0px;

    font-size: large;
}
.item {
    padding: 5px;
}
.pad {
	margin-top: 10px;
}

.church-name, .agency-name {
	height: 60px;
}

button.btn.btn-lg {
        background-color: #111;
    color: #fff;
    float: right;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 25px;
    padding: 15px;
    margin-right:-13px;
}
button.btn.btn-lg {
    color: #fff;
    background-color: #111;
}
label {
    font-weight: bold;
}
.help-block.with-errors {
    color: red;
}
</style>

<?php //var_dump($_POST);
include 'config.php';

$query = $db->prepare('select countyname from countylist order by countyname asc');
$query->execute();
$counties = $query->fetchAll();

$query = $db->prepare('select * from ethnicitylist');
$query->execute();
$ethnicities = $query->fetchAll();

$query = $db->prepare('select * from genderlist');
$query->execute();
$genders = $query->fetchAll();

$query = $db->prepare('select * from agerangelist');
$query->execute();
$ages = $query->fetchAll();

$query = $db->prepare('select * from racelist');
$query->execute();
$races = $query->fetchAll();

$query = $db->prepare("select s.description from shelterlist s left join sheltertypelist t on t.sheltertypeid = s.sheltertype where t.description = 'Unsheltered' order by s.description asc");
$query->execute();
$shelters = $query->fetchAll();


	$county = $_POST['county'];
	$emergency = $_POST['shelter'];
	$shelter = $_POST['shelter'];
	$hotel = $_POST['shelter'];
	if($hotel == "Church"){
		$hotelName = $_POST["$county-name1"];
	}
	else {
		$hotelName = $_POST["$county-name2"];
	}
	$temp = $_POST['shelter'];
	$unsheltered = $_POST['unsheltered'];
	$notes = $_POST['notes'];
	$name = $_POST['volunteer-name'];
	$location = $_POST['location'];
	$city = $_POST['city'];
	$zip = $_POST['zip'];
	$numAdults = $_POST['num-adults'];
	$numKids = $_POST['num-kids'];

?>
<script type="text/javascript">
function showChurch() {
	var x = "H";
	$('#myhidden').val(x);
	$("#yo").prop("selectedIndex", -1);
	val = document.getElementById('county').value;

    if (document.getElementById(''+val+'-church').checked) {
        jQuery('#'+val+'-name2').show();
        jQuery('#'+val+'-name1').hide();
    }
    else if (document.getElementById(''+val+'-agency').checked) {
        jQuery('#'+val+'-name1').show();
        jQuery('#'+val+'-name2').hide();
    }
    else {
		jQuery('#'+val+'-name1').hide();
	    jQuery('#'+val+'-name2').hide();
	}
}
</script>


<body>
	<div class="container" style="padding-top: 75px;">

    <div class="row">
        <form class="grid-form form-inline" id="form" action="./2016PIT-formsubmit.php" method="post" style="padding-bottom: 50px;">
            <fieldset>
                <div class="col-md-4" style="    border-right: 2px solid #404040;">
                    <center><h1>Point in Time Count Survey</h1></center><br>

					<div data-field-span="1" style="height: 55px;" class="">
						<legend>Select your County *</legend>
						<select name="county" id="county" onchange="changeCounty(this.value)" data-validate="required">
							<option disabled selected> -- select a county -- </option>
							<?php foreach($counties as $county)
							{
								echo "<option value='$county[0]'>$county[0]</option>";

							}
							?>

						</select>
					</div>
					<br>
					<div class="living" style="display: none;">
					<legend>Where are you sleeping right now? *</legend>
					</div>

					<div id="anderson" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Agape House" >
							    Agape House
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Oasis of Love">
							    Oasis of love
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="TORCH Warming Center">
							    TORCH Warming Center
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Transitional shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Ridgeview">
							    Ridgeview
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="TORCH" onclick="showChurch()">
							    TORCH
							  </label>
							</div>

							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Anderson-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Anderson-name1" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Anderson-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Anderson-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Anderson-name2" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Anderson-name2">
						            </div>
			                    </div>
							</div>
						</div>
					</div>

					<div id="blount" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Family Promise of Blount County">
							    Family Promise of Blount County
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Haven House">
							    Haven House
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Blount County Drug Court">
							    Blount County Drug Court
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="House of Miracles">
							    House of Miracles
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Transitional shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Family Promise of Blount County">
							    Family Promise of Blount County
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Haven Sent Home Men's Shelter">
							    Haven Sent Home Men's Shelter
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Haven Sent Home Women's Shelter">
							    Haven Sent Home Women's Shelter
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="First United Methodist Church" onclick="showChurch()">
							    First United Methodist Church
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Chilhowee Baptist Center" onclick="showChurch()">
							    Chilhowee Baptist Center
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Red Cross" onclick="showChurch()">
							    Red Cross
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Blount-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Blount-name1" class="church-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Blount-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Blount-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Blount-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Blount-name2">
						            </div>
			                    </div>
							</div>

						</div>
					</div>

					<div id="campbell" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Community Health of East TN (CHET)">
							    Community Health of East Tn (CHET)
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Transitional shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Community Health of East Tn (CHET)">
							    Community Health of East TN (CHET)
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="The Sheperds Home">
							    The Shepherd's Home
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Campbell-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Campbell-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Campbell-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Campbell-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Campbell-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Campbell-name2">
						            </div>
			                    </div>
							</div>

						</div>
					</div>

					<div id="claiborne" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="CEASE">
							    CEASE
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Claiborne Refuge">
							    Claiborne Refuge
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Pmup Springs Baptist Church" onclick="showChurch()">
							    Pmup Springs Baptist Church
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Claiborne-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Claiborne-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Claiborne-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Claiborne-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Claiborne-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Claiborne-name2">
						            </div>
			                    </div>
							</div>

						</div>
					</div>

					<div id="cocke" style="display: none;">
						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Cocke-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Cocke-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Cocke-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Cocke-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Cocke-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Cocke-name2">
						            </div>
			                    </div>
							</div>
						</div>
					</div>

					<div id="grainger" style="display: none;">
						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Grainger-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Grainger-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Grainger-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Grainger-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Grainger-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Grainger-name2">
						            </div>
			                    </div>
							</div>

						</div>
					</div>

					<div id="hamblen" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="CEASE">
							    CEASE
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="MATS Inc">
							    MATS, Inc.
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Breath of Life">
							    Breath of Life
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Morristown Police Department" onclick="showChurch()">
							    Morristown Police Department
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Red Cross" onclick="showChurch()">
							    Red Cross
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Hamblen-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Hamblen-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Hamblen-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Hamblen-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Hamblen-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Hamblen-name2">
						            </div>
			                    </div>
							</div>
						</div>
					</div>

					<div id="jefferson" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="SafeSpace">
							    SafeSpace
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Samaritan House">
							    Samaritan House
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="The Windmill Way">
							    The Windmill Way
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Transitional shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="The Windmill Way">
							    The Windmill Way
							  </label>
							</div>

						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Jefferson-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Jefferson-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Jefferson-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Jefferson-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Jefferson-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Jefferson-name2">
						            </div>
			                    </div>
							</div>
						</div>
					</div>

					<div id="loudon" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Ivas Place">
							    Iva's Place
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Loudon-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Loudon-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Loudon-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Loudon-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Loudon-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Loudon-name2">
						            </div>
			                    </div>
							</div>

						</div>
					</div>

					<div id="monroe" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="The Way of Hope">
							    The Way of Hope
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="Sweetwater Area Ministries">
							    Sweetwater Area Ministries
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="First Baptist Church">
							    First Baptist Church
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Transitional shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Branches of Monroe County">
							    Branches of Monroe County
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onClick="isTrans()" value="Shaw Hollow Farms">
							    Shaw Hollow Farms
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="First Baptist Church of Madisonville" onclick="showChurch()">
							    First Baptist Church of Madisonville
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Monroe-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Monroe-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Monroe-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Monroe-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Monroe-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Monroe-name2">
						            </div>
			                    </div>
							</div>						</div>
					</div>

					<div id="sevier" style="display: none;">
						<div class="highlight">
							<legend>Emergency Shelter</legend>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="isEmergency()" value="SWARM">
							    SWARM
							  </label>
							</div>
						</div>

						<div class="highlight">
							<legend>Hotel Paid for By:</legend>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="SWARM" onclick="showChurch()">
							    SWARM
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Sevierville Police Department" onclick="showChurch()">
							    Sevierville Police Department
							  </label>
							</div>
							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Sevier-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Sevier-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Sevier-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Sevier-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Sevier-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Sevier-name2">
						            </div>
			                    </div>
							</div>

						</div>
					</div>

					<div id="union" style="display: none;">
						<div class="highlight">
							<legend>Hotel Paid for By:</legend>

							<div class="radio" >
							  <label>
							    <input type="radio" name="shelter"  class="shelter" value="Charitable Donor (not an Org)" onclick="showChurch()">
							    Charitable Donor (not an Org)
							  </label>
							</div>

							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Union-agency" value="Agency">
							    Agency
							  </label>
								<div data-row-span="1" id="Union-name1" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Agency Name:</label>
						                <input type="text" name="Union-name1">
						            </div>
			                    </div>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="shelter"  class="shelter" onclick="showChurch()" id="Union-church" value="Church">
							    Church
							  </label>
								<div data-row-span="1" id="Union-name2" class="agency-name" style="display: none;">
						            <div data-field-span="1" >
						                <label>Church Name:</label>
						                <input type="text" name="Union-name2">
						            </div>
			                    </div>
							</div>

						</div>
					</div>




					<div class="living" style="display: none;">
					<div class="highlight">
						<legend>Temporarily Staying with Family or Friends</legend>
						<div class="radio">
						  <label>
						    <input type="radio" name="shelter"  class="shelter" onclick="isTemp()" value="Must leave within Jan 23- Jan 29">
						    Must leave within Jan 23 - Jan 29
						  </label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="shelter"  class="shelter" onclick="isTemp()" value="Can stay Longer than Jan 29">
						    Can stay Longer than Jan 29
						  </label>
						</div>
					</div>

					<div data-field-span="1"  class="highlight">
						<legend>Unsheltered (Choose One)</legend>
						<select name="shelter"   class="shelter" id="yo"onchange="isUnsheltered()">
							<option ></option>
							<?php foreach($shelters as $shelter)
							{
								echo "<option>$shelter[0]</option>";

							}
							?>
						</select>

					</div>
				</div>

				<div id="shelter-error" style="display: none; padding-bottom: 11px;  margin-top: -12px;
">
							<div class="notifyjs-arrow" style="border-bottom-width: 5px; border-bottom-style: solid; border-bottom-color: rgb(238, 211, 215); border-left-width: 5px; border-left-style: solid;    position: initial; border-left-color: transparent; border-right-width: 5px; border-right-style: solid; border-right-color: transparent;">

							</div>
							<div class="notifyjs-container" style=" display: block;     position: inherit;">
								<div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
								<span data-notify-text="" class="notifyjs-text">Please select where you are living right now.</span>
								</div>
							</div>
						</div>


					<div class="highlight">
						<legend>Notes (optional)</legend>
						<textarea name="notes" class="form-control" rows="3" style="background-color: #fff;"></textarea>
					</div>
                </div>
				<div class="col-md-8">
					<button type="submit" value="submit" class="btn btn-lg" >Submit Your Completed Form</button>
				</div>

                <div class="col-md-4">
                    <div data-row-span="1" class="form-group">
			            <div data-field-span="1">
			                <label for="volunteer-name" class="control-label">Volunteer Name *</label>
			                <input type="text" class="form-control" id="volunteer-name" data-validate="required,name" name="volunteer-name">
			            </div>
			            <div class="help-block with-errors"></div>
                    </div>
                    <div data-row-span="1">
			            <div data-field-span="1">
			                <label>Location or Source of Information *</label>
			                <input type="text" name="location" data-validate="required,location">
			            </div>
                    </div>

                </div>
                <div class="col-md-4">
	                <div class="house row">
		                <div class="info-label">
		                	<center>HOUSEHOLD INFO:</center>
		                </div>

		                <div class="col-md-6">
				                <legend># of Adults</legend>
								<select name="num-adults" onchange="addAdults(this.value)">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								</select>
		                </div>
		                <div class="col-md-6">
								<legend># of Kids</legend>
								<select name="num-kids" onchange="addKids(this.value)">
									<option>0</option>
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								</select>
		                </div>

	                </div>
                </div>

				<div class="col-md-8">
					<div data-row-span="2">
			            <div data-field-span="1">
			                <label>City *</label>
			                <input type="text" name="city" data-validate="required,city">
			            </div>
			            <div data-field-span="1">
			                <label>Zip Code *</label>
			                <input type="text" name="zip" data-validate="required,postcode">
			            </div>
                    </div>
				</div>


					<div class="col-md-4 pad" >
						<div class="info-label">
		                	<center>HEAD OF HOUSEHOLD</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender1">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age1">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>
											<input type='checkbox' data-validate='required' name='race1[]'  value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity1">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population1[]" value="military"> Former Active Duty Military

							</div>
							<div class="checkbox">
								<input type="checkbox" name="population1[]" value="violence"> Fleeing Domestic Violence

							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history1[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history1[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition1[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition1[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition1[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>

					<div class="col-md-4 pad" id="fam1" style="display: none;">
						<div class="info-label">
		                	<center>FAMILY MEMBER</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender2">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age2">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>

											<input type='checkbox' data-validate='required' name='race2[]' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity2">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population2[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population2[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history2[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history2[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition2[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition2[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition2[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>

					<div class="col-md-4 pad" id="fam2" style="display: none;">
						<div class="info-label">
		                	<center>FAMILY MEMBER</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender3">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age3">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>
											<input type='checkbox' data-validate='required' name='race3[]' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity3">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population3[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population3[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history3[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history3[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition3[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition3[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition3[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>

					<div class="col-md-4 pad" id="fam3" style="display: none;">
						<div class="info-label">
		                	<center>FAMILY MEMBER</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender4">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age4">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>
											<input type='checkbox' data-validate='required' name='race4[]' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity4">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population4[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population4[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history4[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history4[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition4[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition4[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition4[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>

					<div class="col-md-4 pad" id="fam4" style="display: none;">
						<div class="info-label">
		                	<center>FAMILY MEMBER</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender5">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age5">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>
											<input type='checkbox' data-validate='required' name='race5[]' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity5">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population5[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population5[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history5[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history5[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition5[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition5[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition5[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>

					<div class="col-md-4 pad" id="kid1" style="display: none;">
						<div class="info-label">
		                	<center>CHILD</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender6">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age6">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>
											<input type='checkbox' data-validate='required' name='race6[]' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity6">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population6[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population6[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history6[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history6[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition6[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition6[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition6[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>


					<div class="col-md-4 pad" id="kid2" style="display: none;">
						<div class="info-label">
		                	<center>CHILD</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender7">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age7">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>
											<input type='checkbox' data-validate='required' name='race7[]' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity7">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population7[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population7[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history7[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history7[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition7[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition7[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition7[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>

					<div class="col-md-4 pad" id="kid3" style="display: none;">
						<div class="info-label">
		                	<center>CHILD</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender8">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age8">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>
											<input type='checkbox' data-validate='required' name='race8[]' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity8">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population8[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population8[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history8[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history8[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition8[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition8[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition8[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>

					<div class="col-md-4 pad" id="kid4" style="display: none;">
						<div class="info-label">
		                	<center>CHILD</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender9">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age9">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='agreement'><label>
											<input type='checkbox' data-validate='required' name='race9[]' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity9">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population9[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population9[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history9[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history9[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition9[]" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition9[]" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition9[]" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>

					<div class="col-md-4 pad" id="kid5" style="display: none;">
						<div class="info-label">
		                	<center>FAMILY MEMBER</center>
		                </div>
						<div class="item">
							<legend>Gender*</legend>
							<select data-validate="required" name="gender10">
								<option disabled selected> -- select  -- </option><?php foreach($genders as $gender)
								{
									echo "<option value='$gender[0]'>$gender[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Age Range*</legend>
							<select data-validate="required" name="age10">
								<option disabled selected> -- select  -- </option><?php foreach($ages as $age)
								{
									echo "<option value='$age[0]'>$age[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Race (check all that apply)*</legend>

								<?php foreach($races as $race)
								{
									echo "<div class='checkbox'><label>
											<input type='checkbox' data-validate='required' name='race1[]0' value='$race[0]'>
											$race[1]
											</label></div>";

								}
								?>
						</div>
						<div class="item">
							<legend>Ethnicity*:</legend>
							<select data-validate="required" name="ethnicity10">
								<option disabled selected> -- select  -- </option><?php foreach($ethnicities as $ethnicity)
								{
									echo "<option value='$ethnicity[0]'>$ethnicity[1]</option>";

								}
								?>
							</select>
						</div>
						<div class="item">
							<legend>Population</legend>
							<div class="checkbox">
								<input type="checkbox" name="population10[]" value="military"> Former Active Duty Military
							</div>
							<div class="checkbox">
								<input type="checkbox" name="population10[]" value="violence"> Fleeing Domestic Violence
							</div>
						</div>
						<div class="item">
							<legend>History of Homelessness</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history10[]" value="option1">
							    Has lived on the streets, emergency shelter, or safe haven for a full 12 months
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="history10[]" value="option2">
							    Has been on the streets, emergency shelter, or safe haven 4 or more times in the last 3 years
							  </label>
							</div>
						</div>
						<div class="item">
							<legend>Disabling Conditions, expected to be of long duration, that could be helped by appropiate shelter (check all that apply):</legend>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition1[]0" value="severe-mental">
							    Severe Mental Illness (including PTSD)
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition1[]0" value="severe-chronic">
							    Severe Chronic Substance Abuse Disorder
							  </label>
							</div>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="condition1[]0" value="hiv">
							    Has HIV / AIDS
							  </label>
							</div>
						</div>
					</div>
				<input type='hidden' id='myhidden' name="hidden" value>
            </fieldset>
        </form>

    </div>
	</div>
</body>
</html>
<script type="text/javascript">

function isUnsheltered() {
	var x = "U";
	$('#myhidden').val(x);
	$("input:radio[name='shelter']").each(function(i) {
       this.checked = false;
	});
}


function validate() {
	if($('#myhidden').val()) {
		return true;
	}
	else {
		$('#shelter-error').show().fadeIn( 1000, function() {
            $(this).css({
                '-webkit-transition': 'all 1s ease-in-out',
                '-moz-transition': 'all 1s ease-in-out',
                '-o-transition': 'all 1s ease-in-out',
                'transition': 'all 1s ease-in-out',
            });
        });
		setTimeout(function() {
			$('#shelter-error').hide().fadeIn('1000');;
	    }, 4000);
		returnToPreviousPage();
		return false;
	}
}

function isTrans() {
	var x = "Trans";
	showChurch();
	$('#myhidden').val(x);

	$("#yo").prop("selectedIndex", -1);

}

function isEmergency() {
	var x = "E";
	showChurch();
	$('#myhidden').val(x);

	$("#yo").prop("selectedIndex", -1);
}

function isTemp() {
	var x = "Temp";
	showChurch();
	$('#myhidden').val(x);

	$("#yo").prop("selectedIndex", -1);
}

function addAdults(val){
	val = val - 1;
	jQuery('#fam1').hide();
	jQuery('#fam2').hide();
	jQuery('#fam3').hide();
	jQuery('#fam4').hide();

	for(i = 1; i <= val; i++){
		jQuery('#fam'+ i +'').show();
	}

}

function addKids(val){
	jQuery('#kid1').hide();
	jQuery('#kid2').hide();
	jQuery('#kid3').hide();
	jQuery('#kid4').hide();
	jQuery('#kid5').hide();

	for(i = 1; i <= val; i++){
		jQuery('#kid'+ i +'').show();
	}
}

function changeCounty(val) {
	jQuery('.living').show();
	jQuery('#anderson').hide();
	jQuery('#blount').hide();
	jQuery('#campbell').hide();
	jQuery('#claiborne').hide();
	jQuery('#cocke').hide();
	jQuery('#grainger').hide();
	jQuery('#hamblen').hide();
	jQuery('#jefferson').hide();
	jQuery('#loudon').hide();
	jQuery('#monroe').hide();
	jQuery('#sevier').hide();
	jQuery('#union').hide();

	if(val == "Anderson"){
		jQuery('#anderson').show();
	}
	else if(val == "Blount"){
		jQuery('#blount').show();
	}
	else if(val == "Campbell"){
		jQuery('#campbell').show();
	}
	else if(val == "Claiborne"){
		jQuery('#claiborne').show();
	}
	else if(val == "Cocke"){
		jQuery('#cocke').show();
	}
	else if(val == "Grainger"){
		jQuery('#grainger').show();
	}
	else if(val == "Hamblen"){
		jQuery('#hamblen').show();
	}
	else if(val == "Jefferson"){
		jQuery('#jefferson').show();
	}
	else if(val == "Loudon"){
		jQuery('#loudon').show();
	}
	else if(val == "Monroe"){
		jQuery('#monroe').show();
	}
	else if(val == "Sevier"){
		jQuery('#sevier').show();
	}
	else if(val == "Union"){
		jQuery('#union').show();
	}
}
</script>
