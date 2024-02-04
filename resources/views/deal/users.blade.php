{{ Form::model($deal, ['route' => ['deal.users.update', $deal->id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('User'), ['class' => 'col-form-label']) }}
            {{ Form::select('users[]', $users, null, ['class' => 'form-control multi-select', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Add'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}

<script>
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function(index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
</script>
