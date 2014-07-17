<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add parameter values to the external boxes								                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/config.php"; 

$action 	= $_GET['action'];

if($action != '') {
    ?>
<script type="text/javascript" >
    $(document).ready(function(){

        $('#jqeditorviewpagecontainer').load('editor/editor_pagemanagementforms.php?action=<?php echo $action;?>');

        // function to load the page options
        $(".jqloadPage").live('click',(function() {  

        	$(".jqloadPage").removeClass('linkactive');
    		$(this).addClass('linkactive');
            var action	=$(this).attr('id');
            $('#jqpageeditorresult').html('');
            $('#jqeditorviewpagecontainer').load('editor/editor_pagemanagementforms.php?action='+action);
            return false;
        }));


        // function to add new page
        //$("#btn_editor_addpage").live('click',(function() {
        $('#btn_editor_addpage').die('click').live('click', function () {

            $.ajax({
                url: "editor/editor_pagemanagementformprocessor.php?type=1",
                type: "POST",
                data: $("#frmeditoraddnewpage").serialize(),
                cache: false,
                dataType:'html',
                success: function(html) { //alert(html);

                    var resval = html.split(/~/);
                    if(resval[0] == 'success')	{
                        page = resval[1];

                    //    $("#jqsiteeditor").slideUp("slow", function(){
                            $("#jqsiteeditor").load('editor_pageloader.php', function() {
                               // $(this).slideDown("slow");
                            });
                       // });
                        //$('#jqsiteeditor').load('editor_pageloader.php');
                        $('#jqsitepagelist').load('editor/editor_ajaxcallresult.php?type=pagelist');
                        $('#opendialogbox').dialog('close');
                        setTimeout(function(){parent.window.location.reload();},1000);
                    }
                    else if(resval[0] == 'editsuccess'){

                        $('#jqpageeditorresult').html('<span class="msggreen">Successfully updated the page</span>');
                        $('#txtaddpagename').val('');
                        $('#txtaddpagelink').val('');

                        $('#jqsitepagelist').load('editor/editor_ajaxcallresult.php?type=pagelist');
                        setTimeout(function(){parent.window.location.reload();},1000);
                    } else if(resval[0] == 'failure'){
                        $('#jqpageeditorresult').html('<span class="msgred">'+resval[1]+'</span>');
                        return false;
                    }
                    
                    
                }
            });
            //setTimeout(function(){parent.window.location.reload();},1000);
        });



        // function to edit the page details
        $(".editpagedetails").live('click',(function() {

            var pageid		= $(this).attr('id');
            //var menuname 	= $('#txtmenuname').val();
            $('#jqeditorviewpagecontainer').load('editor/editor_pagemanagementforms.php?action=addpage&page='+pageid+'&type=edit');
            return false;
        }));


        /*
          // function to delete the page
          $(".deletepage").live('click',(function() {
                  alert('helo');
                        return false;
                  var addassociateID = $(this).attr("id");
                    var $this = $(this);
                    $this.die('click');
                    alert(addassociateID);
                        if (confirm('Are you sure to remove the page')) {
                                var pageid		=$(this).attr('id');

                                $('#pageid_'+pageid).hide();
                                $('#jqpageeditorresult').load('editor/editor_pagemanagementformprocessor.php?type=2&page='+pageid);
                                return false;
                        }
                        return false;

          }));

         */


    });

    function deletePage(pageid)
    {
        if (confirm('Are you sure to remove the page')) {
            if(pageid != '') {
                
                $('#pageid_'+pageid).hide();
                $('#jqpageeditorresult').load('editor/editor_pagemanagementformprocessor.php?type=2&page='+pageid);
                $('#jqsitepagelist').load('editor/editor_ajaxcallresult.php?type=pagelist');
            }
        }
        return false;
    }


</script>

<div class="editpanel_popuptabs"><a href="" class="jqloadPage" id="addpage"  >Add Page</a> | <a href="" class="jqloadPage" id="viewpage">View Pages</a></div>

<form name="frmeditoraddnewpage" id="frmeditoraddnewpage">
    <div id="jqpageeditorresult"></div>
    <input type="hidden" name="txtpageaction" id="txtpageaction" value="<?php echo $action;?>">
    <div id="jqeditorviewpagecontainer" > </div>
</form>

    <?php
}
else {
    echo "Error....";
}

?>