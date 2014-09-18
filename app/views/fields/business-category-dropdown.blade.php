<select name="business_category_id" id="business_category_id" class="form-control">
    @foreach ($businessCategories as $category)
        <optgroup label="{{ $category->name }}">
        @foreach ($category->children as $sub)
            <option value="{{ $sub->id }}" {{ Input::get('business_category_id', $item->business_category_id) === $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
        @endforeach
        </optgroup>
    @endforeach
</select>
