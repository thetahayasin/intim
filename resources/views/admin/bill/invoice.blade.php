<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Invoice">
    <meta name="author" content="Consultancy Firm">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <title>Invoice: {{ $bill->id }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Overpass:wght@100;200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app-light.css') }}" id="lightTheme">
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}" id="darkTheme" disabled>
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <style>
      @media print {
        body { background: white !important; }
        .no-print { display: none !important; }
      }
      .invoice-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .invoice-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
      }
      .divi {
        border: 1px solid black;
      }
      thead {
        color: black !important;
        border-top: 2px solid black !important;
      }
      tr {
        border-bottom: 2px solid black !important;
      }
      th {
        color: black !important;
        border-bottom: 1px solid black !important;
      }
      td {
        color: black;
      }
      body {
        color: black;
      }
      .totals-table td {
        border: none !important;
        padding: 4px 8px;
      }
      .totals-table tr {
        border-bottom: none !important;
      }
      .grand-total-row td {
        border-top: 2px solid black !important;
        font-size: 1.1em;
      }
    </style>
  </head>
  <body class="vertical light">
    <div class="wrapper">
    <main role="main" class="main-invoice">
        <div class="container-fluid">
          <div class="row justify-content-center">
          <div class="col-md-12">
            <div class="card" style="border:none">
                <div class="card-body p-5">
                    {{-- Print Button --}}
                    <div class="text-right mb-3 no-print">
                        <button onclick="window.print()" class="btn btn-primary btn-sm mr-2">
                            <i class="fe fe-printer fe-16"></i> Print
                        </button>
                        <a wire:navigate href="{{ route('e.billings') }}" class="btn btn-secondary btn-sm">
                            <i class="fe fe-arrow-left fe-16"></i> Back
                        </a>
                    </div>

                    {{-- Header --}}
                    <div class="row mb-5 invoice-header">
                        <div class="col-md-6">
                            <img style="width:250px" class="firm-logo" src="{{ ($bill->firm == 1) ? asset('assets/img/hamd.png') : asset('assets/img/logo-full.png') }}" alt="">
                        </div>
                        <div class="col-md-6 d-flex flex-column align-items-end text-left invoice-details">
                            <p><strong>Invoice No:</strong> {{ $bill->id }}</p>
                            <p><strong>NTN:</strong> 1142266-1</p>
                            <p><strong>STRN:</strong> 3277876167618</p>
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($bill->created_at)->format('F j, Y') }}</p>
                        </div>
                    </div>

                    <h5><strong>Client:</strong> {{ $bill->client->name }}</h5>
                    <hr class="divi">
                    <p>We append a memo of our charges for Professional Services rendered, for which we shall be glad</p>
                    <p>to receive an early remittance.</p>
                    <br>
                    <p><b>Account Title: {{ ($bill->firm == 1) ? 'H.A.M.D & CO' : 'Asif Associates Chartered Accountants' }}</b></p>
                    <p><b>Account No: {{ ($bill->firm == 1) ? '0702-0010001693470058' : '0301-0112963118' }}</b></p>
                    <p><b>IBAN: {{ ($bill->firm == 1) ? 'PK31ABPA0010001693470058' : 'PK97MEZN0003010112963118' }}</b></p>
                    <p><b>{{ ($bill->firm == 1) ? 'Allied Bank Limited' : 'Meezan Bank Ltd.' }}</b></p>
                    <p><b>Blue Area, Islamabad</b></p>
                    <br>
                    <br>

                    {{-- Invoice Items Table --}}
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th><b>#</b></th>
                                <th><b>Description</b></th>
                                <th><b>Service</b></th>
                                <th><b>Fee Charged</b></th>
                                <th><b>Sales Tax</b></th>
                                <th class="text-right"><b>Total Amount</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($bill->items->count() > 0)
                                {{-- Multi-item invoice --}}
                                @foreach($bill->items as $index => $item)
                                    <tr>
                                        <td><b>{{ $index + 1 }}</b></td>
                                        <td><b>{{ $item->description }}</b></td>
                                        <td><b>{{ $item->service ?: $bill->remarks }}</b></td>
                                        <td><b>{{ number_format($item->amount) }}</b></td>
                                        <td><b>{{ number_format($item->tax) }}</b></td>
                                        <td class="text-right"><b>{{ number_format($item->amount + $item->tax) }}</b></td>
                                    </tr>
                                @endforeach
                            @else
                                {{-- Legacy single-item invoice --}}
                                <tr>
                                    <td><b>1</b></td>
                                    <td><b>{{ $bill->description }}</b></td>
                                    <td><b>{{ $bill->remarks }}</b></td>
                                    <td><b>{{ number_format($bill->amount) }}</b></td>
                                    <td><b>{{ number_format($bill->tax) }}</b></td>
                                    <td class="text-right"><b>{{ number_format($bill->amount + $bill->tax) }}</b></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <br>

                    {{-- Totals --}}
                    <div class="row mt-3">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <table class="table totals-table text-right">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td>Rs. {{ number_format($bill->computed_amount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sales Tax:</strong></td>
                                    <td>Rs. {{ number_format($bill->computed_tax) }}</td>
                                </tr>
                                @if((float) $bill->discount > 0)
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td style="color: red;">- Rs. {{ number_format($bill->discount) }}</td>
                                </tr>
                                @endif
                                <tr class="grand-total-row">
                                    <td><strong>Total Amount Due:</strong></td>
                                    <td><strong>Rs. {{ number_format($bill->grand_total) }}/-</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <br>
                <hr class="divi">
                <p class="text-center mt-4 text-muted">This is a computer-generated invoice and does not require a signature.</p>
                </div>

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
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/apps.js') }}"></script>
  </body>
</html>
