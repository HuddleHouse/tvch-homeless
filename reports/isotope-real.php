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
	.jumbotron {
	    padding-right: 30px !important;
	    padding-left: 30px !important;
        padding-top: 25px !important;
	    padding-bottom: 25px !important;
	}
	h4 {
	    margin: 0 0 0px !important;
	    padding-top: 0px !important;
	    padding-bottom: 0px !important;
	    padding-left: 0px !important;
	        color: #372260;
	}
@media (min-width: 768px) {
  .col-md-4:nth-child(3n+1) {
    clear:both;
  }
}
/* ---- button ---- */

.button {
  display: inline-block;
  padding: 0.3em 1.0em;
  background: #EEE;
  border: none;
  border-radius: 7px;
      border: none !important;
  background-image: linear-gradient( to bottom, hsla(0, 0%, 0%, 0), hsla(0, 0%, 0%, 0.2) );
  color: #222;
  font-family: sans-serif;
  font-size: 16px;
  text-shadow: 0 1px white;
  cursor: pointer;
}

.button:hover {
  background-color: #8CF;
  text-shadow: 0 1px hsla(0, 0%, 100%, 0.5);
  color: #222;
}

.button:active,
.button.is-checked {
  background-color: #28F;
}

.button.is-checked {
  color: white;
  text-shadow: 0 -1px hsla(0, 0%, 0%, 0.8);
}

.button:active {
  box-shadow: inset 0 1px 10px hsla(0, 0%, 0%, 0.8);
}

/* ---- button-group ---- */

.button-group:after {
  content: '';
  display: block;
  clear: both;
}

.button-group .button {
margin-bottom: 5px;
  float: left;
  border-radius: 0;
  margin-left: 0;
  margin-right: 1px;
}

.button-group .button:first-child, .button-group .knox, .button-group .knox-ky, .button-group .hr, .button-group .medical, .button-group .mortage, .button-group .womens, .button-group .senior { border-radius: 0.5em 0 0 0.5em; }
.button-group .button:last-child, .button-group .jefferson, .button-group .harlan, .button-group .child-services, .button-group .food, .button-group .mental, .button-group .rent, .button-group .veterans { border-radius: 0 0.5em 0.5em 0; }
body {
    background: #262524;
}
.h1, h1 {
    font-size: 36px;
    color: #eee;
}
hr {
    margin-top: 5px;
    margin-bottom: 10px;
    margin-left: -5px;
    border: 0;
    border-top: 1px solid #333;
}
</style>

<?php
	include '../config2.php';

	$tmp = 0;
?>
<script>
// external js: isotope.pkgd.js

$(document).ready( function() {
  // init Isotope
  var $grid = $('.grid').isotope({
    itemSelector: '.element-item'
  });

  // store filter for each group
  var filters = {};

  $('.filters').on( 'click', '.button', function() {
    var $this = $(this);
    // get group key
    var $buttonGroup = $this.parents('.button-group');
    var filterGroup = $buttonGroup.attr('data-filter-group');
    // set filter for group
    filters[ filterGroup ] = $this.attr('data-filter');
    // combine filters
    var filterValue = concatValues( filters );
    // set filter for Isotope
    $grid.isotope({ filter: filterValue });
  });

  // change is-checked class on buttons
  $('.button-group').each( function( i, buttonGroup ) {
    var $buttonGroup = $( buttonGroup );
    $buttonGroup.on( 'click', 'button', function() {
      $buttonGroup.find('.is-checked').removeClass('is-checked');
      $( this ).addClass('is-checked');
    });
  });

});

// flatten object by concatting values
function concatValues( obj ) {
  var value = '';
  for ( var prop in obj ) {
    value += obj[ prop ];
  }
  return value;
}

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
			<div class="row" style="padding-top: 20px;">
			  <div class="ui-group">
				<h1>Services by County</h1>
				<div data-filter-group="counties" class="button-group">  <button class="button is-checked" data-filter="">SHOW ALL</button>
				  <?php
					foreach($countyList as $matt) {
						echo "<button class='button ".$matt['name']."' data-filter='.".$matt['name']."'>".$matt['county']."</button>";
					}
				  ?>
				</div>
			   </div>
			</div>
			<div class="row" style="padding-top: 10px;">
				<div class="ui-group">
					<h1>Services by Type</h1>
					<div data-filter-group="services" class="button-group">
						<button class="button is-checked" data-filter="">SHOW ALL</button>
			  <?php
				foreach($serviceList as $serv) {
					echo "<button class='button ".$serv['name']."' data-filter='.".$serv['name']."'>".$serv['type']."</button>";
				}
			  ?>
			  			<button class="button" data-filter=".other">Other</button>
					</div>
				</div>
			</div>
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