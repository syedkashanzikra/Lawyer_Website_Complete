{{ Form::model($highcourt,['route' => ['highcourts.update',$highcourt->id], 'method' => 'PUT']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('court', __('Courts/Tribunal'), ['class' => 'form-label']) !!}
                {!! Form::select('court_id', $courts, null, ['class' => 'form-control multi-select','id'=>'member']) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}

<script>

</script>
