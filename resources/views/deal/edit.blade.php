{{ Form::model($deal, ['route' => ['deal.update', $deal->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Deal Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('price', __('Price'), ['class' => 'col-form-label']) }}
            {{ Form::number('price', null, ['class' => 'form-control', 'min' => 0]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('pipeline_id', __('Pipeline'), ['class' => 'col-form-label']) }}
            {{ Form::select('pipeline_id', $pipelines, null, ['class' => 'form-control multi-select', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('stage_id', __('Stage'), ['class' => 'col-form-label']) }}
            {{ Form::select('stage_id',$stages,$deal->stage_id,  ['class' => 'form-control multi-select']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('sources', __('Sources'), ['class' => 'col-form-label']) }}
            {{ Form::select('sources[]', $sources, null, ['class' => 'form-control multi-select', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}

        </div>
        <div class="form-group col-md-6">
            {{ Form::label('phone_no', __('Phone No'), ['class' => 'col-form-label']) }}
            {{ Form::text('phone_no', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('notes', __('Notes'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => '3']) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>

{{ Form::close() }}

<script>
    var stage_id = '{{ $deal->stage_id }}';

    $(document).ready(function() {
        $("#exampleModal select[name=pipeline_id]").trigger('change');
    });

    $(document).on("change", "#exampleModal select[name=pipeline_id]", function() {
        $.ajax({
            url: '{{ route('dealStage.json') }}',
            data: {
                pipeline_id: $(this).val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            success: function(data) {
                $("#stage_id").html(
                    '<option value="" selected="selected">{{ __('Select Deal Stages') }}</option>'
                    );
                $.each(data, function(key, data) {
                    var select = '';
                    if (key == '{{ $deal->stage_id }}') {
                        select = 'selected';
                    }

                    $("#stage_id").append('<option value="' + key + '" ' + select + '>' +
                        data + '</option>');
                });
                $("#stage_id").val(stage_id);
            }
        })
    });
</script>

<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script>
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function(index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
</script>
