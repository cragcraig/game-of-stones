<link REL="StyleSheet" TYPE="text/css" HREF="style.css">
<?php
	$travel_mode=array(
		'foot',
		'pony',
		'donkey',
		'mule',
		'aged horse',
		'horse',
		'war horse',
	);
	$travel_mode_cost=array(
		0,
		300,
		1000,
		3000,
		9000,
		20000,
		45000,
	);
	function HorseNamer()
	{
		$first=array(
			'Cloud',
			'Wind',
			'Spray',
			'Mist',
			'Willow',
			'Spirit',
			'Myth',
		 	'Rum',
		 	'Dark',
		 	'Long',
		 	'Quick',
		 	'Rye',
		);
		$last=array(
			'walker',
			'prancer',
			'driver',
			'flasher',
			'breaker',
			'bender',
			'bucker',
			'smacker',
		);
		if (rand(0,10)<7) $horsename=$first[rand(0,count($first)-1)].$last[rand(0,count($last)-1)];
		else $horsename=$first[rand(0,count($first)-1)];
		return $horsename;
	}
?>
<html>
	<head>
	<SCRIPT LANGUAGE="JavaScript">
		<?php
			include('info.php');
			include('connect_info.php');
			if ($travel_mode_cost[$_GET['buy']])
			{
				if ($travel_mode_cost[$_GET['buy']]<=$char['gold'])
				{
					$char['gold']-=$travel_mode_cost[$_GET['buy']];
					$char['travelmode']=$_GET['buy'];
					$char['travelmode_name']=HorseNamer();
					$message="You have purchased a ".ucwords($travel_mode[$_GET['buy']])." named ".$char['travelmode_name'];
					mysql_query("UPDATE Users SET gold='".$char['gold']."', travelmode='".$char['travelmode']."', travelmode_name='".$char['travelmode_name']."' WHERE id='".$char['id']."'");
				}
				else $message="You do not have that much gold";
			}
			if ($_GET['feed'])
			{
				$cost=$char['feedneed']*$char['travelmode']*2;
				if ($cost<=$char['gold'])
				{
				$char['gold']-=$cost;
				$char['feedneed']=0;
				$message=$char['travelmode_name']." has been fed and is ready to travel";
				mysql_query("UPDATE Users SET gold='".$char['gold']."', feedneed='0' WHERE id='".$char['id']."'");
				}
				else $message="You do not have that much gold";
			}
			if ($message) echo "window.onLoad=parent.UpdateTop('$message',".$char[gold].");";
		?>
	</SCRIPT>
	</head>
	<body bgcolor="black">
		<font class="littletext">
		<b>Stable:</b>
		<br>
		<font class="foottext_f">
		<?php
			if ($char['travelmode']==0) echo "Currently traveling on foot";
			else echo "Currently riding ".$char['travelmode_name'].", a ".$travel_mode[$char['travelmode']];
			if ($char['feedneed']*$char['travelmode'])
			{
				if ($char['travelmode']*4+8<=$char['feedneed']) echo "<br>Needs feeding: <b>";
				else echo "<br>Feed: <b>";
				echo number_format($char['feedneed']*$char['travelmode']*2)."g</b> [<a href='stable.php?feed=1'>feed</a>]";
			}
		?>
		<br>
		<br>
		<?php
			for ($x=$char['travelmode']; $x<=$char['travelmode']+2; $x++)
			{
				if ($travel_mode_cost[$x])
				{
					echo "<br><font class='littletext'>".ucwords($travel_mode[$x])."<font class='littletext_f'> - ".number_format($travel_mode_cost[$x])."g<font class='foottext'> [<a href='stable.php?buy=$x&time=".time()."'>buy</a>]";
				}
			}
		?>
		<font class="littletext">
		<br>
		<br>
		
	
	</body>
	<footer>
	</footer>
</html>
