@props(['title' => null, 'heading' => null, 'subtitle' => null])

@include('layouts.public', [
    'title' => $title,
    'heading' => $heading,
    'subtitle' => $subtitle,
    'slot' => $slot,
])
