@php
    $categories = $categories ?? [];
    $settings = $settings ?? [];
@endphp

<div class="space-y-6">
    @foreach ($categories as $category)
        @php
            $categoryKey = $category['key'];
        @endphp

        @if (isset($settings[$categoryKey]))
            <div class="border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                    <x-dynamic-component :component="$category['icon']" class="w-5 h-5 mr-2 text-blue-600" />
                    {{ $category['name'] }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($category['fields'] as $field)
                        @php
                            $key = $field['key'];
                            $value = $settings[$categoryKey][$key] ?? '';
                            $inputType = $field['type'] ?? 'text';
                            $label = $field['label'] ?? $key;
                        @endphp

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
                            <input type="{{ $inputType }}" id="{{ $key }}" value="{{ $value }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400"
                                @if ($inputType === 'password') placeholder="••••••••" @endif>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
