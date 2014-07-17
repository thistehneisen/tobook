<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
class zipfile   
{   

    var $datasec = array(); // array to store compressed data 
    var $ctrl_dir = array(); // central directory    
    var $eof_ctrl_dir = "x50x4bx05x06x00x00x00x00"; //end of Central directory record 
    var $old_offset = 0; 

    function add_dir($name)    

    // adds "directory" to archive - do this before putting any files in directory! 
    // $name - name of directory... like this: "path/" 
    // ...then you can add files using add_file with names like "path/file.txt" 
    {   
        //$name = str_replace("\\", "/", $name);   

        $fr = "x50x4bx03x04"; 
        $fr .= "x0ax00";    // ver needed to extract 
        $fr .= "x00x00";    // gen purpose bit flag 
        $fr .= "x00x00";    // compression method 
        $fr .= "x00x00x00x00"; // last mod time and date 

        $fr .= pack("V",0); // crc32 
        $fr .= pack("V",0); //compressed filesize 
        $fr .= pack("V",0); //uncompressed filesize 
        $fr .= pack("v", strlen($name) ); //length of pathname 
        $fr .= pack("v", 0 ); //extra field length 
        $fr .= $name;   
        // end of "local file header" segment 

        // no "file data" segment for path 

        // "data descriptor" segment (optional but necessary if archive is not served as file) 
        $fr .= pack("V",$crc); //crc32 
        $fr .= pack("V",$c_len); //compressed filesize 
        $fr .= pack("V",$unc_len); //uncompressed filesize 

        // add this entry to array 
        $this -> datasec[] = $fr; 

        $new_offset = strlen(implode("", $this->datasec)); 

        // ext. file attributes mirrors MS-DOS directory attr byte, detailed 
        // at http://support.microsoft.com/support/kb/articles/Q125/0/19.asp 

        // now add to central record 
        $cdrec = "x50x4bx01x02"; 
        $cdrec .="x00x00";    // version made by 
        $cdrec .="x0ax00";    // version needed to extract 
        $cdrec .="x00x00";    // gen purpose bit flag 
        $cdrec .="x00x00";    // compression method 
        $cdrec .="x00x00x00x00"; // last mod time & date 
        $cdrec .= pack("V",0); // crc32 
        $cdrec .= pack("V",0); //compressed filesize 
        $cdrec .= pack("V",0); //uncompressed filesize 
        $cdrec .= pack("v", strlen($name) ); //length of filename 
        $cdrec .= pack("v", 0 ); //extra field length    
        $cdrec .= pack("v", 0 ); //file comment length 
        $cdrec .= pack("v", 0 ); //disk number start 
        $cdrec .= pack("v", 0 ); //internal file attributes 
        $ext = "x00x00x10x00"; 
        $ext = "xffxffxffxff";   
        $cdrec .= pack("V", 16 ); //external file attributes  - 'directory' bit set 

        $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header 
        $this -> old_offset = $new_offset; 

        $cdrec .= $name;   
        // optional extra field, file comment goes here 
        // save to array 
        $this -> ctrl_dir[] = $cdrec;   

          
    } 


    function add_file($data, $name)    

    // adds "file" to archive    
    // $data - file contents 
    // $name - name of file in archive. Add path if your want 

    {   
        $name = str_replace("", "/", $name);   
        //$name = str_replace("", "", $name); 

        $fr = "x50x4bx03x04"; 
        $fr .= "x14x00";    // ver needed to extract 
        $fr .= "x00x00";    // gen purpose bit flag 
        $fr .= "x08x00";    // compression method 
        $fr .= "x00x00x00x00"; // last mod time and date 

        $unc_len = strlen($data);   
        $crc = crc32($data);   
        $zdata = gzcompress($data);  
		$zdata = substr( substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug 
        $c_len = strlen($zdata);   
        $fr .= pack("V",$crc); // crc32 
        $fr .= pack("V",$c_len); //compressed filesize 
        $fr .= pack("V",$unc_len); //uncompressed filesize 
        $fr .= pack("v", strlen($name) ); //length of filename 
        $fr .= pack("v", 0 ); //extra field length 
        $fr .= $name;   
        // end of "local file header" segment 
          
        // "file data" segment 
        $fr .= $zdata;   

        // "data descriptor" segment (optional but necessary if archive is not served as file) 
        $fr .= pack("V",$crc); //crc32 
        $fr .= pack("V",$c_len); //compressed filesize 
        $fr .= pack("V",$unc_len); //uncompressed filesize 

        // add this entry to array 
        $this -> datasec[] = $fr; 

        $new_offset = strlen(implode("", $this->datasec)); 

        // now add to central directory record 
        $cdrec = "x50x4bx01x02"; 
        $cdrec .="x00x00";    // version made by 
        $cdrec .="x14x00";    // version needed to extract 
        $cdrec .="x00x00";    // gen purpose bit flag 
        $cdrec .="x08x00";    // compression method 
        $cdrec .="x00x00x00x00"; // last mod time & date 
        $cdrec .= pack("V",$crc); // crc32 
        $cdrec .= pack("V",$c_len); //compressed filesize 
        $cdrec .= pack("V",$unc_len); //uncompressed filesize 
        $cdrec .= pack("v", strlen($name) ); //length of filename 
        $cdrec .= pack("v", 0 ); //extra field length    
        $cdrec .= pack("v", 0 ); //file comment length 
        $cdrec .= pack("v", 0 ); //disk number start 
        $cdrec .= pack("v", 0 ); //internal file attributes 
        $cdrec .= pack("V", 32 ); //external file attributes - 'archive' bit set 

        $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header 
//        echo "old offset is ".$this->old_offset.", new offset is $new_offset<br>"; 
        $this -> old_offset = $new_offset; 

        $cdrec .= $name;   
        // optional extra field, file comment goes here 
        // save to central directory 
		
        $this -> ctrl_dir[] = $cdrec;   
    } 

    function file() { // dump out file    
        $data = implode("", $this -> datasec);   
        $ctrldir = implode("", $this -> ctrl_dir);   
        return    
            $data.   
            $ctrldir.   
            $this -> eof_ctrl_dir.   
            pack("v", sizeof($this -> ctrl_dir)).     // total # of entries "on this disk" 
            pack("v", sizeof($this -> ctrl_dir)).     // total # of entries overall 
            pack("V", strlen($ctrldir)).             // size of central dir 
            pack("V", strlen($data)).                 // offset to start of central dir 
            "x00x00";                             // .zip file comment length 
    } 
}   
$zipTest = new zipfile();
$zipTest->add_dir("73/images/");
$zipTest->add_file("73/images/box1.jpg", "73/images/box1.jpg");
$zipTest->add_file("73/images/box2.jpg", "73/images/box2.jpg");

// Return Zip File to Browser
Header("Content-type: application/octet-stream");
Header ("Content-disposition: attachment; filename=zipTest.zip");
echo $zipTest->file();


 
 /*$zipfile = new zipfile();   

// add the subdirectory ... important! 
$zipfile -> add_dir("73/"); 

// add the binary data stored in the string 'filedata'
$filename = "73/file.txt";
$handle = fopen($filename, "rb");
$filedata = fread($handle, filesize($filename));
fclose($handle);


//$filedata=file_get_contents("73/file.txt");
$filedata = "(read your file into $filedata)";  
//echo "filedata==" .$filedata;
//exit;
$zipfile -> add_file($filedata, "73/file.txt");   

// the next three lines force an immediate download of the zip file: 
/*header("Content-type: application/octet-stream");   
header("Content-disposition: attachment; filename=test.zip");   
echo $zipfile -> file();*/   


// OR instead of doing that, you can write out the file to the loca disk like this: 

/*$filename = "74/output.zip"; 
$fd = fopen ($filename, "wb"); 
echo $zipfile -> file();
$out = fwrite ($fd, $zipfile -> file()); 
fclose ($fd); */

 

 


?> 
