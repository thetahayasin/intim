@extends('admin.main')

@section('title', 'Asif Associates | Client Stats')

@section('content')

<div class="col-md-12 container-fluid">
    <a wire:navigate href="{{ route('e.client') }}" class="btn btn-secondary"><i class="fe fe-arrow-left fe-16"></i> Back</a> 
    @livewire('client-stats', ['clientId' => $client->id])
</div>




@endsection


