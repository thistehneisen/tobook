<?php
$head_script = '<!--Start of Zopim Live Chat Script-->
    <script type="text/javascript">
    window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
    d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
    _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute(\'charset\',\'utf-8\');
    $.src=\'//v2.zopim.com/?1b7hTzZJGpnsLXf2RNKlDaznUTHizLMr\';z.t=+new Date;$.
    type=\'text/javascript\';e.parentNode.insertBefore($,e)})(document,\'script\');
    </script>
    <!--End of Zopim Live Chat Script-->

    <!-- myTips install -->
    <script type="text/javascript">
    !function(m,y,T,i,p,s){m.myTips=function(onReadyHandler){var l=m.myTips,n=\'onReadyStack\',h=onReadyHandler;(l[n]||(l[n]=[]))&& typeof h===\'function\'&&l[n].push(h)};m.myTips.__={setup:{api_key:\'3d65838b1a3a5171858172736e1e0ddbb64516fa\'},version:\'1.1\'};T=y.createElement(\'script\'),T.type=\'text/javascript\',T.src=\'https://mytips.co/webclient/loader.js\',T.async=!0,y.head.appendChild(T)}(window,document)
    </script>

    <!-- Start of CrazyEgg script -->
    <script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0033/0748.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>
    <!-- End of CrazyEgg script -->

    <script>
  (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');
  ga(\'create\', \'UA-53959606-1\', \'auto\');
  ga(\'send\', \'pageview\');
    </script>';

$bottom_script = "<script type=\"text/javascript\">if(new Date().getTime() < 1414239795000){var fc_CSS=document.createElement(\'link\');fc_CSS.setAttribute(\'rel\',\'stylesheet\');var isSecured = (window.location && window.location.protocol == \'https:\');fc_CSS.setAttribute(\'type\',\'text/css\');fc_CSS.setAttribute(\'href\',((isSecured)? \'https://d36mpcpuzc4ztk.cloudfront.net\':\'http://assets1.chat.freshdesk.com\')+\'/css/visitor.css\');document.getElementsByTagName(\'head\')[0].appendChild(fc_CSS);var fc_JS=document.createElement(\'script\'); fc_JS.type=\'text/javascript\';fc_JS.src=((isSecured)?\'https://d36mpcpuzc4ztk.cloudfront.net\':\'http://assets.chat.freshdesk.com\')+\'/js/visitor.js\';(document.body?document.body:document.getElementsByTagName(\'head\')[0]).appendChild(fc_JS);window.freshchat_setting= \'eyJmY19pZCI6ImEyODllNDc4YWI3NDlhM2ZkNjY4YmE2YTljZTQ2MzIwIiwiYWN0aXZlIjp0cnVlLCJzaG93X29uX3BvcnRhbCI6ZmFsc2UsInBvcnRhbF9sb2dpbl9yZXF1aXJlZCI6ZmFsc2UsInNob3ciOjEsInJlcXVpcmVkIjoyLCJoZWxwZGVza25hbWUiOiJ2YXJhYS5jb20iLCJuYW1lX2xhYmVsIjoiTmFtZSIsIm1haWxfbGFiZWwiOiJFbWFpbCIsInBob25lX2xhYmVsIjoiUGhvbmUgTnVtYmVyIiwidGV4dGZpZWxkX2xhYmVsIjoiVGV4dGZpZWxkIiwiZHJvcGRvd25fbGFiZWwiOiJEcm9wZG93biIsIndlYnVybCI6InZhcmFhLmZyZXNoZGVzay5jb20iLCJub2RldXJsIjoiY2hhdC5mcmVzaGRlc2suY29tIiwiZGVidWciOjEsIm1lIjoiTWUiLCJleHBpcnkiOjE0MTQyMzk3OTUwMDAsImVudmlyb25tZW50IjoicHJvZHVjdGlvbiIsImRlZmF1bHRfd2luZG93X29mZnNldCI6MzAsImRlZmF1bHRfbWF4aW1pemVkX3RpdGxlIjoiQ2hhdCBpbiBwcm9ncmVzcyIsImRlZmF1bHRfbWluaW1pemVkX3RpdGxlIjoiTGV0J3MgdGFsayEiLCJkZWZhdWx0X3RleHRfcGxhY2UiOiJZb3VyIE1lc3NhZ2UiLCJkZWZhdWx0X2Nvbm5lY3RpbmdfbXNnIjoiT2RvdGV0YWFuIGVkdXN0YWphYSIsImRlZmF1bHRfd2VsY29tZV9tZXNzYWdlIjoiSGkhIEhvdyBjYW4gd2UgaGVscCB5b3UgdG9kYXk/IiwiZGVmYXVsdF93YWl0X21lc3NhZ2UiOiJPbmUgb2YgdXMgd2lsbCBiZSB3aXRoIHlvdSByaWdodCBhd2F5LCBwbGVhc2Ugd2FpdC4iLCJkZWZhdWx0X2FnZW50X2pvaW5lZF9tc2ciOiJ7e2FnZW50X25hbWV9fSBoYXMgam9pbmVkIHRoZSBjaGF0IiwiZGVmYXVsdF9hZ2VudF9sZWZ0X21zZyI6Int7YWdlbnRfbmFtZX19IGhhcyBsZWZ0IHRoZSBjaGF0IiwiZGVmYXVsdF90aGFua19tZXNzYWdlIjoiVGhhbmsgeW91IGZvciBjaGF0dGluZyB3aXRoIHVzLiBJZiB5b3UgaGF2ZSBhZGRpdGlvbmFsIHF1ZXN0aW9ucywgZmVlbCBmcmVlIHRvIHBpbmcgdXMhIiwiZGVmYXVsdF9ub25fYXZhaWxhYmlsaXR5X21lc3NhZ2UiOiJXZSBhcmUgc29ycnksIGFsbCBvdXIgYWdlbnRzIGFyZSBidXN5LiBQbGVhc2UgIyBsZWF2ZSB1cyBhIG1lc3NhZ2UgIyBhbmQgd2UnbGwgZ2V0IGJhY2sgdG8geW91IHJpZ2h0IGF3YXkuIiwiZGVmYXVsdF9wcmVjaGF0X21lc3NhZ2UiOiJXZSBjYW4ndCB3YWl0IHRvIHRhbGsgdG8geW91LiBCdXQgZmlyc3QsIHBsZWFzZSB0YWtlIGEgY291cGxlIG9mIG1vbWVudHMgdG8gdGVsbCB1cyBhIGJpdCBhYm91dCB5b3Vyc2VsZi4ifQ==\';}</script>";

return [
    'languages'        => ['fi', 'sv', 'en'],
    'default_language' => 'fi',
    'default_coords'   => [60.1733244, 24.9410248], // Helsinki
    'settings'         => [
        'head_script'      => ['type' => 'Textarea', 'default' => $head_script],
        'bottom_script'    => ['type' => 'Textarea', 'default' => $bottom_script],
    ]
];
