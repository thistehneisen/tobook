$(function () {
    var charset7bit = ['@', '£', '$', '¥', 'è', 'é', 'ù', 'ì', 'ò', 'Ç', "\n", 'Ø', 'ø', "\r", 'Å', 'å', 'Δ', '_', 'Φ', 'Γ', 'Λ', 'Ω', 'Π', 'Ψ', 'Σ', 'Θ', 'Ξ', 'Æ', 'æ', 'ß', 'É', ' ', '!', '"', '#', '¤', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?', '¡', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ä', 'Ö', 'Ñ', 'Ü', '§', '¿', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ä', 'ö', 'ñ', 'ü', 'à'];
    var charset7bitext = ["\f", '^', '{', '}', '\\', '[', '~', ']', '|', '€'];

    function updateSmsLength() {
        var content = $('#content').val();
        var chars_arr = content.split("");
        var coding = '7bit';
        var chars_used = 0;
        var chars_sms = 160;
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
            if (chars_used > 160) {
                chars_sms = 153;
            } else {
                chars_sms = 160;
            }
        } else {
            if (chars_used > 70) {
                chars_sms = 67;
            } else {
                chars_sms = 70;
            }
        }

        $('#content').attr('maxlength', chars_sms);

        var msg = chars_used + '/' + chars_sms;
        if($('#label-max-length').length == 0) {
            $('<span>', {
                'text': msg,
                'class': 'label label-success',
                'id' : 'label-max-length'
            }).appendTo($('#lomake-form'));
             $('#label-max-length').css({
                'display' : 'block',
                'position': 'absolute',
                'white-space':  'nowrap',
                'z-index' : '1099'
             });
            $('#label-max-length').css('top', $('#content').offset().top + $('#content').height() + 12);
            $('#label-max-length').css('left', $('#content').offset().left + ($('#content').width() / 2) - 12);
        } else {
            $('#label-max-length').html(msg);
        }

        if(chars_used >= chars_sms) {
            $('#label-max-length').removeClass(('label-success')).addClass('label-danger');
            var content = content.split('').splice(0, chars_sms);
            $('#content').val(content.join(''));
        } else {
            $('#label-max-length').removeClass(('label-danger')).addClass('label-success');
        }
    }

    var updateTimer = null;

    $('#content').keyup(function(ev) {
        updateSmsLength();
    });

    $('#content').blur(function(e){
        $('#label-max-length').hide();
    });

    $('#content').focus(function(e){
        $('#label-max-length').show();
    });
});
