@extends('admin.main')

@section('title', 'Duplicate Client Scanner')

@section('styles')
<style>
    .dup-scan-btn {
        background: #da1e28; color: #fff; border: none; padding: 10px 24px;
        font-weight: 600; cursor: pointer; border-radius: 4px; font-size: 14px;
    }
    .dup-scan-btn:hover { background: #b81922; }
    .dup-scan-btn:disabled { opacity: .5; cursor: not-allowed; }

    .dup-group {
        border: 1px solid #e0e0e0; border-radius: 8px; margin-bottom: 28px;
        background: #fff; overflow: hidden;
    }
    .dup-group-header {
        background: #262626; color: #f4f4f4; padding: 12px 20px;
        font-weight: 600; font-size: 14px; letter-spacing: .5px;
    }
    .dup-client {
        padding: 20px; border-bottom: 1px solid #e0e0e0;
    }
    .dup-client:last-child { border-bottom: none; }
    .dup-client-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 14px; flex-wrap: wrap; gap: 10px;
    }
    .dup-client-name {
        font-size: 18px; font-weight: 700; color: #161616;
    }
    .dup-client-meta {
        font-size: 12px; color: #6f6f6f; margin-top: 2px;
    }
    .dup-merge-btn {
        padding: 6px 16px; border: none; border-radius: 4px;
        font-weight: 600; cursor: pointer; font-size: 12px;
    }
    .merge-keep { background: #198038; color: #fff; }
    .merge-keep:hover { background: #0e6027; }
    .merge-remove { background: #da1e28; color: #fff; }
    .merge-remove:hover { background: #b81922; }

    .dup-table {
        width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 8px;
    }
    .dup-table th {
        background: #f4f4f4; text-align: left; padding: 8px 12px;
        font-weight: 600; font-size: 11px; text-transform: uppercase;
        letter-spacing: .5px; color: #525252; border-bottom: 2px solid #e0e0e0;
    }
    .dup-table td {
        padding: 7px 12px; border-bottom: 1px solid #e8e8e8; color: #393939;
    }
    .dup-table tbody tr:hover { background: #f9f9f9; }

    .dup-section-label {
        font-size: 12px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; color: #8d8d8d; margin: 14px 0 6px;
    }
    .dup-empty { color: #a8a8a8; font-style: italic; font-size: 13px; }
    .dup-spinner {
        display: inline-block; width: 18px; height: 18px; border: 3px solid rgba(255,255,255,.3);
        border-top-color: #fff; border-radius: 50%; animation: dup-spin .7s linear infinite;
        vertical-align: middle; margin-right: 8px;
    }
    @keyframes dup-spin { to { transform: rotate(360deg); } }

    #dupResults .dup-alert {
        padding: 14px 20px; border-radius: 6px; margin-bottom: 16px; font-size: 14px;
    }
    .dup-alert-error { background: #fff1f1; border: 1px solid #da1e28; color: #da1e28; }
    .dup-alert-success { background: #defbe6; border: 1px solid #198038; color: #0e6027; }
    .dup-alert-info { background: #edf5ff; border: 1px solid #0f62fe; color: #0043ce; }

    .dup-merge-actions {
        display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-top: 10px;
        padding-top: 12px; border-top: 1px dashed #e0e0e0;
    }
    .dup-merge-actions select {
        padding: 6px 10px; border: 1px solid #c6c6c6; border-radius: 4px;
        font-size: 13px; background: #fff;
    }
    .dup-merged-badge {
        display: inline-block; background: #198038; color: #fff; padding: 3px 10px;
        border-radius: 12px; font-size: 11px; font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="col-md-12 my-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:10px;">
        <div>
            <h4 class="mb-1" style="font-weight:700;"><i class="fe fe-copy fe-24 mr-2"></i> Duplicate Client Scanner</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Uses AI to identify potentially duplicate clients and allows merging their records.</p>
        </div>
        <button class="dup-scan-btn" id="scanBtn" onclick="startScan()">
            <i class="fe fe-search fe-16 mr-1"></i> Scan for Duplicates
        </button>
    </div>

    <div id="dupResults"></div>
</div>
@endsection

@section('scripts')
<script>
var dupGroups = [];

function startScan() {
    var btn = document.getElementById('scanBtn');
    var res = document.getElementById('dupResults');
    btn.disabled = true;
    btn.innerHTML = '<span class="dup-spinner"></span> Scanning with AI...';
    res.innerHTML = '';

    fetch('{{ route("e.duplicates.scan") }}', {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fe fe-search fe-16 mr-1"></i> Scan Again';

        if (data.error) {
            res.innerHTML = '<div class="dup-alert dup-alert-error">' + data.error + '</div>';
            return;
        }

        dupGroups = data.groups || [];
        if (!dupGroups.length) {
            res.innerHTML = '<div class="dup-alert dup-alert-success"><i class="fe fe-check-circle mr-1"></i> No duplicate clients found. All clean!</div>';
            return;
        }

        res.innerHTML = '<div class="dup-alert dup-alert-info"><i class="fe fe-alert-circle mr-1"></i> Found <strong>' + dupGroups.length + '</strong> potential duplicate group(s). Review below.</div>';
        renderGroups();
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fe fe-search fe-16 mr-1"></i> Scan for Duplicates';
        res.innerHTML = '<div class="dup-alert dup-alert-error">Network error: ' + err.message + '</div>';
    });
}

function renderGroups() {
    var res = document.getElementById('dupResults');
    var existing = res.querySelector('.dup-alert');
    var html = existing ? existing.outerHTML : '';

    dupGroups.forEach(function(group, gi) {
        html += '<div class="dup-group" id="dupGroup' + gi + '">';
        html += '<div class="dup-group-header">Duplicate Group #' + (gi + 1) + ' — ' + group.length + ' clients</div>';

        group.forEach(function(client, ci) {
            html += '<div class="dup-client" id="dupClient' + client.id + '">';
            html += '<div class="dup-client-header">';
            html += '<div>';
            html += '<div class="dup-client-name">' + esc(client.name) + ' <span style="color:#a8a8a8;font-size:13px;font-weight:400;">#' + client.id + '</span></div>';
            html += '<div class="dup-client-meta">';
            if (client.email) html += '<i class="fe fe-mail fe-12 mr-1"></i>' + esc(client.email) + ' &nbsp; ';
            if (client.representative) html += '<i class="fe fe-user fe-12 mr-1"></i>' + esc(client.representative) + ' &nbsp; ';
            if (client.rep_contact) html += '<i class="fe fe-phone fe-12 mr-1"></i>' + esc(client.rep_contact) + ' &nbsp; ';
            if (client.created_at) html += '<i class="fe fe-calendar fe-12 mr-1"></i>Added: ' + client.created_at;
            html += '</div></div></div>';

            // Billings
            html += '<div class="dup-section-label"><i class="fe fe-dollar-sign fe-12 mr-1"></i>Billings (' + client.billings.length + ')</div>';
            if (client.billings.length) {
                html += '<table class="dup-table"><thead><tr><th>ID</th><th>Description</th><th>Service</th><th>Amount</th><th>Tax</th><th>Total</th><th>Firm</th><th>Date</th></tr></thead><tbody>';
                client.billings.forEach(function(b) {
                    html += '<tr><td>' + b.id + '</td><td>' + esc(b.description || '—') + '</td><td>' + esc(b.remarks || '—') + '</td><td>' + b.amount + '</td><td>' + b.tax + '</td><td><strong>' + b.total + '</strong></td><td>' + (b.firm == 1 ? 'HAMD' : 'AA') + '</td><td>' + (b.date || '—') + '</td></tr>';
                });
                html += '</tbody></table>';
            } else {
                html += '<div class="dup-empty">No billings</div>';
            }

            // Receipts
            html += '<div class="dup-section-label"><i class="fe fe-shopping-cart fe-12 mr-1"></i>Receipts (' + client.receipts.length + ')</div>';
            if (client.receipts.length) {
                html += '<table class="dup-table"><thead><tr><th>ID</th><th>Amount</th><th>Date</th></tr></thead><tbody>';
                client.receipts.forEach(function(r) {
                    html += '<tr><td>' + r.id + '</td><td>' + r.amount + '</td><td>' + (r.date || '—') + '</td></tr>';
                });
                html += '</tbody></table>';
            } else {
                html += '<div class="dup-empty">No receipts</div>';
            }

            html += '</div>'; // .dup-client
        });

        // Merge controls
        html += '<div style="padding:14px 20px;background:#f4f4f4;">';
        html += '<div class="dup-merge-actions" id="mergeControls' + gi + '">';
        html += '<strong style="font-size:13px;">Merge:</strong> ';
        html += '<select id="mergeFrom' + gi + '">';
        group.forEach(function(c) { html += '<option value="' + c.id + '">' + esc(c.name) + ' #' + c.id + '</option>'; });
        html += '</select>';
        html += '<span style="font-size:13px;color:#6f6f6f;">→ into →</span>';
        html += '<select id="mergeTo' + gi + '">';
        group.forEach(function(c, i) { html += '<option value="' + c.id + '"' + (i === group.length - 1 ? ' selected' : '') + '>' + esc(c.name) + ' #' + c.id + '</option>'; });
        html += '</select>';
        html += '<button class="dup-merge-btn merge-keep" onclick="doMerge(' + gi + ')"><i class="fe fe-git-merge fe-12 mr-1"></i>Merge Now</button>';
        html += '</div></div>';

        html += '</div>'; // .dup-group
    });

    res.innerHTML = html;
}

function doMerge(gi) {
    var fromSel = document.getElementById('mergeFrom' + gi);
    var toSel = document.getElementById('mergeTo' + gi);
    var removeId = fromSel.value;
    var keepId = toSel.value;

    if (removeId === keepId) {
        alert('Cannot merge a client into itself. Select different clients.');
        return;
    }

    var removeName = fromSel.options[fromSel.selectedIndex].text;
    var keepName = toSel.options[toSel.selectedIndex].text;

    if (!confirm('Merge "' + removeName + '" INTO "' + keepName + '"?\n\nAll billings, receipts, sales and documents will be moved to "' + keepName + '" and the other client will be DELETED.\n\nThis cannot be undone.')) return;

    var controls = document.getElementById('mergeControls' + gi);
    controls.innerHTML = '<span class="dup-spinner" style="border-color:rgba(0,0,0,.15);border-top-color:#161616;"></span> Merging...';

    fetch('{{ route("e.duplicates.merge") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ keep_id: keepId, remove_id: removeId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            controls.innerHTML = '<span class="dup-merged-badge"><i class="fe fe-check fe-12 mr-1"></i>Merged!</span> <span style="font-size:13px;color:#525252;margin-left:6px;">' + esc(data.message) + '</span>';
            // Grey out the removed client
            var removedEl = document.getElementById('dupClient' + removeId);
            if (removedEl) {
                removedEl.style.opacity = '.35';
                removedEl.style.pointerEvents = 'none';
                removedEl.querySelector('.dup-client-name').innerHTML += ' <span style="background:#da1e28;color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;">DELETED</span>';
            }
        } else {
            controls.innerHTML = '<div class="dup-alert dup-alert-error" style="margin:0;">' + (data.error || data.message || 'Merge failed') + '</div>';
        }
    })
    .catch(err => {
        controls.innerHTML = '<div class="dup-alert dup-alert-error" style="margin:0;">Network error: ' + err.message + '</div>';
    });
}

function esc(s) {
    if (!s) return '';
    var d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}
</script>
@endsection
