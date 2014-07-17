<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
require_once dirname(__FILE__) . '/class.cropcanvas.php';
class cropInterface extends canvasCrop {
				var $file;
				var $img;
				var $crop;
				var $useFilter;

				/**
				 * 
				 * @return cropInterface 
				 * @param bool $debug 
				 * @desc Class initializer
				 */
				function cropInterface($debug = false)
				{
								parent::canvasCrop($debug);

								$this->img = array();
								$this->crop = array();
								$this->useFilter = false;

								$agent = trim($_SERVER['HTTP_USER_AGENT']);
								if ((stristr($agent, 'wind') || stristr($agent, 'winnt')) && (preg_match('|MSIE ([0-9.]+)|', $agent) || preg_match('|Internet Explorer/([0-9.]+)|', $agent))) {
												$this->useFilter = true;
								} else {
												$this->useFilter = false;
								} 
								$this->setResizing();
								$this->setCropMinSize();
				} 

				/**
				 * 
				 * @return void 
				 * @param unknown $do 
				 * @desc Sets whether you want resizing options for the cropping area.
				 * This is handy to use in conjunction with the setCropSize function if you want a set cropping size.
				 */
				function setResizing($do = true)
				{
								$this->crop['resize'] = ($do) ? true : false;
				} 

				/**
				 * 
				 * @return void 
				 * @param int $w 
				 * @param int $h 
				 * @desc Sets the initial size of the cropping area.
				 * If this is not specifically set, then the cropping size will be a fifth of the image size.
				 */
				function setCropDefaultSize($w, $h)
				{
								if ($w > 600)
												$w = 600;
								if ($_GET['act'] == "tx") {
												$this->crop['width'] = 40;
												$this->crop['height'] = 2;
								} else {
												$this->crop['width'] = ($w < 5) ? 5 : $w;
												$this->crop['height'] = ($h < 5) ? 5 : $h;
								} 
				} 

				/**
				 * 
				 * @return void 
				 * @param int $w 
				 * @param int $h 
				 * @desc Sets the minimum size the crop area can be
				 */
				function setCropMinSize($w = 25, $h = 25)
				{
								$this->crop['min-width'] = ($w < 5) ? 5 : $w;
								$this->crop['min-height'] = ($h < 5) ? 5 : $h;
				} 

				/**
				 * 
				 * @return void 
				 * @param string $filename 
				 * @desc Load the cropping interface
				 */
				function loadInterfaceCrop($filename, $imagenametodisplay)
				{
								if (!file_exists($filename)) {
												die("The file '$filename' cannot be found.");
								} else {
												$this->file = $filename;
												$this->img['sizes'] = @getimagesize($filename);
												/* check image width or height >450.if any of them is greater reset it to 450 for better perormence*/
												if ($this->img['sizes'][0] > 450 and $this->img['sizes'][1] > 450) {
																$this->img['sizes'][0] = 450;
																$this->img['sizes'][1] = 450;
																$imgw = 450;
																$imgh = 450;
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else if ($this->img['sizes'][0] > 450) {
																$this->img['sizes'][0] = 450;
																$imgw = 450;

																$imgh = $this->img['sizes'][1];
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else if ($this->img['sizes'][1] > 450) {
																$imgw = $this->img['sizes'][0];
																$imgh = 450;
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else {;
												} 
												// set the crop layer default width
												if (!$this->crop['width'] || !$this->crop['height']) {
																$this->setCropDefaultSize(($this->img['sizes'][0] / 5), ($this->img['sizes'][1] / 5));
												} 
												if ($this->crop['width'] > 450) {
																$this->crop['width'] = 450;
												} 
								} 

								/*find the border color of the crop layer 
									* by checking the color of the image(color at 0,0) to be edit
								*/

								$tmpfile = NewimageCreatefromtype($this->file);
								$rgb = imagecolorat($tmpfile, 0, 0);
								$r = ($rgb >> 16) &0xFF;
								$g = ($rgb >> 8) &0xFF;
								$b = $rgb &0xFF;

								/* if color is balck layercolor will be white
								   *  otherwsie check for red color code in color and if it is <150 layercolor will be white otherwise black
								   */
								if ($r == 0 and $g == 0 and $b == 0) {
												$layercolor = "white";
								} else if ($r == 0) {
												$layercolor = "black";
								} else if ($r <= 150) {
												$layercolor = "white";
								} else if ($r > 150) {
												$layercolor = "black";
								} 

								/* wz_dragdrop.js will be included.
								*  using this  js file we can be move croparea,we will get cordinates of the crop area
								*/
								echo '<script type="text/javascript" src="./includes/wz_dragdrop.js"></script>', "\n";
								echo '<div id="theCrop" style="position:absolute;background-color:transparent;border:4px solid ', $layercolor, ';width:', $this->crop['width'], 'px;height:', $this->crop['height'], 'px;';
								echo "\"></div>\n";
								echo "<table width=\"10%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=outertablebgcolor>";
								echo "<tr>
                                              <td width=\"76%\" align=\"center\">&nbsp;</td>
                                              <td width=\"24%\" align=\"center\">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td align=\"center\"><table width=\"15%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                                <tr>
                                                  <td width=\"19%\"><img src=\"images/cr1.jpg\" width=\"23\" height=\"24\"></td>
                                                  <td width=\"66%\" background=\"images/cr5.jpg\">&nbsp;</td>
                                                  <td width=\"15%\" align=\"right\"><img src=\"images/cr2.jpg\" width=\"23\" height=\"24\"></td>
                                                </tr>
                                                <tr>
                                                  <td height=\"65\" background=\"images/cr6.jpg\">&nbsp;</td>
                                                  <td align=\"center\"><table width=\"82%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
								echo '<tr><td><img id=cropimage src="', $this->file, '" ', $this->img['sizes'][3], ' alt="crop this image" name="theImage"></td></tr>', "\n";
								echo " </table></td>
                                                  <td align=\"right\" background=\"images/cr9.jpg\">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td height=\"19\" align=\"left\" valign=\"bottom\"><img src=\"images/cr4.jpg\" width=\"23\" height=\"24\"></td>
                                                  <td background=\"images/cr7.jpg\">&nbsp;</td>
                                                  <td align=\"right\" valign=\"top\"><img src=\"images/cr3.jpg\" width=\"23\" height=\"24\"></td>
                                                </tr>
												
                                              </table>"; 
								// echo '<tr><td><input type="button" value="crop the image" class=button id="submit" onClick="my_Submit();"></td></tr>';
								echo "\n</table>\n";
				} 
				function loadInterfaceText($filename, $imagenametodisplay)
				{
								$fontsize = "<select name=cmbfontsize id=cmbfontsize class=selectbox>";
								for($i = 10;$i < 50;$i++) {
												$fontsize .= "<option value=$i>$i</option>";
								} 
								$fontname = "<select name=cmbfontname id=cmbfontname class=selectbox>";
								$fontname .= "<option value='times.ttf'>Times</option>";
								$fontname .= "<option value='timesi.ttf'>Times Italic</option>";
								$fontname .= "<option value='timesbd.ttf'>Times Bold</option>";
								$fontname .= "<option value='timesbi.ttf'>Times Bold Italic</option>";
								$fontname .= "<option value='arial.ttf'>Arial</option>";
								$fontname .= "<option value='ariali.ttf'>Arial Italic</option>";
								$fontname .= "<option value='arialbd.ttf'>Arial Bold</option>";
								$fontname .= "<option value='arialbi.ttf'>Arial Bold Italic</option>";
								$fontname .= "</select>";
								if (!file_exists($filename)) {
												die("The file '$filename' cannot be found.");
								} else {
												$this->file = $filename;
												$this->img['sizes'] = @getimagesize($filename);

												/* check image width or height >450.if any of them is greater reset it to 450 for better perormence*/
												if ($this->img['sizes'][0] > 450 and $this->img['sizes'][1] > 450) {
																$this->img['sizes'][0] = 450;
																$this->img['sizes'][1] = 450;
																$imgw = 450;
																$imgh = 450;
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else if ($this->img['sizes'][0] > 450) {
																$this->img['sizes'][0] = 450;
																$imgw = 450;

																$imgh = $this->img['sizes'][1];
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else if ($this->img['sizes'][1] > 450) {
																$imgw = $this->img['sizes'][0];
																$imgh = 450;
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else {;
												} 

												$this->setCropDefaultSize(5, 5);

												if ($this->crop['width'] > 450) {
																$this->crop['width'] = 450;
												} 
								} 
                                /* find the default rows,columns for teatarea inside the text entry layer*/
								$defaultcols = (int)($this->img['sizes'][0] / 10);
								$defaultrows = (int)($this->img['sizes'][1] / 20);
								$txtboxrow = $defaultrows;
								$txtboxcols = $defaultcols;
								if ($txtboxrow > 3)
												$txtboxrow = 3;
								if ($txtboxcols > 3)
												$txtboxcols = 3;
								$tmpfile = NewimageCreatefromtype($this->file);
								$rgb = imagecolorat($tmpfile, 0, 0);
								$r = ($rgb >> 16) &0xFF;
								$g = ($rgb >> 8) &0xFF;
								$b = $rgb &0xFF;
                                 /* find the color of text entry layer*/
								if ($r == 0 and $g == 0 and $b == 0) {
												$layercolor = "white";
								} else if ($r == 0) {
												$layercolor = "black";
								} else if ($r <= 150) {
												$layercolor = "white";
								} else if ($r > 150) {
												$layercolor = "black";
								} 

								echo '<script type="text/javascript" src="./includes/wz_dragdrop.js"></script>', "\n";
								echo '<div id="theCrop" style="position:absolute;background-color:transparent;border:4px solid ', $layercolor, ' ;width:', $this->crop['width'], 'px;height:', $this->crop['height'], 'px;';
								echo "\"><form name=frmtxtvalnew method=post action=editgallery.php onsubmit=\"my_Submittext(this);\">";

								echo "<textarea name=textval id=textval cols=$txtboxcols rows=$txtboxrow></textarea></div>\n";
								echo "<table width=\"10%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
								echo "<tr>
                                              <td width=\"76%\" align=\"center\">&nbsp;</td>
                                              <td width=\"24%\" align=\"center\">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td align=\"center\"><table width=\"15%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                                <tr>
                                                  <td width=\"19%\"><img src=\"images/cr1.jpg\" width=\"23\" height=\"24\"></td>
                                                  <td width=\"66%\" background=\"images/cr5.jpg\">&nbsp;</td>
                                                  <td width=\"15%\" align=\"right\"><img src=\"images/cr2.jpg\" width=\"23\" height=\"24\"></td>
                                                </tr>
                                                <tr>
                                                  <td height=\"65\" background=\"images/cr6.jpg\">&nbsp;</td>
                                                  <td align=\"center\"><table width=\"82%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
								echo '<tr><td><img id=cropimage src="', $this->file, '" ', $this->img['sizes'][3], ' alt="Add Text " name="theImage"></td></tr>', "\n";
								echo " </table></td>
                                                  <td align=\"right\" background=\"images/cr9.jpg\">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td height=\"19\" align=\"left\" valign=\"bottom\"><img src=\"images/cr4.jpg\" width=\"23\" height=\"24\"></td>
                                                  <td background=\"images/cr7.jpg\">&nbsp;</td>
                                                  <td align=\"right\" valign=\"top\"><img src=\"images/cr3.jpg\" width=\"23\" height=\"24\"></td>
                                                </tr>
												
                                              </table>";
								echo "\n</table>\n";
				} 

				function loadInterfaceFilter($filename, $imagenametodisplay)
				{
								if (!file_exists($filename)) {
												die("The file '$filename' cannot be found.");
								} else {
												$this->file = $filename;
												$this->img['sizes'] = @getimagesize($filename);
												if ($this->img['sizes'][0] > 450 and $this->img['sizes'][1] > 450) {
																$this->img['sizes'][0] = 450;
																$this->img['sizes'][1] = 450;
																$imgw = 450;
																$imgh = 450;
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else if ($this->img['sizes'][0] > 450) {
																$this->img['sizes'][0] = 450;
																$imgw = 450;

																$imgh = $this->img['sizes'][1];
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else if ($this->img['sizes'][1] > 450) {
																$imgw = $this->img['sizes'][0];
																$imgh = 450;
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else {;
												} 

												$this->setCropDefaultSize(5, 5);
								} 

								echo '<script type="text/javascript" src="./includes/wz_dragdrop.js"></script>', "\n"; 
								// echo "<form name=frmrotate ><input  type=hidden name=action id=action value=cr>\n";
								echo "<table width=\"10%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
								echo "<tr>
                                              <td width=\"76%\" align=\"center\">&nbsp;</td>
                                              <td width=\"24%\" align=\"center\">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td align=\"center\"><table width=\"15%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                                <tr>
                                                  <td width=\"19%\"><img src=\"images/cr1.jpg\" width=\"23\" height=\"24\"></td>
                                                  <td width=\"66%\" background=\"images/cr5.jpg\">&nbsp;</td>
                                                  <td width=\"15%\" align=\"right\"><img src=\"images/cr2.jpg\" width=\"23\" height=\"24\"></td>
                                                </tr>
                                                <tr>
                                                  <td height=\"65\" background=\"images/cr6.jpg\">&nbsp;</td>
                                                  <td align=\"center\"><table width=\"82%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
								echo '<tr><td><img id=cropimage src="', $this->file, '" ', $this->img['sizes'][3], ' alt="Apply Filter" name="theImage"></td></tr>', "\n";
								echo " </table></td>
                                                  <td align=\"right\" background=\"images/cr9.jpg\">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td height=\"19\" align=\"left\" valign=\"bottom\"><img src=\"images/cr4.jpg\" width=\"23\" height=\"24\"></td>
                                                  <td background=\"images/cr7.jpg\">&nbsp;</td>
                                                  <td align=\"right\" valign=\"top\"><img src=\"images/cr3.jpg\" width=\"23\" height=\"24\"></td>
                                                </tr>
												
                                              </table>";
								echo "\n</table>\n";
				} 
				function loadInterfaceRotate($filename, $imagenametodisplay)
				{
								$rotatecmb = "<select id=cmbrotate class=selectbox>";
								for($i = 1;$i < 360;$i++) {
												$rotatecmb .= "<option value=$i>$i</option>";
								} 
								$rotatecmb .= "</select>";

								if (!file_exists($filename)) {
												die("The file '$filename' cannot be found.");
								} else {
												$this->file = $filename;
												$this->img['sizes'] = @getimagesize($filename);
												if ($this->img['sizes'][0] > 450 and $this->img['sizes'][1] > 450) {
																$this->img['sizes'][0] = 450;
																$this->img['sizes'][1] = 450;
																$imgw = 450;
																$imgh = 450;
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else if ($this->img['sizes'][0] > 450) {
																$this->img['sizes'][0] = 450;
																$imgw = 450;

																$imgh = $this->img['sizes'][1];
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else if ($this->img['sizes'][1] > 450) {
																$imgw = $this->img['sizes'][0];
																$imgh = 450;
																$this->img['sizes'][3] = "width=\"" . $imgw . "\" height=\"" . $imgh . "\"";
												} else {;
												} 

												$this->setCropDefaultSize(5, 5);
								} 
								$basefilename = basename($this->file);

								echo '<script type="text/javascript" src="./includes/wz_dragdrop.js"></script>', "\n";

								echo "\n";
								echo "<table width=\"10%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
								echo "<tr>
                                              <td width=\"76%\" align=\"center\">&nbsp;</td>
                                              <td width=\"24%\" align=\"center\">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td align=\"center\"><table width=\"15%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                                <tr>
                                                  <td width=\"19%\"><img src=\"images/cr1.jpg\" width=\"23\" height=\"24\"></td>
                                                  <td width=\"66%\" background=\"images/cr5.jpg\">&nbsp;</td>
                                                  <td width=\"15%\" align=\"right\"><img src=\"images/cr2.jpg\" width=\"23\" height=\"24\"></td>
                                                </tr>
                                                <tr>
                                                  <td height=\"65\" background=\"images/cr6.jpg\">&nbsp;</td>
                                                  <td align=\"center\"><table width=\"82%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">";
								echo '<tr><td><img id=cropimage src="', $this->file, '" ', $this->img['sizes'][3], '  name="theImage"></td></tr>', "\n";
								echo " </table></td>
                                                  <td align=\"right\" background=\"images/cr9.jpg\">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td height=\"19\" align=\"left\" valign=\"bottom\"><img src=\"images/cr4.jpg\" width=\"23\" height=\"24\"></td>
                                                  <td background=\"images/cr7.jpg\">&nbsp;</td>
                                                  <td align=\"right\" valign=\"top\"><img src=\"images/cr3.jpg\" width=\"23\" height=\"24\"></td>
                                                </tr>
												
                                              </table>";
								echo "\n</table>\n";
				} 

				/**
				 * 
				 * @return void 
				 * @desc Load the javascript required for a functional interface.
				 * This MUST be called at the very end of all your HTML, just before the closing body tag.
				 */
				function loadJavaScript()
				{
								$params = '"theCrop"+MAXOFFLEFT+0+MAXOFFRIGHT+' . $this->img['sizes'][0] . '+MAXOFFTOP+0+MAXOFFBOTTOM+' . $this->img['sizes'][1] . ($this->crop['resize'] ? '+RESIZABLE' : '') . '+MAXWIDTH+' . $this->img['sizes'][0] . '+MAXHEIGHT+' . $this->img['sizes'][1] . '+MINHEIGHT+' . $this->crop['min-height'] . '+MINWIDTH+' . $this->crop['min-width'] . ',"theImage"+NO_DRAG';
								echo <<< EOT
										<script type="text/javascript">
										<!--
										
											SET_DHTML($params);
											dd.elements.theCrop.moveTo(dd.elements.theImage.x, dd.elements.theImage.y);
											dd.elements.theCrop.setZ(dd.elements.theImage.z+1);
											dd.elements.theImage.addChild("theCrop");
											dd.elements.theCrop.defx = dd.elements.theImage.x;
											
										
											function my_DragFunc()
											{
												dd.elements.theCrop.maxoffr = dd.elements.theImage.w - dd.elements.theCrop.w;
												dd.elements.theCrop.maxoffb = dd.elements.theImage.h - dd.elements.theCrop.h;
												dd.elements.theCrop.maxw    = {$this->img['sizes'][0]};
												dd.elements.theCrop.maxh    = {$this->img['sizes'][1]};
											}
										
											function my_ResizeFunc()
											{
												dd.elements.theCrop.maxw = (dd.elements.theImage.w + dd.elements.theImage.x) - dd.elements.theCrop.x;
												dd.elements.theCrop.maxh = (dd.elements.theImage.h + dd.elements.theImage.y) - dd.elements.theCrop.y;
											}
											
											function my_Submit()
											{   
											       var txtvalue,k; 
												 
												   document.getElementById('hsx').value=dd.elements.theCrop.x - dd.elements.theImage.x;
												   document.getElementById('hsy').value=dd.elements.theCrop.y - dd.elements.theImage.y;
												   document.getElementById('hex').value=(dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w;
												   document.getElementById('hey').value=(dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h;
												   document.getElementById('hfile').value='{$this->file}';
												   document.getElementById('haction').value='crop';
												   
												   return true;
													
													
												   /* self.location.href = '{$_SERVER['PHP_SELF']}?&action=crop&file={$this->file}&sx=' + 
													(dd.elements.theCrop.x - dd.elements.theImage.x) + '&sy=' + 
													(dd.elements.theCrop.y - dd.elements.theImage.y) + '&ex=' +
													((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w) + '&ey=' +
													((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);*/
													
											}
											function my_Submittext(frm)
											{ 
											       var txtvalue,k; 
												   document.getElementById('htextval').value=document.getElementById('textval').value;
												   //alert(txtvalue);
												   document.getElementById('hfontcolor').value=document.getElementById('yourcol').value
												   document.getElementById('hsx').value=dd.elements.theCrop.x - dd.elements.theImage.x;
												   document.getElementById('hsy').value=dd.elements.theCrop.y - dd.elements.theImage.y;
												   document.getElementById('hfile').value='{$this->file}';
												   document.getElementById('haction').value='addtext';
												   return true;
												
													
											}
											function my_Submitfilterbrightness(frm)
											{   
											
											        var filtervalue;
											        brvalue=document.getElementById('brighnesscmb').value;
													filtervalue="BR";
												   document.getElementById('hsx').value=dd.elements.theCrop.x - dd.elements.theImage.x;
												   document.getElementById('hsy').value=dd.elements.theCrop.y - dd.elements.theImage.y;
												   document.getElementById('hex').value=(dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w;
												   document.getElementById('hey').value=(dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h;
												   document.getElementById('hfile').value='{$this->file}';
												   document.getElementById('haction').value='applyfilter';
												   document.getElementById('hbrvalue').value=brvalue;
												    document.getElementById('hfiltervalue').value=filtervalue;
												   return true;
													
												    self.location.href = '{$_SERVER['PHP_SELF']}?&action=applyfilter&filtervalue='+filtervalue+'&brvalue='+brvalue+'&file={$this->file}&sx=' + 
													(dd.elements.theCrop.x - dd.elements.theImage.x) + '&sy=' + 
													(dd.elements.theCrop.y - dd.elements.theImage.y) + '&ex=' +
													((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w) + '&ey=' +
													((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);
													
											} 
											function my_Submitfiltercontrast(frm){
											        var filtervalue;
													
											        crvalue=document.getElementById('contrastcmb').value;
													filtervalue="CO";
													document.getElementById('hsx').value=dd.elements.theCrop.x - dd.elements.theImage.x;
												   document.getElementById('hsy').value=dd.elements.theCrop.y - dd.elements.theImage.y;
												   document.getElementById('hex').value=(dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w;
												   document.getElementById('hey').value=(dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h;
												   document.getElementById('hfile').value='{$this->file}';
												   document.getElementById('haction').value='applyfilter';
												   document.getElementById('hcrvalue').value=crvalue;
												   
												    document.getElementById('hfiltervalue').value=filtervalue;
												   return true;
													
												    self.location.href = '{$_SERVER['PHP_SELF']}?&action=applyfilter&filtervalue='+filtervalue+'&crvalue='+crvalue+'&file={$this->file}&sx=' + 
													(dd.elements.theCrop.x - dd.elements.theImage.x) + '&sy=' + 
													(dd.elements.theCrop.y - dd.elements.theImage.y) + '&ex=' +
													((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w) + '&ey=' +
													((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);
											
											}
											
											function my_Submitfiltereffects()
											{    
											
											        var filtervalue;
											        filtervalue=document.getElementById('cmbfiltertype').value;
													
													colorval=document.getElementById('yourcole').value;
												    document.getElementById('hfile').value='{$this->file}';
												    document.getElementById('haction').value='applyfilter';
												    document.getElementById('hfiltervalue').value=document.getElementById('cmbfiltertype').value;
													document.getElementById('hcolorcode').value=colorval;
													
												    self.location.href = '{$_SERVER['PHP_SELF']}?&action=applyfilter&colorcode='+colorval+'&filtervalue='+filtervalue+'&file={$this->file}&sx=' + 
													(dd.elements.theCrop.x - dd.elements.theImage.x) + '&sy=' + 
													(dd.elements.theCrop.y - dd.elements.theImage.y) + '&ex=' +
													((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w) + '&ey=' +
													((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);
													
											}
											function my_Submitrotate()
											{   
											     var rotatevalue;
											       rotatevalue=document.getElementById('cmbrotate').value;
											
											       document.getElementById('hsx').value=dd.elements.theCrop.x - dd.elements.theImage.x;
												   document.getElementById('hsy').value=dd.elements.theCrop.y - dd.elements.theImage.y;
												   document.getElementById('hex').value=(dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w;
												   document.getElementById('hey').value=(dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h;
												   document.getElementById('hfile').value='{$this->file}';
												    document.getElementById('haction').value="rotate";
												   document.getElementById('hrotatevalue').value=rotatevalue;
											  return;
											
											       
												   
										 	        self.location.href = '{$_SERVER['PHP_SELF']}?&action=rotate&rotatevalue='+rotatevalue+'&file={$this->file}&sx=' + 
													(dd.elements.theCrop.x - dd.elements.theImage.x) + '&sy=' + 
													(dd.elements.theCrop.y - dd.elements.theImage.y) + '&ex=' +
													((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w) + '&ey=' +
													((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);
													
											}
											function my_Submitflip()
											{   
											       var flipdirection;
											       flipdirection=document.getElementById('cmbflip').value;
												   document.getElementById('hsx').value=dd.elements.theCrop.x - dd.elements.theImage.x;
												   document.getElementById('hsy').value=dd.elements.theCrop.y - dd.elements.theImage.y;
												   document.getElementById('hex').value=(dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w;
												   document.getElementById('hey').value=(dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h;
												   document.getElementById('hfile').value='{$this->file}';
												   document.getElementById('haction').value="flip";
												   document.getElementById('hflipdirection').value=flipdirection;
												    return;
												   
												   
												   //alert(flipdirection);
										 	        self.location.href = '{$_SERVER['PHP_SELF']}?&action=flip&direction='+flipdirection+'&file={$this->file}&sx=' + 
													(dd.elements.theCrop.x - dd.elements.theImage.x) + '&sy=' + 
													(dd.elements.theCrop.y - dd.elements.theImage.y) + '&ex=' +
													((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w) + '&ey=' +
													((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);
													
											}
											
											
											function my_SetResizingType(proportional)
											{
												if (proportional)
												{
													dd.elements.theCrop.scalable  = 1;
													dd.elements.theCrop.resizable = 0;
												}
												else
												{
													dd.elements.theCrop.scalable  = 0;
													dd.elements.theCrop.resizable = 1;
												}
											}
											
										//-->
										</script>
EOT;
				} 
} 

?>
