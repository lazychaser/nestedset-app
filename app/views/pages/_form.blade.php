{{ Form::beginGroup('title') }}
    {{ Form::label('title', 'Title', array('class' => 'col-lg-2 control-label')) }}
    <div class="col-lg-10">
        {{ Form::text('title', null, array('class' => 'form-control')) }}
    </div>
{{ Form::endGroup('title') }}

{{ Form::beginGroup('slug') }}
    {{ Form::label('slug', 'Slug', array('class' => 'col-lg-2 control-label')) }}
    <div class="col-lg-10">
        {{ Form::text('slug', null, array('class' => 'form-control')) }}
        <span class="help-block">This one accepts only letters, numbers, dash and slash, i.e. "docs/installation".</span>
    </div>
{{ Form::endGroup('slug') }}

{{ Form::beginGroup('body') }}
    {{ Form::label('body', 'Body', array('class' => 'col-lg-2 control-label')) }}
    <div class="col-lg-10">
        {{ Form::textarea('body', null, array('class' => 'form-control')) }}
        <span class="help-block">Supports <a href="http://daringfireball.net/projects/markdown/" target="_blank">markdown</a> syntax.</span>
    </div>
{{ Form::endGroup('body') }}

@if (!isset($page) || !$page->isRoot())
{{ Form::beginGroup('parent_id') }}
    {{ Form::label('parent_id', 'Parent', array('class' => 'col-lg-2 control-label')) }}
    <div class="col-lg-10">
        {{ Form::select('parent_id', $parents, null, array('class' => 'form-control')) }}
    </div>
{{ Form::endGroup('parent_id') }}
@endif