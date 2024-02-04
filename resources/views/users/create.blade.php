{{ Form::open(['route' => 'users.store', 'method' => 'post']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control','placeholder'=>__('Enter User Name')]) !!}

            </div>
            <div class="form-group col-md-12">
                {{ Form::label('Email', __('Email'), ['class' => 'form-label']) }}
                {!! Form::text('email', null, ['class' => 'form-control','placeholder'=>__('Enter User Email')]) !!}

            </div>

            @if (Auth::user()->type == 'company')
                <div class="form-group col-md-12">
                    {{ Form::label('role', __('Role'), ['class' => 'form-label']) }}
                    {!! Form::select('role', $roles, null, ['class' => 'form-control multi-select']) !!}
                </div>
            @endif

            <div class="form-group col-md-12">
                {!! Form::label('password', __('Password'), ['class' => 'form-label']) !!}
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"8"))}}
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}


