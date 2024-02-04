{{Form::model($todo, array('route' => array('to-do.status.update', $todo->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
@csrf
@method('put')
<div class="modal-body">
	<p>{{__('You can\'t edit to-do after marking as complete. Are you sure?')}}</p>
	</div>
<div class="form-group text-right">
</div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <button class="btn btn-primary ms-2" value="{{$todo->status}}" type="submit">{{ __('Yes') }}</button>
</div>

{{Form::close()}}
