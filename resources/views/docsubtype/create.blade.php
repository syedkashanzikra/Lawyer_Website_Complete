{{ Form::open(['route' => 'doctsubype.store', 'method' => 'post']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('name', __('Type'), ['class' => 'form-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('doctype_id', __('Doc Type'), ['class' => 'form-label']) !!}
                {!! Form::select('doctype_id', $doctypes, null, ['class' => 'form-control multi-select','id'=>'doctype_id']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('description', __('Description'), ['class' => 'form-label']) !!}
                {!! Form::textarea('description', null, ['class' => 'form-control','rows'=>'3','maxlength'=>"150"]) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}
