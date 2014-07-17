<table border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" ><table width="100%"  border="0" cellpadding="0" cellspacing="0" background="images/horline.jpg">
                    <tr>
                      <td><img src="images/spacer.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    
                </table>                  <img src="images/spacer.gif" width="1" height="1"> <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center"><table width="84%"  border="0" cellpadding="5" cellspacing="0" class="maintext">
                        <tr>
                          <td><P align=left><b>Affiliate Program Frequently Asked Questions</b><br>&nbsp;<br>

Here are some of the Frequently asked Affiliate questions from the current clients. <br><br>
<table width="100%">
	<tr>
		<td class="maintext">
		<div>
								  
									  <p>
									  <a name="#top">&nbsp;</a>
									  <b>&nbsp;&nbsp;&nbsp;Signing UP</b>
									  <ul>
									  	<li><a href="#1">What is the <?php echo($_SESSION["session_lookupsitename"]); ?> Affiliate Program?</a> </li>
										<li><a href="#2">Who can become a member of the Affiliate Program? </a> </li>
										<li><a href="#3">Can I become an Affiliate even though my website is not uploaded yet?</a>  </li>
										<li><a href="#4">How can I join?</a>   </li>
									  </ul>
									  </p>
									  <p>
									  <b>&nbsp;&nbsp;&nbsp;Linking as an Affiliate</b>
									  <ul>
									  	<li><a href="#5">How do I link my website to <?php echo($_SESSION["session_lookupsitename"]); ?>? </a> </li>
										<li><a href="#6">Is there another way to refer my friends to <?php echo($_SESSION["session_lookupsitename"]); ?>?  </a> </li>
									  </ul>
									  </p>
									  <p>
									  <b>&nbsp;&nbsp;&nbsp;Getting Paid </b>
									  <ul>
									  	<li><a href="#7">How does <?php echo($_SESSION["session_lookupsitename"]); ?> know that I referred the sign-ups?  </a> </li>
										<li><a href="#8">How do I keep track of my affiliate account?  </a> </li>
										<li><a href="#9">When can I start collecting my commission?   </a> </li>
										<li><a href="#10">How will I get paid?  </a> </li>
									  </ul>
									  </p>
									  <p>
									  <b>&nbsp;&nbsp;&nbsp;Getting Help </b>
									  <ul>
									  	<li><a href="#11">Who should I contact when I need help with my affiliate account?  </a> </li>
									  </ul>
									  </p>
								  </div>
		</td>
	</tr>
	<tr>
		<td class="maintext">
<?php
	$sql = "Select vname,vvalue from tbl_lookup where vname='naff_amnt'";
	$result = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_array($result);
		$aff_percent = $row["vvalue"];
	}
?>			
		
		<div>
									<hr>
									<a name="1" class="listing126">What is the <?php echo($_SESSION["session_lookupsitename"]); ?> Affiliate Program?</a>
									<p align="justify" >
									
									The Affiliate Program is a partnership program with <?php echo($_SESSION["session_lookupsitename"]); ?>. Members get to earn $ <?php echo($aff_percent); ?>  for a referred purchase at <?php echo($_SESSION["session_lookupsitename"]); ?>.
									</p>
									<a href="#top">Top</a>
									
							  </div>
									
									<div>
									<hr>
									<a name="2" class="listing126">Who can become a member of the Affiliate Program?</a>
									<p align="justify" >
									Absolutely ANYONE can become an affiliate of <?php echo($_SESSION["session_lookupsitename"]); ?>. You don't even need to have a website to join. You can refer your friends to <?php echo($_SESSION["session_lookupsitename"]); ?> through email or other means.  However we do not tolerate any kind of spam for promoting the affiliate program. 
									</p>
									<a href="#top">Top</a>
									
									</div>
									
									<div>
									<hr>
									<a name="3" class="listing126">Can I become an Affiliate even though my website is not uploaded yet?</a>
									<p align="justify" >
									Definitely. There are different ways to refer your friends to <?php echo($_SESSION["session_lookupsitename"]); ?> so you can earn even when your site hasn't been uploaded yet. 
									</p>
									<a href="#top">Top</a>
									
									</div>
									
									<div>
									<hr>
									<a name="4" class="listing126">How can I join?</a>
									<p align="justify" >
									Just visit the <?php echo($_SESSION["session_rootserver"]); ?>/affiliatesignup.php and sign-up and then you're on your way to earning extra $$$. 
									</p>
									<a href="#top">Top</a>
									
									</div>
									<div>
									<hr>
									<a name="5" class="listing126">How do I link my website to <?php echo($_SESSION["session_lookupsitename"]); ?>?</a>
									<p align="justify" >
									You can choose the way you link your site to <?php echo($_SESSION["session_lookupsitename"]); ?>. You can use either Text link or graphics to link to <?php echo($_SESSION["session_lookupsitename"]); ?> using the link provided when you signed up.  
									</p>
									<a href="#top">Top</a>
									
									</div>
									<div>
									<hr>
									<a name="6" class="listing126">Is there another way to refer my friends to <?php echo($_SESSION["session_lookupsitename"]); ?>?</a>
									<p align="justify" >
									You can use the links in your sites/pages or even refer your friends by word of mouth or by email. But in any case, please dont forget to mention your Affiliate Id.  
									</p>
									<a href="#top">Top</a>
									
									</div>
									<div>
									<hr>
									<a name="7" class="listing126">How does <?php echo($_SESSION["session_lookupsitename"]); ?> know that I referred the sign-ups?</a>
									<p align="justify" >
									Your link will point to your affiliate URL. This URL is basically just the <?php echo($_SESSION["session_lookupsitename"]); ?> homepage that's configured to your affiliate ID (e.g. <?php echo($_SESSION["session_rootserver"]); ?>/index.php?affid=1).  
									</p>
									<a href="#top">Top</a>
									
									</div>
									<div>
									<hr>
									<a name="8" class="listing126">How do I keep track of my affiliate account?</a>
									<p align="justify" >
									Your control panel will let you know how many referrals you have made.  
									</p>
									<a href="#top">Top</a>
									
									</div>
									<div>
									<hr>
									<a name="9" class="listing126">When can I start collecting my commission?</a>
									<p align="justify" >
									We will send you the commissions every month. Each months commissions will be send after 30 days from the end of the month.
									</p>
									<a href="#top">Top</a>
									
									</div>
									<div>
									<hr>
									<a name="10" class="listing126">When can I start collecting my commission?</a>
									<p align="justify" >
									You have a choice between getting a check in the mail directly from <?php echo($_SESSION["session_lookupsitename"]); ?>, and using Paypal.com.  
									If you prefer to get paid using PayPal.com there is $25 monthly minimum.  If you do not have $25 in a month it will be carried over to next month. End of the year we will pay the commission balance regardless of the amount (even  if it is still less than $25 too)
									If you prefer to get paid through Checks, there is $50 minimum. If you do not have $50 in a month it will be carried over to next month. End of the year we will pay the commission balance regardless of the amount (even  if it is still less than $50 too)
									</p>
									<a href="#top">Top</a>
									
									</div>
									<div>
									<hr>
									<a name="11" class="listing126">Who should I contact when I need help with my affiliate account? </a>
									<p align="justify" >
									If you need help in anything about the <?php echo($_SESSION["session_lookupsitename"]); ?> Affiliate Program, just email <a href="mailto:<?php echo($_SESSION["session_lookupadminemail"]); ?>"><?php echo($_SESSION["session_lookupadminemail"]); ?></a>. 
									</p>
									<a href="#top">Top</a>
									
									</div>
		</td>
	</tr>
</table>
</P>

    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left" class="dotedline"><img src="images/spacer.gif" width="1" height="1"></td>
                                </tr>
                              </table>

<br><br>&nbsp;

</td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
                <td width="1"  background=images/vline.gif ></td>
         </tr>
          </table>
                 </td>
        </tr>
      </table>