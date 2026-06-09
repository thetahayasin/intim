@extends('admin.main')

@section('title', 'Asif Associates | Billing Stats')

@section('content')

<div class="col-md-12 container-fluid">
    <a href="{{ URL::previous() }}" class="btn btn-secondary"><i class="fe fe-arrow-left fe-16"></i> Back</a> 
    <div class="row">
        <div class="col-md-6 my-4">
        @include('components.message')
        @if($sales->count() != NULL)
            <div class="list-group mb-5 shadow">
                @foreach($sales as $s)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                        <div class="col">
                            <strong class="mb-2">{{ date('M d, Y', strtotime($s->created_at)) }} </strong>
                            @if($s->billing->recursive == true)
                                <span class="badge badge-pill badge-success">Recursive</span>
                            @else
                                <span class="badge badge-pill badge-warning">One Time</span>
                            @endif
                            @if($s->billing->halt == true)
                                <span class="badge badge-pill badge-danger">This Biilling is Stopped</span>
                            @else
                                <span class="badge badge-pill badge-success">Active</span>
                            @endif
                            <p class="text-muted mb-0">{{ $s->amount }}</p>
                        </div>
                        <!-- .col -->
                        <div class="col-auto">
                            <form action="{{ route('e.sale.delete', $s->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button style="color:white" type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                        <!-- .col -->
                        </div>
                        <!-- .row -->
                    </div>
                @endforeach

            </div>
        @else
        No record
        @endif
    </div>
</div>




@endsection


