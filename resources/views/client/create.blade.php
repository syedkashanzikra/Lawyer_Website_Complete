{{ Form::open(['route' => 'client.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}

            </div>
            <div class="form-group col-md-12">
                {{ Form::label('Email', __('Email'), ['class' => 'form-label']) }}
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('password', __('Password'), ['class' => 'form-label']) !!}
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"8", 'autocomplete' => 'new-password'))}}
                <span class="small">{{__('Minimum 8 characters')}}</span>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}


