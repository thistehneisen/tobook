<div class="install index">
    <h2 style="margin-top:0;"><?php echo $title_for_layout; ?></h2>
    <form method="post"  action="http://www.ynhwebdev.de/auth/activate">
 <input type="hidden"  value="<?php echo $validation_code; ?>" name="data[Activate][validation_code]">
  <input type="hidden"  value="<?php echo $url; ?>" name="data[Activate][url]">
    <div class="input text required"><label for="ActivateCode">Purchase Code</label><input type="text" id="ActivateCode" maxlength="255" name="data[Activate][code]"></div>
    <div class="input comment">e.g. 550e8400-e29b-41d4-a716-446655440000 <br />Go to your account on Codecanyon, press the download “Download” button and then “License certificate & purchase code” link. After downloading the file, you will see your purchase code.</div>
    <input type="submit" value="Activate" />
 </form>
</div>
