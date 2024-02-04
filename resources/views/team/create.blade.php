{{ Form::open(['route' => 'teams.store', 'method' => 'post']) }}
    <div>
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('first_name', __('First Name'), ['class' => 'form-label']) !!}
                {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('last_name', __('Last Name'), ['class' => 'form-label']) !!}
                {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('designation', __('Designation'), ['class' => 'form-label']) !!}
                {!! Form::text('designation', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('mobile_number', __('Mobile Number'), ['class' => 'form-label']) !!}
                {!! Form::text('mobile_number', null, ['class' => 'form-control']) !!}
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}

<script>

</script>
