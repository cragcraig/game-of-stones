<?php
include("admin/tree_skills.php");

$skills=unserialize($char['skill_tree']);

if ($upskill = $_GET[up])
{
if ($skills[$skill_list[$upskill][1]] >= 1 || !$skill_list[$upskill][1]) {$skills[$upskill]++; $message = "$upskill level increased";} else $message = "You may not increase $upskill at this time";
$skills_updated=serialize($skills);
if ($skill_list[$upskill][2] != 2) $char[good]++;
if ($skill_list[$upskill][2] != 1) $char[evil]++;
$good = $char[good];
$evil = $char[evil];
mysql_query("UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET User_data.skill_tree='$skills_updated', Users.good='$good', Users.evil='$evil' WHERE Users.id=$id");
}

$color = array("000000","171717","AAAAAA");
$cell_size = 75;
?>
<center>
<!-- TABS -->
<table border='0' cellpadding='0' cellspacing='0' width='90%'>
	<tr>
		<td>
			<center>
			<table border='0' cellpadding='0' cellspacing='0' width='350'>
				<tr>
					<!-- TOP -->
					<td width='30' valign='bottom'>
						<table height='20' width='30' cellspacing='0' cellpadding='0' style='border: 0px solid #333333; border-bottom-width: 1px;'><tr><td></td></tr></table>
					</td>
					<td valign='bottom'>
						<!-- ONE -->
						<a href='javascript:clickTab(1);'>
						<table height='20' id='tab1' bgcolor='#0F0F0F' cellspacing='0' cellpadding='6' style='border: 1px solid #333333; border-bottom-width: 0px;'><tr><td class='littletext'><b><i>Craftsman</i></b></td></tr></table>
						</a>
					</td>
					<td width='10' valign='bottom'>
						<table height='20' width='10' cellspacing='0' cellpadding='0' style='border: 0px solid #333333; border-bottom-width: 1px;'><tr><td></td></tr></table>
					</td>
					<td valign='bottom'>
						<!-- TWO -->
						<a href='javascript:clickTab(2);'>
						<table height='20' id='tab2' bgcolor='#090909' cellspacing='0' cellpadding='6' style='border: 1px solid #333333; border-bottom-width: 1px;'><tr><td class='littletext'><b><i>Worker</i></b></td></tr></table>
						</a>
					</td>
					<td width='10' valign='bottom'>
						<table height='20' width='10' cellspacing='0' cellpadding='0' style='border: 0px solid #333333; border-bottom-width: 1px;'><tr><td></td></tr></table>
					</td>
					<td valign='bottom'>
						<!-- THREE -->
						<a href='javascript:clickTab(3);'>
						<table height='20' id='tab3' bgcolor='#090909' cellspacing='0' cellpadding='6' style='border: 1px solid #333333; border-bottom-width: 1px;'><tr><td class='littletext'><b><i>Merchant</i></b></td></tr></table>
						</a>
					</td>
					<td width='100%' valign='bottom'>
						<table height='20' width='100%' cellspacing='0' cellpadding='0' style='border: 0px solid #333333; border-bottom-width: 1px;'><tr><td></td></tr></table>
					</td>
				</tr>
				<tr>
					<td valign='top' colspan='8'>
						<table height='300' width='100%' style='border: 1px solid #333333; border-top-width: 0px;' bgcolor='#0F0F0F' cellpadding='5' cellspacing='0'>
							<tr>
								<td valign='middle'>
									<center>
									<!-- CONTENT -->
										<?php
										for ($z=0; $z<3; $z++)
										{
											// HTML STUFF FOR TABS
											echo "<div style='display: ";
											if (!$z) echo "block"; else echo "none";
											echo ";' id='tc".($z+1)."' class='littletext'>";
											
											// SKILL TREE
											?>
											<table border=0 cellspacing=0 cellpadding=0>
												<?php
												$xlength = count($skilltree[$z][0]);
												$ylength = count($skilltree[$z]);
												
												// DRAW SKILL TREE
												
												for ($y = 0; $y < $ylength; $y++)
												{
													echo "<tr height=15><td width=15></td>";
												
													// DRAW VERTICAL DIVIDER CELLS
													for ($x = 0; $x < $xlength; $x++)
													{
														if ($skilltree[$z][$y-1][$x] && $skilltree[$z][$y][$x]) $inside = "background='images/h_cell.gif'"; else $inside = "";
														echo "<td width=$cell_size $inside></td><td width=15></td>\n";
													}
													echo "</tr><tr height=$cell_size><td width=15></td>\n";
												
													// DRAW SKILL CELLS
													for ($x = 0; $x < $xlength; $x++)
													{
														$skill_level = "0";
														if ($skilltree[$z][$y][$x]) {if ($skills[$skilltree[$z][$y][$x]] != '') $skill_level = $skills[$skilltree[$z][$y][$x]]; $inside = "<center><a href='stats.php?show=1&up=".$skilltree[$z][$y][$x]."' OnMouseover=\"setInfo('<b>".$skilltree[$z][$y][$x]."</b>:<font class=foottext> ".$skill_list[$skilltree[$z][$y][$x]][0]."');\" onMouseout=\"showTop('');\"><font color='555555'><table border=0 cellspacing=0 cellpadding=0 width=65 height=40><tr><td><center><font class='foottext'><font color='".$color[$skill_list[$skilltree[$z][$y][$x]][2]]."'><b>".$skilltree[$z][$y][$x]."<br>[$skill_level]</b></td></tr></table></a>"; $background = "background='images/skillcell".$skill_list[$skilltree[$z][$y][$x]][2].".gif'";} else {$inside = ""; $background = "";}
														echo "<td width=$cell_size $background valign='center'>$inside</td>\n";
												
														// DRAW HORIZONTAL DIVIDER CELLS
														if ($skilltree[$z][$y][$x+1] && $skilltree[$z][$y][$x] && $skill_list[$skilltree[$z][$y][$x+1]][2] == $skill_list[$skilltree[$z][$y][$x]][2] && !($skilltree[$z][$y-1][$x] && $skilltree[$z][$y-1][$x+1]) ) $inside = "background='images/v_cell.gif'"; else $inside = "";
														echo "<td width=15 $inside></td>\n";
													}
													echo "</td>";
												}
												
												?>
												</table>
											<?php
											// END TAB
											echo "</div>";
										}
										?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


