@extends('admin.main')

@section('title', 'Financial Reports')

@section('content')

@php
    $quickPeriods = ['7d' => '7 Days', '30d' => '30 Days', '90d' => '90 Days', 'month' => 'This Month', 'year' => 'This Year', 'all' => 'All Time'];
    $periodLabel  = $period === 'custom'
        ? $startDate->format('d M Y') . ' – ' . $endDate->format('d M Y')
        : ($quickPeriods[$period] ?? '7 Days');
@endphp

<div class="col-md-12 container-fluid">

    <div class="row align-items-center mt-3 mb-4">
        <div class="col">
            <h4 class="mb-0" style="font-family:'IBM Plex Sans',sans-serif;font-weight:700;">Financial Reports</h4>
            <small class="text-muted">{{ $periodLabel }}</small>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('e.reports') }}" id="reportFilterForm">

                {{-- Quick period pills --}}
                <div class="d-flex flex-wrap align-items-center mb-3" style="gap:6px;">
                    <span class="text-muted mr-2" style="font-size:11px;font-family:'IBM Plex Sans',sans-serif;letter-spacing:.8px;font-weight:600;">QUICK SELECT</span>
                    @foreach($quickPeriods as $key => $label)
                        <button type="submit" name="period" value="{{ $key }}"
                                style="padding:4px 14px;font-size:12px;font-family:'IBM Plex Sans',sans-serif;font-weight:600;border-radius:2px;cursor:pointer;
                                       {{ $period === $key ? 'background:#161616;color:#fff;border:1px solid #161616;' : 'background:#f4f4f4;color:#525252;border:1px solid #e0e0e0;' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                {{-- Custom date range --}}
                <div class="d-flex flex-wrap align-items-end" style="gap:10px;">
                    <span class="text-muted" style="font-size:11px;font-family:'IBM Plex Sans',sans-serif;letter-spacing:.8px;font-weight:600;line-height:2.2;">CUSTOM RANGE</span>
                    <div>
                        <label class="d-block text-muted mb-1" style="font-size:11px;">From</label>
                        <input type="date" name="from" value="{{ $period === 'custom' ? $startDate->format('Y-m-d') : '' }}"
                               class="form-control form-control-sm" style="width:150px;">
                    </div>
                    <div>
                        <label class="d-block text-muted mb-1" style="font-size:11px;">To</label>
                        <input type="date" name="to" value="{{ $period === 'custom' ? $endDate->format('Y-m-d') : '' }}"
                               class="form-control form-control-sm" style="width:150px;">
                    </div>
                    <button type="submit" class="btn btn-sm btn-dark" style="font-family:'IBM Plex Sans',sans-serif;">Apply</button>
                    <button type="submit" name="export" value="1" class="btn btn-sm btn-secondary ml-2" style="font-family:'IBM Plex Sans',sans-serif;">
                        <i class="fe fe-download fe-12"></i> Export Excel
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-trending-up fe-16 mr-1"></i> Billings vs Receipts — {{ $periodLabel }}</strong>
                </div>
                <div class="card-body" style="height:320px;position:relative;">
                    <canvas id="reportTrendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-pie-chart fe-16 mr-1"></i> Breakdown — {{ $periodLabel }}</strong>
                </div>
                <div class="card-body" style="height:320px;position:relative;">
                    <canvas id="reportDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Financial Overview Table --}}
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-dollar-sign fe-16 mr-1"></i> Financial Overview — {{ $periodLabel }}</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td><strong>Gross Sales (Services)</strong></td>
                                <td class="text-right"><strong>Rs. {{ number_format($totalSales) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Sales Tax Payable (Billed to Client)</strong></td>
                                <td class="text-right text-secondary"><strong>Rs. {{ number_format($totalTaxb) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Total Invoiced Amount</strong></td>
                                <td class="text-right"><strong>Rs. {{ number_format($totalSales + $totalTaxb) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Billing Discounts</strong></td>
                                <td class="text-right text-danger"><strong>- Rs. {{ number_format($totalBillingDiscount) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Total Receipts (Bank/Cash)</strong></td>
                                <td class="text-right text-success"><strong>Rs. {{ number_format($totalReceipts) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Tax Withheld by Client (On Receipt)</strong></td>
                                <td class="text-right text-info"><strong>Rs. {{ number_format($totalTax) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Receipt Discounts / Bad Debts</strong></td>
                                <td class="text-right text-danger"><strong>- Rs. {{ number_format($totalDiscount) }}</strong></td>
                            </tr>
                            <tr class="cds-highlight-row">
                                <td><strong style="font-size:1rem;">Net Receivable Balance</strong></td>
                                <td class="text-right aa-color"><strong style="font-size:1.15rem;">Rs. {{ number_format(($totalSales + $totalTaxb) - $totalBillingDiscount - $totalDiscount - $totalReceipts - $totalTax) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script id="reportTrendData" type="application/json">{!! json_encode(['labels' => $trendLabels, 'billings' => $trendBillings, 'receipts' => $trendReceipts]) !!}</script>
<script id="reportDonutData" type="application/json">{!! json_encode(['invoiced' => $totalSales + $totalTaxb, 'receipts' => $totalReceipts, 'discounts' => $totalDiscount + $totalBillingDiscount, 'tax' => $totalTax]) !!}</script>

<script>
(function () {
    var PLEX = { family: 'IBM Plex Sans', size: 12 };
    var _charts = {};

    function destroyAll() {
        ['reportTrendChart', 'reportDonutChart'].forEach(function (id) {
            if (_charts[id]) { try { _charts[id].destroy(); } catch (e) {} _charts[id] = null; }
        });
    }

    function buildCharts() {
        var trendEl = document.getElementById('reportTrendData');
        if (!trendEl) return;
        destroyAll();

        var trend = JSON.parse(trendEl.textContent);
        var donut = JSON.parse(document.getElementById('reportDonutData').textContent);

        var trendCanvas = document.getElementById('reportTrendChart');
        if (trendCanvas) {
            _charts.reportTrendChart = new Chart(trendCanvas, {
                type: 'line',
                data: {
                    labels: trend.labels,
                    datasets: [
                        { label: 'Billings', data: trend.billings, borderColor: '#161616', backgroundColor: 'rgba(22,22,22,0.06)', fill: true, tension: 0.4, borderWidth: 2, pointRadius: 4, pointBackgroundColor: '#161616' },
                        { label: 'Receipts', data: trend.receipts, borderColor: '#525252', backgroundColor: 'rgba(82,82,82,0.06)', fill: true, tension: 0.4, borderWidth: 2, borderDash: [5, 3], pointRadius: 4, pointBackgroundColor: '#525252' }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: function (v) { return 'Rs. ' + v.toLocaleString(); }, font: PLEX } },
                        x: { grid: { display: false }, ticks: { font: PLEX, maxTicksLimit: 12 } }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'IBM Plex Sans', size: 13 }, usePointStyle: true, padding: 16 } },
                        tooltip: { callbacks: { label: function (ctx) { return ctx.dataset.label + ': Rs. ' + ctx.parsed.y.toLocaleString(); } } }
                    }
                }
            });
        }

        var donutCanvas = document.getElementById('reportDonutChart');
        if (donutCanvas) {
            var inv = donut.invoiced, rec = donut.receipts, dis = donut.discounts, tax = donut.tax;
            var out = inv - dis - rec - tax;
            _charts.reportDonutChart = new Chart(donutCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Receipts', 'Discounts', 'Tax Withheld', 'Outstanding'],
                    datasets: [{ data: [rec, dis, tax, out > 0 ? out : 0], backgroundColor: ['#161616', '#525252', '#8d8d8d', '#c6c6c6'], borderColor: '#ffffff', borderWidth: 2, hoverOffset: 8 }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '58%',
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true, font: PLEX } },
                        tooltip: { callbacks: { label: function (c) { return c.label + ': Rs. ' + c.parsed.toLocaleString(); } } }
                    }
                }
            });
        }
    }

    function init() { setTimeout(buildCharts, 50); }
    document.removeEventListener('livewire:navigated', init);
    document.addEventListener('livewire:navigated', init);
    init();
})();
</script>
@endsection
