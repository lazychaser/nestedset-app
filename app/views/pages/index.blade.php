<p class="alert alert-info">Click on a <em>title</em> to edit a page. Click on a slug to view a page.</p>

<table class="table table-bordered table-hover table-condensed table-pages">
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
        <tr>
            <td>{{ $item->id }}</td>
            <td class="f-title">{{ str_repeat('<span class="space"></span>', $item->depth) }}<a href="{{ route('pages.edit', array('pages' => $item->id)) }}">{{{ $item->title}}}{{ HTML::glyphicon('edit') }}</a></td>
            <td>
            @if ($item->slug)
                <a href="{{ route('page', array('slug' => $item->slug)) }}" target="_blanc">{{ $item->slug }}</a>
            @endif
            </td>
            <td>{{ $item->updated_at }}</td>
            <td class="f-actions">
            @if ($item->isRoot())
                <a href="{{ URL::route('pages.export') }}" class="btn btn-xs">{{ HTML::glyphicon('floppy-save') }} export</a>
            @else
                <div class="btn-group">
                @foreach (array('up', 'down') as $key)
                    <button class="btn btn-xs btn-link" type="submit" title="Destroy" form="form-post" formaction="{{ URL::route("pages.$key", array($item->id)) }}">
                        {{ HTML::glyphicon("arrow-$key") }}
                    </button>
                @endforeach

                    <button class="btn btn-xs btn-link" type="submit" title="Destroy" form="form-delete" formaction="{{ URL::route('pages.destroy', array($item->id)) }}">
                        {{ HTML::glyphicon('trash') }}
                    </button>
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

{{-- This form is used to destroy pages --}}
{{ Form::open(array('method' => 'delete', 'id' => 'form-delete')) }}{{ Form::close() }}