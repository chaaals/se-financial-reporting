@props(['type' => 'success'])

@php
    $typeData = [
        'success' => [
            'class' => 'bg-green-500',
            'title' => 'Success',
            'icon' => 'financial-reporting.assets.toast-success',
        ],
        'error' => [
            'class' => 'bg-red-500',
            'title' => 'Error',
            'icon' => 'financial-reporting.assets.toast-error',
        ],
        'info' => [
            'class' => 'bg-yellow-500',
            'title' => 'Information',
            'icon' => 'financial-reporting.assets.toast-info',
        ],
    ];

    $typeInfo = $typeData[$type] ?? $typeData['success'];
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 3000)"
     x-transition:enter="transition ease-out duration-300" 
     x-transition:enter-start="opacity-0 transform translate-y-4" 
     x-transition:enter-end="opacity-100 transform translate-y-0" 
     x-transition:leave="transition ease-in duration-300" 
     x-transition:leave-start="opacity-100 transform translate-y-0" 
     x-transition:leave-end="opacity-0 transform translate-y-4" 
     class="{{ $typeInfo['class'] }} fixed bottom-4 left-4 p-2 rounded-md text-white font-bold flex justify-between" 
     style="width: 300px;">

  <div class="flex gap-4 items-center justify-center">
    <x-dynamic-component :component="$typeInfo['icon']" />

    <div class="flex-col items-center gap-2">
      <h2 class='text-xl font-bold text-black'>{{ $typeInfo['title'] }}</h2>
      <p class="text-sm font-extralight text-black">{{ $slot }}</p>
    </div>
  </div>
</div>
