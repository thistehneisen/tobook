@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/webuploader/0.1.1/webuploader.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/webuploader/0.1.1/webuploader.min.js') }}

    <script>
$(function () {
    'use strict';
    var uploader = WebUploader.create({
        pick: {
            id: '#file-picker',
            label: 'Select files'
        },
        dnd: '#images-uploader .queue-list',
        paste: document.body,
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },

        swf: '//cdnjs.cloudflare.com/ajax/libs/webuploader/0.1.1/Uploader.swf',
        disableGlobalDnd: true,
        chunked: true,
        server: 'http://2betop.net/fileupload.php',
        fileNumLimit: 300,
        fileSizeLimit: 5 * 1024 * 1024,    // 200 M
        fileSingleSizeLimit: 1 * 1024 * 1024    // 50 M
    });

    uploader.on('fileQueued', function (file) {
        var tpl = $('<li><div class="overlay"><i class="fa fa-trash-o fa-3x"></i></div> <img src="" alt="" class="img-rounded"></li>');
        // Create thumbnail
        uploader.makeThumb(file, function (error, src) {
            if (error) {
                return;
            }

            tpl.find('img').attr('src', src);
            tpl.attr('id', file.id);
            $('#images-uploader').find('.varaa-thumbnails').append(tpl);
        }, 100, 100);
    }).on('uploadSuccess', function(file, res) {
        $('#'+file.id).find('i')
            .removeClass('fa-spinner fa-spin')
            .addClass('fa-check-circle');
    });

    $('#images-uploader').find('.btn-upload').on('click', function() {
        uploader.upload();
        $('#images-uploader').find('.varaa-thumbnails i').removeClass('fa-trash-o').addClass('fa-spinner fa-spin');
        $('#images-uploader').find('.varaa-thumbnails .overlay').addClass('shown');
    });
});
    </script>
@stop

<div id="images-uploader" class="varaa-uploader">
    <div class="queue-list">
        <div id="dnd-area" class="dnd-area text-center">
            <p><i class="fa fa-5x fa-image"></i></p>
            <div id="file-picker"></div>
        </div>

        <ul class="varaa-thumbnails"></ul>
        <div class="clearfix"></div>
    </div>
    <div class="btn-upload"><i class="fa fa-upload"></i> Upload</div>
</div>
