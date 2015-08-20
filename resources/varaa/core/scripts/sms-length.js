var charset7bit = ['@', '£', '$', '¥', 'è', 'é', 'ù', 'ì', 'ò', 'Ç', "\n", 'Ø', 'ø', "\r", 'Å', 'å', 'Δ', '_', 'Φ', 'Γ', 'Λ', 'Ω', 'Π', 'Ψ', 'Σ', 'Θ', 'Ξ', 'Æ', 'æ', 'ß', 'É', ' ', '!', '"', '#', '¤', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?', '¡', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ä', 'Ö', 'Ñ', 'Ü', '§', '¿', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ä', 'ö', 'ñ', 'ü', 'à'];
var charset7bitext = ["\f", '^', '{', '}', '\\', '[', '~', ']', '|', '€'];
var udh_template = '<span class="udh"><span class="udh-total-length" title="UDH length">0x05</span><span class="udh-multipart-header" title="UDH header - Multipart SMS">0x00</span><span class="udh-multipart-length" title="Multipart SMS - UDH length">0x03</span><span class="udh-multipart-id" title="Multipart SMS - Unique ID">0x0a</span><span class="udh-total" title="Multipart SMS - Total number of parts"></span><span class="udh-part" title="Multipart SMS - Part order"></span></span>';

function charToSpan(chr, coding) {
    ret = '';
    clss = '';
    if (coding == '7bit') {
        clss = 'c7b';
        if (charset7bitext.indexOf(chr) >= 0) {
            ret = '<span class="c7eb">ESC</span>';
        }
    }
    if (coding == '16bit') {
        clss = 'c16b';
        if (charset7bit.indexOf(chr) == -1 && charset7bitext.indexOf(chr) == -1) {
            clss = clss + ' c16bf';
        }
    }
    if (chr == ' ') return ret + '<span class="' + clss + '">&nbsp;</span>';
    if (chr == "\n") return ret + '<span class="' + clss + ' c7eb">LF</span>';
    if (chr == "\r") return ret + '<span class="' + clss + ' c7eb">CR</span>';
    return ret + '<span class="' + clss + '">' + chr + '</span>';
}

function getUHDHtml(parts, part) {
    var udh = $(udh_template);
    if (parts < 15) {
        udh.find('.udh-total').html('0x0' + parts.toString(16));
    } else {
        udh.find('.udh-total').html('0x' + parts.toString(16));
    }
    if (part < 15) {
        udh.find('.udh-part').html('0x0' + part.toString(16));
    } else {
        udh.find('.udh-part').html('0x' + part.toString(16));
    }
    return udh;
}

function updateSmsLength() {
    var content = $('#sms-text').val();
    var chars_arr = content.split("");
    var coding = '7bit';
    var parts = 1;
    var part = 1;
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
    $('#sms-parts-container').html('');
    if (coding == '7bit') {
        if (chars_used > 160) {
            var parts = Math.ceil(chars_used / 153);
            var part_chars_used = 7;
            chars_sms = 153;
            var partc = $('<div class="sms-part"></div>');
            partc.append(getUHDHtml(parts, part));
            for (i = 0; i < chars_arr.length; i++) {
                if (part_chars_used + 1 > 160) {
                    part = part + 1;
                    part_chars_used = 7;
                    $('#sms-parts-container').append(partc);
                    var partc = $('<div class="sms-part"></div>');
                    partc.append(getUHDHtml(parts, part));
                }
                if (charset7bitext.indexOf(chars_arr[i]) >= 0 && part_chars_used + 2 > 160) {
                    part = part + 1;
                    part_chars_used = 7;
                    $('#sms-parts-container').append(partc);
                    var partc = $('<div class="sms-part"></div>');
                    partc.append(getUHDHtml(parts, part));
                }
                partc.append($(charToSpan(chars_arr[i], coding)));
                if (charset7bitext.indexOf(chars_arr[i]) == -1) {
                    part_chars_used = part_chars_used + 1;
                } else {
                    part_chars_used = part_chars_used + 2;
                }
            }
        } else {
            chars_sms = 160;
            var partc = $('<div class="sms-part"></div>');
            for (i = 0; i < chars_arr.length; i++) {
                partc.append($(charToSpan(chars_arr[i], coding)));
            }
        }
    } else {
        if (chars_used > 70) {
            var parts = Math.ceil(chars_used / 67);
            var part_chars_used = 3;
            chars_sms = 67;
            var partc = $('<div class="sms-part"></div>');
            partc.append(getUHDHtml(parts, part));
            for (i = 0; i < chars_arr.length; i++) {
                if (part_chars_used + 1 > 70) {
                    part = part + 1;
                    $('#sms-parts-container').append(partc);
                    var partc = $('<div class="sms-part"></div>');
                    partc.append(getUHDHtml(parts, part));
                    part_chars_used = 3;
                }
                partc.append($(charToSpan(chars_arr[i], coding)));
                part_chars_used = part_chars_used + 1;
            }
        } else {
            chars_sms = 70;
            var partc = $('<div class="sms-part"></div>');
            for (i = 0; i < chars_arr.length; i++) {
                partc.append($(charToSpan(chars_arr[i], coding)));
            }
        }
    }
    $('#sms-parts-container').append(partc);
    if (coding == '7bit') {
        $('#smslc-encoding').html('7bit');
    } else {
        $('#smslc-encoding').html('Unicode');
    }
    $('#smslc-parts').html(parts);
    $('#smslc-characters-sms').html(chars_sms);
    $('#smslc-characters').html(chars_used);
}

var updateTimer = null;

$('#sms-text').keyup(function(ev) {
    clearTimeout(updateTimer);
    updateTimer = setTimeout(function() {
        updateSmsLength();
    }, 100);
});
