@php
    $items = $items ?? [];
@endphp

<div class="grid grid-cols-2 md:grid-cols-3 gap-2">
    @foreach ($items as $item)
        <div class="bg-gray-50 dark:bg-gray-700 rounded p-2">
            <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center">
                <x-dynamic-component :component="$item['icon']" class="w-3 h-3 mr-1" />
                {{ $item['label'] }}
            </p>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $item['value'] }}</p>
        </div>
    @endforeach
</div>
