@extends('admin.main')

@section('title', 'Asif Associates | Associate Management')

@section('styles')

<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">

    
@endsection

@section('content')


<div class="col-md-12 container-fluid">
    @include('components.message')
    <div class="row">
        <div class="col-md-6 my-4">
            <div class="card shadow">
                <div class="card-header">
                    <strong class="card-title">Holiday Dates</strong>
                </div>
                <div class="card-body att-body">
                    <form action="{{ route('save.holidays') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control @error('description') is-invalid @enderror">
                            @error('description')
                                <div class="invalid-feedback text-left">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="holidaypicker">Select Date</label>
                            <input type="text" id="holidayPicker" name="holidays" class="form-control" placeholder="Select date range">
                            
                            @error('holidays')
                                <div class="invalid-feedback text-left">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <br>
                        <button type="submit" class="btn btn-primary">Save Holidays</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 my-4">
            <div class="card shadow">
                <div class="card-body att-body">
                    <h5 class="card-title">History</h5>
                    <table class="table table-hover att-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Due to</th>
                                <th>Year</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $h)
                                <tr>
                                    <td>{{ $h->holiday_date }}</td>
                                    <td>{{ $h->description }}</td>
                                    <td>{{ \Carbon\Carbon::parse($h->holiday_date)->year }}</td>
                                    
                                    <td>
                                        <form action="{{ route('delete.holidays', $h->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button style="color:white" type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
        
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $history->links() }}
                    </div>
                </div>
            </div>
        </div>
    
    </div>


</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#holidayPicker').daterangepicker({
            minDate: moment().add(1, 'days'),  // Starts from tomorrow
            maxDate: moment().endOf('year'),  // Ends on December 31
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
</script>



@endsection


