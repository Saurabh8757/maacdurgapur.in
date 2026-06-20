@include('admin.cms.partials.validation-errors')
<div class="form-group">
    <label for="cms_faq_category_id">Category</label>
    <select class="form-control" id="cms_faq_category_id" name="cms_faq_category_id">
        <option value="">Select a category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) old('cms_faq_category_id', optional($faq ?? null)->cms_faq_category_id) === (string) $category->id)>
                {{ $category->name }}{{ $category->status === 'inactive' ? ' (Inactive)' : '' }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="question">Question</label>
    <input class="form-control" id="question" name="question" value="{{ old('question', optional($faq ?? null)->question) }}">
</div>
<div class="form-group">
    <label for="answer">Answer</label>
    <textarea class="form-control" id="answer" name="answer" rows="7">{{ old('answer', optional($faq ?? null)->answer) }}</textarea>
</div>
<div class="row">
    <div class="col-md-6 form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status">
            @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', optional($faq ?? null)->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 form-group">
        <label for="sort_order">Sort order</label>
        <input class="form-control" type="number" min="0" id="sort_order" name="sort_order" value="{{ old('sort_order', optional($faq ?? null)->sort_order ?? 0) }}">
    </div>
</div>
