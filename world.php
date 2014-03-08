<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
include('map/mapdata/coordinates.inc');
$loc=$char['location'];

// JAVASCRIPT
$javascripts=<<<SJAVA
<SCRIPT LANGUAGE="JavaScript">

	window.onLoad = startMoverAndScroll();

	function startMoverAndScroll() {
		window.self.setInterval("Mover()", 200);
		window.self.setInterval("ScrollMap()",51);
	}

	var disX = 0;
	var disY = 0;
	var ScrollX=0;
	var ScrollY=0;
	var totDist = 0;
	var startX = 0;
	var startY = 0;
	var amtMoved = 100;
	var amtPerSec = 5;
	var MapX = 0;
	var MapY = 0;
	var UpdateMap = 0;
	var pageon = "";
	var dontGo = 0;
	var sellRes = 0;
	setTimeout('refreshMap();',1000);
	
	// PRELOAD IMAGES
	preload_image_object = new Image();
		image_url = new Array();
		image_url[0] = 'images/u_scroll_s.gif';
		image_url[1] = 'images/d_scroll_s.gif';
		image_url[2] = 'images/l_scroll_s.gif';
		image_url[3] = 'images/r_scroll_s.gif';
    
	function refreshMap() {
    		UpdateMap=1;
	}
    
	function createCookie(name,value) {
		var date = new Date();
		date.setTime(date.getTime()+(20*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = name+"="+value+expires+"; path=/";
	}

	function Mover() {
		if (amtMoved<totDist)
		{
			amtMoved+=amtPerSec;
			if (amtMoved>totDist) amtMoved=totDist;
			var theCell = document.getElementById("MapMark");
			theCell.width=startX-(disX*amtMoved/totDist);
			theCell.height=startY-(disY*amtMoved/totDist);
		}
	}

	function SetMapPos(x,y,slide) {
		x-=5;
		y-=5;
		if (x<0) x=0;
		if (y<0) y=0;
		if (x>240) x=240;
		if (y>165) y=165;
		var theCell = document.getElementById("MapMark");
		if (!slide) {
			theCell.width=x;
			theCell.height=y;
		}
		else {
			startX=theCell.width;
			startY=theCell.height;
			amtMoved=0;
			disX=startX-x;
			disY=startY-y;
			amtPerSec=slide;
			totDist = (5*Math.sqrt(disX*disX+disY*disY));
		}
	}
	
	function ShowText(sometext) {
		if (sometext!=-1) document.getElementById('infoSet').innerHTML="<font class='medtext'>"+sometext.replace('_',' ');
	}
	
	function marketChange(mar) {
		if (mar) document.getElementById('mar_img').style.display='block';
		else document.getElementById('mar_img').style.display='none';
	}
	
	function findPosID(obj) {
		obj = document.getElementById(obj);
		var curleft = curtop = 0;
		if (obj.offsetParent) {
			curleft = obj.offsetLeft
			curtop = obj.offsetTop
			while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft
				curtop += obj.offsetTop
			}
		}
		return [curleft,curtop];
	}
	
	function setTraveling(loc,mode) {
		var locto='';
		var locnow=window.frames.InfoPage.Here;
		if (loc) {
			hideMe();
			createCookie("mapCo",0);
			if (loc==1) locto=window.frames.TownMap.toNorth;
			if (loc==2) locto=window.frames.TownMap.toSouth;
			if (loc==3) locto=window.frames.TownMap.toEast;
			if (loc==4) locto=window.frames.TownMap.toWest;
			if (loc==5) locto=window.frames.TownMap.toDock1;
			if (loc==6) locto=window.frames.TownMap.toDock2;
			if (loc==7) locto=window.frames.TownMap.toDock3;
			window.frames['TownMap'].scrollTo(0,0);
			window.frames['InfoPage'].document.open();
			window.frames['InfoPage'].document.write("<html><body bgcolor='black'></body></html>");
			window.frames['InfoPage'].document.close();
			window.frames['TownMap'].document.open();
			window.frames['TownMap'].document.write("<html><body bgcolor='black'><font color='#C6CCD8'><center><br><br><br>Preparing to travel . . .</body></html>");
			window.frames['TownMap'].document.close();
			document.getElementById('TownMap').src='map/traveling.php?dock='+mode+'&goto='+locto.replace(' ','_').replace(' ','_').replace(' ','_')+"&l="+locnow.replace(' ','_').replace(' ','_').replace(' ','_')+"&dir="+loc;
		}
	}

	function SetPlacePage(page) {
		if (page=='Trading_Post' || page=='Tavern') {
			if (page=='Trading_Post') location.href='$server_name/resources.php?time=$time';
			if (page=='Tavern') location.href='$server_name/dice.php?time=$time';
		}
		else { 
			if (!page && pageon != "loc") page="loc";
			if (page && !dontGo) {
				pageon=page;
				daughter=window.frames['TownMap'];
				window.frames['InfoPage'].document.open();
				if (pageon != "loc" && pageon != "set_travel") window.frames['InfoPage'].document.write("<html><body bgcolor='black'><font color='#C6CCD8'>Traveling to the "+page.replace('_',' ').replace(' ','_').replace(' ','_')+" . . .</body></html>");
				else window.frames['InfoPage'].document.write("<html><body bgcolor='black'><font color='#C6CCD8'>Loading info . . .</body></html>");
				window.frames['InfoPage'].document.close();
				document.getElementById('InfoPage').src='$server_name/map/places/'+page.toLowerCase()+'.php?l='+daughter.toHere.replace('_',' ').replace(' ','_').replace(' ','_')+'&n='+daughter.toNorth.replace('_',' ').replace(' ','_').replace(' ','_')+'&s='+daughter.toSouth.replace('_',' ').replace(' ','_').replace(' ','_')+'&e='+daughter.toEast.replace('_',' ').replace(' ','_').replace(' ','_')+'&w='+daughter.toWest.replace('_',' ').replace(' ','_').replace(' ','_')+'&d1='+daughter.toDock1.replace('_',' ').replace(' ','_').replace(' ','_')+'&d2='+daughter.toDock2.replace('_',' ').replace(' ','_').replace(' ','_')+'&d3='+daughter.toDock3.replace('_',' ').replace(' ','_').replace(' ','_');
			}
			if (dontGo && page!="loc") showMe(dontGo,1);
		}
	}
	
	function ScrollMapSet(x,y) {
		if ((!x && !y) || x) ScrollX=x;
		if ((!x && !y) || y) ScrollY=y;
	}
	
	function ScrollMap() {
		if (typeof MapX != "undefined" && typeof MapY != "undefined") {
			var PrevMapX=MapX;
			var PrevMapY=MapY;
			MapX+=ScrollX;
			MapY+=ScrollY;

			if (MapX < 0) {MapX=0; document.getElementById('l_scroll').src='images/l_scroll.gif';}
			if (MapY < 0) {MapY=0; document.getElementById('u_scroll').src='images/u_scroll.gif';}
			if (MapX > window.frames['TownMap'].MapWidth*75-450) {MapX=window.frames['TownMap'].MapWidth*75-450;  document.getElementById('r_scroll').src='images/r_scroll.gif';}
			if (MapY > window.frames['TownMap'].MapHeight*50-300) {MapY=window.frames['TownMap'].MapHeight*50-300;  document.getElementById('d_scroll').src='images/d_scroll.gif';}
			if (MapX!=PrevMapX || MapY!=PrevMapY || UpdateMap) {window.frames['TownMap'].scrollTo(MapX,MapY); updateInfoPos();}
			UpdateMap=0;
		}
	}
</SCRIPT>
SJAVA;

// HEADER
if (!$message)
{
	if ($char['arrival']<=time()) $message=str_replace('-ap-','&#39;',$char['location']);
	else $message="Traveling to ".str_replace('-ap-','&#39;',$char['location']);
}
include('header.htm');
?>
<font class="littletext"><center>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="330" onMouseover="hideMe2();">
	<tr>
		<!-- TOWN MAP -->
		<td rowspan="2" width="500" valign="top">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
					</td>
					<td height="25" valign="bottom">
						<center><a onClick="ScrollMapSet(0,-13);"><img src="images/u_scroll.gif" id="u_scroll" border="0" onMouseOver="if (MapY!=0) this.src='images/u_scroll_s.gif';" onMouseOut="if (ScrollY>=0) this.src='images/u_scroll.gif';" border="0" alt="^"></a>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td width="25">
						<a onClick="ScrollMapSet(-13,0);"><img src="images/l_scroll.gif" id="l_scroll" onMouseOver="if (MapX!=0) this.src='images/l_scroll_s.gif';" onMouseOut="if (ScrollX>=0) this.src='images/l_scroll.gif';" border="0" alt="<"></a>
					</td>
					<td width="450" height="300">
						<center><iframe src="<?php if ($char['arrival']<=time()) echo "map/townmap.php?time=".time(); else echo "map/traveling.php?time=".time(); ?>" id="TownMap" name="TownMap" width="450" height="300" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" style="border:solid #000000 1px">Your browser doesn't support iframes.<br><b>Get <a href="http://www.mozilla.com/firefox/">Firefox</a>. It does.</b></iframe>
					</td>
					<td width="25">
						<a onClick="ScrollMapSet(13,0);"><img src="images/r_scroll.gif" id="r_scroll" border="0" onMouseOver="if (MapX!=window.frames.TownMap.MapWidth*75-450) this.src='images/r_scroll_s.gif';" onMouseOut="if (ScrollX<=0) this.src='images/r_scroll.gif';" alt=">"></a>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td height="25" valign="top">
						<center><a onClick="ScrollMapSet(0,13);"><img src="images/d_scroll.gif" id="d_scroll" border="0" onMouseOver="if (MapY!=window.frames.TownMap.MapHeight*50-300) this.src='images/d_scroll_s.gif';" onMouseOut="if (ScrollY<=0) this.src='images/d_scroll.gif';" alt="\/"></a>
					</td>
					<td>
					</td>
				</tr>
			</table>
			
			<!-- MAP INFO -->
			<p align="right"><table border="0" cellpadding="0" cellspacing="0"><tr><td id="infoSet" height="30" align='right'>&nbsp;</td></tr></table>
		</td>
		<td height="150" valign="bottom">
		
			<!-- BUILDING PAGE -->
			<center><iframe src="map/places/blank.php" id="InfoPage" name="InfoPage" width="250" height="150" bgcolor="black" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" style="border:solid #000000 0px">Your browser doesn't support iframes.</iframe>
		</td>
	</tr>
	<tr>
		<td height="180" valign="top">
		
			<!-- MINI MAP -->
			<?php
				if ($char['arrival']>time())
				{
					$loc=$char['travelto'];
					$locfrom=$char['location'];
					$per=(time()-$char['depart'])/($char['arrival']-$char['depart']);
					$plusX=intval($per*($location_array[$loc][0]-$location_array[$locfrom][0]));
					$plusY=intval($per*($location_array[$loc][1]-$location_array[$locfrom][1]));
					$loc=$char['location'];
				}
				else {$plusX=0; $plusY=0;}
			?>
			<a href="javascript:SetPlacePage('');">
			<table id="MarkerTable" background="map/minimap.gif" style="background-repeat: no-repeat;" bgcolor="black" border="0" cellpadding="0" cellspacing="0" width="250" height="175">
				<tr>
					<td id="MapMark" width="<?php echo ($location_array[$loc][0]+$plusX-5); ?>" height="<?php echo ($location_array[$loc][1]+$plusY-5); ?>">
						&nbsp;
					</td>
					<td>
						&nbsp;
					</td>
					<td rowspan="3">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td rowspan="2">
						&nbsp;
					</td>
					<td valign="top" palign="left" width="12" height="12">
						<img src="map/marker.gif" alt="X" border="0">
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
				</tr>
			</table>
			</a>
			<!-- END MINI MAP --> 
		</td>
	</tr>
</table>

<noscript>
Your browser will not currently run Javascript, which is required for this site.<br>I would strongly advise you turn it on and download the <a href="http://www.getfirefox.com">Firefox</a> web browser.
</noscript>

<?php
include('footer.htm');
?>
