@if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="fixed top-0 left-1/2 transform  
    -translate-x-1/2 bg-laravel px-48 text-white py-3">
        <h3>{{ session('message') }}</h3>
    </div>
@endif
