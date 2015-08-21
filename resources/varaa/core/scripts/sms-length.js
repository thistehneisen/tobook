$(function () {
    var charset7bit = ['@', '£', '$', '¥', 'è', 'é', 'ù', 'ì', 'ò', 'Ç', "\n", 'Ø', 'ø', "\r", 'Å', 'å', 'Δ', '_', 'Φ', 'Γ', 'Λ', 'Ω', 'Π', 'Ψ', 'Σ', 'Θ', 'Ξ', 'Æ', 'æ', 'ß', 'É', ' ', '!', '"', '#', '¤', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?', '¡', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ä', 'Ö', 'Ñ', 'Ü', '§', '¿', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ä', 'ö', 'ñ', 'ü', 'à'];
    var charset7bitext = ["\f", '^', '{', '}', '\\', '[', '~', ']', '|', '€'];

    function updateSmsLength(id, maxlength1, maxlength2) {
        var target = $('#'+id);
        var content = target.val();
        var chars_arr = content.split("");
        var coding = '7bit';
        var chars_used = 0;
        var chars_sms = maxlength1;
        for (i = 0; i < chars_arr.length; i++) {
            if (charset7bit.indexOf(chars_arr[i]) >= 0) {
                chars_used = chars_used + 1;
            } else if (charset7bitext.indexOf(chars_arr[i]) >= 0) {
                chars_used = chars_used + 2;
            } else {
                coding = '16bit';
                chars_used = chars_arr.length;
                break;
            }
        }

        if (coding == '7bit') {
            if (chars_used > maxlength1) {
                chars_sms = maxlength1 - 3;
            } else {
                chars_sms = maxlength1;
            }
        } else {
            if (chars_used > maxlength2) {
                chars_sms = maxlength2 - 3;
            } else {
                chars_sms = maxlength2;
            }
        }

        target.attr('maxlength', chars_sms);

        var msg = chars_used + '/' + chars_sms;
        if($('#label-max-length-'+id).length == 0) {
            $('<span>', {
                'text': msg,
                'class': 'label label-success',
                'id' : 'label-max-length-'+id
            }).appendTo($('#lomake-form'));
             $('#label-max-length-'+id).css({
                'display' : 'block',
                'position': 'absolute',
                'white-space':  'nowrap',
                'z-index' : '1099'
             });
            $('#label-max-length-'+id).css('top', target.offset().top + target.height() + 12);
            $('#label-max-length-'+id).css('left', target.offset().left + (target.width() / 2) - 12);
        } else {
            $('#label-max-length-'+id).html(msg);
        }

        if(chars_used >= chars_sms) {
            $('#label-max-length-'+id).removeClass(('label-success')).addClass('label-danger');
            var content = content.split('').splice(0, chars_sms);
            target.val(content.join(''));
        } else {
            $('#label-max-length-'+id).removeClass(('label-danger')).addClass('label-success');
        }
    }

    function setUp(id, maxlength1, maxlength2) {
        $('#'+id).keyup(function(ev) {
            updateSmsLength(id, maxlength1, maxlength2);
        });

        $('#'+id).blur(function(e){
            $('#label-max-length-'+id).hide();
        });

        $('#'+id).focus(function(e){
            $('#label-max-length-'+id).show();
        });
    }
    setUp('content', 160, 70);
    setUp('from_name', 10, 10);
});
