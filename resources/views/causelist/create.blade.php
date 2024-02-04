{{ Form::open(['route' => 'cause.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('court', __('Courts/Tribunal'), ['class' => 'form-label']) !!}
                {{ Form::select('court', $courts, '', ['class' => 'form-control  item multi-select','id'=>'court', 'required' => 'required']) }}
            </div>

            <div class="form-group col-md-12 d-none" id="highcourt_div">
                {!! Form::label('highcourt', __('High Court'), ['class' => 'form-label']) !!}


            </div>

            <div class="form-group col-md-12 d-none" id="bench_div">
                {!! Form::label('court', __('Circuit/Devision'), ['class' => 'form-label']) !!}
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('causelist_by', __('Causelist By'), ['class' => 'form-label']) !!}
               {{ Form::select('causelist_by', ['Advocate Name'=>'Advocate Name','Keyword'=>'Keyword','Party Name'=>'Party Name','Judge Name'=>'Judge Name'], '', ['class' => 'form-control  item', 'required' => 'required']) }}
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('advocate_name', __('Advocate Name'), ['class' => 'form-label','id'=>'adv_label']) !!}
               {{ Form::text('advocate_name', '', ['class' => 'form-control ', 'required' => 'required']) }}
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}
