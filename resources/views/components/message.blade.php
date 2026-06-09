@if(session('message'))
    <div class="alert alert-primary">
        <span class="fe fe-alert-circle fe-16 mr-2"></span>
        {{ session('message') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
    <span class="fe fe-check-circle fe-16 mr-2"></span> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <span class="fe fe-minus-circle fe-16 mr-2"></span> {{ session('error') }} 
    </div>
@endif
