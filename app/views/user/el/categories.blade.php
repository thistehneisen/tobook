@section ('scripts')
    @parent
    <script>
$(function () {
    $('#categories').find('input.category-checkbox').on('change', function () {
        $('#category-'+this.value).collapse('toggle');
    });
});
    </script>
@stop

<div class="panel-group" id="categories">
@foreach ($categories as $category)
    <div class="checkbox">
        <label data-parent="#categories" href="#category-{{ $category->id }}">
            {{ Form::checkbox('categories[]', $category->id, in_array($category->id, $selectedCategories), ['class' => 'category-checkbox']) }} {{ $category->name }}
        </label>
        <div id="category-{{ $category->id }}" class="panel-collapse collapse {{ in_array($category->id, $selectedCategories) ? 'in' : '' }}">
            <div class="panel-body">
            @foreach ($category->children as $child)
                <div class="checkbox">
                    <label>
                        {{ Form::checkbox('categories[]', $child->id, in_array($child->id, $selectedCategories), ['class' => 'category-checkbox']) }} {{ $child->name }}
                    </label>
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endforeach
</div>
