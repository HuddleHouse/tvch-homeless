<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<title></title>
</head>
<style>
	.button {
		text-decoration: initial;
	    border: 2px solid #111 !important;
	    color: #111;
	    padding: 5px;
	}
	h4 {
	    margin: 0 0 0px;
	    padding-top: 10px;
	    padding-bottom: 10px;
	    padding-left: 15px;
	}
	th {
    	text-align: center;
	}
	tr.success.title {
	    font-size: smaller;
	}
	th.first {
	    font-size: smaller;
	    width: 45%;
	    text-align: left;
	}
	th.num {
	    font-weight: 400;
	}
	.all {
	    border-spacing: 2px;
	    border-color: grey;
        background-color: #f5f5f5;
	}
	.purp {
		border-spacing: 2px;
	    border-color: grey;
        background-color: #ddd8f0 !important;
	}
	.vet {
		border-spacing: 2px;
	    border-color: grey;
        background-color: #b0c4de !important;
	}
	.vet2 {
		border-spacing: 2px;
	    border-color: grey;
        background-color: #bc8f8f !important;
	}
	.vols {
		border-spacing: 2px;
	    border-color: grey;
        background-color: #FFD7B3 !important;
	}
</style>

<?php
	include '../config.php';

	$query = $db->prepare('select countyid,countyname from countylist order by countyname asc');
	$query->execute();
	$counties = $query->fetchAll();
?>


<body>
	<div class="container">
		<div class="row" style="padding-top: 50px;">
			<center><h1><?php echo $county ?></h1></center>

	<div class="bs-callout bs-callout-primary">
			<center><h4>HUD Report for 2016 Point-in-Time Count</h4></center>
	</div>
	<!--
	*
	*
	* EVERYONE
	*
	*
	-->
	<div class="col-md-8 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes active" data-example-id="contextual-backgrounds-helpers">
					<h4 class="all">Shelter List Report</h4>
				</div>
		        <tr class="active title">
		          <th class='column'>County Name</th>
		          <th class='column'>Agency Name</th>
		          <th class='column'>Sheltered Count</th>

		        </tr>
			</thead>
			<tbody>

<?php
	//EVERYONE
	//
	$query = $db->prepare("select s.county as county, s.emergency_shelter as agency, count(p.id) as count
	from survey_data s
		left join person_data p
			on p.survey_data_id = s.id
		where (is_trans = 1 or is_emergency = 1 or is_hotel = 1)
	group by county, agency");

	$query->execute();
	$count = $query->fetchAll();
	$total = 0;

	foreach($count as $c) {
	echo "<tr><th class='num'>".($c['county'])."</th>";

	echo "<th class='num'>".$c['agency']."</th>";

	echo "<th class='num'>".$c['count']."</th></tr>";
	$total = $total + $c['count'];
}
	//Total number of persons

	echo "<tr><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>

	</div>
</div>
</div>

</body>