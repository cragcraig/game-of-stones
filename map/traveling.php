<?php
	// CONNECT TO DATABASE
	include('mapdata/coordinates.inc');
	include('places/info.php');
	$no_query=1;
	include('places/connect_info.php');
	$char = mysql_fetch_array(mysql_query("SELECT * FROM Users LEFT JOIN Users_data ON Users.id=Users_data.id WHERE Users.id='$id'"));
	$error=0;
	
	// START TRAVELING
	if (str_replace('_',' ',$_GET['goto']) && $_GET['dir'])
	{
		if (str_replace('_',' ',$_GET['l'])==$char['location'] && (!$_GET['dock'] || (($_GET['dock']-2)/7 == intval(($_GET['dock']-2)/7) && ($_GET['dock']-2)/7<=$char['gold'])))
		{
			$char['gold']-=($_GET['dock']-2)/7;
			$char['travelto']=str_replace('_',' ',$_GET['goto']);
			if ($char['arrival']<=time() && $location_array[$char['travelto']][0])
			{
				// SET VARIABLES / LOAD MAP / GET TRAVEL TIME
				if ($_GET['dir']<=4 && $_GET['dir']>0)
				{
					$filename="mapdata/".str_replace(' ','_',strtolower($char['location'])).".map";
					include($filename);
					$mapdata=explode('#',$mapdata);
					$dimensions=explode('|',$mapdata[0]);
					$time_mult=$dimensions[8+$_GET['dir']];
				}
				else $time_mult=15;
				
				// SET TRAVELING
				if ($char['travelmode']*4+8<=$char['feedneed']) $char['travelmode']=0; // WALK IF HORSE IS TOO HUNGRY
				$char['depart']=time();
				if ($char['type'] == 'Channeler') $time_mult = $time_mult * 0.3;
				$char['arrival']=time()+round(7*$time_mult*(1.0 - 0.1 * $char['travelmode']));
				if ($_GET['dock']) {$char['arrival']=time()+15; $char['traveltype']=1; $more_javascript="// parent.UpdateTop('Passage Secured','".number_format($char['gold'])."');\n";}
				else $char['traveltype']=0;
				if ($char['travelmode'] && !$_GET['dock']) $char['feedneed']++;
				mysql_query("UPDATE Users SET travelto='".$char['travelto']."', depart='".$char['depart']."', arrival='".$char['arrival']."', feedneed='".$char['feedneed']."', gold='".$char['gold']."', traveltype='".$char['traveltype']."' WHERE id='$id'");
			}
			else $error=3;
		}
		else {$error=1; $message="Not enough gold";}
	}
	elseif ($char['arrival']<=time()) $error=1;
	
	if ($error) {header("Location: $server_name/map/townmap.php?update=1&message=$message"); exit;} // ERROR CHECK
	
	$loc=$char['travelto'];
	$locfrom=$char['location'];
	$speed=intval(0.3+SQRT(pow($location_array[$loc][0]-$location_array[$locfrom][0],2)+pow($location_array[$loc][1]-$location_array[$locfrom][1],2))/($char['arrival']-time()));
	if ($speed<1) $speed=1;
?>
<link REL="StyleSheet" TYPE="text/css" HREF="places/style.css">
<html>
<head>
	<SCRIPT LANGUAGE="JavaScript">
	
	<?php echo $more_javascript; ?>
	var TimeLeft = <?php echo ($char['arrival']-time()); ?>;
	var travelled = 0;
	window.onLoad = startCounter();
	parent.SetMapPos(<?php echo $location_array[$loc][0].",".$location_array[$loc][1].",".$speed; ?>);
	parent.setInfo('<?php echo "Traveling to ".str_replace('-ap-','&#39;',$char['travelto']); ?>');
	var MapWidth=6;
	var MapHeight=6;
	var mult=0.0;

	function startCounter()
	{
		window.self.setInterval("myCounter()", 1000);
	}
	
	function myCounter()
	{
		TimeLeft--;
		if (TimeLeft<0)
		{
			TimeLeft=0;
			if (!travelled)
			{
				travelled=1;
				parent.document.getElementById('TownMap').src='map/townmap.php?update=1&load='+(Math.random()*1000);
			}
		}
		var t_per = TimeLeft/<?php echo ($char['arrival']-$char['depart']); ?>;
		if (TimeLeft>0) document.getElementById('travelInfo').innerHTML="<font class='littletext'>"+Math.round(100-t_per*100)+' percent completed';
		else document.getElementById('travelInfo').innerHTML="<font class='medtext'>Entering Town . . .";
		document.getElementById('moveBar').width=300*(<?php echo ($char['arrival']-$char['depart']); ?>-TimeLeft)/<?php echo ($char['arrival']-$char['depart']); ?>;
	}
	
	</SCRIPT>
</head>
<body bgcolor="black">

	<br>
	<br>
	<br>
	<center>
	<font class="medtext">
	Traveling from
	<br>
	<?php echo "<b>".str_replace('-ap-','&#39;',$char['location'])."</b> to <b>".str_replace('-ap-','&#39;',$char['travelto'])."</b>"; ?>
	<br>
	<br>
	<table width="300" height="30"><tr><td width="<?php echo intval(300*(time()-$char['depart'])/($char['arrival']-$char['depart'])+1); ?>" id="moveBar" background="loadbar<?php if ($char['traveltype']==1) echo "2"; ?>.gif" bgcolor="#999999"></td><td bgcolor="#000000"></td></tr><table>
	<br>
	<br>
	<table border="0" cellpadding="0" cellspacing="0"><tr><td id="travelInfo"><font class='littletext'><?php echo intval(100*(time()-$char['depart'])/($char['arrival']-$char['depart'])); ?> percent completed</td></tr></table> 
<br><br><br>
</body>
<footer>
</footer>
</html>
