@section ('scripts')
    @parent
    {{ HTML::script(asset('packages/sortable/Sortable.js')) }}
    <script>
$(function() {
    var el = document.getElementById('js-crud-tbody');
    new Sortable(el, {
        group: 'crud-body',
        store: {
            get: function(sortable) {
                // PHP will sort this first
                return [];
            },
            set: function(sortable) {
                $.ajax({
                    url: '{{ route($routes['order']) }}',
                    type: 'POST',
                    data: {
                        orders: sortable.toArray()
                    }
                });
            }
        }
    });

    $('#js-crud-tbody').find('tr').tooltip();
});
    </script>
@stop
