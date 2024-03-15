@props(['imageMatchingKey', 'auctionTypes', 'deliveryTypes'])

@php 
    if (!isset($imageMatchingKey)) {
        $imageMatchingKey = Ramsey\Uuid\Uuid::uuid4()->toString();
    }
@endphp

<form method="POST" action="{{ route('auction.store') }}" hx-post="{{ route('auction.store') }}" hx-swap="outerHTML"
    hx-target="body" id="createAuctionForm">
    @csrf
</form>

<input type="hidden" id="auctionCreateKey" name="auctionCreateKey" value="{{ $imageMatchingKey }}" form="createAuctionForm">
<div class="mb-4">
    <x-input-label for="title" :value="__('Title')" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required autofocus
        autocomplete="name" form="createAuctionForm" />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>
<div class="mb-4">
    <x-input-label for="description" :value="__('Description')" />
    <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" required
        autocomplete="Description" form="createAuctionForm" />
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>
<div class="mb-4">
    <x-input-label for="features" :value="__('Features')" />
    <x-text-input id="features" name="features" type="text" class="mt-1 block w-full" required
        autocomplete="Features" form="createAuctionForm" />
    <x-input-error class="mt-2" :messages="$errors->get('features')" />
</div>

<form class="dropzone" action="{{ route('auction.images.store') }}" id="myDragAndDropUploader"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="imageMatchingKey" name="imageMatchingKey" value="{{ $imageMatchingKey }}" form="myDragAndDropUploader">
</form>

<div id="message" class="text-white pb-4">

</div>

<div class="mb-4">
    <div class="flex gap-2 align-middle">
        <x-input-label for="auction-type" :value="__('Type of auction')" />
        <x-tooltip>
            <div class="mb-4">
                {{ __('Last bid: this is how last bid works') }}
            </div>
            {{ __('Time limit: this is how it works') }}
        </x-tooltip>
    </div>

    <x-select-input id="auction-type" name="auction-type" class="mt-1 block w-full z-50" required
        form="createAuctionForm">
        @foreach ($auctionTypes as $auctionType)
            <option value="{{ $auctionType }}">{{ $auctionType->name }}</option>
        @endforeach
    </x-select-input>
    <x-input-error class="mt-2" :messages="$errors->get('auction-type')" />
</div>
<div class="mb-4">
    <x-input-label for="delivery-type" :value="__('Delivery Type')" />
    <x-select-input id="delivery-type" name="delivery-type" class="mt-1 block w-full" required form="createAuctionForm">
        @foreach ($deliveryTypes as $deliveryType)
            <option value="{{ $deliveryType }}">{{ $deliveryType->name }}</option>
        @endforeach
    </x-select-input>
    <x-input-error class="mt-2" :messages="$errors->get('delivery-type')" />
</div>
<div class="mb-4">
    <div class="flex gap-2 align-middle">
        <x-input-label for="price" :value="__('Starting price (£)')" />
        <x-tooltip>
            {{ __('This is the starting bid of the auction, it is recommened to set it to the lowest value you\'d be happy to sell the item for.') }}
        </x-tooltip>
    </div>
    <x-text-input id="price" name="price" type="text" class="mt-1 block w-full" required
        autocomplete="Starting price" form="createAuctionForm" />
    <x-input-error class="mt-2" :messages="$errors->get('price')" />
</div>
<div class="mb-4">
    <div class="flex gap-2 align-middle">
        <x-input-label for="end-time" :value="__('End time')" />
        <x-tooltip>
            {{ __('The time when the auction will end.') }}
        </x-tooltip>
    </div>

    <x-flatpickr :min-date="today()" :max-date="today()->addMonths(2)" show-time id="end-time" name="end-time"
        class="mt-1 block font-normal text-md text-gray-300 bg-gray-800 rounded w-full" required autocomplete="End time"
        form="createAuctionForm" />
    <x-input-error class="mt-2" :messages="$errors->get('end-time')" />
</div>
<div class="flex items-center justify-end mt-4">
    <x-primary-button form="createAuctionForm">
        {{ __('Create') }}
    </x-primary-button>
</div>


@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script type="text/javascript">
        var maxFilesizeVal = 12;
        var maxFilesVal = 20;

        Dropzone.autoDiscover = false;

        $(document).ready(function() {
            $("#myDragAndDropUploader").dropzone({
                maxFiles: maxFilesVal,
                url: "{{ route('auction.images.store') }}",
                success: function(file, response) {
                    console.log(response);
                }
            });
        })

        // Note that the name "myDragAndDropUploader" is the camelized id of the form.
        Dropzone.options.myDragAndDropUploader = {

            paramName: "file",
            maxFilesize: maxFilesizeVal, // MB
            maxFiles: maxFilesVal,
            resizeQuality: 1.0,
            uploadMultiple: true,
            acceptedFiles: ".jpeg,.jpg,.png,.webp",
            addRemoveLinks: true,
            dictDefaultMessage: "Drop your files here or click to upload",
            dictFallbackMessage: "Your browser doesn't support drag and drop file uploads.",
            dictFileTooBig: "File is too big. Max filesize: " + maxFilesizeVal + "MB.",
            dictInvalidFileType: "Invalid file type. Only JPG, JPEG, PNG and GIF files are allowed.",
            dictMaxFilesExceeded: "You can only upload up to " + maxFilesVal + " files.",
            maxfilesexceeded: function(file) {
                this.removeFile(file);
            },
            sending: function(file, xhr, formData) {
                $('#message').text('Image Uploading...');
            },
            success: function(file, response) {
                $('#message').text(response.success);
            },
            error: function(file, response) {
                $('#message').text('Something Went Wrong! ' + response);
                return false;
            }
        };
    </script>
    @include('flatpickr::components.script')
    <script
    type="module"
    src="https://unpkg.com/@material-tailwind/html@latest/scripts/tooltip.js"
    ></script>
    <script
    type="module"
    src="https://unpkg.com/@material-tailwind/html@latest/scripts/popover.js"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/collapse.js"></script>
@endpush


