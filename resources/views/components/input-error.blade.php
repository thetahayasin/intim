@props(['messages'])

@if ($messages)
    <div class="invalid-feedback login-error">
            @foreach ((array) $messages as $message)
                {{ $message }}
            @endforeach
    </div>
@endif
