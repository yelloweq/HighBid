@props(['model', 'type'])

<div class="flex justify-center align-middle items-center">
    <div class="flex flex-col justify-between">
        <div class="mb-2 hover:text-blue-accent cursor-pointer p-2"
            hx-post="{{ route('rating.createOrUpdate', ['model' => $model, 'type' => $type]) }}" hx-trigger="click"
            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' hx-vals='{"value": 1}' hx-target="next .rating">
            <i class="fa-solid fa-angle-up fa-xl"></i>
        </div>

        <div class="hover:text-blue-accent cursor-pointer p-2"
            hx-post="{{ route('rating.createOrUpdate', ['model' => $model, 'type' => $type]) }}" hx-trigger="click"
            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' hx-vals='{"value": -1}' hx-target="next .rating">
            <i class="fa-solid fa-angle-down fa-xl"></i>
        </div>
    </div>
    <div>
        <span class="rating">{{ $model->getRatingAttribute() }}</span>
    </div>
</div>
