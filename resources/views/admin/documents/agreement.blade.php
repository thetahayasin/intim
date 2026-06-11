<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agreement — {{ $doc->client_name }}</title>

    {{-- Per-firm color theme --}}
    @if($doc->firm == 1)
    <style>
        :root {
            --firm-accent:         #1a3e6e;
            --firm-accent-text:    #1a4a8a;
            --firm-card-bg:        #f0f5fb;
            --firm-card-border:    #b8d0e8;
            --firm-card-title-bdr: #90b8d8;
            --firm-tint-bg:        #e8f0f8;
            --firm-table-stripe:   #edf3f9;
        }
    </style>
    @else
    <style>
        :root {
            --firm-accent:         #f4af1a;
            --firm-accent-text:    #c8860a;
            --firm-card-bg:        #fffdf5;
            --firm-card-border:    #e8d9b0;
            --firm-card-title-bdr: #f0e0b0;
            --firm-tint-bg:        #fdf6e3;
            --firm-table-stripe:   #fdf9f0;
        }
    </style>
    @endif

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; color: #222; background: #f0f0f0; font-size: 14px; line-height: 1.6; }

        /* Each logical page is its own block */
        .doc-page {
            max-width: 860px;
            margin: 0 auto 24px;
            padding: 44px 50px;
            background: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Cover page */
        .top-bar { text-align: center; letter-spacing: 3px; font-size: 11px; color: var(--firm-accent); border-bottom: 2px solid var(--firm-accent); padding-bottom: 8px; margin-bottom: 24px; font-family: Arial, sans-serif; }
        .top-bar span { margin: 0 8px; color: #888; }

        .logo-block { text-align: center; margin: 24px 0 20px; }
        .logo-block img { max-width: 200px; }

        .doc-title-label { text-align: center; letter-spacing: 5px; color: var(--firm-accent); font-size: 12px; margin-top: 24px; font-family: Arial, sans-serif; font-weight: 600; }
        .doc-title { text-align: center; font-size: 38px; font-weight: bold; text-transform: uppercase; margin: 6px 0 8px; letter-spacing: 2px; }
        .dots { text-align: center; color: var(--firm-accent); letter-spacing: 6px; margin-bottom: 8px; font-size: 16px; }
        .doc-subtitle { text-align: center; font-style: italic; color: #555; font-size: 13px; margin-bottom: 24px; }

        .meta-table { margin: 0 auto 24px; width: 62%; border-collapse: collapse; }
        .meta-table td { padding: 7px 14px; border-bottom: 1px solid #e0d9cc; }
        .meta-table td:first-child { background: var(--firm-tint-bg); color: #999; letter-spacing: 2px; font-size: 10px; width: 38%; font-family: Arial, sans-serif; }
        .meta-table td:last-child { color: var(--firm-accent-text); font-weight: bold; font-size: 22px; }

        .footer-addr { text-align: center; color: #777; font-size: 11px; border-top: 1px solid #ddd; padding-top: 10px; font-family: Arial, sans-serif; }

        /* Inner page header / footer */
        .inner-header { display: flex; justify-content: space-between; font-size: 10px; color: #bbb; margin-bottom: 22px; border-bottom: 1px solid #e8e0d0; padding-bottom: 5px; font-family: Arial, sans-serif; letter-spacing: 0.5px; }
        .inner-footer { display: flex; justify-content: space-between; font-size: 10px; color: #bbb; margin-top: auto; padding-top: 16px; border-top: 1px solid #e8e0d0; font-family: Arial, sans-serif; letter-spacing: 0.5px; }

        /* Section */
        .section { margin-bottom: 22px; page-break-inside: avoid; break-inside: avoid; }
        .section-title { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid var(--firm-accent); padding-bottom: 5px; margin-bottom: 12px; }
        .firm-intro { color: #333; text-align: justify; }

        /* Services grid */
        .svc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 10px; }
        .svc-card {
            border: 1px solid var(--firm-card-border);
            border-radius: 5px;
            padding: 14px 16px;
            background: var(--firm-card-bg);
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .svc-card-title { font-weight: bold; font-size: 13px; letter-spacing: 1px; color: var(--firm-accent-text); text-transform: uppercase; margin-bottom: 8px; font-family: Arial, sans-serif; border-bottom: 1px solid var(--firm-card-title-bdr); padding-bottom: 5px; }
        .svc-card ul { margin: 0; padding-left: 18px; color: #333; font-size: 13px; line-height: 1.85; }

        /* Engagement services table */
        .services-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 14px; }
        .services-table thead tr { background: #1a1a1a; color: #fff; }
        .services-table thead th { padding: 10px 14px; text-align: left; font-size: 13px; letter-spacing: 0.5px; font-family: Arial, sans-serif; }
        .services-table tbody td { padding: 9px 14px; border-bottom: 1px solid #ece6d8; vertical-align: top; }
        .services-table tbody tr:nth-child(even) { background: var(--firm-table-stripe); }
        .svc-name { font-weight: bold; font-size: 14px; }
        .fee-cell { font-weight: bold; color: var(--firm-accent-text); white-space: nowrap; font-size: 14px; }

        /* Clauses */
        .clause { margin-bottom: 9px; color: #333; }

        /* Signatures */
        .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-top: 28px; }
        .sig-label { font-size: 11px; letter-spacing: 3px; color: var(--firm-accent); text-transform: uppercase; margin-bottom: 20px; font-family: Arial, sans-serif; }
        .sig-name { font-weight: bold; font-size: 15px; }
        .sig-role { color: #555; font-style: italic; font-size: 13px; }
        .sig-org { font-weight: bold; font-size: 14px; }
        .sig-date { color: #555; font-size: 13px; margin-top: 10px; }
        .sig-date-line { display: inline-block; border-bottom: 1px solid #333; width: 160px; }

        .notes-box { background: var(--firm-tint-bg); border-left: 4px solid var(--firm-accent); padding: 12px 18px; border-radius: 4px; color: #444; }

        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .doc-page { margin: 0 auto; padding: 28px 40px; min-height: 100vh; page-break-after: always; break-after: page; }
            .svc-card { page-break-inside: avoid; break-inside: avoid; }
            .svc-grid { page-break-inside: avoid; break-inside: avoid; }
            .section { page-break-inside: avoid; break-inside: avoid; }
        }
        @media (max-width: 600px) {
            .doc-toolbar { flex-direction: column !important; align-items: stretch !important; gap: 10px !important; }
            .doc-toolbar-title { font-size: 12px !important; }
            .doc-toolbar-actions { display: flex !important; flex-direction: column !important; gap: 6px !important; width: 100%; }
            .doc-toolbar-actions a,
            .doc-toolbar-actions button { width: 100% !important; text-align: center !important; box-sizing: border-box !important; }
        }
    </style>
</head>
<body>

<div class="no-print doc-toolbar" style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;background:#161616;border-bottom:1px solid #393939;">
    <span class="doc-toolbar-title" style="font-family:'IBM Plex Sans',Arial,sans-serif;font-size:13px;color:#8d8d8d;letter-spacing:0.5px;">
        {{ $doc->client_name }} &nbsp;—&nbsp; {{ $doc->created_at->format('d M Y') }}
    </span>
    <div class="doc-toolbar-actions" style="display:flex;gap:8px;">
        <button onclick="window.print()" style="background:#f4f4f4;color:#161616;border:none;padding:7px 18px;font-weight:600;cursor:pointer;font-family:'IBM Plex Sans',Arial,sans-serif;font-size:13px;">
            Print / Save PDF
        </button>
        <a href="{{ route('e.documents.edit', $doc->id) }}" style="background:#393939;color:#f4f4f4;padding:7px 18px;font-weight:600;text-decoration:none;font-family:'IBM Plex Sans',Arial,sans-serif;font-size:13px;display:inline-block;">
            Edit
        </a>
        <a href="{{ route('e.documents') }}" style="background:transparent;color:#8d8d8d;border:1px solid #525252;padding:7px 18px;font-weight:600;text-decoration:none;font-family:'IBM Plex Sans',Arial,sans-serif;font-size:13px;display:inline-block;">
            &larr; Back
        </a>
    </div>
</div>

{{-- ===================== PAGE 1: COVER ===================== --}}
<div class="doc-page">

    <div class="top-bar">
        TAXATION <span>•</span> SALES TAX <span>•</span> AUDIT <span>•</span> CORPORATE (SECP)
    </div>

    <div style="flex:1;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;">

        <div class="logo-block">
            <img src="{{ asset($doc->firm_logo) }}" alt="{{ $doc->firm_name }}">
            @if($doc->firm == 0)
                <div style="font-weight:bold;margin-top:6px;font-size:14px;">Chartered Accountants</div>
            @endif
        </div>

        <div class="doc-title-label">P R O F E S S I O N A L &nbsp;&nbsp; S E R V I C E S</div>
        <div class="doc-title">Agreement</div>
        <div class="dots">&#9670; &nbsp; &#9670; &nbsp; &#9670;</div>
        <div class="doc-subtitle">A formal record of professional services agreed between the Firm and the Client,<br>inclusive of scope, fee schedule, responsibilities, and mutual acceptance.</div>

        <table class="meta-table" style="margin-top:28px;">
            <tr>
                <td>Client Name</td>
                <td>{{ $doc->client_name }}</td>
            </tr>
        </table>

    </div>

    <div class="footer-addr">
        @if($doc->firm == 0)
            2nd Floor, Benazir Plaza, Blue Area, Islamabad &nbsp;•&nbsp; Phone: 051-2120368 &nbsp;&nbsp; Asif Associates
        @else
            2nd Floor, Benazir Plaza, Blue Area, Islamabad &nbsp;•&nbsp; Phone: 051-2120368 &nbsp;&nbsp; H.A.M.D &amp; Co.
        @endif
    </div>

</div>

{{-- ===================== PAGE 2: THE FIRM + SERVICES ===================== --}}
<div class="doc-page">

    <div class="inner-header">
        <span>Professional Services Agreement</span>
        <span>{{ $doc->firm_name }}</span>
    </div>

    <div class="section">
        <div class="section-title">The Firm</div>
        @if($doc->firm == 0)
        <p class="firm-intro">
            Asif Associates Chartered Accountants is a professional services firm based in Islamabad, providing expert
            advisory across accounting, audit, taxation, corporate governance and business consultancy. Established in
            2007 by Muhammad Asif Raza (FCA) under the Chartered Accountants Ordinance, 1961, the Firm has built a
            reputation grounded in technical rigour, client responsiveness and unwavering professional integrity.
        </p>
        <p class="firm-intro" style="margin-top:10px;">
            The Firm is staffed by a team of qualified Chartered Accountants, tax specialists and corporate compliance
            consultants with collective experience spanning over 20 years. Our client base includes listed companies,
            mid-market enterprises, foreign subsidiaries, and emerging businesses across multiple industries in Pakistan.
        </p>
        @else
        <p class="firm-intro">
            H.A.M.D &amp; Co. Chartered Accountants is a professional services firm based in Islamabad, providing expert
            advisory across accounting, audit, taxation, corporate governance and business consultancy. The Firm has built
            a reputation grounded in technical rigour, client responsiveness and unwavering professional integrity.
        </p>
        <p class="firm-intro" style="margin-top:10px;">
            The Firm is staffed by a team of qualified Chartered Accountants, tax specialists and corporate compliance
            consultants with collective experience spanning over 20 years. Our client base includes listed companies,
            mid-market enterprises, foreign subsidiaries, and emerging businesses across multiple industries in Pakistan.
        </p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Services Provided</div>
        <p class="firm-intro" style="margin-bottom:12px;">
            The Firm offers the following professional services. Detailed scope is set out in each block below.
            Services not listed herein shall be considered additional work and billed separately upon mutual agreement.
        </p>
        <div class="svc-grid">

            <div class="svc-card">
                <div class="svc-card-title">Taxation Services</div>
                <ul>
                    <li>Preparation and filing of annual income tax return u/s 114.</li>
                    <li>Monthly, Quarterly and Annual Filing of Income Tax Withholding statements u/s 165.</li>
                    <li>Monthly sale tax return for all the Provinces, Federal, Gilgit Baltistan and Azad Kashmir.</li>
                    <li>To prepare all kinds of letters or documents relating to litigations issued under relevant sections of Income Tax Ordinance 2001.</li>
                    <li>To prepare all kinds of letters or documents relating to litigations issued under relevant sections of Sales Tax 1990.</li>
                </ul>
            </div>

            <div class="svc-card">
                <div class="svc-card-title">Others Taxation Services</div>
                <ul>
                    <li>Any Letter impacting eligibility or continuity of exemption under section 100C.</li>
                    <li>Application for rectification and penalty waiver request where required.</li>
                    <li>To represent client before offices of FBR, KPRA, BRA, SRB and AJ&amp;K.</li>
                    <li>Tax Opinions.</li>
                </ul>
            </div>

            <div class="svc-card" style="grid-column:1/-1;">
                <div class="svc-card-title">Audit &amp; Assurance Services</div>
                <ul style="columns:2;column-gap:30px;">
                    <li>Statutory Audits.</li>
                    <li>Internal Audits.</li>
                    <li>Review Engagements.</li>
                    <li>Forensic Audits.</li>
                    <li>Special Purpose Audits.</li>
                </ul>
            </div>

            <div class="svc-card">
                <div class="svc-card-title">Advisory &amp; Consultancy Support</div>
                <ul>
                    <li>Book Keeping Services.</li>
                    <li>Financial Modeling Services.</li>
                    <li>Consultancy services for Non-Banking Financial Companies (NBFCs).</li>
                    <li>Budgeting.</li>
                </ul>
            </div>

            <div class="svc-card">
                <div class="svc-card-title">Corporate &amp; SECP Compliance</div>
                <ul>
                    <li>Filing of Annual Return.</li>
                    <li>Filing of Form A.</li>
                    <li>Filing of Form B.</li>
                    <li>Filing of Form 19.</li>
                    <li>Company Incorporation.</li>
                </ul>
            </div>

        </div>
    </div>

    <div class="inner-footer">
        <span>2nd Floor, Benazir Plaza, Blue Area, Islamabad &nbsp;•&nbsp; Phone: 051-2120368</span>
        <span>{{ $doc->firm_name }}</span>
    </div>

</div>

{{-- ===================== PAGE 3: ENGAGEMENT AGREEMENT ===================== --}}
<div class="doc-page">

    <div class="inner-header">
        <span>Professional Services Agreement</span>
        <span>{{ $doc->firm_name }}</span>
    </div>

    <div class="section">
        <div class="section-title">Engagement Agreement</div>
        <p class="firm-intro" style="margin-bottom:14px;">
            This Agreement formalizes the engagement of <strong>{{ $doc->firm_name }}</strong> (the "Firm") by
            <strong style="color:var(--firm-accent-text);">{{ $doc->client_name }}</strong> (the "Client")
            @if($doc->start_date && $doc->end_date)
                for the period from <strong style="color:var(--firm-accent-text);">{{ $doc->start_date->format('F j, Y') }}</strong>
                to <strong style="color:var(--firm-accent-text);">{{ $doc->end_date->format('F j, Y') }}</strong>.
            @else
                as agreed between the Parties.
            @endif
            The agreed scope and fee schedule is as follows:
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

        <p style="font-style:italic;color:#777;font-size:12px;margin-top:8px;font-family:Arial,sans-serif;">
            All fees stated above are exclusive of applicable sales tax and actual out-of-pocket expenses, which will be billed separately at cost.
        </p>
    </div>

    <div class="section">
        <p class="clause"><strong>Scope.</strong> &nbsp; The Firm shall deliver the services listed above. Any work falling outside the agreed scope shall be treated as additional services and charged separately, subject to prior written agreement.</p>
        <p class="clause"><strong>Professional Fee.</strong> &nbsp; The fee shall be as agreed above or as subsequently confirmed in writing. The Firm reserves the right to review fees annually with reasonable notice to the Client.</p>
        <p class="clause"><strong>Client Responsibilities.</strong> &nbsp; The Client shall ensure timely provision of accurate records, documentation and internal access necessary for the Firm to perform its duties. The Firm accepts no liability for delays, penalties or incorrect filings arising from incomplete or erroneous information provided by the Client.</p>
    </div>

    @if($doc->notes)
    <div class="section">
        <div class="section-title">Additional Terms / Notes</div>
        <div class="notes-box">{{ $doc->notes }}</div>
    </div>
    @endif

    <div class="inner-footer">
        <span>2nd Floor, Benazir Plaza, Blue Area, Islamabad &nbsp;•&nbsp; Phone: 051-2120368</span>
        <span>{{ $doc->firm_name }}</span>
    </div>

</div>

{{-- ===================== PAGE 4: ACCEPTANCE ===================== --}}
<div class="doc-page">

    <div class="inner-header">
        <span>Professional Services Agreement</span>
        <span>{{ $doc->firm_name }}</span>
    </div>

    <div class="section">
        <div class="section-title">Acceptance</div>
        <p class="firm-intro">
            By executing this Agreement, the Parties confirm that they have read, understood and voluntarily agreed
            to all terms set out herein. This Agreement shall become legally binding upon the signatures of both
            authorized representatives below. A duly executed copy authorizes <strong>{{ $doc->firm_name }}</strong>
            to commence performance of the agreed services on behalf of the Client.
        </p>
        <p class="firm-intro" style="margin-top:10px;">
            The signatories below represent and warrant that they have full authority to bind their respective
            organizations to the obligations contained in this Agreement.
        </p>
    </div>

    <div class="sig-grid">
        <div>
            <div class="sig-label">F O R &nbsp; T H E &nbsp; F I R M</div>
            <div style="height:80px;margin-bottom:12px;border-bottom:1px solid #333;display:flex;align-items:flex-end;padding-bottom:6px;">
                @if($doc->firm == 0)
                    <img src="{{ asset('assets/img/sig-asif.jpg') }}" alt="Signature" style="max-height:64px;max-width:220px;object-fit:contain;">
                @else
                    <img src="{{ asset('assets/img/sig-hamd.png') }}" alt="Signature" style="max-height:64px;max-width:220px;object-fit:contain;">
                @endif
            </div>
            <div class="sig-name">Muhammad Asif Raza (FCA)</div>
            <div class="sig-role">Partner</div>
            <div class="sig-date" style="margin-top:16px;">Date: &nbsp;{{ now()->format('d M Y') }}</div>
        </div>

        <div>
            <div class="sig-label">F O R &nbsp; T H E &nbsp; C L I E N T</div>
            <div style="height:80px;border-bottom:1px solid #333;margin-bottom:12px;"></div>
            <div class="sig-name">Name: &nbsp;<span class="sig-date-line" style="width:180px;"></span></div>
            <div class="sig-role" style="margin-top:8px;">Designation: &nbsp;<span class="sig-date-line" style="width:140px;"></span></div>
            <div class="sig-date" style="margin-top:10px;">Date: &nbsp;<span class="sig-date-line"></span></div>
        </div>
    </div>

    <div class="inner-footer">
        <span>2nd Floor, Benazir Plaza, Blue Area, Islamabad &nbsp;•&nbsp; Phone: 051-2120368</span>
        <span>{{ $doc->firm_name }}</span>
    </div>

</div>

</body>
</html>
