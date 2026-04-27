@php
    $standalone = (bool) ($standalone ?? false);
    $selectedPerPage = strtolower((string) request('per_page', '10'));
    if (!in_array($selectedPerPage, ['10', '50', 'all'], true)) {
        $selectedPerPage = '10';
    }
@endphp

@if($standalone)
<form method="GET" action="{{ url()->current() }}" class="{{ $class ?? 'd-flex align-items-center gap-2 flex-wrap' }}">
    @foreach(request()->except('per_page') as $key => $value)
        @if(is_array($value))
            @foreach($value as $item)
                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
@endif

<label class="mb-0 text-muted" style="font-size:.78rem; white-space:nowrap;">Tampilkan</label>
<select name="per_page" class="form-select form-select-sm" style="width:96px;" onchange="this.form.submit()">
    <option value="10" {{ $selectedPerPage === '10' ? 'selected' : '' }}>10</option>
    <option value="50" {{ $selectedPerPage === '50' ? 'selected' : '' }}>50</option>
    <option value="all" {{ $selectedPerPage === 'all' ? 'selected' : '' }}>All</option>
</select>

@if($standalone)
</form>
@endif
