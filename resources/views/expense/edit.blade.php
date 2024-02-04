{{ Form::model($expense,['route' => ['expenses.update',$expense->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('case', __('Case'), ['class' => 'form-label']) !!}
               {!! Form::select('case',$cases, $expense->case, ['class' => 'form-control ']) !!}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                <input id="timesheet_date"  placeholder="DD/MM/YYYY" data-input class="form-control text-center" name="date" required/ value="{{$expense->date}}">
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('particulars', __('Particulars'), ['class' => 'form-label']) !!}
                {!! Form::text('particulars', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('money', __('Money Spent'), ['class' => 'form-label']) !!}
                {!! Form::number('money', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('method', __('Payment Method'), ['class' => 'form-label']) !!}
                {!! Form::select('method',$payTypes,null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('member', __('Advocate/Member'), ['class' => 'form-label']) !!}
                {!! Form::select('member',$members, null, ['class' => 'form-control  multi-select','id'=>'member']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('notes', __('Notes'), ['class' => 'form-label']) !!}
                {!! Form::text('notes', null, ['class' => 'form-control']) !!}
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
