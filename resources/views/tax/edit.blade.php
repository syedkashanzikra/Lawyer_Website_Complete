{{ Form::model($tax,['route' => ['taxs.update',$tax->id], 'method' => 'PUT']) }}
<div class="modal-body">
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('name', __('Tax Name'),['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control','placeholder' => __('Enter Tax Name'),'required' => 'required']) }}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('rate', __('Rate').__(' (%)'),['class' => 'col-form-label']) }}
            {{ Form::number('rate', null, ['class' => 'form-control','placeholder' => __('Enter Rate'),'required' => 'required']) }}
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
    </div>
</div>
</div>
{{ Form::close() }}
