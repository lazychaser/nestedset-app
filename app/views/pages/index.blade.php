<table class="table table-bordered table-hover table-condensed page-list">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Updated at</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
@if (count($pages))
    @foreach ($pages as $item)
        <tr class="item">
            <td>{{ $item->id }}</td>
            <td class="f-title">
                {{ str_repeat('<span class="space"></span>', $item->depth) }}<a href="{{ route('pages.edit', array('pages' => $item->id)) }}">{{{ $item->title}}}{{ HTML::glyphicon('edit') }}</a>
            </td>
            <td>
            @if ($item->slug)
                <a href="{{ route('page', array('slug' => $item->slug)) }}" target="_blanc">{{ $item->slug }}</a>
            @endif
            </td>
            <td class="f-date">{{ $item->updated_at }}</td>
            <td class="f-actions">
            @if ($item->isRoot())
                <a href="{{ URL::route('pages.export') }}" class="btn btn-xs">{{ HTML::glyphicon('floppy-save') }} export</a>
            @else
                <div class="btn-group actions">
                @foreach (array('up', 'down') as $key)
                    <button class="btn btn-xs btn-link" type="submit" title="Move {{$key}}" form="form-post" formaction="{{ URL::route("pages.$key", array($item->id)) }}">
                        {{ HTML::glyphicon("arrow-$key") }}
                    </button>
                @endforeach

                    <a class="btn btn-xs" type="submit" title="Destroy" href="{{ URL::route('pages.confirm', array($item->id)) }}">
                        {{ HTML::glyphicon('trash') }}
                    </a>
                </div>
            @endif
            </td>
        </tr>
    @endforeach
@else
        <tr><td colspan="5" class="text-info text-center">No items found.</td></tr>
@endif
    </tbody>

    <tfoot>
        <tr>
            <td colspan="5" class="text-center"><a href="{{ route('pages.create') }}" class="btn"><i class="icon-plus"></i> Create a page</a></td>
        </tr>
    </tfoot>
</table>

{{-- This form is used for general post requests --}}
{{ Form::open(array('method' => 'post', 'id' => 'form-post')) }}{{ Form::close() }}