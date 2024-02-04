{{ Form::model($deal, ['route' => ['deal.discussion.store', $deal->id], 'method' => 'POST']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('comment', __('Message'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('comment', null, ['class' => 'form-control', 'rows' => '3']) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Add'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
