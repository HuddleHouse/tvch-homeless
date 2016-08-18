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
	    padding: 8px;
	    font-size: large;
	}

	th {
    	text-align: center;
	}
</style>
<?php
include 'config.php';

$query = $db->prepare('select countyid,countyname from countylist order by countyname asc');
$query->execute();
$counties = $query->fetchAll();
?>
<body>
<div class="container">
<div class="row" style="padding-top: 60px;">
	<div class="bs-callout bs-callout-primary">
			<center><h4>This accurately reflects all surveys that were turned in to us by each county.</h4></center>
	</div>
	<div class="col-md-6 col-md-offset-3" data-example-id="striped-table">
		<table class="table table-bordered table-striped">
			<thead>
		        <tr class="success">
		          <th></th>
		          <th>Sheltered</th>
		          <th>Unsheltered</th>
		          <th>Precariously Housed</th>
		          <th>Total</th>
		          <th>Reporting Status</th>
		        </tr>
			</thead>
			<tbody>

<?php foreach($counties as $county) {



	echo "<tr><th scope='row'><a href='./reports?county=".$county[1]."' >".$county[1]."</a></th>";

	//sheltered
	$query = $db->prepare("select coalesce(count(p.id), 0) from survey_data s
	left join person_data p
	on p.survey_data_id = s.id
	where county = :county AND (is_trans = 1 OR is_emergency = 1 OR is_hotel = 1)");
	$query->bindParam(':county', $county[1], PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<th>".$count[0]."</th>";

	//unsheltered
	$query = $db->prepare("select coalesce(count(p.id), 0) from survey_data s
	left join person_data p
	on p.survey_data_id = s.id
	where county = :county AND (is_unsheltered = 1)");
	$query->bindParam(':county', $county[1], PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<th>".$count[0]."</th>";

	//precariously Housed
	$query = $db->prepare("select coalesce(count(p.id), 0) from survey_data s
	left join person_data p
	on p.survey_data_id = s.id
	where county = :county AND (is_temp = 1)");
	$query->bindParam(':county', $county[1], PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<th>".$count[0]."</th>";

	//total
	$query = $db->prepare('select coalesce(count(p.id), 0) from survey_data s
	left join person_data p
	on p.survey_data_id = s.id
	where county = :county');
	$query->bindParam(':county', $county[1], PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<th class='warning'>".$count[0]."</th><th><center><a href='/reports/county-totals-summary.php?county=".$county[1]."'>Report</a></center></th></tr>";
}
//Totals
	//sheltered
	$query = $db->prepare("select coalesce(count(p.id), 0) from survey_data s
	left join person_data p
	on p.survey_data_id = s.id
	where (is_trans = 1 OR is_emergency = 1 OR is_hotel = 1)");
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th>Totals</th><th class='warning'>".$count[0]."</th>";

	//unsheltered
	$query = $db->prepare("select coalesce(count(p.id), 0) from survey_data s
	left join person_data p
	on p.survey_data_id = s.id
	where (is_unsheltered = 1)");
	$query->execute();
	$count = $query->fetch();

	echo "<th class='warning'>".$count[0]."</th>";

	//precariously Housed
	$query = $db->prepare("select coalesce(count(p.id), 0) from survey_data s
	left join person_data p
	on p.survey_data_id = s.id
	where (is_temp = 1)");
	$query->execute();
	$count = $query->fetch();

	echo "<th class='warning'>".$count[0]."</th>";

	//total
	$query = $db->prepare('select coalesce(count(p.id), 0) from survey_data s
	left join person_data p
	on p.survey_data_id = s.id');
	$query->execute();
	$count = $query->fetch();

	echo "<th class='danger'>".$count[0]."</th><th></th></tr>";


?>




			</tbody>
		</table>
	</div>

</div>
	<div class="row" style="padding-top: 50px;">
<center><a href="/reports/totals.php" class="button">View all Data From All Counties</a></center><br>
<center><a href="/reports/totals-summary.php" class="button">View summary of Data From All Counties</a></center><br>
<center><a href="/reports/shelterlist.php" class="button">Sheltered Count by Agency and County</a></center><br>
		</div>
</div>
</body>