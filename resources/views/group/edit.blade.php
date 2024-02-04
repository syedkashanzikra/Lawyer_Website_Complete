{{Form::model($grp, array('route' => array('groups.update', $grp->id), 'method' => 'PUT')) }}
<div class="modal-body">
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Name')) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">

            {!! Form::label('members', __('Select Team Member'), ['class' => 'form-label']) !!}
            {!! Form::select('members[]', $users,$my_members, ['class' => 'form-control multi-select', 'id' => 'choices-multiple', 'multiple','data-role'=>'tagsinput']) !!}
        </div>
    </div>
</div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}"  id="saverating" class="btn btn-primary ms-2">
</div>
{{Form::close()}}
