
{{ Form::open(array('route' => ['lead.email.store',$lead->id])) }}
<div class="modal-body">
    <div class="row"> 
        <div class="form-label">
            {{ Form::label('to', __('Mail To'),['class' => 'col-form-label']) }}
            {{ Form::email('to', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-label">
            {{ Form::label('subject', __('Subject'),['class' => 'col-form-label']) }}
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-label">
            {{ Form::label('description', __('Description'),['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, array('class' => 'form-control','rows'=>3)) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Add'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}
</div>