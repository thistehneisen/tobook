<?php echo $rss->header(); 
$channel = array ('title' => Configure::read("Settings.ctitle"), 'link' => Configure::read("Settings.clink"), 'description' => Configure::read("Settings.cdescription"));
 
$items= $rss->items($mails, 'transformRSS');

//Callback function
function transformRSS($mail){
	return array(
		'title' => $mail['Mail']['subject'],
		'link' => "/show/". $mail['Mail']['id']."/0-GUEST",
		'guid' => "/show/". $mail['Mail']['id']."/0-GUEST",
		'description' => "",
		'author' => Configure::read("Settings.author"),
		'pubDate' => $mail['Mail']['send_on']
	);
};
$channelEl = $rss->channel(array(), $channel,$items );

echo $rss->document(array(), $channelEl);

?>