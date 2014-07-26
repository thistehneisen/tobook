<div>
    <h2 style="margin-top: 0px;"><?php __("Send to friend"); ?></h2>
    <?php
   if(!isset($mail)){
        echo $this->Form->create('Form', array("url" => array('action' => 'sendtof', $mid . "")));

 
        echo $this->Form->input('sender_name', array("label" => __("Your Name", true)));
        echo $this->Form->input('sender_mail', array("label" => __("Your Mail", true)));
        echo $this->Form->input('friend_name', array("label" => __("Friend's Name", true)));
        echo $this->Form->input('friend_mail', array("label" => __("Friend's Mail", true)));
        echo $this->Form->input('message', array("type" => "textarea", "label" => __("Message", true), "default" => __("I thought you'd like this newsletter", true) . " " . $this->Html->url("/show/" . $mid . "/0-GUEST", true)));
        echo $this->Form->end(__("Send", true));
   }else  if ($mail == 1) {
      __("Thank you for forwarding this newsletter.");
    } else if ($mail == 2) {
          __("Oops! something went wrong!!");
           
    }
    ?>
</div>