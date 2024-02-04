{{ Form::model($lead, ['route' => ['lead.update', $lead->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label']) }}
            {{ Form::text('subject', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('user_id', __('User'), ['class' => 'col-form-label']) }}
            {{ Form::select('user_id', $employees, null, ['class' => 'form-control multi-select', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('email', __('Email'), ['class' => 'col-form-label']) }}
            {{ Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('pipeline_id', __('Pipeline'), ['class' => 'col-form-label']) }}
            {{ Form::select('pipeline_id', $pipelines, $lead->pipeline_id, ['class' => 'form-control multi-select', 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('stage_id', __('Stage'), ['class' => 'col-form-label']) }}
            {{ Form::select('stage_id',$stages,$lead->stage_id,['class' => 'form-control multi-select', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('sources', __('Sources'), ['class' => 'col-form-label']) }}
            {{ Form::select('sources[]', $sources, $lead->sources, ['class' => 'form-control multi-select', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('phone_no', __('Phone No'), ['class' => 'col-form-label']) }}
            {{ Form::text('phone_no', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('notes', __('Notes'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>

{{ Form::close() }}
    @push('script-page')
    <script>

    var stage_id = '{{ $lead->stage_id }}';
    var pipeline_id = $('[name=pipeline_id]').val();
    getStages(pipeline_id);

    $(document).on("change", "#exampleModal select[name=pipeline_id]", function() {
        var currVal = $(this).val();
        getStages(currVal);
    });

    function getStages(id) {

        $.ajax({
            url: '{{ route('lead.json') }}',
            data: {
                pipeline_id: id,
                _token: $('meta[name="csrf-token"]').attr('content')

            },
            type: 'POST',
            success: function(data) {
                var stage_cnt = Object.keys(data).length;
                $("#stage_id").empty().append(
                    '<option value="" selected="selected">{{ __('Select Stages') }}</option>');
                if (stage_cnt > 0) {
                    $.each(data, function(key, data) {
                        var select = '';
                        if (key == '{{ $lead->stage_id }}') {
                            select = 'selected';
                        }
                        $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data +
                            '</option>');
                    });
                }
                $("#stage_id").val(stage_id);
                $('#stage_id').multi-select({
                    placeholder: "{{ __('Select Stage') }}"
                });
            }
        })
    }
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
