@extends('admin.main')

@section('title', 'Asif Associates | Admin Dashboard')

@section('content')

<div class="col-md-12 container-fluid">

    <div class="row my-4">

        <div class="col-md-4">
            <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted mb-1">Cumulative</small>
                    <h3 class="card-title mb-0">Associates</h3>
                    <p class="small text-muted mb-0">
                    <span class="aa-color" style="font-size:1.5rem; font-weight:700;">{{ $numberOfAss }}</span>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <i class="fe fe-user-check fe-32" style="color: #f4af1a;"></i>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted mb-1">Cumulative</small>
                    <h3 class="card-title mb-0">Work Hours</h3>
                    <p class="small text-muted mb-0">
                    <span class="aa-color" style="font-size:1.5rem; font-weight:700;">{{ $totalWorkHours }}</span>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <i class="fe fe-clock fe-32" style="color: #17a2b8;"></i>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted mb-1">Cumulative</small>
                    <h3 class="card-title mb-0">Pending Leaves</h3>
                    <p class="small text-muted mb-0">
                    <span class="aa-color" style="font-size:1.5rem; font-weight:700;">{{ $totalLeaves }}</span>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <i class="fe fe-cloud-drizzle fe-32" style="color: #dc3545;"></i>
                </div>
                </div>
            </div>
            </div>
        </div>

    </div>

    {{-- Financial Summary Table --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong class="card-title mb-0"><i class="fe fe-dollar-sign fe-16 mr-1"></i> Financial Overview</strong>
                    <a href="{{ route('export.client.report') }}" class="btn btn-sm btn-primary">
                        <i class="fe fe-download fe-12 mr-1"></i> Export Excel
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <td><strong>Gross Sales (Services)</strong></td>
                                <td class="text-right"><strong>Rs. {{ number_format($totalSales) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Sales Tax Payable (Billed to Client)</strong></td>
                                <td class="text-right text-warning"><strong>Rs. {{ number_format($totalTaxb) }}</strong></td>
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
                            <tr style="background: linear-gradient(135deg, #fff9e6, #fff3cc); border-top: 2px solid #f4af1a;">
                                <td><strong style="font-size: 1.1rem;">Net Receivable Balance</strong></td>
                                <td class="text-right aa-color"><strong style="font-size: 1.25rem;">Rs. {{ number_format(($totalSales + $totalTaxb) - ($totalBillingDiscount ?? 0) - $totalDiscount - $totalReceipts - $totalTax) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 7-Day Trend Chart - Full Width --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-trending-up fe-16 mr-1"></i> Last 7 Days — Billings vs Receipts</strong>
                </div>
                <div class="card-body" style="height: 350px; position: relative;">
                    <canvas id="weeklyTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Doughnut + Bar Charts --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-pie-chart fe-16 mr-1"></i> 7-Day Financial Breakdown</strong>
                </div>
                <div class="card-body" style="height: 320px; position: relative;">
                    <canvas id="billingChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title mb-0"><i class="fe fe-users fe-16 mr-1"></i> Associate Status</strong>
                </div>
                <div class="card-body" style="height: 320px; position: relative;">
                    <canvas id="associateChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Quran Ayat --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="ayat text-center">
                        <span class="ayat-text"><b>"the life of this world is no more than the delusion of enjoyment"</b></span>
                        <br>
                        <span class="ref-text"><i>Quran (3:185)</i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
function initDashboardCharts() {
    // Destroy existing charts if traversing back to clean canvas state
    Chart.getChart("weeklyTrendChart")?.destroy();
    Chart.getChart("billingChart")?.destroy();
    Chart.getChart("associateChart")?.destroy();

    // 7-Day Trend Line Chart
    var weeklyEl = document.getElementById('weeklyTrendChart');
    if (weeklyEl) {
        new Chart(weeklyEl.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyLabels) !!},
                datasets: [
                    {
                        label: 'Billings',
                        data: {!! json_encode($dailyBillings) !!},
                        borderColor: 'rgba(244, 175, 26, 1)',
                        backgroundColor: 'rgba(244, 175, 26, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(244, 175, 26, 1)',
                    },
                    {
                        label: 'Receipts',
                        data: {!! json_encode($dailyReceipts) !!},
                        borderColor: 'rgba(40, 167, 69, 1)',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(40, 167, 69, 1)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            callback: function(v) { return 'Rs. ' + v.toLocaleString(); }
                        }
                    },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ctx.dataset.label + ': Rs. ' + ctx.parsed.y.toLocaleString(); }
                        }
                    }
                }
            }
        });
    }

    // Financial Doughnut Chart (7 Days)
    var billingEl = document.getElementById('billingChart');
    if (billingEl) {
        var invoiced = {{ $weeklySales + $weeklyTaxb }};
        var receipts = {{ $weeklyReceipts }};
        var discounts = {{ $weeklyDiscount + ($weeklyBillingDiscount ?? 0) }};
        var taxWithheld = {{ $weeklyTax }};
        var payable = invoiced - discounts - receipts - taxWithheld;

        new Chart(billingEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Receipts', 'Discounts', 'Tax Withheld', 'Outstanding'],
                datasets: [{
                    data: [receipts, discounts, taxWithheld, payable > 0 ? payable : 0],
                    backgroundColor: ['rgba(40,167,69,0.85)', 'rgba(220,53,69,0.85)', 'rgba(255,193,7,0.85)', 'rgba(244,175,26,0.85)'],
                    borderWidth: 2, hoverOffset: 8
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '55%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true } },
                    tooltip: { callbacks: { label: function(c) { return c.label + ': Rs. ' + c.parsed.toLocaleString(); } } }
                }
            }
        });
    }

    // Associate Status Doughnut
    var assocEl = document.getElementById('associateChart');
    if (assocEl) {
        new Chart(assocEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Archived'],
                datasets: [{
                    data: [{{ $activeAssociates }}, {{ $archivedAssociates }}],
                    backgroundColor: ['rgba(40,167,69,0.85)', 'rgba(220,53,69,0.85)'],
                    borderColor: ['rgba(40,167,69,1)', 'rgba(220,53,69,1)'],
                    borderWidth: 2, hoverOffset: 8
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '55%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } },
                    tooltip: { callbacks: { label: function(c) { return c.label + ': ' + c.parsed; } } }
                }
            }
        });
    }
}

// Mount for standard hard-loads (F5) explicitly to prevent boot misses:
document.addEventListener('DOMContentLoaded', initDashboardCharts);
// Mount for Livewire SPA inner-transitions:
document.addEventListener('livewire:navigated', initDashboardCharts);
</script>
@endsection
