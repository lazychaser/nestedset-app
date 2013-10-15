{{ Form::model($page, array(
    'route' => array('pages.update', $page->id), 
    'class' => 'form-horizontal',
    'method' => 'patch',
)) }}
    @include('pages._form', compact('parents', 'page'))

    <div class="form-group form-actions">
        <div class="col-lg-10 col-lg-offset-2">
            <button class="btn btn-primary" type="submit" name="save" value="1">Save & return</button>
            <button class="btn btn-default" type="submit" name="apply" value="1">Apply</button>
            <a href="{{ route('pages.index') }}" class="btn">Cancel</a>
        </div>
    </div>
{{ Form::close() }}