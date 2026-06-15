@extends('admin.main')

@section('title', 'Asif Associates | Admin Dashboard')

@section('content')

<div class="col-md-12 container-fluid">

    {{-- Row 1: Donut Charts --}}
    <div class="row my-4">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-pie-chart fe-16 mr-1"></i> 7-Day Financial Breakdown</strong>
                </div>
                <div class="card-body" style="height: 300px; position: relative;">
                    <canvas id="billingChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-users fe-16 mr-1"></i> Associate Status</strong>
                </div>
                <div class="card-body" style="height: 300px; position: relative;">
                    <canvas id="associateChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Financial Table + Stat Cards --}}
    <div class="row">

        {{-- Financial Overview --}}
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong class="card-title mb-0"><i class="fe fe-dollar-sign fe-16 mr-1"></i> Financial Overview</strong>
                    <a href="{{ route('e.reports') }}" wire:navigate class="btn btn-sm btn-dark">
                        <i class="fe fe-bar-chart-2 fe-12"></i> Reports
                    </a>
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
                                <td class="text-right text-danger"><strong>- Rs. {{ number_format($totalBillingDiscount ?? 0) }}</strong></td>
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
                                <td><strong style="font-size: 1rem;">Net Receivable Balance</strong></td>
                                <td class="text-right aa-color"><strong style="font-size: 1.15rem;">Rs. {{ number_format(($totalSales + $totalTaxb) - ($totalBillingDiscount ?? 0) - $totalDiscount - $totalReceipts - $totalTax) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="col-md-5">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Cumulative</small>
                            <div class="h5 mb-0 font-weight-bold">Associates</div>
                            <div class="aa-color mt-1" style="font-size:1.75rem; font-weight:700; line-height:1;">{{ $numberOfAss }}</div>
                        </div>
                        <i class="fe fe-user-check" style="font-size:2rem; color: var(--cds-border-strong);"></i>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Cumulative</small>
                            <div class="h5 mb-0 font-weight-bold">Work Hours</div>
                            <div class="cds-stat-hours" style="font-size:1.75rem; font-weight:700; line-height:1; margin-top:4px;">{{ $totalWorkHours }}</div>
                        </div>
                        <i class="fe fe-clock" style="font-size:2rem; color: var(--cds-border-strong);"></i>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Cumulative</small>
                            <div class="h5 mb-0 font-weight-bold">Pending Leaves</div>
                            <div class="cds-stat-absents" style="font-size:1.75rem; font-weight:700; line-height:1; margin-top:4px;">{{ $totalLeaves }}</div>
                        </div>
                        <i class="fe fe-cloud-drizzle" style="font-size:2rem; color: var(--cds-border-strong);"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Row 3: Trend Chart + Ayat --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-trending-up fe-16 mr-1"></i> Last 7 Days — Billings vs Receipts</strong>
                </div>
                <div class="card-body" style="height: 320px; position: relative;">
                    <canvas id="weeklyTrendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4" style="height: calc(100% - 1.5rem);">
                <div class="card-body d-flex align-items-center justify-content-center text-center">
                    <div>
                        <span class="ayat-text"><b>"the life of this world is no more than the delusion of enjoyment"</b></span>
                        <br><br>
                        <span class="text-muted"><i>Quran (3:185)</i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script id="dashTrendData" type="application/json">{!! json_encode(['labels' => $dailyLabels, 'billings' => $dailyBillings, 'receipts' => $dailyReceipts]) !!}</script>
<script id="dashBillingData" type="application/json">{!! json_encode(['invoiced' => $weeklySales + $weeklyTaxb, 'receipts' => $weeklyReceipts, 'discounts' => $weeklyDiscount + ($weeklyBillingDiscount ?? 0), 'tax' => $weeklyTax]) !!}</script>
<script id="dashAssocData" type="application/json">{!! json_encode(['active' => $activeAssociates, 'archived' => $archivedAssociates]) !!}</script>

<script>
(function() {
    var PLEX = { family: 'IBM Plex Sans', size: 12 };
    var _charts = {};

    function destroyAll() {
        ['weeklyTrendChart', 'billingChart', 'associateChart'].forEach(function(id) {
            if (_charts[id]) { try { _charts[id].destroy(); } catch(e) {} _charts[id] = null; }
        });
    }

    function buildCharts() {
        var trendEl = document.getElementById('dashTrendData');
        if (!trendEl) return;

        destroyAll();

        var trend   = JSON.parse(trendEl.textContent);
        var billing = JSON.parse(document.getElementById('dashBillingData').textContent);
        var assoc   = JSON.parse(document.getElementById('dashAssocData').textContent);

        // 7-Day Trend
        var weeklyEl = document.getElementById('weeklyTrendChart');
        if (weeklyEl) {
            _charts.weeklyTrendChart = new Chart(weeklyEl, {
                type: 'line',
                data: {
                    labels: trend.labels,
                    datasets: [
                        {
                            label: 'Billings',
                            data: trend.billings,
                            borderColor: '#161616',
                            backgroundColor: 'rgba(22,22,22,0.06)',
                            fill: true, tension: 0.4, borderWidth: 2,
                            pointRadius: 4, pointBackgroundColor: '#161616'
                        },
                        {
                            label: 'Receipts',
                            data: trend.receipts,
                            borderColor: '#525252',
                            backgroundColor: 'rgba(82,82,82,0.06)',
                            fill: true, tension: 0.4, borderWidth: 2,
                            borderDash: [5, 3],
                            pointRadius: 4, pointBackgroundColor: '#525252'
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { callback: function(v) { return 'Rs. ' + v.toLocaleString(); }, font: PLEX }
                        },
                        x: { grid: { display: false }, ticks: { font: PLEX } }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'IBM Plex Sans', size: 13 }, usePointStyle: true, padding: 16 } },
                        tooltip: { callbacks: { label: function(ctx) { return ctx.dataset.label + ': Rs. ' + ctx.parsed.y.toLocaleString(); } } }
                    }
                }
            });
        }

        // Financial Doughnut
        var billingEl = document.getElementById('billingChart');
        if (billingEl) {
            var inv  = billing.invoiced, rec = billing.receipts,
                dis  = billing.discounts, tax = billing.tax,
                out  = inv - dis - rec - tax;
            _charts.billingChart = new Chart(billingEl, {
                type: 'doughnut',
                data: {
                    labels: ['Receipts', 'Discounts', 'Tax Withheld', 'Outstanding'],
                    datasets: [{
                        data: [rec, dis, tax, out > 0 ? out : 0],
                        backgroundColor: ['#161616', '#525252', '#8d8d8d', '#c6c6c6'],
                        borderColor: '#ffffff', borderWidth: 2, hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '58%',
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true, font: PLEX } },
                        tooltip: { callbacks: { label: function(c) { return c.label + ': Rs. ' + c.parsed.toLocaleString(); } } }
                    }
                }
            });
        }

        // Associate Doughnut
        var assocEl = document.getElementById('associateChart');
        if (assocEl) {
            _charts.associateChart = new Chart(assocEl, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Archived'],
                    datasets: [{
                        data: [assoc.active, assoc.archived],
                        backgroundColor: ['#161616', '#c6c6c6'],
                        borderColor: '#ffffff', borderWidth: 2, hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '58%',
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, font: PLEX } },
                        tooltip: { callbacks: { label: function(c) { return c.label + ': ' + c.parsed; } } }
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
