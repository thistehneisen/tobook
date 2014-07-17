<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                            |
// |                                                                                                            |
// +----------------------------------------------------------------------+
?>
<?php
// include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
$_SESSION['edittype']="usergallery";
$_SESSION['currentimage']="";
$_SESSION["session_checkid"]="";

// manage uploaded picture
        if ($_GET["act"] == "post") {
                                 if(!validateSizePerUser($_SESSION["session_userid"],$size_per_user,$allowed_space)) {
                                           $message = "The space taken by you has exceeded the allowed limit.<br>(Space taken by you: " . human_read($size_per_user) . " <br>Allowed space: " . human_read($allowed_space) . ")<br>Delete
                                                                           unused images or any/all of the sites created by you to proceed further.<br>&nbsp;";
                             }        else{



                   $saveimage="0";
                    if($_SESSION["session_checkid1"] >= $_POST["checkid"]) {
                                           ;
                                        }
                                        else {
                                                 $_SESSION["session_checkid1"]=$_POST["checkid"];
                                                 $saveimage="1" ;
                                        }

                                        $message = "";
                                        $type = ImageType($_FILES['imagefile']['tmp_name']);
                                        $type = substr($type, 0, strpos($type, ':'));

                                        if (($type != "jpg") && ($type != "png") && ($type != "gif")) {

                                                $message = "Only JPG,GIF and PNG Formats are allowed" ;

                                        } else {
                                                $usergal="./usergallery/" . $_SESSION["session_userid"] . "/images";
                                                if(!is_dir($usergal)){

                                                        @mkdir("./usergallery/" . $_SESSION["session_userid"],0777);
														@chmod("./usergallery/" . $_SESSION["session_userid"],0777);
                                                        @mkdir($usergal,0777);
														@chmod($usergal,0777);

                                                }
                                                if($saveimage=="1"){
                                                $assignedname = "ug_".$_SESSION["session_userid"] . "_" . time() . "." . $type;
                                                chmod("./usergallery/" . $_SESSION["session_userid"] . "/images", 0777);
                                                move_uploaded_file($_FILES['imagefile']['tmp_name'], "./usergallery/" . $_SESSION["session_userid"] . "/images/" . $assignedname);
                                                chmod("./usergallery/" . $_SESSION["session_userid"] . "/images", 0755);

                                                }

                                                $message = "File uploaded successfully" ;

                                        }
                        }

        }else if($_GET["del"] == "no"){

                $message = "File could not be deleted since it is in use!" ;

        }else if($_GET["del"] == "yes"){

                $message = "File deleted successfully!" ;

        }

// get the gallery directory
        $dir = "./usergallery/" . $_SESSION["session_userid"] . "/flash/";
// function to get the total number of pages
// for a set of files in a directory

        function getTotal($total)
        {
                $total_pages = (integer)($total / 10);
                $reminder = $total % 10;

                if ($reminder > 0) $total_pages++;
                return $total_pages;
        }
        // function to generate the tr for  each display

        function displayImages($file, $filecount)
        {


                global $dir;
                global $curpage;
                return "<tr><td class=maintext width=10%>&nbsp;</td><td width=30%><fieldset style=width:100px><br>



        <EMBED pluginspage=http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash src=./".$dir."/".$file." type=application/x-shockwave-flash>
</EMBED></fieldset></td><td align=center width=30%></td><td align=center  width=30%><a href='#' class=toplinks onclick='javascript:confirmDelete(\"$file\",\"$curpage\")';>Delete</a></td></tr><tr bgcolor=#ffffff><td width=100% colspan=4><img src=images/xyz.jpg height=1px; width=0px;></td></tr>";
        }


// Open the directory, and proceed to read its contents
        $filearray = array();
        if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                        $filecount = 0;
                        while (($file = readdir($dh)) !== false) {
                                if (($file != ".") && ($file != "..") && ($file != "thumbimages") && ($file != "Thumbs.db")) {
                                        $filecount++;
                                        // store file names in array
                                        $filearray[] = $file;
                                }
                        }
                        closedir($dh);
                }
        }

        // alphabetaically sort the array
        sort($filearray, 2);

        // get the current page
        // if current page is not set set it to 1
        $curpage = $_GET["curpage"];
        if ($curpage == "") {
                $curpage = 1;
        }

        // get total number of pages
        $totalpages = getTotal(count($filearray));

        // get the stating value of the array to be displayed 10 at a time
        $startval = 0;
        $startval = ((integer)($curpage-1) * 10) + 1;

        // get the ending value of the array to be displayed 10 at a time
        $endval = 0;


        if ($totalpages == 0) {

                $endval = 0;
                $message = "Sorry! You have no flash files in your gallery.";

        } elseif ($curpage < $totalpages && $totalpages > 0) {

                $endval = $startval + 10;

        } elseif ($curpage == $totalpages) {

                $endval = $startval + ($filecount - (($totalpages-1) * 10));

        } elseif ($curpage > $totalpages) {

                header("location:manageflash.php?curpage=" . $totalpages);

        }

        include "includes/userheader.php";

        // print the sorted files
        $data = "<br><table border=0 width=80%><tr><td colspan=4 width=100% class=maintextbold><img src='images/manageflashfiles.gif' ></td></tr><tr><td colspan=4 width=100% class=redtext>$message</td></tr><tr><td colspan=4 width=100% class=redtext>&nbsp;</td></tr>";

        if (is_dir($dir)) {

                // echo array contents
                for($i = $startval; $i < $endval ; $i++) {

                        $data .= displayImages($filearray[$i-1], $filecount);
                }

                $data .= "<tr><td align=left>&nbsp;";

                // generate the previous page link
                if ($curpage > 1) {

                        $previouspage = ((integer)$curpage)-1;
                        $data .= "<a href=manageflash.php?curpage=" . $previouspage . " class=maintextbold>Previous</a>";

                }

                $data .= "</td><td>&nbsp;</td><td>&nbsp;</td><td align=right>&nbsp;";
                // generate the next page link
                if ($totalpages > $curpage) {

                        $nextpage = ((integer)$curpage) + 1;
                        $data .= "<a href=manageflash.php?curpage=" . $nextpage . " class=maintextbold>Next</a>";

                }

                $data .= "</td></tr>";

        }

        $data .= "</table><br>&nbsp;";

        echo "$data";

?>

<br>
<br>
<script>
function validate(){
         if(document.imgForm.imagefile.value==""){

         alert("Please select a file");

         }else{

         document.imgForm.submit();

         }

}
</script>

<form name=imgForm  method=post action="">
<a class=maintext href="gallerymanager.php"><b>Manage Images</b></a><br><br>

<br><br><br>&nbsp;
<input type="hidden" name="checkid" id="checkid" value="<?php echo($_SESSION["session_checkid1"]); ?>">
</form>
<?php
  include "includes/userfooter.php";
?>
<script>
       if(isNaN(document.all("checkid").value) || document.all("checkid").value.length <= 0 || (parseInt(document.all("checkid").value) <= 0)) {
                        document.all("checkid").value=1;
                }
                else {
                        document.all("checkid").value = parseInt(document.all("checkid").value) + 1;
                }

function confirmDelete(filename,currentpage){
	frm = document.imgForm;
	if(confirm("Are you sure you want to delete this file?")){
		frm.action = 'deleteflash.php?fname='+filename+'&page='+currentpage;
		frm.submit();
	}else{
	;
	}
	
	//frm.action = 'deleteflash.php?fname='+filename+'&page='+currentpage;
	//frm.submit;
}
</script>