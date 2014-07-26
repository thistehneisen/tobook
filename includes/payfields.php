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
<tr>
	<td width="60%" align="left">
					&nbsp;&nbsp;First Name :
	</td>
	<td width="40%" align="left">
	<input type="text" name="txtFirstName" id="txtFirstName" value="<?php echo($_POST["txtFirstName"]); ?>" size="30" maxlength="40" class="textbox">&nbsp;
	
	</td>
</tr>
<tr>
	<td width="40%"  align="left">
	&nbsp;&nbsp;Last Name :
	</td>
	<td width="60%" align="left" >
	<input type="text" name="txtLastName" id="txtLastName" value="<?php echo($_POST["txtLastName"]); ?>" size="30" maxlength="40" class="textbox">
	</td>
</tr>
<tr>
	<td width="40%" align="left">
	 &nbsp;&nbsp;Card Number :
	</td>
	<td width="60%" align="left">
	<input type=text name="txtccno" class="textbox" id="txtccno" size="30" maxlength="16" onBlur="javascript:checkNumber(this);">
	 <br><img src=images/visa_amex.gif>
	</td>
</tr>
<tr>
	<td width="80%"  align="left">
	 &nbsp;&nbsp;Validation Code :
	</td>
	<td width="20%" align="left">
	<input type=text name="txtcvv2" class="textbox" id="txtcvv2" size=14 maxlength="4" onBlur="javascript:checkNumber(this);">
	</td>
</tr>
<tr>
	<td width="40%" align="left">
	 &nbsp;&nbsp;Expiration Date : <br>
	&nbsp;&nbsp;(MM/YYYY)
 </td>
	<td width="60%" align="left">
	 <input type=text name="txtMM" class="textbox" id="txtMM" size=3 maxlength="2" onBlur="javascript:checkNumber(this);"> /
	 <input type=text name="txtYY" class="textbox" id="txtYY" size=6 maxlength="4" onBlur="javascript:checkNumber(this);">
	</td>
</tr>

 <tr>
	<td colspan=2 align="center" >
	<br><b>Billing Address Details</b>
	</td>
</tr>
<tr >
	<td colspan=2 align="left" >
	  
	  <table width="100%"  border="0" cellpadding="0" cellspacing="0" background="./images/horline.jpg">
	        <tr><td><img src="./images/spacer.gif" width="1" height="1"></td></tr>
	  </table>
	  
	</td>
</tr>
<tr>
	<td width="40%"  align="left">
	&nbsp;&nbsp;Address:
	</td>
	<td width="60%" align="left">
	<input type=text name="txtAddress" class="textbox" id="txtAddress" size=30 maxlength=30 value="<?php echo($_POST["txtAddress"]); ?>">
	</td>
</tr>
<tr>
	<td width="40%"  align="left"  height="20">
	&nbsp;&nbsp;City:
	</td>
	<td width="60%" align="left">
	<input type=text name="txtCity" class="textbox" id="txtCity" size=30 maxlength="30" value="<?php echo($_POST["txtCity"]); ?>">
	</td>
</tr>
<tr>
	<td width="40%"  align="left">
	&nbsp;&nbsp;State/Province :
	</td>
	<td width="60%" align="left">
	<input type=text name="txtState" class="textbox" id="txtState" size=30 maxlength=30 value="<?php echo($_POST["txtState"]); ?>">
	</td>
</tr>
<tr>
	<td width="40%" class="maintext" align="left">
	&nbsp;&nbsp;Postal Code :
	</td>
	<td width="60%" align="left">
	<input type=text name="txtPostal" class="textbox" id="txtPostal" size=30 maxlength=10  value="<?php echo($_POST["txtPostal"]); ?>">
	</td>
</tr>
<tr>
	<td width="40%"  align="left">
	&nbsp;&nbsp;Country :
	</td>
	<td width="60%" align="left">
	<SELECT name="cmbCountry" class="SelectBox" style="width:167px; ">
	  <option>Afghanistan
	  <option>Albania
	  <option>Algeria
	  <option>Andorra
	  <option>Angola
	  <option>Antigua&nbsp; and&nbsp; Barbuda
	  <option>Argentina
	  <option>Armenia
	  <option>Australia
	  <option>Austria
	  <option>Azerbaijan
	  <option>Bahamas
	  <option>Bahrain
	  <option>Bangladesh
	  <option>Barbados
	  <option>Belarus
	  <option>Belgium
	  <option>Belize
	  <option>Benin
	  <option>Bhutan
	  <option>Bolivia
	  <option>Bosnia &amp; Herzegovina
	  <option>Botswana
	  <option>Brazil
	  <option>Brunei
	  <option>Bulgaria
	  <option>Burkina Faso
	  <option>Burundi
	  <option>Cambodia
	  <option>Cameroon
	  <option>Canada
	  <option>Cape Verde
	  <option>Cent African Rep
	  <option>Chad
	  <option>Chile
	  <option>China
	  <option>Colombia
	  <option>Comoros
	  <option>Congo
	  <option>Costa Rica
	  <option>Croatia
	  <option>Cuba
	  <option>Cyprus
	  <option>Czech Republic
	  <option>C&ocirc;te d'Ivoire
	  <option>Denmark
	  <option>Djibouti
	  <option>Dominica
	  <option>Dominican Republic
	  <option>East Timor
	  <option>Ecuador
	  <option>Egypt
	  <option>El Salvador
	  <option>Equatorial Guinea
	  <option>Eritrea
	  <option>Estonia
	  <option>Ethiopia
	  <option>Fiji
	  <option>Finland
	  <option>France
	  <option>Gabon
	  <option>Gambia
	  <option>Georgia
	  <option>Germany
	  <option>Ghana
	  <option>Greece
	  <option>Grenada
	  <option>Guatemala
	  <option>Guinea
	  <option>Guinea-Bissau
	  <option>Guyana
	  <option>Haiti
	  <option>Honduras
	  <option>Hungary
	  <option>Iceland
	  <option>India
	  <option>Indonesia
	  <option>Iran
	  <option>Iraq
	  <option>Ireland
	  <option>Israel
	  <option>Italy
	  <option>Jamaica
	  <option>Japan
	  <option>Jordan
	  <option>Kazakhstan
	  <option>Kenya
	  <option>Kiribati
	  <option>Korea, North
	  <option>Korea, South
	  <option>Kuwait
	  <option>Kyrgyzstan
	  <option>Laos
	  <option>Latvia
	  <option>Lebanon
	  <option>Lesotho
	  <option>Liberia
	  <option>Libya
	  <option>Liechtenstein
	  <option>Lithuania
	  <option>Luxembourg
	  <option>Macedonia
	  <option>Madagascar
	  <option>Malawi
	  <option>Malaysia
	  <option>Maldives
	  <option>Mali
	  <option>Malta
	  <option>Marshall Islands
	  <option>Mauritania
	  <option>Mauritius
	  <option>Mexico
	  <option>Micronesia
	  <option>Moldova
	  <option>Monaco
	  <option>Mongolia
	  <option>Morocco
	  <option>Mozambique
	  <option>Myanmar
	  <option>Namibia
	  <option>Nauru
	  <option>Nepal
	  <option>Netherlands
	  <option>New Zealand
	  <option>Nicaragua
	  <option>Niger
	  <option>Nigeria
	  <option>Norway
	  <option>Oman
	  <option>Pakistan
	  <option>Palau
	  <option>Panama
	  <option>Papua New Guinea
	  <option>Paraguay
	  <option>Peru
	  <option>Philippines
	  <option>Poland
	  <option>Portugal
	  <option>Qatar
	  <option>Romania
	  <option>Russia
	  <option>Rwanda
	  <option>Saint Kitts
	  <option>Saint Lucia
	  <option>Saint Vincent
	  <option>Samoa
	  <option>San Marino
	  <option>Sao Tome
	  <option>Saudi Arabia
	  <option>Senegal
	  <option>Seychelles
	  <option>Sierra Leone
	  <option>Singapore
	  <option>Slovakia
	  <option>Slovenia
	  <option>Solomon Islands
	  <option>Somalia
	  <option>South Africa
	  <option>Spain
	  <option>Sri Lanka
	  <option>Sudan
	  <option>Suriname
	  <option>Swaziland
	  <option>Sweden
	  <option>Switzerland
	  <option>Syria
	  <option>Taiwan
	  <option>Tajikistan
	  <option>Tanzania
	  <option>Thailand
	  <option>Togo
	  <option>Tonga
	  <option>Trinidad and Tobago
	  <option>Tunisia
	  <option>Turkey
	  <option>Turkmenistan
	  <option>Tuvalu
	  <option>Uganda
	  <option>Ukraine
	  <option>United Arab Emirates
	  <option>United Kingdom
	  <option selected>UnitedStates
	  <option>Uruguay
	  <option>Uzbekistan
	  <option>Vanuatu
	  <option>Vatican City
	  <option>Venezuela
	  <option>Vietnam
	  <option>Western Sahara
	  <option>Yemen
	  <option>Yugoslavia
	  <option>Zambia
	  <option>Zimbabwe</option>
	</SELECT>
	</td>
</tr>
<tr>
	<td width="40%" align="left">
	&nbsp;&nbsp;Email :
	</td>
	<td width="60%" align="left">
	<input type=text name="txtEmail" class="textbox" id="txtEmail" size=30 maxlength=50 value="<?php echo($_POST["txtEmail"]); ?>">
	</td>
</tr>
