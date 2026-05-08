@props(['event'])

@php
    // We use the 'status_details' accessor we defined in the Event.php Model
    $details = $event->status_details;
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border ' . $details['color']]) }}>
    <span class="mr-1.5 text-xs">{{ $details['dot'] }}</span>
    {{ $details['label'] }}
</div>