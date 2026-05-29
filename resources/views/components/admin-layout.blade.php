@props(['title' => null, 'heading' => null])

@include('layouts.admin', [
    'title' => $title,
    'heading' => $heading,
    'slot' => $slot,
])
