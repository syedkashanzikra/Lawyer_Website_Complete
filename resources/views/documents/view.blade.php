@php
    $docfile = \App\Models\Utility::get_file('uploads/documents/');

@endphp
<div class="modal-body">
<div class="row">
    <div class="col-lg-12">

        <div class="">
            <dl class="row">

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Name:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ !empty($doc->name) ? $doc->name : '-' }}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Type:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{!empty($doc->getDocType) ? $doc->getDocType->name : '-' }}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Purpose:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ !empty($doc->purpose) ? $doc->purpose : '-' }}</span></dd>


                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Description:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ !empty($doc->description) ? $doc->description : '-' }}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Uploaded by:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ App\Models\User::getUser($doc->created_by)->name }}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('File:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ !empty($doc->file) ? $doc->file : '-' }}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Doc Size:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ !empty($doc->doc_size) ? $doc->doc_size . ' MB' : '-' }}</span></dd>


                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View:') }}</span></dt>
                @if (!empty($doc->file))

                    <dd class="col-md-8"><span class="text-md"><a href="{{$docfile.$doc->file}}" target="_blank">{{__('Click here')}}</a></span></dd>
                @else
                    <dd class="col-md-8"><span class="text-md">-</span></dd>
                @endif


                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download:') }}</span></dt>
                @if (!empty($doc->file))

                    <dd class="col-md-8"><span class="text-md"><a href="{{$docfile.$doc->file}}" target="_blank" download>{{__('Click here')}}</a></span></dd>
                @else
                    <dd class="col-md-8"><span class="text-md">-</span></dd>
                @endif

            </dl>
        </div>

    </div>

</div>
</div>
