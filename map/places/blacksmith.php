<link REL="StyleSheet" TYPE="text/css" HREF="style.css">
<?php
	$travel_mode=array(
		'canvas sack',
		'backpack',
		'small cart',
		'cart',
		'farm cart',
		'carriage',
		'wagon',
	);
	$travel_mode_cost=array(
		0,
		200,
		1000,
		3000,
		5000,
		10000,
		20000,
	);
?>
<html>
	<head>
	<SCRIPT LANGUAGE="JavaScript">
		<?php
			include('info.php');
			include('connect_info.php');
			if ($travel_mode_cost[$_GET['buy']])
			{
				if ($char['travelmode2']<=$char['travelmode'])
				{
					if ($travel_mode_cost[$_GET['buy']]<=$char['gold'])
					{
						$char['gold']-=$travel_mode_cost[$_GET['buy']];
						$char['travelmode2']=$_GET['buy'];
						$message="You have purchased a ".ucwords($travel_mode[$_GET['buy']]);
						mysql_query("UPDATE Users SET gold='".$char['gold']."', travelmode2='".$char['travelmode2']."' WHERE id='".$char['id']."'");
					}
					else $message="You do not have that much gold";
				}
				else $message="You need a stronger animal to pull a ".$travel_mode[$_GET['buy']];
			}
			if ($message) echo "window.onLoad=parent.UpdateTop('$message',".$char[gold].");";
		?>
	</SCRIPT>
	</head>
	<body bgcolor="black">
		<font class="littletext">
		<b>Blacksmith:</b>
		<br>
		<font class="foottext">
		<?php
			if ($char['travelmode2']==0) echo "Currently carrying a bag";
			else echo "Currently using a ".$travel_mode[$char['travelmode2']];
		?>
		<br>
		<font class="foottext_f">
		[<i>holds <?php echo (10+4*$char['travelmode2']); ?> items</i>]
		<br>
		<br>
		<?php
			for ($x=$char['travelmode2']+1; $x<=$char['travelmode2']+2; $x++)
			{
				if ($travel_mode_cost[$x])
				{
					echo "<br><font class='littletext'>".ucwords($travel_mode[$x])."<font class='littletext_f'> - ".number_format($travel_mode_cost[$x])."g<font class='foottext'> [<a href='blacksmith.php?buy=$x&time=".time()."'>buy</a>]";
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
