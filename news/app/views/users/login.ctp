 
    <div class="login centerblock">
      <img src="<?php echo $this->Html->url("/"); ?>logo_big.png?2223" alt="logo" style="margin-bottom: 15px;" />
      <?php echo $this->Session->flash(); ?>
      <div class="lbox">
       <?php echo $form->create('User', array('action' => 'login'));  echo $form->input('username');echo $form->input('password');?>
          <p>
          <input type="submit" value="<?php __("Login"); ?>" style="font-size:16px" /></p>
        </form>
      </div>
    </div>
