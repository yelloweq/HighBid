@props(['tags'])

@foreach ($tags as $tag)
    <option value="{{ $tag->name }}">
        {{ ucfirst($tag->name) }}</option>
@endforeach
