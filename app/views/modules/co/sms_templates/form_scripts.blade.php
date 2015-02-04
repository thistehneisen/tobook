{{ HTML::script(asset('packages/bootstrap-maxlength/bootstrap-maxlength.min.js')) }}

<script>
$(function () {
    $('#content').maxlength({
        alwaysShow: true
    });
});
</script>
