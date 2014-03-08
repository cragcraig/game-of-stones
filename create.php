<?php
if (!$_GET['message']) $title = "Create a Character";
else $title = $_GET['message'];
include('headerno.htm');
?>

<center>
<form method="post" action="adduser.php">
<table border="0" cellspacing="25">
<tr>
<td><label for="userid"><font class="littletext"><b>Character Name:<br></b></label></td>
<td><input type="text" name="userid" id="userid" class="form" maxlength="10" /></td>
</tr>
<tr>
<td><label for="last"><font class="littletext"><b>House Name:<br></b></label></td>
<td><input type="text" name="last" id="last" class="form" maxlength="10" /></td>
</tr>
<tr> 
<td><label for="password"><font class="littletext"><b>Password:<br></b></label></td>
<td> <input type="password" name="password" id="password" class="form" maxlength="10" /></td>
</tr>
<tr> 
<td><label for="pass2"><font class="littletext"><b>Re-enter Password:<br></b></label></td>
<td> <input type="password" name="pass2" id="pass2" class="form" maxlength="10" /></td>
</tr>
<tr>
<td><label for="email"><font class="littletext"><b>E-Mail:<br></b></label></td>
<td><input type="text" name="email" id="email" maxlength="40" class="form" /></td>
</tr>
<!--         INPUT CHARACTER SEX -->
<tr>
<td><label for="sex"><font class="littletext"><b>Gender:</b></label><br></b></td>
<td><input type="radio" name="sex" id="sex" VALUE="0" CHECKED/><font class="littletext">Male<br>
<input type="radio" name="sex" id="sex" VALUE="1" /><font class="littletext">Female</td>
</tr>
</table>
<!-- -->
<br>
<center>
<table class='blank'><tr><td>
<input type="checkbox" name="noalt">&nbsp;
</td><td class='littletext'>
I will act considerately towards all players.<br><font class='littletext_f'>(no foul language, cheating, stealing clans/characters, etc)
</td>
</tr><tr>
<td></td><td><br></td>
</tr><tr>
<td>
<input type="checkbox" name="channeler">&nbsp;
</td><td class='littletext'>
Make this a Channeler. Donators only.<br><font class='littletext_f'>(Use the same email as you gave Paypal.)
</td></tr></table>
<br>
<!-- -->
<br>
<input type="Submit" name="submit" value="Create New Character" class="form">
&nbsp;&nbsp; <b>or</b> &nbsp;&nbsp;
<input type="Submit" name="transfer" value="Transfer Old Character" class="form">
</form>
</p>
</center>

<br><br><br><br><br><br>

<?php
include('footer.htm');
?>

