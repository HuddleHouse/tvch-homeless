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
	$county = $_GET['county'];
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
					<h4 class="all">All Reports</h4>
				</div>
		        <tr class="active title">
		          <th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
			</thead>
			<tbody>

<?php
	//EVERYONE
	//
	$query = $db->prepare("select coalesce(sum(is_trans), 0) as trans, coalesce(sum(is_unsheltered), 0) as un, coalesce(sum(is_temp), 0) as temp, coalesce(sum(is_hotel), 0) as hotel, coalesce(sum(is_emergency), 0) as emer, count(county) as total from survey_data where county = :county");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";

	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Total number of persons
	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						right join survey_data s
							on s.id = p.survey_data_id
						where county = :county");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";
	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Persons age
		//Persons
	$query = $db->prepare("select count(p.id) as total, a.description as age, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						left join survey_data s
							on s.id = p.survey_data_id
						left join agerangelist a
							on a.agerangeid = p.age
						where county = :county
						group by age;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	foreach($count as $c){
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of persons (".$c['age'].")</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";

	}
?>

	<tr class="active title">
		     	<th><h4>Gender (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php
	//GENDERSS
	$query = $db->prepare("select genderid id, gender from genderlist");
	$query->execute();
	$genders = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, g.gender as gender, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join genderlist g
					on g.genderid = p.gender
				where county = :county
				group by g.gender");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['gender']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
	?>

	<tr class="active title">
		     	<th><h4>Ethnicity (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php

//ethnicity
	$query = $db->prepare("select ethnicityid id, description ethnicity from ethnicitylist");
	$query->execute();
	$ethnicities = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, e.description as ethnicity, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join ethnicitylist e
					on e.ethnicityid = p.ethnicity
				where county = :county
				group by ethnicity");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['ethnicity']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";

	?>

	<tr class="active title">
		     	<th><h4>Race (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php

//race
	$query = $db->prepare("select raceid id, description race from racelist");
	$query->execute();
	$races = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, r.description as race, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from race_data rd
				left join person_data p
					on p.id = rd.person_data_id
				left join survey_data s
					on s.id = rd.survey_data_id
				left join racelist r
					on r.raceid = rd.racelist_id
				where county = :county
				group by race");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['race']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>



	<!--
		*
		*
		*
		FAMILIES WITH NO CHILDREN
		*
		*
		-->
	<div class="col-md-8 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">


		<!--WITHOUT CHILDREN-->
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="bg-info">Households without Children</h4>
				</div>
		        <tr class="info title">
		          <th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
			</thead>
			<tbody>

<?php
	//Without childsren
	//
	$query = $db->prepare("select coalesce(sum(distinct is_trans), 0) as trans, coalesce(sum(distinct is_unsheltered), 0) as un, coalesce(sum(distinct is_temp), 0) as temp, coalesce(sum(distinct is_hotel), 0) as hotel, coalesce(sum(distinct is_emergency), 0) as emer, count(distinct s.id) as total
	from survey_data s
		left join person_data p
			on p.survey_data_id = s.id
		where s.county = :county
		AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0 ");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";


	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Total number of persons
	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						right join survey_data s
							on s.id = p.survey_data_id
						where county = :county
						AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";


	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Persons
	$query = $db->prepare("select count(p.id) as total, a.description as age, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						left join survey_data s
							on s.id = p.survey_data_id
						left join agerangelist a
							on a.agerangeid = p.age
						where county = :county
						AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0
						group by age;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	foreach($count as $c){
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of persons (".$c['age'].")</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";

	}

?>

	<tr class="info title">
		     	<th><h4>Gender (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php
	//GENDERSS

	$query = $db->prepare("select count(p.id) as total, g.gender as gender, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join genderlist g
					on g.genderid = p.gender
				where county = :county
				AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0
				group by g.gender");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['gender']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
	?>

	<tr class="info title">
		     	<th><h4>Ethnicity (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php

//ethnicity

	$query = $db->prepare("select count(p.id) as total, e.description as ethnicity, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join ethnicitylist e
					on e.ethnicityid = p.ethnicity
				where county = :county
				AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0
				group by ethnicity");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['ethnicity']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";

		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";

	?>

	<tr class="info title">
		     	<th><h4>Race (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php

//race

	$query = $db->prepare("select count(p.id) as total, r.description as race, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from race_data rd
				left join person_data p
					on p.id = rd.person_data_id
				left join survey_data s
					on s.id = rd.survey_data_id
				left join racelist r
					on r.raceid = rd.racelist_id
				where county = :county
				AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0
				group by race");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['race']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>

<!--
	*
	*
	* WITH AT LEAST ONE CHILD
	*
	*
	-->
	<div class="col-md-8 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="bg-danger">Households with at least one adult and one child</h4>
				</div>
		        <tr class="danger title">
		          <th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
			</thead>
			<tbody>

<?php
	//With at least one kid
	//
	$query = $db->prepare("select coalesce(sum(is_trans), 0) as trans, coalesce(sum(is_unsheltered), 0) as un, coalesce(sum(is_temp), 0) as temp, coalesce(sum(is_hotel), 0) as hotel, coalesce(sum(is_emergency), 0) as emer, count(county) as total from survey_data s where s.county = :county AND (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Total number of persons
	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						right join survey_data s
							on s.id = p.survey_data_id
						where county = :county
						and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";


	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Persons age
		//Persons
	$query = $db->prepare("select count(p.id) as total, a.description as age, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						left join survey_data s
							on s.id = p.survey_data_id
						left join agerangelist a
							on a.agerangeid = p.age
						where county = :county
						and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
						group by age;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	foreach($count as $c){
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of persons (".$c['age'].")</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";

	}
?>

	<tr class="danger title">
		     	<th><h4>Gender (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php
	//GENDERSS
	$query = $db->prepare("select genderid id, gender from genderlist");
	$query->execute();
	$genders = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, g.gender as gender, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join genderlist g
					on g.genderid = p.gender
				where county = :county
				and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0
				and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
				group by g.gender");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['gender']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
	?>

	<tr class="danger title">
		     	<th><h4>Ethnicity (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//ethnicity
	$query = $db->prepare("select ethnicityid id, description ethnicity from ethnicitylist");
	$query->execute();
	$ethnicities = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, e.description as ethnicity, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join ethnicitylist e
					on e.ethnicityid = p.ethnicity
				where county = :county
				and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
				group by ethnicity");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['ethnicity']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";

	?>

	<tr class="danger title">
		     	<th><h4>Race (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//race
	$query = $db->prepare("select raceid id, description race from racelist");
	$query->execute();
	$races = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, r.description as race, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from race_data rd
				left join person_data p
					on p.id = rd.person_data_id
				left join survey_data s
					on s.id = rd.survey_data_id
				left join racelist r
					on r.raceid = rd.racelist_id
				where county = :county
				and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
	group by race");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['race']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>

<!--
	*
	*
	* WITH CHILSREN UNDER !*
	*
	*
	-->
	<div class="col-md-8 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="bg-success">Households with Only Children (under age 18)</h4>
				</div>
		        <tr class="success title">
		          <th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
			</thead>
			<tbody>

<?php
	//KIDS UNSWER 18
	//
	$query = $db->prepare("select count(distinct s.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from survey_data s
		where county = :county
		and (select count(pd.id)
			from person_data pd
				where pd.survey_data_id = s.id
				and (pd.age = 2 or pd.age = 3)) = 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Total number of persons
	$query = $db->prepare("select s.id, count(s.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join agerangelist a
			on p.age = a.agerangeid
		where county = :county
		AND (a.description = '0-17')
		and (select count(pd.id)
			from person_data pd
				left join survey_data sd
					on sd.id = pd.survey_data_id
				left join agerangelist al
					on pd.age = al.agerangeid
				where pd.survey_data_id = s.id
				and al.description != '0-17') = 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

		//Persons
	$query = $db->prepare("select count(p.id) as total, a.description as age, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join agerangelist a
			on a.agerangeid = p.age
		where county = :county
		and (select count(pd.id)
			from person_data pd
				left join survey_data sd
					on sd.id = pd.survey_data_id
				left join agerangelist al
					on pd.age = al.agerangeid
				where pd.survey_data_id = s.id
				and al.description != '0-17') = 0
	group by age;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	foreach($count as $c){
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of persons (".$c['age'].")</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";

		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";

	}

?>

	<tr class="success title">
		     	<th><h4>Gender (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

	$query = $db->prepare("select count(p.id) as total, g.gender as gender, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
		from person_data p
			left join survey_data s
				on s.id = p.survey_data_id
			left join genderlist g
				on g.genderid = p.gender
			right join agerangelist a
				on p.age = a.agerangeid
			where county = :county
			AND (a.description = '0-17')
			and (select count(pd.id)
			from person_data pd
				left join survey_data sd
					on sd.id = pd.survey_data_id
				left join agerangelist al
					on pd.age = al.agerangeid
				where pd.survey_data_id = s.id
				and al.description != '0-17') = 0
			group by g.gender");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['gender']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";

		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
	?>

	<tr class="success title">
		     	<th><h4>Ethnicity (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php

//ethnicity

	$query = $db->prepare("select count(p.id) as total, e.description as ethnicity, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join ethnicitylist e
					on e.ethnicityid = p.ethnicity
				right join agerangelist a
					on p.age = a.agerangeid
				where county = :county
				AND (a.description = '0-17')
			and (select count(pd.id)
			from person_data pd
				left join survey_data sd
					on sd.id = pd.survey_data_id
				left join agerangelist al
					on pd.age = al.agerangeid
				where pd.survey_data_id = s.id
				and al.description != '0-17') = 0
				group by ethnicity;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['ethnicity']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";

	?>

	<tr class="success title">
		     	<th><h4>Race (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//race

	$query = $db->prepare("select count(p.id) as total, r.description as race, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
		from race_data rd
			left join person_data p
				on p.id = rd.person_data_id
			left join survey_data s
				on s.id = rd.survey_data_id
			left join racelist r
				on r.raceid = rd.racelist_id
			right join agerangelist a
				on p.age = a.agerangeid
			where county = :county
			AND (a.description = '0-17')
		and (select count(pd.id)
				from person_data pd
					left join survey_data sd
						on sd.id = pd.survey_data_id
					left join agerangelist al
						on pd.age = al.agerangeid
					where pd.survey_data_id = s.id
					and al.description != '0-17') = 0
			group by race");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['race']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>

<!--
	*
	*
	* WITH AT LEAST ONE CHILD and Head of household 18-24
	*
	*
	-->
	<div class="col-md-8 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="vols">Households with at least 1 child and parenting youth aged 18-24</h4>
				</div>
		        <tr class="vols title">
		          <th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
			</thead>
			<tbody>

<?php
	//With at least one kid
	//
	$query = $db->prepare("select coalesce(sum(is_trans), 0) as trans, coalesce(sum(is_unsheltered), 0) as un, coalesce(sum(is_temp), 0) as temp, coalesce(sum(is_hotel), 0) as hotel, coalesce(sum(is_emergency), 0) as emer, count(county) as total
	from survey_data s
	left join person_data p
		on p.survey_data_id = s.id
	where s.county = :county
	and p.is_head = 1
	and p.age = 2
	AND (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0
	and (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Total number of persons
	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						right join survey_data s
							on s.id = p.survey_data_id
						where county = :county
						and (select count(pd.id) from person_data pd where pd.age = 2 and pd.is_head = 1 and pd.survey_data_id = s.id) = 1						and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Persons age
		//Persons
	$query = $db->prepare("select count(p.id) as total, a.description as age, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						left join survey_data s
							on s.id = p.survey_data_id
						left join agerangelist a
							on a.agerangeid = p.age
						where county = :county
						and (select count(pd.id) from person_data pd where pd.age = 2 and pd.is_head = 1 and pd.survey_data_id = s.id) = 1
						and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
						group by age;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	foreach($count as $c){
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of persons (".$c['age'].")</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";

	}
?>

	<tr class="vols title">
		     	<th><h4>Gender (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php
	//GENDERSS
	$query = $db->prepare("select genderid id, gender from genderlist");
	$query->execute();
	$genders = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, g.gender as gender, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join genderlist g
					on g.genderid = p.gender
				where county = :county
				and (select count(pd.id) from person_data pd where pd.age = 2 and pd.is_head = 1 and pd.survey_data_id = s.id) = 1
				and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0
				and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
				group by g.gender");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['gender']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
	?>

	<tr class="vols title">
		     	<th><h4>Ethnicity (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//ethnicity
	$query = $db->prepare("select ethnicityid id, description ethnicity from ethnicitylist");
	$query->execute();
	$ethnicities = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, e.description as ethnicity, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join ethnicitylist e
					on e.ethnicityid = p.ethnicity
				where county = :county
				and (select count(pd.id) from person_data pd where pd.age = 2 and pd.is_head = 1 and pd.survey_data_id = s.id) = 1
				and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0
				and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
				group by ethnicity");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['ethnicity']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";

	?>

	<tr class="vols title">
		     	<th><h4>Race (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//race
	$query = $db->prepare("select raceid id, description race from racelist");
	$query->execute();
	$races = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, r.description as race, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from race_data rd
				left join person_data p
					on p.id = rd.person_data_id
				left join survey_data s
					on s.id = rd.survey_data_id
				left join racelist r
					on r.raceid = rd.racelist_id
				where county = :county
				and (select count(pd.id) from person_data pd where pd.age = 2 and pd.is_head = 1 and pd.survey_data_id = s.id) = 1
				and (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
	group by race");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['race']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>

<!--
	*
	*
	* VETERANS WITH AT LEAST ONE CHILD

		Copy above chart down here and add the veteran flag.
	*
	*
	-->
	<div class="col-md-8 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="vet">
Veteran Households with at Least One Adult and One Child</h4>
				</div>
		        <tr class="vet title">
		          <th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
			</thead>
			<tbody>

<?php
	//With at least one kid
	//
	$query = $db->prepare("select coalesce(sum(is_trans), 0) as trans, coalesce(sum(is_unsheltered), 0) as un, coalesce(sum(is_temp), 0) as temp, coalesce(sum(is_hotel), 0) as hotel, coalesce(sum(is_emergency), 0) as emer, count(county) as total from survey_data s where s.county = :county and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0 AND (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Total number of persons
	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						right join survey_data s
							on s.id = p.survey_data_id
						where county = :county
						and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0 AND (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Persons age
		//Persons
	$query = $db->prepare("select count(p.id) as total, a.description as age, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						left join survey_data s
							on s.id = p.survey_data_id
						left join agerangelist a
							on a.agerangeid = p.age
						where county = :county
						and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0 AND (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
						group by age;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	foreach($count as $c){
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of persons (".$c['age'].")</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";

	}
?>

	<tr class="vet title">
		     	<th><h4>Gender (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php
	//GENDERSS
	$query = $db->prepare("select genderid id, gender from genderlist");
	$query->execute();
	$genders = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, g.gender as gender, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join genderlist g
					on g.genderid = p.gender
				where county = :county
				and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0 AND (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
				group by g.gender");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['gender']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
	?>

	<tr class="vet title">
		     	<th><h4>Ethnicity (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//ethnicity
	$query = $db->prepare("select ethnicityid id, description ethnicity from ethnicitylist");
	$query->execute();
	$ethnicities = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, e.description as ethnicity, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join ethnicitylist e
					on e.ethnicityid = p.ethnicity
				where county = :county
				and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0 AND (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
				group by ethnicity");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['ethnicity']."</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";

	?>

	<tr class="vet title">
		     	<th><h4>Race (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//race
	$query = $db->prepare("select raceid id, description race from racelist");
	$query->execute();
	$races = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, r.description as race, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from race_data rd
				left join person_data p
					on p.id = rd.person_data_id
				left join survey_data s
					on s.id = rd.survey_data_id
				left join racelist r
					on r.raceid = rd.racelist_id
				where county = :county
				and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0 AND (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
				group by race");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['race']."</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>

<!--
	*
	*
	* VETERANS WITHOUT CHILDREN
	*
	*
	-->
	<div class="col-md-8 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="vet2">
Veteran Households without Children</h4>
				</div>
		        <tr class="vet2 title">
		          <th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
			</thead>
			<tbody>

<?php
	//With at least one kid
	//
	$query = $db->prepare("select coalesce(sum(is_trans), 0) as trans, coalesce(sum(is_unsheltered), 0) as un, coalesce(sum(is_temp), 0) as temp, coalesce(sum(is_hotel), 0) as hotel, coalesce(sum(is_emergency), 0) as emer, count(county) as total
	from survey_data s
	where s.county = :county
	and (select count(pd.id)
		from person_data pd
			left join survey_data sd
			on sd.id = pd.survey_data_id
		where pd.is_military = 1
		and s.id = sd.id) > 0
		AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Total number of persons
	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						right join survey_data s
							on s.id = p.survey_data_id
						where county = :county
						AND (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0
						AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Persons age
		//Persons
	$query = $db->prepare("select count(p.id) as total, a.description as age, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
					from person_data p
						left join survey_data s
							on s.id = p.survey_data_id
						left join agerangelist a
							on a.agerangeid = p.age
						where county = :county
						and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0
						AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0
						group by age;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	foreach($count as $c){
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of persons (".$c['age'].")</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";

	}
?>

	<tr class="vet2 title">
		     	<th><h4>Gender (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php
	//GENDERSS
	$query = $db->prepare("select genderid id, gender from genderlist");
	$query->execute();
	$genders = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, g.gender as gender, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join genderlist g
					on g.genderid = p.gender
				where county = :county
				and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0
				AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0
				group by g.gender");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['gender']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
	?>

	<tr class="vet2 title">
		     	<th><h4>Ethnicity (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//ethnicity
	$query = $db->prepare("select ethnicityid id, description ethnicity from ethnicitylist");
	$query->execute();
	$ethnicities = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, e.description as ethnicity, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from person_data p
				left join survey_data s
					on s.id = p.survey_data_id
				left join ethnicitylist e
					on e.ethnicityid = p.ethnicity
				where county = :county
				and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0
				AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0
				group by ethnicity");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['ethnicity']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";

	?>

	<tr class="vet2 title">
		     	<th><h4>Race (adults and children)</h4></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
<?php

//race
	$query = $db->prepare("select raceid id, description race from racelist");
	$query->execute();
	$races = $query->fetchAll();

	$query = $db->prepare("select count(p.id) as total, r.description as race, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
			from race_data rd
				left join person_data p
					on p.id = rd.person_data_id
				left join survey_data s
					on s.id = rd.survey_data_id
				left join racelist r
					on r.raceid = rd.racelist_id
				where county = :county
				and (select count(pd.id) from person_data pd left join survey_data sd on sd.id = pd.survey_data_id where pd.is_military = 1 and s.id = sd.id) > 0
				AND (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0
				group by race");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$c['race']."</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>


	<!--
	*
	*
	* Homeless subpopulations
	*
	*
	-->
	<div class="col-md-8 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="purp">Homeless subpopulations</h4>
				</div>

				<tr class="purp title">
		     	<th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>

		          <th>Total</th>
		        </tr>
		    </thead>
<?php

//ethnicity

	$query = $db->prepare("select s.id, count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where county = :county
		and	(select count(cd.id) from condition_data cd where cd.person_data_id = p.id) > 0
		and (p.history_1 = 1
		or p.history_2 = 1)
		");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chronically Homeless Individuals</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}



		$query = $db->prepare("select count(distinct s.id) as total, coalesce(sum(distinct s.is_emergency), 0) as emer, coalesce(sum(distinct s.is_hotel), 0) as hotel, coalesce(sum(distinct s.is_temp), 0) as temp, coalesce(sum(distinct s.is_trans), 0) as trans, coalesce(sum(distinct s.is_unsheltered), 0) as un
	from survey_data s
		left join person_data p
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where county = :county
		and (select count(pd.id) from person_data pd where pd.is_head = 1 and (pd.history_1 = 1
		or pd.history_2 = 1) and pd.survey_data_id = s.id and (select count(cd.id) from condition_data cd where cd.person_data_id = pd.id) > 0) = 1");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chronically Homeless Families</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

			$query = $db->prepare("select count(s.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from survey_data s
		left join person_data p
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where county = :county
		and (select count(pd.id) from person_data pd where pd.is_head = 1 and (pd.history_1 = 1
		or pd.history_2 = 1) and pd.survey_data_id = s.id and (select count(cd.id) from condition_data cd where cd.person_data_id = pd.id) > 0) = 1 ");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Persons in Chronically Homeless Families</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";


		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}


	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$total."</th></tr>";

	?>

	<tr class="purp title">
		     	<th></th>
		          <th class='column'>Emergency Shelter</th>
		          <th class='column'>Transitional Housing</th>
		          <th class='column'>Unsheltered</th>
		          <th class='column'>Precariously Housed</th>
		          <th>Total</th>
		        </tr>
<?php
	$overalTotal = 0;

	$query = $db->prepare("select count(s.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where county = :county
		and c.cond = 'severe-mental'
		and p.age != 1");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adults with a severe mental illness</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";

		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	$overalTotal = $overalTotal + $total;

	$query = $db->prepare("select count(s.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where county = :county
		and c.cond = 'severe-chronic'
		and p.age != 1");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adults with a Substance Use Disorder</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	$overalTotal = $overalTotal + $total;

	$query = $db->prepare("select count(s.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where county = :county
		and c.cond = 'hiv'
		and p.age != 1");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adults with HIV/AIDS</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	$overalTotal = $overalTotal + $total;

	$query = $db->prepare("select count(s.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		where county = :county
		and p.history_1 = 1");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Has lived on the streets or in an emergency shelter for a full 12 months</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";

		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	$overalTotal = $overalTotal + $total;

$query = $db->prepare("select count(s.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		where county = :county
		and p.history_2 = 1");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Has been on the streets or in a shelter four times in the last three years</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	$overalTotal = $overalTotal + $total;

	echo "<tr><th></th><th></th><th></th><th></th><th></th><th class='danger'>".$overalTotal."</th></tr>";
?>
			</tbody>
		</table>
	</div>


	<!--
	*
	*
	* LOCATIONS
	*
	*
	-->
	<div class="col-md-4 col-md-offset-1" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="bg-warning title">Survey Volunteers</h4>
				</div>

				<tr class="warning title">
		          <th class='column'>Name</th>
		          <th class='column'>Surveys Submitted</th>
		        </tr>
		    </thead>
<?php

//ethnicity

	$query = $db->prepare("select count(s.id) as total, s.volunteer_name name
	from survey_data s
	where county = :county
	group by name;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {

		echo "<th class='num'>".$c['name']."</th>";

		//total
		echo "<th >".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}


	echo "<tr><th>Total Surveys Taken:</th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>


	<!--
	*
	*
	* LOCATIONS
	*
	*
	-->
	<div class="col-md-4 col-md-offset-2" data-example-id="striped-table" style="padding-top: 20px;">
				<table class="table table-bordered table-striped table-condensed">
			<thead>
				<div class="bs-example bs-example-bg-classes" data-example-id="contextual-backgrounds-helpers">
					<h4 class="bg-warning title">Locations</h4>
				</div>

				<tr class="warning title">
		          <th class='column'>Name</th>
		          <th class='column'>Date</th>
		          <th class='column'>Count</th>
		        </tr>
		    </thead>
<?php

//ethnicity

	$query = $db->prepare("select count(s.id) as total, s.location name, s.date date
	from survey_data s
	where county = :county
	group by name ASC;");
	$query->bindParam(':county', $county, PDO::PARAM_STR);
	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {

		echo "<th class='num'>".$c['name']."</th>";
		echo "<th class='num'>".date("d-m-Y", strtotime($c['date']))."</th>";
		//total
		echo "<th >".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}


	echo "<tr><th></th><th></th><th class='danger'>".$total."</th></tr>";
?>
			</tbody>
		</table>
	</div>

	</div>
</div>
</div>

</body>