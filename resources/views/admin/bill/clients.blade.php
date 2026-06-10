@extends('admin.main')

@section('title', 'Asif Associates | Clients Management')

@section('content')

<div class="col-md-12 container-fluid">
    @include('components.message')
    @livewire('client-list')
</div>


@endsection


