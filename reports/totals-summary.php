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
			<center><h4>Summary HUD Report for 2016 Point-in-Time Count</h4></center>
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
					<h4 class="all">All Counties 2016 Point in Time</h4>
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
	$query = $db->prepare("select coalesce(sum(is_trans), 0) as trans, coalesce(sum(is_unsheltered), 0) as un, coalesce(sum(is_temp), 0) as temp, coalesce(sum(is_hotel), 0) as hotel, coalesce(sum(is_emergency), 0) as emer, count(county) as total from survey_data");

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
							on s.id = p.survey_data_id");

	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";
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
		where (select count(pd.id)
			from person_data pd
				where pd.survey_data_id = s.id
				and (pd.age = 2 or pd.age = 3)) = 0");

	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

	//Total number of persons
	$query = $db->prepare("select count(distinct s.id) as total, coalesce(sum(distinct s.is_emergency), 0) as emer, coalesce(sum(distinct s.is_hotel), 0) as hotel, coalesce(sum(distinct s.is_temp), 0) as temp, coalesce(sum(distinct s.is_trans), 0) as trans, coalesce(sum(distinct s.is_unsheltered), 0) as un
	from survey_data s
		left join person_data p
			on s.id = p.survey_data_id
		left join agerangelist a
			on p.age = a.agerangeid
		where (a.description = '0-17')
		and (select count(pd.id)
			from person_data pd
				left join survey_data sd
					on sd.id = pd.survey_data_id
				left join agerangelist al
					on pd.age = al.agerangeid
				where pd.survey_data_id = s.id
				and al.description != '0-17') = 0
		and (select count(pd.id)
			from person_data pd
				left join survey_data sd
					on sd.id = pd.survey_data_id
				left join agerangelist al
					on pd.age = al.agerangeid
				where pd.survey_data_id = s.id
				and al.description = '0-17') = 1");

	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Single-child Households</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

		//Persons
	$query = $db->prepare("select count(distinct s.id) as total, coalesce(sum(distinct s.is_emergency), 0) as emer, coalesce(sum(distinct s.is_hotel), 0) as hotel, coalesce(sum(distinct s.is_temp), 0) as temp, coalesce(sum(distinct s.is_trans), 0) as trans, coalesce(sum(distinct s.is_unsheltered), 0) as un
	from survey_data s
		left join person_data p
			on s.id = p.survey_data_id
		left join agerangelist a
			on p.age = a.agerangeid
		where (a.description = '0-17')
		and (select count(pd.id)
			from person_data pd
				left join survey_data sd
					on sd.id = pd.survey_data_id
				left join agerangelist al
					on pd.age = al.agerangeid
				where pd.survey_data_id = s.id
				and al.description != '0-17') = 0
		and (select count(pd.id)
			from person_data pd
				left join survey_data sd
					on sd.id = pd.survey_data_id
				left join agerangelist al
					on pd.age = al.agerangeid
				where pd.survey_data_id = s.id
				and al.description = '0-17') > 1");

	$query->execute();
	$c = $query->fetch();

		echo "<tr><th class='first'>Multi-child Households</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='danger'>".$c['total']."</th></tr>";


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
	$query = $db->prepare("select coalesce(sum(is_trans), 0) as trans, coalesce(sum(is_unsheltered), 0) as un, coalesce(sum(is_temp), 0) as temp, coalesce(sum(is_hotel), 0) as hotel, coalesce(sum(is_emergency), 0) as emer, count(county) as total from survey_data s where (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0");

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
						where (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0");

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
						where (select count(pd.id) from person_data pd where pd.age != 1 and pd.survey_data_id = s.id) > 0 and
	(select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) > 0
						group by age;");

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
		where (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0 ");

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
						where (select count(pd.id) from person_data pd where pd.age = 1 and pd.survey_data_id = s.id) = 0");

	$query->execute();
	$count = $query->fetch();

	echo "<tr><th class='first'>Total number of persons</th><th class='num'>".($count['emer']+$count['hotel'])."</th>";

	echo "<th class='num'>".$count['trans']."</th>";

	echo "<th class='num'>".$count['un']."</th>";

	echo "<th class='num'>".$count['temp']."</th>";



	//total
	echo "<th class='danger'>".$count['total']."</th></tr>";

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
		where (select count(cd.id) from condition_data cd where cd.person_data_id = p.id) > 0
		and (p.history = 'option1'
		or p.history = 'option2')
		");

	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>Chronically Homeless Individuals</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

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
		where (select count(pd.id) from person_data pd where pd.is_head = 1 and (pd.history = 'option1' or pd.history = 'option2') and pd.survey_data_id = s.id and (select count(cd.id) from condition_data cd where cd.person_data_id = pd.id) > 0) = 1");

	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>Chronically Homeless Families</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}

			$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		where p.is_military = 1");

	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>Veterans (individuals)</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}



	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join genderlist g
			on p.gender = g.genderid
		where p.is_military = 1
		and g.genderid = 2;");

	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>Female Veterans (individuals)</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	$overalTotal = $overalTotal + $total;

	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where c.cond = 'severe-mental'");

	$query->execute();
	$c = $query->fetch();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

		echo "<tr><th class='first'>Severely Mentally Ill (individuals)</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;

	$overalTotal = $overalTotal + $total;

	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where c.cond = 'severe-chronic'");

	$query->execute();
	$c = $query->fetch();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

		echo "<tr><th class='first'>Chronic Substance Abuse (individuals)</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";

		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	$overalTotal = $overalTotal + $total;

	$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		left join condition_data c
			on c.person_data_id = p.id
		where c.cond = 'hiv'");

	$query->execute();
	$count = $query->fetchAll();

	//var_dump($count);
	$tmp = 0;
	$total = 0;

	foreach($count as $c) {
		echo "<tr><th class='first'>Persons with HIV/AIDS</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;
	}
	$overalTotal = $overalTotal + $total;

$query = $db->prepare("select count(p.id) as total, coalesce(sum(s.is_emergency), 0) as emer, coalesce(sum(s.is_hotel), 0) as hotel, coalesce(sum(s.is_temp), 0) as temp, coalesce(sum(s.is_trans), 0) as trans, coalesce(sum(s.is_unsheltered), 0) as un
	from person_data p
		left join survey_data s
			on s.id = p.survey_data_id
		where p.is_violence = 1");

	$query->execute();
	$c = $query->fetch();

	//var_dump($count);
	$tmp = 0;
	$total = 0;


		echo "<tr><th class='first'>Victims of Domestic Violence</th><th class='num'>".($c['emer']+$c['hotel'])."</th>";

		echo "<th class='num'>".$c['trans']."</th>";

		echo "<th class='num'>".$c['un']."</th>";

		echo "<th class='num'>".$c['temp']."</th>";



		//total
		echo "<th class='warning'>".$c['total']."</th></tr>";
		$total = $total + $c['total'];
		$tmp++;

	$overalTotal = $overalTotal + $total;
?>
			</tbody>
		</table>
	</div>


	</div>
</div>
</div>

</body>