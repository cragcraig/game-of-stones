<?php
	/* establish a connection with the database */
	include("admin/connect.php");
	include("admin/userdata.php");
	include("admin/itemarray.php");
	$itmlist=unserialize($char['itmlist']);
	$num_items=count($itmlist);
	
	$javascripts=<<<SJAVA
	<SCRIPT LANGUAGE="JavaScript">
	
	function updateItem(name) {
		document.getElementById('itemImg').alt="items/"+(name[0]).replace(" ","").toLowerCase()+".gif";
		document.getElementById('itemImg').src="items/"+(name[0]).replace(" ","").toLowerCase()+".gif";
	}
	
	</SCRIPT>
SJAVA;

	include('header.htm');
?>
<font class="littletext">
<center>
<form action="bio.php?trade=1&name=<?php echo $_GET[name]."&last=".$_GET[lastname];?>" method="post">
	<?php echo $div_img; ?>
	<table border="0" cellspacing="10" cellpadding="3">
		<tr>
			<td>
				<font class="littletext">
				<a align="left">
				<b>Trade:</b><br><br>
 				<select name="item" id="itmList" size="1" class="form" onChange="updateItem(this.options[selectedIndex].value.split('|'));">
				<?php
					$have_item = 0;
					for ($x=0; $x<$num_items; $x++) {
						if (($itmlist[$x][3] == 0 || $itmlist[$x][3] == -2) && $item_base[$itmlist[$x][0]][1] != 8) {echo "<option value='".$itmlist[$x][0]."|".$itmlist[$x][1]."|".$itmlist[$x][2]."'>".iname($x,$itmlist)."</option>\n"; $have_item=1; if (!$f_item) $f_item=strtolower(str_replace(" ","",$itmlist[$x][0]));}
					}
					if (!$have_item) echo "<option value=''>No items</option>";
				?>
				</select>
			</td>
			<td width="110" height="110">
			<?php
				if ($have_item) {
			?>
				<img src="<?php echo "items/".$f_item.".gif"; ?>" id="itemImg" alt="">
			<?php
				}
				else echo "<font class='foottext'><center>You have no items to trade";
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p align="right">
				<font class="littletext">
				in return for
				<input type="text" name="gold" size="5" maxlength="6" value="1" class="form" style="text-align:right;"> gold
				<br><br>
				expires in 
				<select name="time" size="1" class="form">
					<option value="43200">Five days</option>
					<option selected value="259200">Three days</option>
					<option value="86400">One day</option>
					<option value="21600">Six hours</option>
					<option value="3600">One hour</option>
				</select>
				<br><br>
				<center>
				<input type="Submit" name="submit" value="Offer Trade" class="form">
			</td>
		</tr>
	</table>
	<?php echo $div_img; ?>
</form>



<?php
	include('footer.htm');
?>
