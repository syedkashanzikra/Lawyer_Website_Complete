{{ Form::model($lead, array('route' => array('lead.sources.update', $lead->id), 'method' => 'post')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-label">
            <div class="row gutters-xs">
                @foreach ($sources as $source)
                    <div class="col-12 custom-control custom-checkbox mt-2 mb-2">
                        {{Form::checkbox('sources[]',$source->id,($selected && array_key_exists($source->id,$selected))?true:false, ['class'=>'form-check-input isscheck isscheck_','id'=>'sources_'.$source->id])}}

                        {{ Form::label('sources_'.$source->id, ucfirst($source->name),['class'=>'custom-control-label ml-4 bg-'.$source->color]) }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Add'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}
