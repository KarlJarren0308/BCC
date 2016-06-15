@if(session()->has('flash_status'))
    <div class="gap-vertical">
        <div class="super alert alert-{{ session()->get('flash_status') }}">{{ session()->get('flash_message') }}</div>
    </div>
@endif