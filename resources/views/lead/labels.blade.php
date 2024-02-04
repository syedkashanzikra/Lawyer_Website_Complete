{{ Form::open(array('route' => ['lead.label.store',$lead->id])) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            <div class="row gutters-xs">
                @foreach ($labels as $label)
                    <div class="col-12 custom-control custom-checkbox  mt-2 mb-2">
                        {{ Form::checkbox('labels[]',$label->id,(array_key_exists($label->id,$selected))?true:false,['class' => 'form-check-input','id'=>'labels_'.$label->id]) }}
                        {{ Form::label('labels_'.$label->id, ucfirst($label->name),['class'=>'custom-control-label ml-4 badge fix_badges rounded-pill bg-'.$label->color]) }}
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
