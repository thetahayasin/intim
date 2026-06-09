<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Proposal — {{ $doc->client_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Overpass:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Overpass', sans-serif; color: #222; background: #fff; font-size: 13px; }
        .page { max-width: 850px; margin: 0 auto; padding: 40px; }

        /* Header bar */
        .top-bar { text-align: center; letter-spacing: 3px; font-size: 11px; color: #f4af1a; border-bottom: 1px solid #f4af1a; padding-bottom: 8px; margin-bottom: 30px; }
        .top-bar span { margin: 0 8px; color: #555; }

        /* Logo block */
        .logo-block { text-align: center; margin: 30px 0; }
        .logo-block img { max-width: 220px; }

        /* Title */
        .doc-title-label { text-align: center; letter-spacing: 4px; color: #f4af1a; font-size: 13px; margin-top: 30px; }
        .doc-title { text-align: center; font-size: 32px; font-weight: 800; text-transform: uppercase; margin: 4px 0 10px; }
        .doc-subtitle { text-align: center; font-style: italic; color: #555; font-size: 13px; margin-bottom: 30px; }
        .dots { text-align: center; color: #f4af1a; letter-spacing: 4px; margin-bottom: 30px; }

        /* Prepared for table */
        .meta-table { margin: 0 auto 30px; width: 60%; border-collapse: collapse; }
        .meta-table td { padding: 6px 12px; border-bottom: 1px solid #ddd; }
        .meta-table td:first-child { background: #fdf6e3; color: #888; letter-spacing: 2px; font-size: 11px; width: 40%; }
        .meta-table td:last-child { color: #f4af1a; font-weight: 600; }

        /* Footer address */
        .footer-addr { text-align: center; color: #777; font-size: 11px; border-top: 1px solid #ddd; margin-top: 40px; padding-top: 10px; }

        /* Page 2+ */
        .section { margin-bottom: 28px; }
        .section-title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #f4af1a; padding-bottom: 6px; margin-bottom: 12px; }
        .firm-intro { line-height: 1.8; color: #333; }

        /* Services table */
        .services-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .services-table thead tr { background: #222; color: #fff; }
        .services-table thead th { padding: 8px 12px; text-align: left; font-weight: 600; font-size: 12px; }
        .services-table tbody td { padding: 8px 12px; border-bottom: 1px solid #eee; vertical-align: top; }
        .services-table tbody tr:nth-child(even) { background: #fdf6e3; }
        .svc-name { font-weight: 700; }
        .fee-cell { font-weight: 700; color: #f4af1a; white-space: nowrap; }

        /* Notes */
        .notes-box { background: #fdf6e3; border-left: 3px solid #f4af1a; padding: 12px 16px; border-radius: 4px; color: #444; line-height: 1.7; }

        /* Footer note */
        .footer-note { text-align: center; color: #aaa; font-size: 11px; margin-top: 40px; }

        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .page { padding: 20px; }
        }
    </style>
</head>
<body>

{{-- Print button --}}
<div class="no-print" style="text-align:right;padding:12px 24px;background:#f8f9fa;border-bottom:1px solid #dee2e6;">
    <button onclick="window.print()" style="background:#f4af1a;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;margin-right:8px;">
        &#128438; Print / Save PDF
    </button>
    <a href="{{ route('e.documents') }}" style="background:#6c757d;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;text-decoration:none;">
        &larr; Back
    </a>
</div>

<div class="page">
    {{-- Cover Page --}}
    <div class="top-bar">
        TAXATION <span>•</span> SALES TAX <span>•</span> AUDIT <span>•</span> CORPORATE (SECP)
    </div>

    <div class="logo-block">
        <img src="{{ asset($doc->firm_logo) }}" alt="{{ $doc->firm_name }}">
        @if($doc->firm == 0)
            <div style="font-weight:700;margin-top:6px;">Chartered Accountants</div>
        @endif
    </div>

    <div class="doc-title-label">P R O F E S S I O N A L &nbsp; S E R V I C E S</div>
    <div class="doc-title">Proposal</div>
    <div class="dots">■ ■ ■</div>
    <div class="doc-subtitle">A statement of the professional services offered by the firm.</div>

    <table class="meta-table">
        <tr>
            <td>P r e p a r e d &nbsp; F o r</td>
            <td>{{ $doc->client_name }}</td>
        </tr>
        <tr>
            <td>D a t e</td>
            <td>{{ $doc->created_at->format('F j, Y') }}</td>
        </tr>
    </table>

    <div class="footer-addr">
        @if($doc->firm == 0)
            72-West, 2nd Floor, Benazir Plaza, Jinnah Avenue, Blue Area, Islamabad &nbsp;•&nbsp;
            Phone: 051-2120368 &nbsp;•&nbsp; E-mail: asif@argroup.com.pk
        @else
            H.A.M.D &amp; CO &nbsp;•&nbsp; Chartered Accountants
        @endif
    </div>

    {{-- Page break --}}
    <div style="page-break-after:always; margin-top:40px;"></div>

    {{-- Page 2: The Firm --}}
    <div style="display:flex;flex-direction:column;min-height:900px;">

        <div style="display:flex;justify-content:space-between;font-size:11px;color:#aaa;margin-bottom:20px;margin-top:10px;border-bottom:1px solid #f0f0f0;padding-bottom:6px;">
            <span>{{ $doc->firm_name }}</span>
            <span>Professional Services &nbsp;•&nbsp; Proposal</span>
        </div>

        <div class="section">
            <div class="section-title">The Firm</div>
            @if($doc->firm == 0)
            <p class="firm-intro">
                Asif Associates, Chartered Accountants is a firm based in Islamabad offering a wide range of professional
                services in accounting, audit, taxation, business advisory and corporate affairs. Established in 2007 by Mr.
                Muhammad Asif Raza (FCA) under the Chartered Accountants Ordinance, 1961, the firm brings over fifteen
                years of experience and a team of qualified professionals, including Chartered Accountants, Cost &amp;
                Management Accountants, tax specialists and corporate consultants, serving clients across Pakistan.
            </p>
            <br>
            <p class="firm-intro">
                Our approach is partner-led: an individual partner handles each client's affairs and serves as the focal point
                for every enquiry, supported by a pool of experts.
            </p>
            @else
            <p class="firm-intro">
                H.A.M.D &amp; CO, Chartered Accountants, is a professional services firm providing comprehensive accounting,
                audit, taxation and advisory services to clients across Pakistan.
            </p>
            @endif
        </div>

        <div class="section">
            <div class="section-title">1. &nbsp; Services to be Provided</div>
            <p style="margin-bottom:12px;color:#444;">
                The following professional services are offered by <strong>{{ $doc->firm_name }}</strong>.
                Any service not specifically listed below shall be treated as additional work and quoted separately.
            </p>
            <table class="services-table">
                <thead>
                    <tr>
                        <th style="width:55%">Service Area</th>
                        <th>Fee (PKR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doc->services as $svc)
                    <tr>
                        <td class="svc-name">{{ $svc['name'] }}</td>
                        <td class="fee-cell">{{ !empty($svc['fee']) ? $svc['fee'] : '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="margin-top:8px;font-style:italic;color:#777;font-size:11px;">
                All fees are exclusive of sales tax and out-of-pocket expenses, which are charged at actual.
            </p>
        </div>

        @if($doc->notes)
        <div class="section">
            <div class="section-title">Additional Notes</div>
            <div class="notes-box">{{ $doc->notes }}</div>
        </div>
        @endif

    </div>
</div>
</body>
</html>
