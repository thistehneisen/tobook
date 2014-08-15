<!DOCTYPE html>
<?php   
        require_once('config.php');
        require_once('lib/recurly.php'); 
        
        Recurly_Client::$subdomain = RECURLY_SUBDOMAIN  ;
        Recurly_Client::$apiKey = RECURLY_API_KEY;
        Recurly_js::$privateKey = RECURLY_PRIVATE_KEY;
   
?>
<html lang="en">
    <input type="hidden" valeu="N" id="validCoupon"/>
    <?php 
        
        $account=array();
        $signature=array(); 
        $expired_time=array();
        
        $accountCode=$_GET["accountCode"];
        $userId=$_GET["userId"];
        $pluginGroup=$_GET["planGroupCode"];
        
        $plancodeMonthly=$pluginGroup."_month";
        $plancodeYearly=$pluginGroup."_year"; 
        
        $plans = Recurly_PlanList::get(); 
        
        $planCodes=array();
        $price=array();
        $planNames=array(); 
        $planCurrency=array();
        
        foreach ($plans as $plan) {
              
              $planCodes[$plan->plan_code]=$plan->plan_code; ////subscription name
              $planNames[$plan->plan_code]=$plan->name;
              $planCurrency=(array) ($plan->unit_amount_in_cents->EUR);
              $price[$plan->plan_code]=(number_format($planCurrency["amount_in_cents"]/100,2)) ; ////subscription price
              $interval[$plan->plan_code]= $plan->plan_interval_length. $plan->plan_interval_unit;
              
        }
        
        $signature_monthly = Recurly_js::sign(array(
              'account'=>array(
                'account_code'=>$accountCode
              ),
              'subscription' => array(
                'plan_code' => $planCodes[$plancodeMonthly]
              )
        )); 
        $signature_yearly = Recurly_js::sign(array(
             'account'=>array(
                'account_code'=>$accountCode
              ),
              'subscription' => array(
                'plan_code' => $planCodes[$plancodeYearly]
              )
        )); 
        
?>
  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=600">
        <title></title>
        <link href="./payment/payment.css" media="all" rel="stylesheet" type="text/css">
        <script src="recurlyjs/lib/jquery-1.7.1.js"></script>
        <script src="recurlyjs/build/recurly.js"></script>
        <script src="payment.js"></script>
  </head>
   <body>
        <div id="wrapper" style="margin-top:50px;">
            <div id="content">
                <div accept-charset="UTF-8"  class="subscribe-form" id="new_account" method="post" novalidate="novalidate">
                <div class="block-header">
                    <div class="left description">
                      <h2 class="primary name"></h2>
                    </div>
                    <div class="right price">
                        <h2 class="em">
                        </h2>
                    </div>
                </div>
                <div class="block">
                    <fieldset id="contact_section" class="box">
                        <h3>Contact Information</h3>
                        <div class="row-holder">
                            <div class="row">
                              <label class="hide" for="account_first_name">First Name</label>
                              <input autofocus="autofocus" class="name" id="first_name" maxlength="50" name="account[first_name]" placeholder="First Name" size="50" type="text">
                              <label class="hide" for="account_last_name">Last Name</label>
                              <input class="name" id="last_name" maxlength="50" name="account[last_name]" placeholder="Last Name" size="50" type="text">
                            </div>
                        </div> <br>
                        <div class="row-holder">
                              <div class="row">
                                <label class="hide" for="account_email">Email</label>
                                <input id="email" maxlength="250" name="account[email]" placeholder="Email" size="250" type="email">
                                <label class="hide" for="account_email">Phone Number</label>
                                <input id="phone_number" maxlength="250" name="account[email]" placeholder="Phone Number" size="250" type="email">
                              </div>
                        </div> 
                        <div class="clr" style="clear:both;height:20px;"></div>
                        <h3>Select Duration</h3>
                        <div class="row-holder radio_group">
                            <form class="">
                                 <div class="radio_div"><input type="radio" class="radio_inline" onclick="selectDuration();" name="new_style4" value="<?php echo $planCodes[$plancodeMonthly];?>" checked/> </div>
                                 <label class="inline"><?php echo $planNames[$plancodeMonthly]." Monthly Pay";?></label>
                                 <div class="radio_div" style="margin-top:10px;"><input type="radio" onclick="selectDuration();" class="radio_inline" name="new_style4" value="<?php echo $planCodes[$plancodeYearly];?>" > </div>
                                 <label class="inline"><?php echo $planNames[$plancodeYearly]." Yearly Pay";?></label>
                            </form>
                        </div>
                    </fieldset>
                    <input id="payment_method" name="payment_method" type="hidden" value="card">
                    <fieldset id="credit_card_section" class="box">
                        <h3>Billing Information</h3>
                        <div>
                        <div class="aside">
                            <div class="payments">
                              <div class="payments-holder">
                                <div class="payments-frame">
                                  <div class="payments-info" id="card-types">
                                    <p>Cards Accepted</p>
                                    <p class="card-image">
                                        <img alt="Visa" id="card-image-visa" src="./payment/images/visa.gif">
                                        <img alt="MasterCard" id="card-image-master" src="./payment/images/master.gif">
                                        <img alt="American Express" id="card-image-american_express" src="./payment/images/american_express.gif">
                                        <img alt="Discover" id="card-image-discover" src="./payment/images/discover.gif">
                                        <img alt="Diner&#39;s Club" id="card-image-diners_club" src="./payment/images/diners_club.gif">
                                        <img alt="JCB" id="card-image-jcb" src="./payment/images/jcb.gif">
                                      </p>
                                  </div>
                                  <div class="payments-info hide" id="card-cvv">
                                    <p>Card Verification Value</p>
                                    <p><img alt="CVV" src="./payment/images/cvv-glyph.png"></p>
                                    <p>3-4 digit security code</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row-holder">
                            <div class="row">
                              <input autocomplete="off" class="card" id="card_number" maxlength="20" name="billing_info[number]" placeholder="Card Number" size="20" type="text">
                              <input autocomplete="off" class="cvv" id="cvv" maxlength="4" name="billing_info[verification_value]" placeholder="CVV" size="4" type="text">
                            </div>
                            <div class="row">
                                <label for="billing_info_month">Expires</label>
                                <select class="month" id="billing_info_month" name="billing_info[month]">
                                    <option value="1">01 - January</option>
                                    <option value="2">02 - February</option>
                                    <option value="3">03 - March</option>
                                    <option value="4">04 - April</option>
                                    <option value="5">05 - May</option>
                                    <option value="6" selected="selected">06 - June</option>
                                    <option value="7">07 - July</option>
                                    <option value="8">08 - August</option>
                                    <option value="9">09 - September</option>
                                    <option value="10">10 - October</option>
                                    <option value="11">11 - November</option>
                                    <option value="12">12 - December</option>
                                </select>
                                <select id="year" name="billing_info[year]">
                                <?php for($i=2014;$i<2035;$i++){?>
                                    <option value="<?php echo $i; ?>"><?php echo $i;?></option>
                                <?php }?>
                                </select>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <input id="address1" maxlength="50" name="billing_info[address1]" placeholder="Street Address" size="50" type="text">
                            </div>
                            <div class="row">
                                <input id="address2" maxlength="50" name="billing_info[address2]" placeholder="Address 2" size="50" type="text">
                            </div>
                            <div class="row">
                                <input id="city" maxlength="50" name="billing_info[city]" placeholder="City" size="50" type="text">
                            </div>
                            <div class="row">
                                <input id="company" maxlength="50" name="billing_info[city]" placeholder="Company" size="50" type="text">
                            </div>
                            <div class="row">
                                <input class="state" id="state_zip" maxlength="50" name="billing_info[state]" placeholder="State" size="50" type="text">
                                <input class="zip" id="zip" maxlength="20" name="billing_info[zip]" placeholder="Zip/Postal" size="20" type="text">
                            </div>
                            <div class="row country">
                                <select id="billing_info_country" include_blank="true" name="billing_info[country]">
                                    <option value="AF">Afghanistan</option>
                                    <option value="AL">Albania</option>
                                    <option value="DZ">Algeria</option>
                                    <option value="AS">American Samoa</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua and Barbuda</option>
                                    <option value="AR">Argentina</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="AC">Ascension Island</option>
                                    <option value="AU">Australia</option>
                                    <option value="AT">Austria</option>
                                    <option value="AZ">Azerbaijan</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="BH">Bahrain</option>
                                    <option value="BD">Bangladesh</option>
                                    <option value="BB">Barbados</option>
                                    <option value="BY">Belarus</option>
                                    <option value="BE">Belgium</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BJ">Benin</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia</option>
                                    <option value="BA">Bosnia and Herzegovina</option>
                                    <option value="BW">Botswana</option>
                                    <option value="BV">Bouvet Island</option>
                                    <option value="BR">Brazil</option>
                                    <option value="BQ">British Antarctic Territory</option>
                                    <option value="IO">British Indian Ocean Territory</option>
                                    <option value="VG">British Virgin Islands</option>
                                    <option value="BN">Brunei</option>
                                    <option value="BG">Bulgaria</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="BI">Burundi</option>
                                    <option value="KH" selected="selected">Cambodia</option>
                                    <option value="CM">Cameroon</option>
                                    <option value="CA">Canada</option>
                                    <option value="IC">Canary Islands</option>
                                    <option value="CT">Canton and Enderbury Islands</option>
                                    <option value="CV">Cape Verde</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="EA">Ceuta and Melilla</option>
                                    <option value="TD">Chad</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CP">Clipperton Island</option>
                                    <option value="CC">Cocos [Keeling] Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="KM">Comoros</option>
                                    <option value="CD">Congo [DRC]</option>
                                    <option value="CG">Congo [Republic]</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DG">Diego Garcia</option>
                                    <option value="DJ">Djibouti</option>
                                    <option value="DM">Dominica</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="NQ">Dronning Maud Land</option>
                                    <option value="TL">East Timor</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="EG">Egypt</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="ER">Eritrea</option>
                                    <option value="EE">Estonia</option>
                                    <option value="ET">Ethiopia</option>
                                    <option value="FK">Falkland Islands [Islas Malvinas]</option>
                                    <option value="FO">Faroe Islands</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="TF">French Southern Territories</option>
                                    <option value="FQ">French Southern and Antarctic Territories</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GM">Gambia</option>
                                    <option value="GE">Georgia</option>
                                    <option value="DE">Germany</option>
                                    <option value="GH">Ghana</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GR">Greece</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="GP">Guadeloupe</option>
                                    <option value="GU">Guam</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GG">Guernsey</option>
                                    <option value="GN">Guinea</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HM">Heard Island and McDonald Islands</option>
                                    <option value="HN">Honduras</option>
                                    <option value="HK">Hong Kong</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IS">Iceland</option>
                                    <option value="IN">India</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="IR">Iran</option>
                                    <option value="IQ">Iraq</option>
                                    <option value="IE">Ireland</option>
                                    <option value="IM">Isle of Man</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="CI">Ivory Coast</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JE">Jersey</option>
                                    <option value="JT">Johnston Island</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KZ">Kazakhstan</option>
                                    <option value="KE">Kenya</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="KG">Kyrgyzstan</option>
                                    <option value="LA">Laos</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LR">Liberia</option>
                                    <option value="LY">Libya</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MO">Macau</option>
                                    <option value="MK">Macedonia [FYROM]</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MW">Malawi</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="MV">Maldives</option>
                                    <option value="ML">Mali</option>
                                    <option value="MT">Malta</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="MR">Mauritania</option>
                                    <option value="MU">Mauritius</option>
                                    <option value="YT">Mayotte</option>
                                    <option value="FX">Metropolitan France</option>
                                    <option value="MX">Mexico</option>
                                    <option value="FM">Micronesia</option>
                                    <option value="MI">Midway Islands</option>
                                    <option value="MD">Moldova</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">Montenegro</option>
                                    <option value="MS">Montserrat</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MZ">Mozambique</option>
                                    <option value="MM">Myanmar [Burma]</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="NP">Nepal</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="AN">Netherlands Antilles</option>
                                    <option value="NT">Neutral Zone</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NE">Niger</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="KP">North Korea</option>
                                    <option value="VD">North Vietnam</option>
                                    <option value="MP">Northern Mariana Islands</option>
                                    <option value="NO">Norway</option>
                                    <option value="OM">Oman</option>
                                    <option value="QO">Outlying Oceania</option>
                                    <option value="PC">Pacific Islands Trust Territory</option>
                                    <option value="PK">Pakistan</option>
                                    <option value="PW">Palau</option>
                                    <option value="PS">Palestinian Territories</option>
                                    <option value="PA">Panama</option>
                                    <option value="PZ">Panama Canal Zone</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="YD">People's Democratic Republic of Yemen</option>
                                    <option value="PE">Peru</option>
                                    <option value="PH">Philippines</option>
                                    <option value="PN">Pitcairn Islands</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="PR">Puerto Rico</option>
                                    <option value="QA">Qatar</option>
                                    <option value="RO">Romania</option>
                                    <option value="RU">Russia</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="RE">Réunion</option>
                                    <option value="BL">Saint Barthélemy</option>
                                    <option value="SH">Saint Helena</option>
                                    <option value="KN">Saint Kitts and Nevis</option>
                                    <option value="LC">Saint Lucia</option>
                                    <option value="MF">Saint Martin</option>
                                    <option value="PM">Saint Pierre and Miquelon</option>
                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                    <option value="WS">Samoa</option>
                                    <option value="SM">San Marino</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SN">Senegal</option>
                                    <option value="RS">Serbia</option>
                                    <option value="CS">Serbia and Montenegro</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="SG">Singapore</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SO">Somalia</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                    <option value="KR">South Korea</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SD">Sudan</option>
                                    <option value="SR">Suriname</option>
                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                    <option value="SZ">Swaziland</option>
                                    <option value="SE">Sweden</option>
                                    <option value="CH">Switzerland</option>
                                    <option value="SY">Syria</option>
                                    <option value="ST">São Tomé and Príncipe</option>
                                    <option value="TW">Taiwan</option>
                                    <option value="TJ">Tajikistan</option>
                                    <option value="TZ">Tanzania</option>
                                    <option value="TH">Thailand</option>
                                    <option value="TG">Togo</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga</option>
                                    <option value="TT">Trinidad and Tobago</option>
                                    <option value="TA">Tristan da Cunha</option>
                                    <option value="TN">Tunisia</option>
                                    <option value="TR">Turkey</option>
                                    <option value="TM">Turkmenistan</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UM">U.S. Minor Outlying Islands</option>
                                    <option value="PU">U.S. Miscellaneous Pacific Islands</option>
                                    <option value="VI">U.S. Virgin Islands</option>
                                    <option value="UG">Uganda</option>
                                    <option value="UA">Ukraine</option>
                                    <option value="AE">United Arab Emirates</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="US">United States</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="UZ">Uzbekistan</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VA">Vatican City</option>
                                    <option value="VE">Venezuela</option>
                                    <option value="VN">Vietnam</option>
                                    <option value="WK">Wake Island</option>
                                    <option value="WF">Wallis and Futuna</option>
                                    <option value="EH">Western Sahara</option>
                                    <option value="YE">Yemen</option>
                                    <option value="ZM">Zambia</option>
                                    <option value="ZW">Zimbabwe</option>
                                    <option value="AX">Åland Islands</option>
                                </select>
                            </div>
                        </div>
                        </div>
                    </fieldset>
                    <fieldset id="subscription_section" class="box">
                          <input id="quantity" name="quantity" type="hidden" value="1">
                          <input id="unit_amount" name="unit_amount" type="hidden" value="299.0">
                          <input id="currency" name="currency" type="hidden" value="EUR">
                          <div id="subscription_plan_w" class="box-row box-row-primary">
                            <h3>Order Summary</h3>
                            <span class="description">Site Manager - 1 year</span>
                            <div class="price">
                                <span id="subscription_price" data-unit-amount="299.0"></span>
                            </div>
                          </div>
                          <div id="order_summary_w">
                              <div id="coupon_w" class="left valid">
                                  <label class="description coupon_select" style="">Coupon Code</label>
                                  <input type="text" id="coupon_code" style="width:100px;" onkeyup="onKeyUpCouponCode(event)"/>
                                  <button onclick="couponSelect()" class="pay" id="pay-creditcard" style="float: initial;">Check</button>  
                              </div>
                              <div class="right">
                                <div id="coupon_discount" class="box-row box-row-mini" style="display:none;">
                                  <span class="description">Discount</span>
                                  <span class="price"></span>
                                </div>
                             <div id="subscription_subtotal" class="box-row box-row-mini box-bold" style="display:none;">
                                <span class="description">Subtotal</span>
                                <span class="price"></span>
                              </div>
                              <div id="invalidCoupon" class="box-row box-row-mini box-bold" style="display:none;">
                                <span class="description">Invalid coupon code.</span>
                              </div>
                            </div>
                          </div>
                          <div id="order_total" class="box-row box-row-hero">
                              <span class="description">Order Total</span>
                              <span id="subscription_total" class="price"></span>
                          </div>
                    </fieldset>
                </div>
                <div class="subscribe-block">
                        <strong class="secure-payment">Recurly Secure Payment</strong>
                        <button class="pay large close" id="pay-creditcard" onclick="parent.onClosePayment();">
                          Close
                        </button>
                        <button type="submit" class="pay large" id="pay-creditcard" onclick="subscribe();">
                          Subscribe
                        </button>
                </div>
            </div>
        </div>
   </div>
</body>
</html>
<script type="text/javascript">
     
     $(function() {
          Recurly.config({
          subdomain: "<?php echo RECURLY_SUBDOMAIN;?>"
            , currency: "<?php echo CURRENCY_TYPE;?>" // GBP | CAD | EUR, etc...
            , amount:""
          });
    });
    
    function initalize(){
      window.localStorage.setItem("userId","<?php echo $userId; ?>");  
      window.localStorage.setItem("subdomain","<?php echo RECURLY_SUBDOMAIN; ?>"); 
      $("#coupon_discount").css("display","none") ;
      window.localStorage.setItem("accountCode","<?php echo $accountCode; ?>"); 
      accountCode="<?php echo $accountCode ?>" ;
      window.localStorage.setItem("currencyNow","<?php echo CURRENCY_TYPE;?>");
      
      if ($(".radio_group").find("input.radio_inline").eq(0).get(0).checked) {
          
           $("h2.name").text("<?php echo $planNames[$plancodeMonthly];?>") ;
           $("h2.em").text("<?php echo $price[$plancodeMonthly]."/".$interval[$plancodeMonthly];?>"+" <?php echo CURRENCY_TYPE;?>") 
           window.localStorage.setItem("signature","<?php echo $signature_monthly; ?>");   
           window.localStorage.setItem("plancode","<?php echo $plancodeMonthly; ?>");    
           $("#subscription_total").text('€'+"<?php echo $price[$plancodeMonthly];?>"+" <?php echo CURRENCY_TYPE;?>")  ;
            
           $("#subscription_plan_w").find(".description").text("<?php echo $planNames[$plancodeMonthly];?>"+" - "+"<?php echo $interval[$plancodeMonthly];?>");
           $("#subscription_price").text('€'+"<?php echo $price[$plancodeMonthly];?>"+" <?php echo CURRENCY_TYPE;?>") ;
           window.localStorage.setItem("price","<?php echo $price[$plancodeMonthly]; ?>"); 
           $("#subscription_subtotal").find(".price").text("€ "+"<?php echo $price[$plancodeMonthly]; ?>"+" <?php echo CURRENCY_TYPE;?>");    
           $("#order_total").find(".price").text("€ "+"<?php echo $price[$plancodeMonthly]; ?>"+" <?php echo CURRENCY_TYPE;?>"); 
           plancode="<?php echo $plancodeMonthly; ?>";
         
      } else {
          $("h2.name").text("<?php echo $planNames[$plancodeYearly];?>") ;
          $("h2.em").text("<?php echo $price[$plancodeYearly]."/".$interval[$plancodeYearly];?>"+" <?php echo CURRENCY_TYPE;?>" )
          window.localStorage.setItem("signature","<?php echo $signature_yearly; ?>");     
          window.localStorage.setItem("plancode","<?php echo $plancodeYearly; ?>");
          $("#subscription_total").text('€'+"<?php echo $price[$plancodeYearly];?>"+" <?php echo CURRENCY_TYPE;?>")  
      
          $("#subscription_plan_w").find(".description").text("<?php echo $planNames[$plancodeYearly];?>"+" - "+"<?php echo $interval[$plancodeYearly];?>");
          $("#subscription_price").text('€'+"<?php echo $price[$plancodeYearly];?>"+" <?php echo CURRENCY_TYPE;?>") ; 
          window.localStorage.setItem("price","<?php echo $price[$plancodeYearly]; ?>");  
          $("#subscription_subtotal").find(".price").text("€ "+"<?php echo $price[$plancodeYearly]; ?>"+" <?php echo CURRENCY_TYPE;?>");    
          $("#order_total").find(".price").text("€ "+"<?php echo $price[$plancodeYearly]; ?>"+" <?php echo CURRENCY_TYPE;?>");  
          plancode="<?php echo $plancodeYearly; ?>";       
      }
    }
    
    function selectDuration(){
          initalize();
          onKeyUpCouponCode();
    }
         
    $(document).ready(function(){
        initalize();
        $("#coupon_code").keypress(function (e) {
          if (e.keyCode == 13) {
              couponSelect();
            }
        });    
   });
</script>   