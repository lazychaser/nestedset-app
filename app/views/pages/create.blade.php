{{ Form::open(array(
    'route' => 'pages.store',
    'class' => 'form-horizontal',
    'method' => 'post',
)) }}

    @include('pages._form', compact('parents'))

    <div class="form-group form-actions">
        <div class="col-lg-10 col-lg-offset-2">
            <button class="btn btn-primary" type="submit" name="save" value="1">Save</button>
            <a href="{{ route('pages.index') }}" class="btn">Cancel</a>
        </div>
    </div>
{{ Form::close() }}