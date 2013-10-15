<table class="table table-bordered table-hover">
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
            <td>{{ str_repeat('<span class="space"></span>', $item->depth).e($item->title) }}</td>
            <td>
            @if ($item->slug)
                <a href="{{ route('page', array('slug' => $item->slug)) }}">{{ $item->slug }}</a>
            @endif
            </td>
            <td>{{ $item->updated_at }}</td>
            <td>
                <a href="{{ route('pages.edit', array('pages' => $item->id)) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>

            @if (!$item->isRoot())
                <button class="btn btn-link" type="submit" form="destroy{{ $item->id }}" title="Destroy">
                    <span class="glyphicon glyphicon-trash"></span>
                </button>

                {{ Form::open(array(
                    'route' => array('pages.destroy', 'pages' => $item->id), 
                    'method' => 'delete',
                    'id' => 'destroy'.$item->id,
                )) }}
                {{ Form::close() }}
            @endif
            </td>
        </tr>
    @endforeach
@else
        <tr><td colspan="5" class="text-info">No items found.</td></tr>
@endif
    </tbody>

    <tfoot>
        <tr>
            <td colspan="5" class="text-center"><a href="{{ route('pages.create') }}"><i class="icon-plus"></i> Create a page</a></td>
        </tr>
    </tfoot>
</table>