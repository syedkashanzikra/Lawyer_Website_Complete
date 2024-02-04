{{ Form::open(['route' => ['deal.email.store', $deal->id]]) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('to', __('Mail To'), ['class' => 'col-form-label']) }}
            {{ Form::email('to', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group">
            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label']) }}
            {{ Form::text('subject', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
