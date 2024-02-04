
{{Form::model($todo, array('route' => array('to-do.update', $todo->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
@csrf
@method('put')
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('title', __('Title'), ['class' => 'form-label']) !!}
                {!! Form::text('title', null, ['rows' => 4, 'class'=>'form-control','required'=>'required']) !!}
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('relate_to', __('Relate to (Case\'s)'), ['class' => 'form-label']) !!}
                {!! Form::select('relate_to[]',$cases, $relate_to, ['class' => 'form-control multi-select','id'=>'choices-multiple','multiple','required'=>'required']) !!}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('due_date', __('Assigned Date'), ['class' => 'form-label']) }}
                <input value="{{$todo->start_date}}"  placeholder="DD/MM/YYYY" data-input class="form-control text-center single-date" name="assigned_date" required/>
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}
                <input  value="{{$todo->end_date}}" placeholder="DD/MM/YYYY" data-input class="form-control text-center single-date" name="due_date" required/>
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('assign_to', __('Assign To (Admin/Advocates/Members)'), ['class' => 'form-label']) !!}
                {!! Form::select('assign_to[]',$teams, $assign_to, ['class' => 'form-control multi-select','id'=>'choices-multiple1','multiple']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('assign_to', __('Priority'), ['class' => 'form-label']) !!}
                <select name="priority" id="" class="form-control">
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}" {{ $todo->priority == $priority ? 'selected' : '' }}>{{ $priority }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('description', __('Description'), ['class' => 'form-label']) !!}

                {!! Form::textarea('description', null, ['rows' => 2, 'class'=>'form-control','maxlength'=>"150"]) !!}
            </div>
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}
