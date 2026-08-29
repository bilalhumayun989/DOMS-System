@props(['items' => []])
<nav class="flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        @foreach($items as $index => $item)
            <li class="inline-flex items-center">
                @if($index > 0)
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                @endif
                @if($item['route'] && $index < count($items) - 1)
                    <a href="{{ $item['route'] }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-sm font-medium text-gray-500">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
