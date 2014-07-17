<?php
    function logToFile($filename, $msg)
    {
        // open file
        $fd = fopen($filename, "a");
        // append date/time to message
        $str = "[" . date("Y/m/d h:i:s", time()) . "] " . $msg;
        // write string
        fwrite($fd, $str . "\n");
        // close file
        fclose($fd);
    }
    function valid_set(){
        $sql="select *
              from 
              where 
        
            ";
    }
?>