@props(['title', 'count', 'color'])

<div class="bg-{{ $color }}-600 text-white p-4 rounded-lg shadow">
    <h3 class="text-lg">{{ $title }}</h3>
    <p class="text-2xl font-bold">{{ $count }}</p>
</div>
