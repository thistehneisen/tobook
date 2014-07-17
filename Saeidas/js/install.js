$(document).ready(function(){
     if($('#auto_set').prop('checked')){
        $('#err_div').slideDown('slow');
    }
    $('#auto_set').click(function(){divToggle(this)});
});

function divToggle(elem)
{
    if($(elem).prop('checked')){
        $('#err_div').slideDown('slow');
    }
    else{
        $('#err_div').slideUp('slow');
    }
}
