<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>@yield('title', 'Asif Associates')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
    @yield('styles')

    <link rel="stylesheet" href="{{ asset('assets/css/app-light.css') }}" id="lightTheme">
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}" id="darkTheme" disabled>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  </head>
  <body class="vertical  light">
    <div class="wrapper">
    <main role="main" class="main-report">
        <div class="container-fluid">
          <div class="row justify-content-center">
          <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="row mb-5">
                        <div class="col-12 text-center mb-4">
                            <img class="aalogo" src="{{ asset('assets/img/logo-full.png') }}" alt="Asif Associates Logo">
                            <h2 class="mb-0 text-uppercase">Progress Report for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h2>
                            <p class="text-muted">Asif Associates <br> Chartered Accountants </p>
                        </div>
                    </div>
                    <table class="table table-borderless table-striped text-center">
                        <thead class="text-center">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Work Done</th>
                                <th scope="col" class="text-right">Presents</th>
                                <th scope="col" class="text-right">Absents</th>
                                <th scope="col" class="text-right">Leaves</th>
                                <th scope="col" class="text-right">Hours Worked</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @php $index = 1 @endphp
                            @foreach ($workHoursByMonth as $record)
                            <tr>
                                <td>{{ $index++ }}</td>
                                <td>{{ $record->work_done ?: 'Office Work' }}</td>
                                <td class="text-right">{{ $record->total_presents ?? '0' }}</td>
                                <td class="text-right">{{ $record->total_absents ?? '0' }}</td>
                                <td class="text-right">{{ $record->total_approved_leaves ?? '0' }}</td>
                                <td class="text-right">{{ $record->total_work_hours ?? '0' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row mt-5">
                        <div class="col-md-12 float-right">
                            <div class="text-right mr-2">
                                <p class="mb-2 h6">
                                    <span class="text-muted">Presents : </span>
                                    <strong>{{ $totalPresents ?? '0' }}</strong>
                                </p>
                                <p class="mb-2 h6">
                                    <span class="text-muted">Absents : </span>
                                    <strong>{{ $total_absents ?? '0' }}</strong>
                                </p>
                                <p class="mb-2 h6">
                                    <span class="text-muted">Leaves : </span>
                                    <strong>{{ $totalLeaves ?? '0' }}</strong>
                                </p>
                                <p class="mb-2 h6">
                                    <span class="text-muted">Hours Worked : </span>
                                    <span>{{ $totalWorkHours ?? '0' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
          </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/tinycolor-min.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/apps.js') }}"></script> 
    
  </body>
</html>