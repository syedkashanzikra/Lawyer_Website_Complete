<form action="" method="post">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <div class="from-group">
                        <label class="col-form-label"for="name">{{ __('Operating Hour Name') }}</label>
                        <span class="red" style="color: red;">*</span>
                        <input type="text" name="name" id="name" class="form-control" required />
                    </div>

                </div>
                <div class="col-md-12">

                    <div class="accordion-body">
                        @foreach ($days as $key => $day)
                            <div class="row align-items-center gy-4">
                                <div class="col-xs-12 col-sm-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $day }}"
                                            name="days[{{ $day }}]" id="content">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{ $day }}
                                            </label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-2"
                                    style="


                                    padding: 7px;
                                    width: 110px;">
                                    <select class="form-control"  name="content[{{ $day }}][start_hour]" id="content2"
                                        placeholder="08">
                                        <option value=""></option>

                                        <?php for ($i = 0; $i <= 23; $i++) { $i = $i < 10 ? '0' . $i : $i; ?>
                                        <option value="{{ $i }}">{{ $i }}</option>

                                        <?php } ?>


                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-2"
                                    style="
                                    padding: 7px;
                                    width: 110px;">
                                    <select class="form-control form-control-light"  name="content[{{$day}}][start_min]" placeholder="00"
                                        id="content3" >
                                        <option value=""></option>

                                        <?php for ($i = 0; $i < 61; $i += 10) { $i = $i < 10 ? '0' . $i : $i; ?>

                                        <option value="{{ $i }}">{{ $i }}</option>

                                        <?php } ?>

                                    </select>
                                </div>
                                <div class="col-xs-12 col-sm-1 hint text-center">to</div>
                                <div class="col-xs-6 col-sm-2"
                                    style="
                                    padding: 7px;
                                    width: 110px;">
                                    <select class="form-control"  name="content[{{$day}}][end_hour]" id="content4"
                                        placeholder="17">
                                        <option value=""></option>

                                        <?php for ($i = 0; $i <= 23; $i++) { $i = $i < 10 ? '0' . $i : $i; ?>

                                        <option value="{{ $i }}">{{ $i }}</option>

                                        <?php } ?>

                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-2"
                                    style="
                                    padding: 7px;
                                    width: 110px;">
                                    <select class="form-control form-control-light"  name="content[{{$day}}][end_min]" id="content5"
                                        placeholder="00">
                                        <option value=""></option>

                                        <?php for ($i = 0; $i < 61; $i += 10) { $i = $i < 10 ? '0' . $i : $i; ?>

                                        <option value="{{ $i }}">{{ $i }}</option>

                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
        <div class="col-12">
            <hr class="my-3">
        </div>

        <div class="row">
            <div class="d-flex justify-content-end text-end">
                <a class="btn btn-secondary btn-light btn-submit" href="">{{ __('Cancel') }}</a>
                <button class="btn btn-primary btn-submit ms-2" type="submit">{{ __('Add') }}</button>
            </div>
        </div>
    </div>

</form>

<script src="{{ asset('public/libs/select2/dist/js/select2.min.js')}}"></script>

<script>
    if ($(".multi-select").length > 0) {
    $( $(".multi-select") ).each(function( index,element ) {
        var id = $(element).attr('id');
           var multipleCancelButton = new Choices(
                '#'+id, {
                    removeItemButton: true,
                }
            );
    });

}


if ($(".select2").length) {
        $('.select2').select2({
            "language": {
                "noResults": function () {
                    return "No result found";
                }
            },
        });
    }

</script>
