<?php

function divi($a, $b) {
    if ($b == 0) {
        return 0;
    }
    return $a / $b;
}

if (substr($this->params['url']['url'], 0, 4) != "show") {
    $content = str_replace(array("%5B", "%5D"), array("[", "]"), $content);
    foreach ($mail["Link"] as $value) {
        $url = "goto/" . $value["id"] . "/{\$SUBSCRIBER_ID}";
        $link_pos = 0;
        $link_pos = strpos($content, $url, $link_pos);
        while (!($link_pos === false)) {

            $endpos = strpos(substr($content, $link_pos), "</a>") + $link_pos;
            $content = substr($content, 0, $endpos + 4) . '<span style="line-height:12px;font-family:sans-serif;margin-left:-26px;position:relative;text-align:center;top:-9px;color:#ffffff;text-align: center;display: inline-block;width:26px;left:20px;padding:2px;font-size:8px;font-weight:700;-moz-border-radius:4px;border-radius:4px;background: #FF8F00;background: -moz-linear-gradient(top, #FF8F00 0%, #C56E00 100%);background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FF8F00), color-stop(100%,#C56E00));filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#FF8F00\', endColorstr=\'#C56E00\',GradientType=0 );">' . round(divi($value["clicks"] * 100, $mail["read"])) . '%</span>' . substr($content, $endpos + 4, strlen($content) - $endpos - 4);
            $next = strpos(substr($content, $link_pos + 1), $url);
            if ($next !== false) {
                $link_pos+=$next + 1;
            } else {
                $link_pos = $next;
            }
        }
    }
}
$pos = stripos($content, "</head");
$add = "";
if (isset($_GET["sendtofriend"])) {
    $add = "<script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js\" type=\"text/javascript\">
            </script>      <script type=\"text/javascript\" src=\"" . $this->Html->url("/") . "fancybox/jquery.fancybox-1.3.2.js\"></script>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->Html->url("/") . "fancybox/jquery.fancybox-1.3.2.css\" media=\"screen\" />
            <script type=\"text/javascript\">
                $(document).ready(function() {
                $('body').append($('<a id=\"sendtofsendtof\" href=\"" . $this->Html->url("/forms/sendtof/" . $id) . "\" class=\"modal\">a</a>').hide());
                    $(\".modal\").fancybox({
               'width' : 600,
'height' : 375,
                        'autoScale'     	: false,
                        'transitionIn'		: 'none',
                        'transitionOut'		: 'none',
                        'type'				: 'iframe'
                    });
               $(\"#sendtofsendtof\").click();
                });
            </script>";
}
if ($pos == false) {
    echo $add;
}

echo str_ireplace("</head", $add . "</head", $content);
?>