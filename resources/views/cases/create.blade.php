@php
    $file_validation = App\Models\Utility::file_upload_validation();
@endphp
@extends('layouts.app')

@section('page-title', __('Add Case'))


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __(' Add Case') }}</li>
@endsection

@php
    $setting = App\Models\Utility::settings();
@endphp

@section('content')

    {{ Form::open(['route' => 'cases.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-lg-10">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('court', __('Courts/Tribunal'), ['class' => 'form-label']) !!}
                                <select class="form-control  item" name="court" id='court' required>
                                    <option value="0" disabled selected>{{ __('Select Court') }}</option>
                                    @foreach ($courts as $key => $court)
                                        <option value="{{ $key }}" data-name="{{ $court }}">
                                            {{ $court }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group d-none" id="casetype_div">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group d-none" id="casenumber_div">

                                {!! Form::label('casenumber', __('Case Type'), ['class' => 'form-label']) !!}

                                <select id="casenumber" class="form-control  item" name="casenumber">
                                    <option value=""> {{ __('Please select') }} </option>
                                    @foreach (App\Models\Cases::caseType() as $typ)
                                        <option value="{{ $typ }}">{{ $typ }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group d-none" id="diarybumber_div">
                                {!! Form::label('diarybumber', __('Diary Number'), ['class' => 'form-label']) !!}
                                {{ Form::number('diarybumber', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group  d-none" id="highcourt_div">
                                {!! Form::label('highcourt', __('High Court'), ['class' => 'form-label']) !!}

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group  d-none" id="bench_div">
                                {!! Form::label('court', __('Circuit/Devision'), ['class' => 'form-label']) !!}

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('case_number', __('Case Number'), ['class' => 'form-label']) !!}
                                {{ Form::text('case_number', null, ['class' => 'form-control  item', 'id' => 'case_number', 'required' => 'required']) }}
                                <small>{{ __('(Please enter the case number assigned by court)') }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('year', __('Year'), ['class' => 'form-label', 'required' => 'required']) !!}
                                <select class="form-control multi-select" name="year" id="year">
                                    <option value="">{{ __('Please Select') }}</option>
                                    @foreach (App\Models\Utility::getYears() as $year)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {!! Form::label('title', __('Title'), ['class' => 'form-label']) !!}
                                {{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) }}
                                <small>{{ __('(Please enter the case number assigned by court)') }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('filing_date', __('Date of filing'), ['class' => 'col-form-label', 'required' => 'required']) }}
                                {{ Form::date('filing_date', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('judge', __('Judge name'), ['class' => 'col-form-label', 'required' => 'required']) }}
                                {{ Form::text('judge', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('court_room', __('Court Room no.'), ['class' => 'col-form-label']) }}
                                {{ Form::number('court_room', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                                <small> {{ __('(Please enter primary details about the case, client, etc)') }} </small>
                                {{ Form::textarea('description', null, ['class' => 'form-control summernote', 'rows' => 2, 'placeholder' => __('Description'), 'id' => 'description']) }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('under_acts', __('Under Acts'), ['class' => 'col-form-label']) }}
                                {{ Form::text('under_acts', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('under_sections', __('Under Section'), ['class' => 'col-form-label']) }}
                                {{ Form::text('under_sections', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('police_station', __('FIR Police Station'), ['class' => 'col-form-label']) }}
                                {{ Form::text('police_station', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('FIR_number', __('FIR No.'), ['class' => 'col-form-label']) }}
                                {{ Form::number('FIR_number', null, ['class' => 'form-control']) }}

                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('FIR_year', __('FIR Year'), ['class' => 'col-form-label']) }}
                                <select class="form-control multi-select" name="FIR_year" id="year">
                                    <option value="">{{ __('Please Select') }}</option>
                                    @foreach (App\Models\Utility::getYears() as $year)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('stage', __('Stage'), ['class' => 'col-form-label']) }}
                                {{ Form::text('stage', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('your_party', __('Your Party'), ['class' => 'col-form-label', 'required' => 'required']) }}
                                <select name="your_party" id="" class="form-control">
                                    <option value="" disabled selected>{{ __('Please select') }}</option>
                                    <option value="0">{{ __('Petitioner/Plaintiff') }}</option>
                                    <option value="1">{{ __('Respondent/Defendant') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 repeater">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card my-3 shadow-none rounded-0 border">
                                        <div class="card-header">
                                            <div class="row flex-grow-1">
                                                <div class="col-md d-flex align-items-center col-6">
                                                    <h5 class="card-header-title">{{ __('Your Party Name') }}</h5>
                                                </div>

                                                <div class="col-md-6 justify-content-between align-items-center col-6">
                                                    <div class="col-md-12 d-flex align-items-center  justify-content-end">
                                                        <a data-repeater-create=""
                                                            class="btn btn-primary btn-sm add-row text-white"
                                                            data-toggle="modal">
                                                            <i class="fas fa-plus"></i> {{ __('Add Row') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-border-style">
                                            <div class="table-responsive">
                                                <table class="table  mb-0 table-custom-style"
                                                    data-repeater-list="your_party_name" id="sortable-table">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('Clients') }}</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="ui-sortable" data-repeater-item>
                                                        <tr>
                                                            <td width="25%" class="form-group">
                                                                <input type="text" class="form-control name"
                                                                    name="name">
                                                            </td>
                                                            <td width="25%">
                                                                {{ Form::select('clients', $clients, null, ['class' => 'form-control']) }}
                                                            </td>
                                                            <td width="2%">
                                                                <a href="javascript:;"
                                                                    class="ti ti-trash text-white action-btn bg-danger p-3 desc_delete"
                                                                    data-repeater-delete></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 repeater">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow-none rounded-0 border my-3">
                                        <div class="card-header">
                                            <div class="row flex-grow-1">
                                                <div class="col-md d-flex align-items-center col-6">
                                                    <h5 class="card-header-title">{{ __('Opposite Party') }}</h5>
                                                </div>

                                                <div class="col-md-6 justify-content-between align-items-center col-6">
                                                    <div class="col-md-12 d-flex align-items-center  justify-content-end">
                                                        <a data-repeater-create=""
                                                            class="btn btn-primary btn-sm add-row text-white"
                                                            data-toggle="modal" data-target="#add-bank">
                                                            <i class="fas fa-plus"></i> {{ __('Add Row') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-border-style">
                                            <div class="table-responsive">
                                                <table class="table  mb-0 table-custom-style"
                                                    data-repeater-list="opp_party_name" id="sortable-table">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Name') }}</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="ui-sortable" data-repeater-item>
                                                        <tr>
                                                            <td width="25%" class="form-group">
                                                                <input type="text" class="form-control name"
                                                                    name="name">
                                                            </td>
                                                            <td width="2%">
                                                                <a href="javascript:;"
                                                                    class="ti ti-trash text-white action-btn bg-danger p-3 desc_delete"
                                                                    data-repeater-delete></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('advocates', __('Advocates'), ['class' => 'col-form-label']) }}
                                {!! Form::select('advocates[]', $advocates, null, [
                                    'class' => 'form-control multi-select',
                                    'id' => 'choices-multiple',
                                    'multiple',
                                    'data-role' => 'tagsinput',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {{ Form::label('opp_adv', __('Opposite Party Advocate'), ['class' => 'col-form-label']) }}
                                {{ Form::text('opp_adv', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="col-md-4 choose-files fw-semibold ">


                            <label for="case_docs" class="upload__btn">

                                {{ Form::label('case_docs', __('Case Summary Upload'), ['class' => 'col-form-label']) }}

                                <div class="bg-primary profile_update" style="max-width: 100% !important;">
                                    <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                </div>

                                <input type="file" name="case_docs[]" id="case_docs"
                                    class="form-control file upload__inputfile" style="width: 0px !important" multiple
                                    onchange="image_upload_bar($('input[id=case_docs]').val().split('.')[1])" />
                                <p><span
                                        class="text-muted m-0 file-info">{{ __('Allowed file extension : ') }}{{ $file_validation['mimes'] }}</span>
                                    <span
                                        class="text-muted">({{ __('Max Size: ') }}{{ $file_validation['max_size'] }})</span>
                                </p>
                                <div id="progressContainer" class="p-0" style="display: none;">
                                    <progress class="bg-primary progress rounded" id="progressBar" value="0"
                                        max="100" style="width: 310px !important"></progress>
                                    <span id="progressText">0%</span>
                                </div>
                            </label>



                        </div>

                        <div class="upload__box ">

                            <div class="upload__img-wrap"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-1"></div>
        <div class="col-lg-10">
            <div class="card shadow-none rounded-0 border ">
                <div class="card-body p-2">
                    <div class="form-group col-12 d-flex justify-content-end col-form-label mb-0">

                        <a href="{{ route('cases.index') }}"
                            class="btn btn-secondary btn-light ms-3">{{ __('Cancel') }}</a>
                        <input type="submit" value="{{ __('Save') }}" class="btn btn-primary ms-2">
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{ Form::close() }}
    <!-- [ Main Content ] end -->
@endsection


@push('custom-script')
    <script src="{{ asset('public/assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('public/assets/js/repeater.js') }}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    if ($('.select2').length) {
                        $('.select2').select2();
                    }

                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        if ($('.disc_qty').length < 6) {
                            $(".add-row").show();

                        }
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }
                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }

        }

        $(".add-row").on('click', function(event) {
            var $length = $('.disc_qty').length;
            if ($length == 5) {
                $(this).hide();
            }
        });
        $(".desc_delete").on('click', function(event) {

            var $length = $('.disc_qty').length;
        });
    </script>


    <script>
        jQuery(document).ready(function() {
            ImgUpload();
        });
        $('body').on('change', "#court", function(e) {
            var token = $('meta[name="csrf-token"]').attr('content');
            var type = $("#court option:selected").attr('data-name');
            var id = $("#court option:selected").val();

            $.ajax({
                url: '{{ url('/subcourt/list') }}',
                method: "POST",
                data: {
                    "_token": token,
                    'id': id,
                    'type': type
                },
                success: function(data) {
                    $('#constant').css('display', '')
                    $('#constant').replaceWith($('#constant').html(data));

                    $('#last-cons-tant').css('display', 'none');
                    $('#sub-constant').css('display', 'none');
                }
            });
        });
        $('body').on('change', "#sub-list", function(e) {
            var token = $('meta[name="csrf-token"]').attr('content');
            var id = $("#sub-list option:selected").val();
            var type = $("#sub-list option:selected").attr('data-id');
            $.ajax({
                url: '{{ url('/district/list') }}',
                method: "POST",
                data: {
                    "_token": token,
                    'id': id,
                    'type': type
                },
                success: function(data) {

                    $('#sub-constant').css('display', '');
                    $('#sub-constant').replaceWith($('#sub-constant').html(data));
                    if (type == 'State Commission - NCDRC') {
                        $('#case-type-div').css('display', '');
                    } else {
                        $('#case-type-div').css('display', 'none');
                    }



                }
            });
        });
        $('body').on('change', "#sub-contstant-list", function(e) {
            var token = $('meta[name="csrf-token"]').attr('content');
            var id = $("#sub-contstant-list option:selected").val();
            var type = $("#sub-contstant-list option:selected").attr('data-id');
            $.ajax({
                url: '{{ url('/district/list') }}',
                method: "POST",
                data: {
                    "_token": token,
                    'id': id,
                    'type': type
                },
                success: function(data) {
                    $('#last-cons-tant').css('display', '');
                    $('#last-cons-tant').replaceWith($('#last-cons-tant').html(data));

                    $('#case-type-div').css('display', '');

                }
            });
        });

        function ImgUpload() {
            var imgWrap = "";
            var imgArray = [];

            $('.upload__inputfile').each(function() {
                $(this).on('change', function(e) {
                    imgWrap = $('.upload__box').find('.upload__img-wrap');

                    var maxLength = $(this).attr('data-max_length');

                    var files = e.target.files;
                    var filesArr = Array.prototype.slice.call(files);
                    var iterator = 0;
                    filesArr.forEach(function(f, index) {

                        // if (!f.type.match('image.*')) {
                        //   return;
                        // }

                        if (imgArray.length > maxLength) {
                            return false
                        } else {
                            var len = 0;
                            for (var i = 0; i < imgArray.length; i++) {
                                if (imgArray[i] !== undefined) {
                                    len++;
                                }
                            }
                            if (len > maxLength) {
                                return false;
                            } else {

                                imgArray.push(f);

                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    if (!f.type.match('image.*')) {

                                        var html =
                                            "<div class='upload__img-box'><div style='background-image: url(" +
                                            e.target.result + ")' data-number='" + $(
                                                ".upload__img-close").length + "' data-file='" +
                                            f.name + "' class='img-bg'><p>" + f.name +
                                            "</p><div class='upload__img-close'></div></div></div>";
                                        imgWrap.append(html);
                                        iterator++;
                                    } else {
                                        var html =
                                            "<div class='upload__img-box'><div style='background-image: url(" +
                                            e.target.result + ")' data-number='" + $(
                                                ".upload__img-close").length + "' data-file='" +
                                            f.name + "' class='img-bg'><p>" + f.name +
                                            "</p><div class='upload__img-close'></div></div></div>";
                                        imgWrap.append(html);
                                        iterator++;
                                    }

                                }
                                reader.readAsDataURL(f);
                            }
                        }
                    });
                });
            });

            $('body').on('click', ".upload__img-close", function(e) {
                var file = $(this).parent().data("file");
                for (var i = 0; i < imgArray.length; i++) {
                    if (imgArray[i].name === file) {
                        imgArray.splice(i, 1);
                        break;
                    }
                }
                $(this).parent().parent().remove();
            });
        }
    </script>

    <script>
        $(document).on('change', '#court', function() {
            var selected_opt = $(this).val();
            var seletor = $(this);

            $.ajax({
                url: "{{ route('get.highcourt') }}",
                datType: 'json',
                method: 'POST',
                data: {
                    selected_opt: selected_opt
                },
                success: function(data) {
                    if (data.status == 1) {
                        $('#highcourt_div').removeClass('d-none');
                        $('#highcourt_div').empty();
                        $('#casetype_div').addClass('d-none').empty();
                        $('#casenumber_div').addClass('d-none');
                        $('#diarybumber_div').addClass('d-none');

                        $('#highcourt_div').append(
                            '<label for="highcourt" class="form-label">High Court</label> <select class="form-control" name="highcourt" id="highcourt"> </select>'
                        );
                        $('#highcourt').append('<option value="">{{ __('Please Select') }}</option>');

                        $.each(data.dropdwn, function(key, value) {
                            $('#highcourt').append('<option value="' + key + '">' + value +
                                '</option>');
                        });

                    } else {
                        var text = $("#court option:selected").text();

                        $('#highcourt_div').addClass('d-none').empty();
                        $('#bench_div').addClass('d-none').empty();

                        $('#casetype_div').removeClass('d-none').append(
                            '<label for="casetype" class="form-label">' + text +
                            '</label><select class="form-control" name="casetype" id="casetype"> <option value="">{{ __('Please Select') }}</option><option value="Case Number">{{ __('Case Number') }}</option><option value="Diary Number">{{ __('Diary Number') }}</option></select>'
                        );



                        $(document).on('change', '#casetype', function() {
                            var type = $("#casetype option:selected").text();
                            $('#casenumber_div').addClass('d-none');
                            $('#diarybumber_div').addClass('d-none');
                            if (type == 'Case Number') {
                                $('#casenumber_div').removeClass('d-none');
                                $('#case_number_div').removeClass('d-none');

                            }
                            if (type == 'Diary Number') {
                                $('#case_number_div').addClass('d-none');
                                $('#diarybumber_div').removeClass('d-none');

                            }
                        });

                    }

                }
            })
        });

        $(document).on('change', '#highcourt', function() {
            var selected_opt = $(this).val();

            $.ajax({
                url: "{{ route('get.bench') }}",
                datType: 'json',
                method: 'POST',
                data: {
                    selected_opt: selected_opt
                },
                success: function(data) {
                    console.log(data.status);
                    if (data.status == 1) {
                        $('#bench_div').removeClass('d-none');
                        $('#bench_div').empty();
                        $('#bench_div').append(
                            '<label for="bench" class="form-label">Bench</label> <select class="form-control" name="bench" id="bench"> </select>'
                        );
                        $('#bench').append('<option value="">{{ __('Please Select') }}</option>');

                        $.each(data.dropdwn, function(key, value) {
                            $('#bench').append('<option value="' + key + '">' + value +
                                '</option>');
                        });

                        $('#danger-span').addClass('d-none').remove();
                    } else {
                        $('#bench_div').addClass('d-none').empty();
                        $('#danger-span').addClass('d-none').remove();
                        $('#highcourt_div').removeClass('d-none').append(
                            '<a href="#" data-url={{ route('bench.create') }} data-title="Add Circuit/Devision" data-ajax-popup="true" data-size="md" title={{ __('Create New Circuit/Devision') }}><span class="text-danger" id="danger-span">Please add Circuit/Devision to current high court</span></a>'
                        )
                    }

                }
            })

        });

        $(document).on('change', '#causelist_by', function() {
            $('#adv_label').html($(this).val())
        });

        $(document).on('change', '#bench', function() {

        });
    </script>




    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script>
        $('.summernote').summernote({
            dialogsInBody: !0,
            minHeight: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ]
        });
    </script>
@endpush
