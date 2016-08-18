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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>

<title></title>
</head>

<style>
</style>

<?php
	include './config2.php';

	$tmp = 0;
?>
<script>
// external js: isotope.pkgd.js
</script>
<?php
$qu = $db->prepare("select name as name, county as county from county;");
	$qu->execute();
	$countyList = $qu->fetchAll();
$qu = $db->prepare("select name as name, servtype as type from services;");
	$qu->execute();
	$serviceList = $qu->fetchAll();
?>

<body>
	<div class="container" style="padding-top: 20px; ">
		<center><h1>Community Resources</h1></center>
		<div class="filters">
		</div>

		<div class="row" style="padding-top: 50px;">
<div class="grid">
<?php

	$query = $db->prepare("select distinct a.faith as faith, a.govt as gov, p.program as program, a.agencyname as agency, p.address1 as address, p.address2 as city, p.state as state, p.zip as zip, p.phone as phone, p.altphone as phone2, p.contact as contact, p.email as email, p.othserv as othserv, p.othqual as othqual, p.id as id, a.website as website
	from program p
		left join agency a
			on p.agencyid = a.id
		left join programcounty pc
			on pc.progid = p.id
		left join county c
			on c.id = pc.countyid");

	$query->execute();
	$count = $query->fetchAll();
	$total = 0;

	foreach($count as $c) {

		$q = $db->prepare("select c.county as county, p.program as program, c.name as cname
	from program p
		left join programcounty pc
			on pc.progid = p.id
		left join county c
			on c.id = pc.countyid
	where p.program = :county and p.id = :id
	group by county");
			$q->bindParam(':county', $c['program'], PDO::PARAM_STR);
			$q->bindParam(':id', $c['id'], PDO::PARAM_STR);
			$q->execute();
			$counties = $q->fetchAll();

			$p = $db->prepare("select s.servtype as service, p.program as program, s.name as sname
	from program p
		left join programservices pc
			on pc.progid = p.id
		left join services s
			on s.id = pc.servicesid
	where p.program = :county and p.id = :id
	group by service");
			$p->bindParam(':county', $c['program'], PDO::PARAM_STR);
			$p->bindParam(':id', $c['id'], PDO::PARAM_STR);
			$p->execute();
			$services = $p->fetchAll();

			$s = $db->prepare("select s.qual as qual
	from program p
		left join programqual pc
			on pc.progid = p.id
		left join qual s
			on s.id = pc.qualid
	where p.program = :county and p.id = :id
	group by qual");
			$s->bindParam(':county', $c['program'], PDO::PARAM_STR);
			$s->bindParam(':id', $c['id'], PDO::PARAM_STR);
			$s->execute();
			$quals = $s->fetchAll();

			$d = $db->prepare("select s.disq as qual, p.program as program
	from program p
		left join programdisqual pc
			on pc.progid = p.id
		left join disqual s
			on s.id = pc.disqid
	where p.program = :county and p.id = :id
	group by disq");
			$d->bindParam(':county', $c['program'], PDO::PARAM_STR);
			$d->bindParam(':id', $c['id'], PDO::PARAM_STR);
			$d->execute();
			$disquals = $d->fetchAll();
	?>

	<div class="col-md-4 element-item <?php foreach($counties as $county){echo $county['cname']." "; } ?><?php foreach($services as $service){echo $service['sname']." "; } ?><?php if($c['othserv'] != ' ') {echo"other";}?>"  style="padding-top: 20px;">
		<div class="jumbotron" id="callout-type-b-i-elems">
			<address>
			  <strong><h4><?php echo $c['agency'] ?></h4></strong><hr>
			  <div style="color: #337ab7;margin-bottom: -15px;"><strong><?php echo $c['program'] ?></strong></div><br>
			  <?php if($c['address']){echo $c['address']."<br>";} ?>
			  <?php if($c['city']){echo $c['city'].", ".$c['state']; }?><?php if($c['zip']){echo " ".$c['zip']."<br>"; }?><br>
			  <?php
				if($c['gov'] == 'on') {echo "<strong>FEDERALLY FUNDED</strong><br><br>";}
				if($c['faith'] == 'on') {echo "<strong>FAITH BASED</strong><br><br>";}
				if($c['contact']) {echo "<strong>Website:&nbsp;&nbsp;</strong><a title='Phone' href='".$c['website']."' target='_blank'>".$c['website']."</a><br>";}
				if($c['contact']) {echo "<abbr title='Phone'><strong>Contact:&nbsp;&nbsp;</strong></abbr>".$c['contact']."<br>";}
				if($c['phone']) {echo "<abbr title='Phone'><strong>P:&nbsp;&nbsp;</strong></abbr>".$c['phone']."<br>";}
				if($c['phone2']) {echo "<abbr title='Phone'><strong>P:&nbsp;&nbsp;</strong></abbr>".$c['phone2']."<br>";}
				if($c['email']) {echo "<abbr title='Phone'><strong>Email:&nbsp;&nbsp;</strong></abbr><a href='mailto:".$c['email']."'>".$c['email']."</a><br>";}
			  ?>
			</address>
	<?php

			echo "<ul class='list-inline'><strong>Counties Served:</strong>";
			$tmp = 0;
			foreach($counties as $county) {
					if($tmp >= 1) {
						echo ",</li><li>".$county['county'];
					}
					else {
						echo "<li>".$county['county'];
					}
				$tmp += 1;
				}
				echo "</li></ul>";
				$tmp = 0;
			echo "<ul class='list-inline'><strong>Services Offered:</strong>";
				if($c['othserv'] != ' ') {
					echo "<li>".$c['othserv']."</li>";
				}

			if($services[0][0] != NULL) {
				foreach($services as $service) {
					if($tmp >= 1) {
						echo ",</li><li>".$service['service'];
					}
					else {
						echo "<li>".$service['service'];
					}
					$tmp += 1;
				}
			}

				echo "</li></ul>";

			if($quals[0][0] != NULL || $c['othqual'] != ' ') {
					echo "<ul class='list-inline'><strong>Eligibility Requirements:</strong>";
					if($c['othqual'] != ' ') {
						echo "<li>".$c['othqual']."</li>";
					}
				foreach($quals as $qual) {
					echo "<li>".$qual['qual']."</li>";
					}
					echo "</ul>";
			}

			if($disquals[0][0] != NULL) {
					echo "<ul class='list-inline'><strong>Disqualifying Factors:</strong>";

				foreach($disquals as $disqual) {
					echo "<li>".$disqual['qual']."</li>";
					}
					echo "</ul>";
			}
			?>
		</div>
	</div>
<?php }
	//Total number of persons

?>
</div>
	</div>

	</div>
</div>


</body>