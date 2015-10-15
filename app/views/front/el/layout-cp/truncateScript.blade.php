     <script>
$(function () {
    var countPs = function (element) {
      var cnt = 0;
      $(element).children().each(function(){
        cnt += this.tagName === 'P' ? 1 : countPs(this);
      })
      return cnt;
    }

    var countRows = function () {
        var lineHeight = $('div.description').css('line-height')
        lineHeight = parseFloat(lineHeight)
        var height = $('div.description').height()
        var rows = height / lineHeight;
        return rows;
    }

    var ps = countPs($('div.description'));
    var originalContent = $('div.description').html();
    var truncateDescription = function() {
        var originalContent = $('div.description').html();
        if (ps > 1) {
            var firstP = $("p", $('div.description')).first().text();
            $('div.description').find("p").remove();
            $('div.description').prepend(firstP);
            $('a.readmore').show();
        }
       
        var rows = countRows();
        if (rows > 3) {
            $('a.readmore').show();
            $("#business-description").dotdotdot({
                ellipsis : '',
                height   : 60,
                after    : "a.readmore",
            });
        }
       
    }
    var showMore = function(event) {
        event.preventDefault();
        var rows = countRows();
        if (ps == 1) {
            var content = $("#business-description").triggerHandler("originalContent");
            $("#business-description").trigger("destroy");
            $("#business-description").html(content);
        } else {
            $('div.description').html(originalContent);
        }
        $('a.readmore').remove();
        $('div.description > p').last().append(" ");
        var showmore = $('<a/>', { class: 'readmore', href: '#business-description'}).appendTo($('div.description > p').last());
        $('<i/>', { class : 'fa fa-caret-up'}).appendTo(showmore);
        $('a.readmore').on('click', showLess);
    }

    var showLess = function(event) {
        event.preventDefault();
        truncateDescription();
        $('a.readmore').remove();
        var showmore = $('<a/>', { class: 'readmore', href: '#business-description'}).appendTo($('div.description'));
        $('<i/>', { class : 'fa fa-caret-down'}).appendTo(showmore);
        $('a.readmore').on('click', showMore);
    }

    $('a.readmore').on('click', showMore);

    $(document).ready(function(){
        truncateDescription();
    });
});

    </script>