<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
  $currentloc="./";
  include "./includes/session.php";
  include "./includes/config.php";
  include "./includes/applicationheader.php";
?>
<script>
 function canceltblpro(){
	        window.close();
	}
function InitColorPalette() {
  var x;
  if (document.getElementsByTagName)
    x = document.getElementsByTagName('TD');
  else if (document.all)
    x = document.all.tags('TD');
  for (var i=0;i<x.length;i++)
  {
    x[i].onmouseover = over;
    x[i].onmouseout = out;
    x[i].onclick = click;
  }
}

function over()
{
this.style.border='1px dotted white';
}

function out()
{
	this.style.border='1px solid gray';
}

function click()
{
 opener.setclrvalue(this.id);
 window.close();
}
</script>
<body onload="InitColorPalette()" bgcolor="white">
<table border="1" cellpadding="1" cellspacing="1" STYLE="cursor:hand;">
<tbody><tr>
<td id="#FFFFFF" bgcolor="#ffffff" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFCCCC" bgcolor="#ffcccc" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFCC99" bgcolor="#ffcc99" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFFF99" bgcolor="#ffff99" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFFFCC" bgcolor="#ffffcc" height="20" width="20"><img height="1" width="1"></td>
<td id="#99FF99" bgcolor="#99ff99" height="20" width="20"><img height="1" width="1"></td>
<td id="#99FFFF" bgcolor="#99ffff" height="20" width="20"><img height="1" width="1"></td>
<td id="#CCFFFF" bgcolor="#ccffff" height="20" width="20"><img height="1" width="1"></td>
<td id="#CCCCFF" bgcolor="#ccccff" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFCCFF" bgcolor="#ffccff" height="20" width="20"><img height="1" width="1"></td>
</tr>
<tr>
<td id="#CCCCCC" bgcolor="#cccccc" height="20" width="20"><img height="1" width="1"></td>
<td id="#FF6666" bgcolor="#ff6666" height="20" width="20"><img height="1" width="1"></td>
<td id="#FF9966" bgcolor="#ff9966" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFFF66" bgcolor="#ffff66" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFFF33" bgcolor="#ffff33" height="20" width="20"><img height="1" width="1"></td>
<td id="#66FF99" bgcolor="#66ff99" height="20" width="20"><img height="1" width="1"></td>
<td id="#33FFFF" bgcolor="#33ffff" height="20" width="20"><img height="1" width="1"></td>
<td id="#66FFFF" bgcolor="#66ffff" height="20" width="20"><img height="1" width="1"></td>
<td id="#9999FF" bgcolor="#9999ff" height="20" width="20"><img height="1" width="1"></td>
<td id="#FF99FF" bgcolor="#ff99ff" height="20" width="20"><img height="1" width="1"></td>
</tr>
<tr>
<td id="#C0C0C0" bgcolor="#c0c0c0" height="20" width="20"><img height="1" width="1"></td>
<td id="#FF0000" bgcolor="#ff0000" height="20" width="20"><img height="1" width="1"></td>
<td id="#FF9900" bgcolor="#ff9900" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFCC66" bgcolor="#ffcc66" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFFF00" bgcolor="#ffff00" height="20" width="20"><img height="1" width="1"></td>
<td id="#33FF33" bgcolor="#33ff33" height="20" width="20"><img height="1" width="1"></td>
<td id="#66CCCC" bgcolor="#66cccc" height="20" width="20"><img height="1" width="1"></td>
<td id="#33CCFF" bgcolor="#33ccff" height="20" width="20"><img height="1" width="1"></td>
<td id="#6666CC" bgcolor="#6666cc" height="20" width="20"><img height="1" width="1"></td>
<td id="#CC66CC" bgcolor="#cc66cc" height="20" width="20"><img height="1" width="1"></td>
</tr>
<tr>
<td id="#999999" bgcolor="#999999" height="20" width="20"><img height="1" width="1"></td>
<td id="#CC0000" bgcolor="#cc0000" height="20" width="20"><img height="1" width="1"></td>
<td id="#FF6600" bgcolor="#ff6600" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFCC33" bgcolor="#ffcc33" height="20" width="20"><img height="1" width="1"></td>
<td id="#FFCC00" bgcolor="#ffcc00" height="20" width="20"><img height="1" width="1"></td>
<td id="#33CC00" bgcolor="#33cc00" height="20" width="20"><img height="1" width="1"></td>
<td id="#00CCCC" bgcolor="#00cccc" height="20" width="20"><img height="1" width="1"></td>
<td id="#3366FF" bgcolor="#3366ff" height="20" width="20"><img height="1" width="1"></td>
<td id="#6633FF" bgcolor="#6633ff" height="20" width="20"><img height="1" width="1"></td>
<td id="#CC33CC" bgcolor="#cc33cc" height="20" width="20"><img height="1" width="1"></td>
</tr>
<tr>
<td id="#666666" bgcolor="#666666" height="20" width="20"><img height="1" width="1"></td>
<td id="#990000" bgcolor="#990000" height="20" width="20"><img height="1" width="1"></td>
<td id="#CC6600" bgcolor="#cc6600" height="20" width="20"><img height="1" width="1"></td>
<td id="#CC9933" bgcolor="#cc9933" height="20" width="20"><img height="1" width="1"></td>
<td id="#999900" bgcolor="#999900" height="20" width="20"><img height="1" width="1"></td>
<td id="#009900" bgcolor="#009900" height="20" width="20"><img height="1" width="1"></td>
<td id="#339999" bgcolor="#339999" height="20" width="20"><img height="1" width="1"></td>
<td id="#3333FF" bgcolor="#3333ff" height="20" width="20"><img height="1" width="1"></td>
<td id="#6600CC" bgcolor="#6600cc" height="20" width="20"><img height="1" width="1"></td>
<td id="#993399" bgcolor="#993399" height="20" width="20"><img height="1" width="1"></td>
</tr>
<tr>
<td id="#333333" bgcolor="#333333" height="20" width="20"><img height="1" width="1"></td>
<td id="#660000" bgcolor="#660000" height="20" width="20"><img height="1" width="1"></td>
<td id="#993300" bgcolor="#993300" height="20" width="20"><img height="1" width="1"></td>
<td id="#996633" bgcolor="#996633" height="20" width="20"><img height="1" width="1"></td>
<td id="#666600" bgcolor="#666600" height="20" width="20"><img height="1" width="1"></td>
<td id="#006600" bgcolor="#006600" height="20" width="20"><img height="1" width="1"></td>
<td id="#336666" bgcolor="#336666" height="20" width="20"><img height="1" width="1"></td>
<td id="#000099" bgcolor="#000099" height="20" width="20"><img height="1" width="1"></td>
<td id="#333399" bgcolor="#333399" height="20" width="20"><img height="1" width="1"></td>
<td id="#663366" bgcolor="#663366" height="20" width="20"><img height="1" width="1"></td>
</tr>
<tr>
<td id="#000000" bgcolor="#000000" height="20" width="20"><img height="1" width="1"></td>
<td id="#330000" bgcolor="#330000" height="20" width="20"><img height="1" width="1"></td>
<td id="#663300" bgcolor="#663300" height="20" width="20"><img height="1" width="1"></td>
<td id="#663333" bgcolor="#663333" height="20" width="20"><img height="1" width="1"></td>
<td id="#333300" bgcolor="#333300" height="20" width="20"><img height="1" width="1"></td>
<td id="#003300" bgcolor="#003300" height="20" width="20"><img height="1" width="1"></td>
<td id="#003333" bgcolor="#003333" height="20" width="20"><img height="1" width="1"></td>
<td id="#000066" bgcolor="#000066" height="20" width="20"><img height="1" width="1"></td>
<td id="#330099" bgcolor="#330099" height="20" width="20"><img height="1" width="1"></td>
<td id="#330033" bgcolor="#330033" height="20" width="20"><img height="1" width="1"></td>
</tr>
<tr>
  <td colspan=12 align=center><input class=button type=button value="Cancel" onclick="canceltblpro();"></td>
</tr>
</tbody></table>
<?php
  include "./includes/colorselectionfooter.php";
?>  