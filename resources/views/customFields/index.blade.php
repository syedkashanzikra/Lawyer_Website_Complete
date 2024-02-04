@extends('layouts.app')

@section('page-title', __('Manage Priority'))
@php
    $logos = \App\Models\Utility::get_file('uploads/profile/');
@endphp
@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Manage Priority') }}</li>
@endsection

@section('content')
<div class="col-sm-12">
    <div class="row g-0">
        <div class="col-xl-3 border-end border-bottom">
            @include('layouts.setup')
        </div>
        <div class="col-xl-9" data-bs-spy="scroll" data-bs-target="#useradd-sidenav" data-bs-offset="0" tabindex="0">
            <div id="ticket-fields-settings" class="card shadow-none rounded-0 border-bottom">
                <div class="custom-fields" data-value="{{ json_encode($customFields) }}">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="">
                            <h5 class="">{{ __('Ticket Fields Settings') }}</h5>
                            <label class="form-check-label pe-5 text-muted"
                                for="enable_chat">{{ __('You can easily change order of fields using drag & drop.') }}</label>
                        </div>
                        <button data-repeater-create type="button"
                            class="btn btn-sm btn-primary btn-icon m-1 float-end ms-2" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="{{ __('Create Custom Field') }}">
                            <i class="ti ti-plus mr-1"></i>
                        </button>
                    </div>
                    <form method="post" action="{{ route('custom-fields.store') }}">
                    <div class="card-body table-border-style p-0">
                            @csrf
                            <div class="table-responsive m-0 custom-field-table">

                                <table class="table dataTable-table" id="pc-dt-simple"
                                    data-repeater-list="fields">
                                    <thead class="thead-light">
                                        <tr>
                                            <th></th>
                                            <th>{{ __('Labels') }}</th>
                                            <th>{{ __('Placeholder') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Require') }}</th>
                                            <th>{{ __('Width') }}</th>
                                            <th class="text-right">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-repeater-item>
                                            <td><i class="ti ti-arrows-maximize sort-handler"></i></td>
                                            <td>
                                                <input type="hidden" name="id" id="id" />
                                                <input type="text" name="name" class="form-control mb-0"
                                                    required />
                                            </td>
                                            <td>
                                                <input type="text" name="placeholder"
                                                    class="form-control mb-0" required />
                                            </td>
                                            <td>
                                                <select class="form-control select-field field_type mr-2"
                                                    name="type">
                                                    @foreach (\App\Models\CustomField::$fieldTypes as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select class="form-control select-field field_type"
                                                    name="is_required">
                                                    <option value="1">{{ __('Yes') }}</option>
                                                    <option value="0">{{ __('No') }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select-field" name="width">
                                                    <option value="3">25%</option>
                                                    <option value="4">33%</option>
                                                    <option value="6">50%</option>
                                                    <option value="8">66%</option>
                                                    <option value="12">100%</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <a data-repeater-delete class="delete-icon"><i
                                                        class="fas fa-trash text-danger"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                    </div>
                    <div class="card-footer text-end ">
                        <button class="btn btn-primary btn-block btn-submit"
                            type="submit">{{ __('Save Changes') }}</button>
                    </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-script')

<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/repeater.js') }}"></script>
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300,
    })

    $(".list-group-item").click(function() {
        $('.list-group-item').filter(function() {
            return this.href == id;
        }).parent().removeClass('text-primary');
    });
</script>
<script>
    function myFunction() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        show_toastr('Success', "{{ __('Link copied') }}", 'success');
    }


    function check_theme(color_val) {
        $('#theme_color').prop('checked', false);
        $('input[value="' + color_val + '"]').prop('checked', true);
    }
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>

<script>
    var multipleCancelButton = new Choices(
        '#choices-multiple-remove-button', {
            removeItemButton: true,
        }
    );

    var multipleCancelButton = new Choices(
        '#choices-multiple-remove-button1', {
            removeItemButton: true,
        }
    );

    var multipleCancelButton = new Choices(
        '#choices-multiple-remove-button2', {
            removeItemButton: true,
        }
    );
</script>
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300,
    })
    $(".list-group-item").click(function() {
        $('.list-group-item').filter(function() {
            return this.href == id;
        }).parent().removeClass('text-primary');
    });

    function check_theme(color_val) {
        $('#theme_color').prop('checked', false);
        $('input[value="' + color_val + '"]').prop('checked', true);
    }

    $(document).on('change', '[name=storage_setting]', function() {
        if ($(this).val() == 's3') {
            $('.s3-setting').removeClass('d-none');
            $('.wasabi-setting').addClass('d-none');
            $('.local-setting').addClass('d-none');
        } else if ($(this).val() == 'wasabi') {
            $('.s3-setting').addClass('d-none');
            $('.wasabi-setting').removeClass('d-none');
            $('.local-setting').addClass('d-none');
        } else {
            $('.s3-setting').addClass('d-none');
            $('.wasabi-setting').addClass('d-none');
            $('.local-setting').removeClass('d-none');
        }
    });
</script>

<script>
    $(document).on("click", '.send_email', function(e) {

        e.preventDefault();
        var title = $(this).attr('data-title');

        var size = 'md';
        var url = $(this).attr('data-url');
        if (typeof url != 'undefined') {
            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);
            $("#commonModal").modal('show');

            $.post(url, {
                mail_driver: $("#mail_driver").val(),
                mail_host: $("#mail_host").val(),
                mail_port: $("#mail_port").val(),
                mail_username: $("#mail_username").val(),
                mail_password: $("#mail_password").val(),
                mail_encryption: $("#mail_encryption").val(),
                mail_from_address: $("#mail_from_address").val(),
                mail_from_name: $("#mail_from_name").val(),
            }, function(data) {
                $('#commonModal .modal-body').html(data);
            });
        }
    });
    $(document).on('submit', '#test_email', function(e) {
        e.preventDefault();
        $("#email_sending").show();
        var post = $(this).serialize();
        var url = $(this).attr('action');
        $.ajax({
            type: "post",
            url: url,
            data: post,
            cache: false,
            beforeSend: function() {
                $('#test_email .btn-create').attr('disabled', 'disabled');
            },
            success: function(data) {
                if (data.is_success) {
                    show_toastr('Success', data.message, 'success');
                } else {
                    show_toastr('Error', data.message, 'error');
                }
                $("#email_sending").hide();
            },
            complete: function() {
                $('#test_email .btn-create').removeAttr('disabled');
            },
        });
    });

    // $(document).on('change','.SITE_RTL',function(){
    //     $()
    // });
    $(document).ready(function() {
        var $dragAndDrop = $("body .custom-fields tbody").sortable({
            handle: '.sort-handler'
        });

        var $repeater = $('.custom-fields').repeater({
            initEmpty: true,
            defaultValues: {},
            show: function() {
                $(this).slideDown();
                var eleId = $(this).find('input[type=hidden]').val();
                if (eleId > 7 || eleId == '') {
                    $(this).find(".field_type option[value='file']").remove();
                    $(this).find(".field_type option[value='select']").remove();
                }
            },
            hide: function(deleteElement) {
                if (confirm('{{ __('Are you sure ? ') }}')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function(setIndexes) {
                $dragAndDrop.on('drop', setIndexes);
            },
            isFirstItemUndeletable: true
        });

        var value = $(".custom-fields").attr('data-value');
        if (typeof value != 'undefined' && value.length != 0) {
            value = JSON.parse(value);
            $repeater.setList(value);
        }

        $.each($('[data-repeater-item]'), function(index, val) {
            var elementId = $(this).find('input[type=hidden]').val();
            if (elementId <= 8) {
                $.each($(this).find('.field_type'), function(index, val) {
                    $(this).prop('disabled', 'disabled');
                });
                $(this).find('.delete-icon').remove();
            }
        });
    });
</script>

<script type="text/javascript">
    function enablecookie() {
        const element = $('#enable_cookie').is(':checked');
        $('.cookieDiv').addClass('disabledCookie');
        if (element == true) {
            $('.cookieDiv').removeClass('disabledCookie');
            $("#cookie_logging").attr('checked', true);
        } else {
            $('.cookieDiv').addClass('disabledCookie');
            $("#cookie_logging").attr('checked', false);
        }
    }
</script>

<script type="text/javascript">
    $(document).on("click", ".email-template-checkbox", function() {
        var chbox = $(this);
        $.ajax({
            url: chbox.attr('data-url'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: chbox.val()
            },
            type: 'post',
            success: function(response) {
                if (response.is_success) {
                    show_toastr('Success', response.success, 'success');
                    if (chbox.val() == 1) {
                        $('#' + chbox.attr('id')).val(0);
                    } else {
                        $('#' + chbox.attr('id')).val(1);
                    }
                } else {
                    show_toastr('Error', response.error, 'error');
                }
            },
            error: function(response) {
                response = response.responseJSON;
                if (response.is_success) {
                    show_toastr('Error', response.error, 'error');
                } else {
                    show_toastr('Error', response, 'error');
                }
            }
        })
    });
</script>


<script>
    $(document).on('change', '.domain_click#enable_storelink', function(e) {
        $('#StoreLink').show();
        $('.sundomain').hide();
        $('.domain').hide();
        $('#domainnote').hide();
        $("#enable_storelink").parent().addClass('active');
        $("#enable_domain").parent().removeClass('active');
        $("#enable_subdomain").parent().removeClass('active');
    });
    $(document).on('change', '.domain_click#enable_domain', function(e) {
        $('.domain').show();
        $('#StoreLink').hide();
        $('.sundomain').hide();
        $('#domainnote').show();
        $("#enable_domain").parent().addClass('active');
        $("#enable_storelink").parent().removeClass('active');
        $("#enable_subdomain").parent().removeClass('active');
    });
    $(document).on('change', '.domain_click#enable_subdomain', function(e) {
        $('.sundomain').show();
        $('#StoreLink').hide();
        $('.domain').hide();
        $('#domainnote').hide();
        $("#enable_subdomain").parent().addClass('active');
        $("#enable_domain").parent().removeClass('active');
        $("#enable_domain").parent().removeClass('active');
    });

    var custdarklayout = document.querySelector("#cust-darklayout");
    custdarklayout.addEventListener("click", function() {
        if (custdarklayout.checked) {

            document
                .querySelector("#main-style-link")
                .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
            document
                .querySelector(".m-header > .b-brand > .logo-lg")
                .setAttribute("src", "{{ asset('/storage/uploads/logo/logo-light.png') }}");
        } else {

            document
                .querySelector("#main-style-link")
                .setAttribute("href", "{{ asset('assets/css/style.css') }}");
            document
                .querySelector(".m-header > .b-brand > .logo-lg")
                .setAttribute("src", "{{ asset('/storage/uploads/logo/logo-dark.png') }}");
        }
    });


    var custthemebg = document.querySelector("#cust-theme-bg");
    custthemebg.addEventListener("click", function() {
        if (custthemebg.checked) {
            document.querySelector(".dash-sidebar").classList.add("transprent-bg");
            document
                .querySelector(".dash-header:not(.dash-mob-header)")
                .classList.add("transprent-bg");
        } else {
            document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
            document
                .querySelector(".dash-header:not(.dash-mob-header)")
                .classList.remove("transprent-bg");
        }
    });
</script>
@endpush
