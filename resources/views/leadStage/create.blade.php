{{ Form::open(array('url' => 'leadStage')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name') ,['class' => 'col-form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('name', __('Pipeline') ,['class' => 'col-form-label']) }}
            {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control multi-select' ,'id'=>'pipeline_id','data-toggle="select"','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>

{{ Form::close() }}
