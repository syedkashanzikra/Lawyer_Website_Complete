{{Form::open(array('url'=>'permissions','method'=>'post'))}}
<div class="modal-body">
<div class="card-body">
    <div class="form-group">
        {{Form::label('name',__('Name'))}}
        {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Permission Name')))}}
        @error('name')
        <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
        @enderror
    </div>
    <div class="form-group">
        @if(!$roles->isEmpty())
            <h6>{{__('Assign Permission to Roles')}}</h6>
            @foreach ($roles as $role)
                <div class="custom-control custom-checkbox">
                    {{Form::checkbox('roles[]',$role->id,false, ['class'=>'custom-control-input','id' =>'role'.$role->id])}}
                    {{Form::label('role'.$role->id, __(ucfirst($role->name)),['class'=>'custom-control-label '])}}
                </div>
            @endforeach
        @endif
        @error('roles')
        <span class="invalid-roles" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
</div>
{{Form::close()}}
