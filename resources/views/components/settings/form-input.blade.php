@props([
    'type' => 'text',
    'label' => '',
    'model' => '',
    'placeholder' => '',
    'description' => '',
    'min' => null,
    'max' => null,
    'options' => [],
    'required' => false
])

<div class="space-y-2">
    <label class="block text-sm font-medium text-slate-300">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    @if($type === 'select')
        <select 
            x-model="{{ $model }}"
            @change="markAsChanged()"
            class="w-full px-3 py-2 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-700 text-white transition-colors"
        >
            @foreach($options as $value => $text)
                <option value="{{ $value }}">{{ $text }}</option>
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea 
            x-model="{{ $model }}"
            @input="markAsChanged()"
            class="w-full px-3 py-2 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-700 text-white transition-colors resize-none"
            rows="3"
            placeholder="{{ $placeholder }}"
        ></textarea>
    @else
        <input 
            type="{{ $type }}" 
            x-model="{{ $model }}"
            @input="markAsChanged()"
            class="w-full px-3 py-2 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-700 text-white transition-colors"
            placeholder="{{ $placeholder }}"
            @if($min) min="{{ $min }}" @endif
            @if($max) max="{{ $max }}" @endif
            @if($required) required @endif
        >
    @endif
    
    @if($description)
        <p class="text-xs text-slate-400">{{ $description }}</p>
    @endif
</div>