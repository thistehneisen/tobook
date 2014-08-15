<html>
<head>
	<title>Sample CKEditor Site</title>
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript">
	 $(document).ready(function(){
	
		CKEDITOR.replace( 'editor1',
        {
            
            toolbar :
            [
                ['newplugin','footnote','Bold','Italic','Underline', '-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-', 'NumberedList','BulletedList','-','Outdent','Indent'],
                ['Link','Unlink','Anchor','Image', '-', 'Format','Font','FontSize', 'TextColor']
            ],
            
            filebrowserBrowseUrl : 'http://localhost/ckeditor/ckeditor/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'http://localhost/ckeditor/ckeditor/ckfinder/ckfinder.html?Type=Images',
            filebrowserUploadUrl : 'http://localhost/ckeditor/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'http://localhost/ckeditor/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
            });

     CKEDITOR.config.width = 700;
     CKEDITOR.config.height = 150;
     CKEDITOR.on( 'dialogDefinition', function( ev ){

                // Take the dialog name and its definition from the event data.
		var dialogName = ev.data.name;
		var dialogDefinition = ev.data.definition;
		// Check if the definition is from the dialog we're
		// interested on (the Link dialog).
		if ( dialogName == 'link' ){
			// FCKConfig.LinkDlgHideAdvanced = true
                        dialogDefinition.removeContents('advanced');

                        /*
                        var definition = ev.data.definition;
                        var content = definition.getContents( 'target' );
                        content.remove( 'linkTargetType' );
                        */
                        var info = dialogDefinition.getContents( 'info' );
                        info.remove('protocol');
                        

                        info.add( {
					type : 'text',
					label : 'Protocol',
					id : 'protocol',
					'default' : 'http://',
					validate : function()
					{
						if ( /\d/.test( this.getValue() ) )
							return 'Protocol is must';
					}
				});

			// FCKConfig.LinkDlgHideTarget = true
			//dialogDefinition.removeContents( 'default' );
                         /*Enable this part only if you don't remove the 'target' tab in the previous block.*/
			// FCKConfig.DefaultLinkTarget = '_blank'
			// Get a reference to the "Target" tab.
			var targetTab = dialogDefinition.getContents( 'target' );

                        /*
                        var cdrt  =   targetTab.get('linkTargetType');
                        cdrt.remove('_blank');
                        */

                        //targetTab.remove('PopupWindowFeatures');
                        //targetTab.remove('linkTargetType');

                        //$("#cke_81_select option[value='_blank']").remove();
                       


                     
                        targetTab.add( {
					type : 'select',
                                        id : 'cke_81_select',
                                        label : 'Target',
                                        items : [ [ 'New Window', '_blank' ], [ 'Same Window', '_self' ] ],
                                        'default' : 'Football',
                                        onChange : function( api ) {
                                        //this = CKEDITOR.ui.dialog.select
                                        alert( 'Current value: ' + this.getValue() );
                                        }
                                       });
                      /*
                        targetTab.add( {
					type : 'radio',
					label : 'Link Type',
					id : 'cke_81_select',
					items   :   [ [ 'New Window', '_blank' ], [ 'Same Window', '_self' ] ]
				});
                       */
                            targetTab.add( {
					type : 'file',
                                        id : 'upload',
                                        label : 'Select file from your computer',
                                        size : 38

				});
                        targetTab.add( {
					type : 'text',
					label : 'My Custom Field',
					id : 'customField',
					'default' : 'added new!',
					validate : function()
					{
						if ( /\d/.test( this.getValue() ) )
							return 'My Custom Field must not contain digits';
					}
				});


			// Set the default value for the URL field.
			//var targetField = targetTab.get( 'linkTargetType' );
                        
			//targetField[ 'default' ] = '_blank';
		}
		if ( dialogName == 'image' ){
			
			dialogDefinition.removeContents( 'advanced' );
                        var info        =   dialogDefinition.getContents( 'info' );

                        var brwse       =   info.get('browse');
                        brwse.label     =   'Browse';

                        var alttxt      =   info.get('txtAlt');
                        alttxt.label    =   'Image Title';
                        
                        var wdth        =   info.get('txtWidth');
                        wdth.label      =   'Image Width';
                        wdth.width      =   60;
                        
                        var hght = info.get('txtHeight');
                        hght.label  =   'Image Height';
                        hght.width  =   60;

                        var valg = info.get('cmbAlign');
                        valg.items = [['left','left'],['right','right']];
                        valg['default'] =   'left';

                        var Link        =   dialogDefinition.getContents( 'Link' );
                        var Url         =   Link.get('txtUrl');
                        Url.label       =   'Make Image Clickable Link';
                        Url['default']  =   'http://';
                        Link.remove('browse');

                        var target       =   Link.get( 'cmbTarget' );
                        target.label     =   '<br />Target Page';
                        target.items     =   [['New Window','_blank'],['Same Window','_self']];

                        var Upload       =   dialogDefinition.getContents( 'Upload' );
                        var upload       =   Upload.get('uploadButton');
                        upload.label     =   'Upload Image';

                       
		}
		/*if ( dialogName == 'flash' )
		{
			// FCKConfig.FlashDlgHideAdvanced = true
			dialogDefinition.removeContents( 'advanced' );
		}*/
	});

       
	});
        </script>

        <script type="text/javascript">
            function submitFunction()
            {
                //var editor_data = CKEDITOR.instances.editor1;
                var editor_data = CKEDITOR.instances.editor1.getData();
                alert(editor_data);
                CKEDITOR.instances.editor1.setData('<p>Some other editor data.Its been set to the editor..</p>');
                //alert(document.getElementById('editor1').value);
                return false;
            }
</script>
</head>
<body>
	<form method="post">
		<p>
			My Editor:<br />
			<textarea id="editor1" name="editor1">&lt;p&gt;Initial value.&lt;/p&gt;</textarea>
			
		</p>
		<p>
			<input type="button" value="Submit" onClick="submitFunction();" />
		</p>
	</form>

    <div id="simple_div" style="display: none"> PDF Upload </div>
</body>
</html>