{{ Form::model($motion,['route' => ['motions.update',$motion->id], 'method' => 'put', 'enctype' => 'multipart/form-data']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('type', __('Type'), ['class' => 'form-label']) !!}
                {!! Form::text('type', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('description', __('Description'), ['class' => 'form-label']) !!}
                {!! Form::textarea('description', null, ['class' => 'form-control','rows'=>'3','maxlength'=>"150"]) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}

