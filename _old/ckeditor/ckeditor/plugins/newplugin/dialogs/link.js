





CKEDITOR.dialog.add( 'myDialog', function( editor )
{
	return {
		title : 'My Dialog',
		minWidth : 400,
		minHeight : 200,
		contents : [
			{
				id : 'tab1',
				label : 'First Tab',
				title : 'First Tab',
				elements :
				[
					{
						id : 'input1',
						type : 'text',
						label : 'Input 1'
					}
				]
			}
		]
	};
} );
















//$(".jqPopupErrorDisplay").html('').css('display', 'none');
 /*
    var popID   = 'simple_div'; //Get Popup Name
    var popURL  = '#?w=650'; //Get Popup href to define size
    
    //Pull Query & Variables from href URL
    var query= popURL.split('?');
    var dim= query[1].split('&');
    var popWidth = dim[0].split('=')[1]; //Gets the first query string value
    //Fade in the Popup and add close button
    $('#' + popID).fadeIn().css({'width': Number( popWidth )});

    //Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
    var popMargTop = ($('#' + popID).height() + 80) / 2;
    var popMargLeft = ($('#' + popID).width() + 80) / 2;

    //Apply Margin to Popup
    $('#' + popID).css({
        'margin-top' : -popMargTop,
        'margin-left' : -popMargLeft
    });

    //Fade in Background
    $('body').append('<div id="fade_page"></div>'); //Add the fade layer to bottom of the body tag.
    $('#fade_page').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies

    return false;

*/






////alert('HRE');
//alert('1');

//(function(){function a(b,c){var d=b.lang.placeholder,e=b.lang.common.generalTab;return{title:d.title,minWidth:300,minHeight:80,contents:[{id:'info',label:e,title:e,elements:[{id:'text',type:'text',style:'width: 100%;',label:d.text,'default':'',required:true,validate:CKEDITOR.dialog.validate.notEmpty(d.textMissing),setup:function(f){if(c)this.setValue(f.getText().slice(2,-2));},commit:function(f){var g='[['+this.getValue()+']]';CKEDITOR.plugins.placeholder.createPlaceholder(b,f,g);}}]}],onShow:function(){if(c)this._element=CKEDITOR.plugins.placeholder.getSelectedPlaceHoder(b);this.setupContent(this._element);},onOk:function(){this.commitContent(this._element);delete this._element;}};};CKEDITOR.dialog.add('createplaceholder',function(b){return a(b);});CKEDITOR.dialog.add('editplaceholder',function(b){return a(b,1);});})();
//alert('2');

/*
(function()
{
    function a(b,c)
    {
        var d=b.lang.placeholder,e=b.lang.common.generalTab;
        return{
            title:d.title,
            minWidth:300,
            minHeight:80,
            contents:[
                {
                    id:'info',
                    label:e,
                    title:e,
                    elements:[
                        {
                            id:'text',
                            type:'text',
                            style:'width: 100%;',
                            label:d.text,
                            'default':'',
                            required:true,
                            validate:CKEDITOR.dialog.validate.notEmpty(d.textMissing),
                            setup:function(f)
                            {
                                if(c)
                                    this.setValue(f.getText().slice(2,-2));
                            },
                            commit:
                                function(f)
                                {
                                    var g='[['+this.getValue()+']]';
                                    CKEDITOR.plugins.placeholder.createPlaceholder(b,f,g);}}]
                                }]
         ,onShow:function()
                {
                    if(c)this._element=CKEDITOR.plugins.placeholder.getSelectedPlaceHoder(b);
                    this.setupContent(this._element);
                }
         ,onOk:function()
                {
                    this.commitContent(this._element);
                    delete this._element;
                }
            };
        };
        
        CKEDITOR.dialog.add('createplaceholder',
                                function(b)
                                {
                                    return a(b);
                                }
                            );
       CKEDITOR.dialog.add('editplaceholder',
                                function(b)
                                {
                                    return a(b,1);
                                }
                            );
 })();
*/
//window.location="http://www.w3schools.com" ;