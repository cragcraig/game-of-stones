<link REL="StyleSheet" TYPE="text/css" HREF="style.css">
<?php
	include('info.php');
	$no_query=1;
	include('connect_info.php');
	$char = mysql_fetch_array(mysql_query("SELECT * FROM Users LEFT JOIN Users_data ON Users.id=Users_data.id WHERE Users.id='$id'"));
	
	// CHECK RESOURCES
	include($_SERVER['DOCUMENT_ROOT']."/".$subfile."/admin/tree_skills.php");
	$resources=unserialize($char['resources']);
	$skill_tree=unserialize($char['skill_tree']);
	$over_limit=0;
	for ($x=0; $x<3; $x++) {
		for ($y=0; $y<3; $y++)
			if ($skill_tree[$skilltree[2][$y][$x]]*6<$resources[$x][$y]) $over_limit=1;
		if ($skill_tree[$skilltree[2][2][3]]*6<$resources[$x][3]) $over_limit=1;
	}
	$_GET['l']=str_replace('_',' ',$_GET['l']);
	$_GET['n']=str_replace('_',' ',$_GET['n']);
	$_GET['s']=str_replace('_',' ',$_GET['s']);
	$_GET['e']=str_replace('_',' ',$_GET['e']);
	$_GET['w']=str_replace('_',' ',$_GET['w']);
?>
<html>
	<head>
		<SCRIPT LANGUAGE="JAVASCRIPT">
			var Here='<?php echo $_GET['l']; ?>';
			var arrowSelected=0;
			var Loc = new Array();
			Loc[0]='<?php echo $_GET['l']; ?>';
			Loc[1]='<?php echo $_GET['n']; ?>';
			Loc[2]='<?php echo $_GET['s']; ?>';
			Loc[3]='<?php echo $_GET['e']; ?>';
			Loc[4]='<?php echo $_GET['w']; ?>';
			parent.sellRes=<?php echo $over_limit; ?>;
			
			function showArea(dir)
			{
				var speed = 35;
				if (dir==-1) {speed=0; dir=0;}
				parent.SetMapPos(Loc[dir][0],Loc[dir][1],speed);
			}
			
			function clickArrow(arrow)
			{
				if (arrowSelected && arrowSelected!=arrow.id) document.getElementById(arrowSelected).src="imgs/"+document.getElementById(arrowSelected).id+".gif";
				arrowSelected=arrow.id;
				if (Loc[arrow.name]) {
					if (parent.sellRes) parent.popConfirm('You have more supplies than you are skilled to transport. Sell all extras and continue on the road to '+Loc[arrow.name].replace('-ap-',"&#39;").replace('-ap-',"&#39;").replace('-ap-',"&#39;")+'?','javascript:setTraveling('+arrow.name+',0)');
					else parent.popConfirm('Take the road to '+Loc[arrow.name].replace('-ap-',"&#39;").replace('-ap-',"&#39;").replace('-ap-',"&#39;")+'?','javascript:setTraveling('+arrow.name+',0)');
				}
			}
			
			function leaveArrow(arrow)
			{
				/*if (arrow.id != arrowSelected)*/ arrow.src="imgs/"+arrow.id+".gif";
				document.getElementById('Go').src="imgs/center.gif";
				document.getElementById('infoSet').innerHTML="";
				//parent.hideMe2();
				//parent.ShowText('');
			}
			
			function overArrow(arrow)
			{
				if (Loc[arrow.name])
				{
					arrow.src='imgs/'+arrow.id+'_s.gif';
					document.getElementById('Go').src="imgs/go.gif";
					document.getElementById('infoSet').innerHTML="<font class='littletext'><b>Road to "+Loc[arrow.name].replace('-ap-',"&#39;").replace('-ap-',"&#39;").replace('-ap-',"&#39;")+"</b><br>";
					//parent.ShowText('<b>Travel to '+Loc[arrow.name]+'</b>');
					//var pos=findPos(arrow);
					//parent.showInfo('<b>Travel to '+Loc[arrow.name]+'</b>',470,250);
				}
			}
		</SCRIPT>
	</head>
	<body bgcolor="black">
		<table border="0" cellpadding="0" cellspacing="0" height="20">
			<tr>
				<td id="textblock" class='foottext_f'>
					<b>Travel from <?php echo str_replace('-ap-','&#39;',$char['location']); ?>:</b>
				</td>
			</tr>
		</table>
		
		<!-- COMPASS -->
		<center>
		<table>
			<tr>
				<td></td>
				<td width="25" height="25">
					<?php if ($_GET['n']) { ?>
					<img src="imgs/n.gif" border="0" alt="N" onMouseover="overArrow(this);" onMouseout="leaveArrow(this);" onClick="clickArrow(this);" id="n" name="1">
					<?php } ?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td width="25" height="25">
					<?php if ($_GET['w']) { ?>
					<img src="imgs/w.gif" border="0" alt="W" onMouseover="overArrow(this);" onMouseout="leaveArrow(this);" onClick="clickArrow(this);" id="w" name="4">
					<?php } ?>
				</td>
				<td width="25" height="25">
					<img src="imgs/center.gif" id="Go" alt="Go">
				</td>
				<td width="25" height="25">
					<?php if ($_GET['e']) { ?>
					<img src="imgs/e.gif" border="0" alt="E" onMouseover="overArrow(this);" onMouseout="leaveArrow(this);" onClick="clickArrow(this);" id="e" name="3">
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td width="25" height="25">
					<?php if ($_GET['s']) { ?>
					<img src="imgs/s.gif" border="0" alt="S" onMouseover="overArrow(this);" onMouseout="leaveArrow(this);" onClick="clickArrow(this);" id="s" name="2">
					<?php } ?>
				</td>
				<td></td>
			</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0"><tr><td id="infoSet" height="30" align='center'>&nbsp;</td></tr></table>
	</body>
	<footer>
	</footer>
</html>
