@props(['status'])

@if ($status)

    <div class="alert alert-success">
        <span class="fe fe-check-circle fe-16 mr-2"></span> {{ $status }}
    </div>
@endif
