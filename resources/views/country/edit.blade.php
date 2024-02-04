{{ Form::model($country,['route' => ['country.update',$country->id], 'method' => 'put']) }}
    <div class="modal-body">

        <div class="row">

            <div class="form-group col-md-12">
                {!! Form::label('country', __('Country'), ['class' => 'form-label']) !!}
                {{ Form::text('country', NULL , ['class' => 'form-control ', 'required' => 'required','id'=>'country']) }}
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}
