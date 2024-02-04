{{ Form::open(['route' => ['payment.store', $bill->id], 'mothod' => 'post']) }}

<div class="modal-body">
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
        <div class="form-icon-user">
            {{ Form::number('amount', $bill->due_amount, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('method', __('Method'), ['class' => 'form-label']) }}
        <div class="form-icon-user">
            {{ Form::select('method', ['Bank Transfer' => 'Bank Transfer', 'Cash' => 'Cash', 'Cheque' => 'Cheque', 'Online Payment' => 'Online Payment'], '', ['class' => 'form-control', 'required' => 'required']) }}

        </div>
    </div>

    <div class="form-group  col-md-12">
        {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
        <div class="form-icon-user">
            {{ Form::date('date', null, ['class' => 'form-control', 'required' => 'required']) }}

        </div>
    </div>

    <div class="form-group col-md-12">
        {{ Form::label('note', __('Note'), ['class' => 'form-label']) }}
        {!! Form::textarea('note', '', ['class' => 'form-control', 'rows' => '2']) !!}
    </div>
</div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Add') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
