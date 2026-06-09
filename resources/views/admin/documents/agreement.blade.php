<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agreement — {{ $doc->client_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Overpass:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Overpass', sans-serif; color: #222; background: #fff; font-size: 13px; }
        .page { max-width: 850px; margin: 0 auto; padding: 40px; }

        .top-bar { text-align: center; letter-spacing: 3px; font-size: 11px; color: #f4af1a; border-bottom: 1px solid #f4af1a; padding-bottom: 8px; margin-bottom: 30px; }
        .top-bar span { margin: 0 8px; color: #555; }

        .logo-block { text-align: center; margin: 30px 0; }
        .logo-block img { max-width: 220px; }

        .doc-title-label { text-align: center; letter-spacing: 4px; color: #f4af1a; font-size: 13px; margin-top: 30px; }
        .doc-title { text-align: center; font-size: 32px; font-weight: 800; text-transform: uppercase; margin: 4px 0 10px; }
        .dots { text-align: center; color: #f4af1a; letter-spacing: 4px; margin-bottom: 10px; }
        .doc-subtitle { text-align: center; font-style: italic; color: #555; font-size: 13px; margin-bottom: 30px; }

        .meta-table { margin: 0 auto 30px; width: 60%; border-collapse: collapse; }
        .meta-table td { padding: 6px 12px; border-bottom: 1px solid #ddd; }
        .meta-table td:first-child { background: #fdf6e3; color: #888; letter-spacing: 2px; font-size: 11px; width: 40%; }
        .meta-table td:last-child { color: #f4af1a; font-weight: 600; }

        .footer-addr { text-align: center; color: #777; font-size: 11px; border-top: 1px solid #ddd; margin-top: 40px; padding-top: 10px; }

        /* Inner pages */
        .section { margin-bottom: 28px; }
        .section-title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #f4af1a; padding-bottom: 6px; margin-bottom: 12px; }
        .firm-intro { line-height: 1.8; color: #333; }

        .services-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .services-table thead tr { background: #222; color: #fff; }
        .services-table thead th { padding: 8px 12px; text-align: left; font-weight: 600; font-size: 12px; }
        .services-table tbody td { padding: 8px 12px; border-bottom: 1px solid #eee; vertical-align: top; }
        .services-table tbody tr:nth-child(even) { background: #fdf6e3; }
        .svc-name { font-weight: 700; }
        .fee-cell { font-weight: 700; color: #f4af1a; white-space: nowrap; }

        /* Agreement-specific */
        .clause { margin-bottom: 10px; line-height: 1.8; color: #333; }
        .clause strong { color: #222; }

        /* Signatures */
        .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 24px; }
.sig-label { font-size: 11px; letter-spacing: 3px; color: #f4af1a; text-transform: uppercase; margin-bottom: 24px; }
        .sig-line { border-top: 1px solid #333; margin-bottom: 6px; }
        .sig-name { font-weight: 700; font-size: 14px; }
        .sig-role { color: #555; font-style: italic; font-size: 12px; }
        .sig-org { font-weight: 700; font-size: 13px; }
        .sig-date { color: #555; font-size: 12px; margin-top: 8px; }
        .sig-date-line { display: inline-block; border-bottom: 1px solid #333; width: 160px; }

        .notes-box { background: #fdf6e3; border-left: 3px solid #f4af1a; padding: 12px 16px; border-radius: 4px; color: #444; line-height: 1.7; }
        .footer-note { text-align: center; color: #aaa; font-size: 11px; margin-top: 30px; }

        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .page { padding: 20px; }
        }
    </style>
</head>
<body>

<div class="no-print" style="text-align:right;padding:12px 24px;background:#f8f9fa;border-bottom:1px solid #dee2e6;">
    <button onclick="window.print()" style="background:#f4af1a;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;margin-right:8px;">
        &#128438; Print / Save PDF
    </button>
    <a href="{{ route('e.documents') }}" style="background:#6c757d;color:#fff;border:none;padding:8px 20px;border-radius:4px;font-weight:600;cursor:pointer;text-decoration:none;">
        &larr; Back
    </a>
</div>

<div class="page">

    {{-- Page 1: Cover --}}
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
    <div class="doc-title">Agreement</div>
    <div class="dots">■ ■ ■</div>
    <div class="doc-subtitle">A statement of the professional services offered by the firm, with the engagement agreement and acceptance to follow.</div>

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

    <div style="page-break-after:always; margin-top:40px;"></div>

    {{-- Page 2: The Firm + What We Offer (static) --}}
    <div style="display:flex;justify-content:space-between;font-size:11px;color:#aaa;margin-bottom:20px;margin-top:10px;border-bottom:1px solid #f0f0f0;padding-bottom:6px;">
        <span>{{ $doc->firm_name }}</span>
        <span>Professional Services &nbsp;•&nbsp; Agreement</span>
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
        <div class="section-title">Services We Provide</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:10px;">

            <div style="border:1px solid #f0e8d0;border-radius:6px;padding:14px;">
                <div style="font-weight:800;font-size:12px;letter-spacing:1px;color:#f4af1a;text-transform:uppercase;margin-bottom:8px;">Taxation</div>
                <ul style="margin:0;padding-left:16px;line-height:2;color:#333;font-size:12px;">
                    <li>Income Tax — Corporate &amp; Individual</li>
                    <li>Advance Tax &amp; Withholding Tax</li>
                    <li>Tax Planning &amp; Advisory</li>
                    <li>FBR Registration &amp; Representation</li>
                    <li>Tax Refund Matters</li>
                </ul>
            </div>

            <div style="border:1px solid #f0e8d0;border-radius:6px;padding:14px;">
                <div style="font-weight:800;font-size:12px;letter-spacing:1px;color:#f4af1a;text-transform:uppercase;margin-bottom:8px;">Sales Tax</div>
                <ul style="margin:0;padding-left:16px;line-height:2;color:#333;font-size:12px;">
                    <li>Sales Tax Registration (FBR / SRB / PRA)</li>
                    <li>Monthly Sales Tax Returns</li>
                    <li>Input / Output Tax Reconciliation</li>
                    <li>Sales Tax Refund Claims</li>
                    <li>Sales Tax Audit Assistance</li>
                </ul>
            </div>

            <div style="border:1px solid #f0e8d0;border-radius:6px;padding:14px;">
                <div style="font-weight:800;font-size:12px;letter-spacing:1px;color:#f4af1a;text-transform:uppercase;margin-bottom:8px;">Audit &amp; Assurance</div>
                <ul style="margin:0;padding-left:16px;line-height:2;color:#333;font-size:12px;">
                    <li>Statutory / External Audit</li>
                    <li>Internal Audit &amp; Controls Review</li>
                    <li>Special Purpose Audits</li>
                    <li>Due Diligence</li>
                    <li>Financial Statement Compilation</li>
                </ul>
            </div>

            <div style="border:1px solid #f0e8d0;border-radius:6px;padding:14px;">
                <div style="font-weight:800;font-size:12px;letter-spacing:1px;color:#f4af1a;text-transform:uppercase;margin-bottom:8px;">Corporate (SECP)</div>
                <ul style="margin:0;padding-left:16px;line-height:2;color:#333;font-size:12px;">
                    <li>Company Incorporation</li>
                    <li>SECP Annual Filings &amp; Compliance</li>
                    <li>Corporate Secretarial Services</li>
                    <li>Foreign Investment Registration</li>
                    <li>Change of Memorandum / Articles</li>
                </ul>
            </div>

        </div>
    </div>

    <div style="page-break-after:always; margin-top:30px;"></div>

    {{-- Page 3: Engagement Agreement --}}
    <div style="display:flex;justify-content:space-between;font-size:11px;color:#aaa;margin-bottom:20px;margin-top:10px;border-bottom:1px solid #f0f0f0;padding-bottom:6px;">
        <span>{{ $doc->firm_name }}</span>
        <span>Professional Services &nbsp;•&nbsp; Agreement</span>
    </div>

    <div class="section">
        <div class="section-title">2. &nbsp; Engagement Agreement</div>
        <p class="firm-intro" style="margin-bottom:14px;">
            This Agreement records the terms on which <strong>{{ $doc->firm_name }}</strong> (the "Firm") will
            provide professional services to <strong style="color:#f4af1a;">{{ $doc->client_name }}</strong> (the "Client")
            @if($doc->start_date && $doc->end_date)
                for the period from <strong style="color:#f4af1a;">{{ $doc->start_date->format('F j, Y') }}</strong>
                to <strong style="color:#f4af1a;">{{ $doc->end_date->format('F j, Y') }}</strong>.
            @else
                as agreed between the Parties.
            @endif
            The Client engages the Firm for the services set out in the table below:
        </p>

        <table class="services-table">
            <thead>
                <tr>
                    <th style="width:65%">Service</th>
                    <th>Agreed Fee (PKR)</th>
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

        <p style="font-style:italic;color:#777;font-size:11px;margin-top:8px;">
            All fees are exclusive of applicable taxes. Out-of-pocket expenses shall be charged at actual or 10% of the professional fee, whichever is higher.
        </p>
    </div>

    <div class="section">
        <p class="clause"><strong>• &nbsp; Scope.</strong> The Firm shall provide the services set out above. Any service not specifically included shall be treated as additional work and charged separately.</p>
        <p class="clause"><strong>• &nbsp; Professional fee.</strong> The professional fee shall be as recorded in the table above or as otherwise agreed in writing between the Parties.</p>
        <p class="clause"><strong>• &nbsp; Client responsibilities.</strong> The Client shall provide accurate and complete records, information and access on a timely basis. The Firm is not responsible for delays or penalties arising from late or incorrect information.</p>
        <p class="clause"><strong>• &nbsp; Term.</strong> This engagement continues until the services are completed or until terminated by either Party by reasonable notice in writing.</p>
    </div>

    @if($doc->notes)
    <div class="section">
        <div class="section-title">Additional Terms / Notes</div>
        <div class="notes-box">{{ $doc->notes }}</div>
    </div>
    @endif

    <div style="page-break-after:always; margin-top:30px;"></div>

    {{-- Page 4: Acceptance / Signatures --}}
    <div style="display:flex;flex-direction:column;min-height:900px;">

        <div style="display:flex;justify-content:space-between;font-size:11px;color:#aaa;margin-bottom:20px;margin-top:10px;border-bottom:1px solid #f0f0f0;padding-bottom:6px;">
            <span>{{ $doc->firm_name }}</span>
            <span>Professional Services &nbsp;•&nbsp; Agreement</span>
        </div>

        <div class="section">
            <div class="section-title">3. &nbsp; Acceptance</div>
            <p class="firm-intro">
                By signing below, the Parties confirm that they have read and agree to the terms of this Agreement.
                A signed copy authorizes <strong>{{ $doc->firm_name }}</strong> to perform the services set out above
                on behalf of the Client.
            </p>
        </div>

        <div class="sig-grid">
            <div class="sig-block">
                <div class="sig-label">F O R &nbsp; T H E &nbsp; F I R M</div>
                <div style="height:80px;border-bottom:1px solid #333;margin-bottom:12px;"></div>
                @if($doc->firm == 0)
                    <div class="sig-name">Mr. Muhammad Asif Raza (FCA)</div>
                    <div class="sig-role">Partner</div>
                    <div class="sig-org">Asif Associates, Chartered Accountants</div>
                @else
                    <div class="sig-name">Authorised Signatory</div>
                    <div class="sig-org">H.A.M.D &amp; CO</div>
                @endif
                <div class="sig-date" style="margin-top:16px;">Date: &nbsp;<span class="sig-date-line"></span></div>
            </div>

            <div class="sig-block">
                <div class="sig-label">F O R &nbsp; T H E &nbsp; C L I E N T</div>
                <div style="height:80px;border-bottom:1px solid #333;margin-bottom:12px;"></div>
                <div class="sig-name" style="color:#f4af1a;">{{ $doc->client_name }}</div>
                <div class="sig-role">Authorised Signatory</div>
                <div class="sig-date" style="margin-top:16px;">Date: &nbsp;<span style="color:#f4af1a;">{{ $doc->created_at->format('F j, Y') }}</span></div>
            </div>
        </div>

    </div>

</div>
</body>
</html>
