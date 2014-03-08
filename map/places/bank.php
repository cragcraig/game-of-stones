<link REL="StyleSheet" TYPE="text/css" HREF="style.css">
<html>
	<head>
	<SCRIPT LANGUAGE="JavaScript">
		<?php
			$_POST['gold']=abs($_POST['gold']);
			include('info.php');
			include('connect_info.php');
			include($_SERVER['DOCUMENT_ROOT']."/".$subfile."/map/mapdata/coordinates.inc");
			if (intval($_POST['gold']) && $location_array[$char['location']][5])
			{
				if (time()-$char['lastbank']>=30)
				{
					if ($_POST['deposit'])
					{
						if (($char['gold']+$char['bankgold'])*0.80>=intval($_POST['gold'])+$char['bankgold'] && $char['gold']>=intval($_POST['gold']))
						{
							$char['gold']-=intval($_POST['gold']);
							$char['bankgold']+=intval($_POST['gold']);
							$char['lastbank']=time();
							mysql_query("UPDATE Users SET gold='".$char['gold']."', bankgold='".$char['bankgold']."', lastbank='".$char['lastbank']."' WHERE id='".$char['id']."'");
							$message=number_format(intval($_POST['gold']))." gold deposited";
						}
						else $message="You must keep at least ".number_format(intval(($char['gold']+$char['bankgold'])*0.20)+1)." gold out";
					}
					if ($_POST['withdraw'])
					{
						if ($char['bankgold']>=intval($_POST['gold']))
						{
							$char['bankgold']-=intval($_POST['gold']);
							$char['gold']+=intval($_POST['gold']);
							$char['lastbank']=time();
							mysql_query("UPDATE Users SET gold='".$char['gold']."', bankgold='".$char['bankgold']."', lastbank='".$char['lastbank']."' WHERE id='".$char['id']."'");
							$message=number_format(intval($_POST['gold']))." gold withdrawn";
						}
						else $message="There is not that much gold in your account";
					}
				}
				else $message="You must wait ".(31-(time()-$char['lastbank']))." seconds before using a bank again";
				if ($message) echo "window.onLoad=parent.UpdateTop('$message',".$char[gold].");";
			}
		?>
	</SCRIPT>
	</head>
	<body bgcolor="black">
		<font class="littletext">
		<table class='blank'><tr><td><img src='tools/gold.gif'></td><td class='littletext'>&nbsp;<b>Bank:</b>
		<font class='littletext_f'>
		<?php
			echo number_format($char['bankgold'])." g";
			$gold_dep = floor(($char['gold']+$char['bankgold'])*0.80)-$char['bankgold'];
			if ($gold_dep<0) $gold_dep = 0;
		?>
		</td></tr></table>
		<font class="littletext">
		<br>
		<form action="bank.php" method="post">
			Gold: 
			<input type="text" name="gold" value="<?php echo $gold_dep ?>" class="form" size="7" maxlength="10">
			<br>
			<br>
			<input type="submit" value="Deposit" name="deposit" class="form">
			&nbsp;
			<input type="submit" value="Withdraw" name="withdraw" class="form">
		</form>
	
	</body>
	<footer>
	</footer>
</html>
