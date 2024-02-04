{{ Form::open(array('route' => 'testimonials_store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                    {{ Form::text('testimonials_title',null, ['class' => 'form-control ', 'placeholder' => __('Enter Title')]) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Star', __('Star'), ['class' => 'form-label']) }}
                    {{ Form::number('testimonials_star',null, ['class' => 'form-control', 'min'=>'1', 'max'=>'5','required'=>'required', 'placeholder' => __('Enter Star')]) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                    {{ Form::textarea('testimonials_description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('User', __('User'), ['class' => 'form-label']) }}
                    {{ Form::text('testimonials_user',null, ['class' => 'form-control ', 'placeholder' => __('Enter User Name')]) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Designation', __('Designation'), ['class' => 'form-label']) }}
                    {{ Form::text('testimonials_designation',null, ['class' => 'form-control ', 'placeholder' => __('Enter Designation')]) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('User Avtar', __('User Avtar'), ['class' => 'form-label']) }}
                    <input type="file" name="testimonials_user_avtar" class="form-control" required="required">
                </div>
            </div>


        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}

