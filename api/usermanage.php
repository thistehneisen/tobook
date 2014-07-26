<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+

include('server_class.php');
// Section 1: Info retreival
if (isset($HTTP_RAW_POST_DATA)) {
 $request_xml = $HTTP_RAW_POST_DATA;
}
else {
 $request_xml = implode("\r\n", file('php://input'));
}

// Section 2: Create Server
$x = new xml_server();

// Section 3: parse XML
if ($x) {
 $success = $x->parse_xml($request_xml);
 }
else {
 $x->errno = "100";
}

// Section 4: generate XML response
$results = $x->generate_xml();

// Section 5: send XML response
print $results;
?>