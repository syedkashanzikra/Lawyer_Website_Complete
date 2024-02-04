{{ Form::open(['route' => 'appointments.store', 'method' => 'post']) }}
   <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-6">
                {!! Form::label('title', __('Title'), ['class' => 'form-label']) !!}
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group col-md-6">
                {!! Form::label('contact', __('Contact'), ['class' => 'form-label']) !!}
                {!! Form::select('contact',$contacts, null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                {!! Form::label('motive', __('Motive'), ['class' => 'form-label']) !!}
                {{ Form::textarea('motive', null, ['class' => 'form-control', 'required' => 'required', 'rows' => '1','maxlength'=>"250"]) }}
            </div>

            <div class="form-group col-md-6">
                {!! Form::label('date', __('Date'), ['class' => 'form-label']) !!}
                {!! Form::date('date' ,null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('notes', __('Notes'), ['class' => 'form-label']) !!}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'required' => 'required', 'rows' => '3','maxlength'=>"250"]) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}

