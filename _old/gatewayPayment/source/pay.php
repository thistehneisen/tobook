<?php

require_once('lib/recurly.php');
 
// Required for the API


Recurly_js::$privateKey = '02a2488b22cf4c8f85b3ffff54565fcf';
 
$signature = Recurly_js::sign(array(
  'account'=>array(
    'account_code'=>"123" 
  ),
  'subscription' => array(
    'plan_code' => 'gold'
  )
)); 
?>
<html>
  <head>
    <link href="recurlyjs/themes/default/recurly.css" rel="stylesheet">
    <script src="recurlyjs/lib/jquery-1.7.1.js"></script>
    <script src="recurlyjs/build/recurly.js"></script>
    <script>
    $(function(){
      Recurly.config({
        subdomain: '<?php echo "sdf";?>'
        , currency: 'USD' // GBP | CAD | EUR, etc...
      });

      Recurly.buildSubscriptionForm({
        target: '#recurly-form',
        planCode: 'gold',
        successURL: 'success.php',
        signature: '<?php echo $signature;?>',
      });

    });
    </script>
  </head>
  <body>
    <h1>Test Form</h1>
    <div id="recurly-form"></div>
  </body>
</html>