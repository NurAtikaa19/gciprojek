@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tinymce@5.10.7/skins/content/default/content.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tinymce@5.10.7/skins/ui/oxide/skin.min.css">
@endsection

@section('content')
<div class="sidebar-and-mainbar-container">

    {{-- SIDEBAR --}}
    <div x-data="{ open: window.innerWidth >= 1024, search: '' }" class="sidebar"
        :style="{ width: open ? '330px' : '0' }"
        x-init="window.addEventListener('resize', () => { open = window.innerWidth >= 1024 })">
        <div class="sidebar-content" x-show="open">

            {{-- SEARCH FORM --}}
            <div class="search-container" style="position: relative; max-width: 400px; margin: 0 auto; display: flex; align-items: center;">
                <input type="text" 
                       x-model="search" 
                       placeholder="Search products or features..." 
                       class="search-input" 
                       style="width: 100%; padding: 10px 15px; font-size: 16px; border: 2px solid #ddd; border-radius: 25px; outline: none; transition: all 0.3s ease;">
            </div>

            {{-- LOOP THROUGH PRODUCTS --}}
            @foreach ($products as $product)
                <div x-data="{ open: JSON.parse(localStorage.getItem('sidebarOpen{{ $product->id }}')) || false }"
                    x-show="search === '' || 
                            '{{ $product->name }}'.toLowerCase().includes(search.toLowerCase()) ||
                            {{ json_encode($product->features->pluck('name')->toArray()) }}.some(feature => feature.toLowerCase().includes(search.toLowerCase()))">
                    <p @click="open = !open; localStorage.setItem('sidebarOpen{{ $product->id }}', open)">
                        {{ $product->name }}
                    </p>
                    <ul x-show="open">
                        {{-- LOOP THROUGH FEATURES --}}
                        @foreach ($product->features as $feature)
                            <li x-show="search === '' || 
                                        '{{ $feature->name }}'.toLowerCase().includes(search.toLowerCase()) || 
                                        '{{ $product->name }}'.toLowerCase().includes(search.toLowerCase())"
                                class="{{ request()->routeIs('home.view') && request()->route('product') === $product->route && request()->route('feature') === $feature->route ? 'activate' : '' }}">
                                <a href="{{ route('home.view', [$product->route, $feature->route]) }}">{{ $feature->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        <div @click="open = !open" class="sidebar-button" x-text="open ? '>' : '<'"></div>
    </div>

    {{-- MAINBAR --}}
    <div class="mainbar">
        @if ($content)
            {!! $content !!}
        @else
            <no-content>There is no content for you to see.</no-content>
        @endif
    </div>

</div>
@endsection
