<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agreement — {{ $doc->client_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; color: #222; background: #fff; font-size: 14px; line-height: 1.6; }
        .page { max-width: 860px; margin: 0 auto; padding: 44px 50px; }

        /* Cover page */
        .top-bar { text-align: center; letter-spacing: 3px; font-size: 11px; color: #f4af1a; border-bottom: 2px solid #f4af1a; padding-bottom: 8px; margin-bottom: 24px; font-family: Arial, sans-serif; }
        .top-bar span { margin: 0 8px; color: #888; }

        .logo-block { text-align: center; margin: 24px 0 20px; }
        .logo-block img { max-width: 200px; }

        .doc-title-label { text-align: center; letter-spacing: 5px; color: #f4af1a; font-size: 12px; margin-top: 24px; font-family: Arial, sans-serif; font-weight: 600; }
        .doc-title { text-align: center; font-size: 38px; font-weight: bold; text-transform: uppercase; margin: 6px 0 8px; letter-spacing: 2px; }
        .dots { text-align: center; color: #f4af1a; letter-spacing: 6px; margin-bottom: 8px; font-size: 16px; }
        .doc-subtitle { text-align: center; font-style: italic; color: #555; font-size: 13px; margin-bottom: 24px; }

        .meta-table { margin: 0 auto 24px; width: 62%; border-collapse: collapse; }
        .meta-table td { padding: 7px 14px; border-bottom: 1px solid #e0d9cc; }
        .meta-table td:first-child { background: #fdf6e3; color: #999; letter-spacing: 2px; font-size: 10px; width: 38%; font-family: Arial, sans-serif; }
        .meta-table td:last-child { color: #c8860a; font-weight: bold; font-size: 15px; }

        .footer-addr { text-align: center; color: #777; font-size: 11px; border-top: 1px solid #ddd; margin-top: 30px; padding-top: 10px; font-family: Arial, sans-serif; }

        /* Inner page header */
        .inner-header { display: flex; justify-content: space-between; font-size: 10px; color: #bbb; margin-bottom: 18px; margin-top: 8px; border-bottom: 1px solid #e8e0d0; padding-bottom: 5px; font-family: Arial, sans-serif; letter-spacing: 0.5px; }

        /* Section */
        .section { margin-bottom: 22px; }
        .section-title { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid #f4af1a; padding-bottom: 5px; margin-bottom: 12px; }
        .firm-intro { color: #333; text-align: justify; }

        /* Services grid */
        .svc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 10px; }
        .svc-card { border: 1px solid #e8d9b0; border-radius: 5px; padding: 14px 16px; background: #fffdf5; }
        .svc-card-title { font-weight: bold; font-size: 13px; letter-spacing: 1px; color: #c8860a; text-transform: uppercase; margin-bottom: 8px; font-family: Arial, sans-serif; border-bottom: 1px solid #f0e0b0; padding-bottom: 5px; }
        .svc-card ul { margin: 0; padding-left: 18px; color: #333; font-size: 13px; line-height: 1.85; }

        /* Engagement services table */
        .services-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 14px; }
        .services-table thead tr { background: #1a1a1a; color: #fff; }
        .services-table thead th { padding: 10px 14px; text-align: left; font-size: 13px; letter-spacing: 0.5px; font-family: Arial, sans-serif; }
        .services-table tbody td { padding: 9px 14px; border-bottom: 1px solid #ece6d8; vertical-align: top; }
        .services-table tbody tr:nth-child(even) { background: #fdf9f0; }
        .svc-name { font-weight: bold; font-size: 14px; }
        .fee-cell { font-weight: bold; color: #c8860a; white-space: nowrap; font-size: 14px; }

        /* Clauses */
        .clause { margin-bottom: 9px; color: #333; }

        /* Signatures */
        .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-top: 28px; }
        .sig-label { font-size: 11px; letter-spacing: 3px; color: #f4af1a; text-transform: uppercase; margin-bottom: 20px; font-family: Arial, sans-serif; }
        .sig-name { font-weight: bold; font-size: 15px; }
        .sig-role { color: #555; font-style: italic; font-size: 13px; }
        .sig-org { font-weight: bold; font-size: 14px; }
        .sig-date { color: #555; font-size: 13px; margin-top: 10px; }
        .sig-date-line { display: inline-block; border-bottom: 1px solid #333; width: 160px; }

        .notes-box { background: #fdf6e3; border-left: 4px solid #f4af1a; padding: 12px 18px; border-radius: 4px; color: #444; }

        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .page { padding: 24px 36px; }
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

<div class="page">

    {{-- Page 1: Cover --}}
    <div class="top-bar">
        TAXATION <span>•</span> SALES TAX <span>•</span> AUDIT <span>•</span> CORPORATE (SECP)
    </div>

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

    <table class="meta-table">
        <tr>
            <td>C L I E N T &nbsp; N A M E</td>
            <td>{{ $doc->client_name }}</td>
        </tr>
        <tr>
            <td>D A T E</td>
            <td>{{ $doc->created_at->format('F j, Y') }}</td>
        </tr>
    </table>

    <div class="footer-addr">
        @if($doc->firm == 0)
            72-West, 2nd Floor, Benazir Plaza, Jinnah Avenue, Blue Area, Islamabad &nbsp;•&nbsp;
            Phone: 051-2120368 &nbsp;•&nbsp; E-mail: asif@argroup.com.pk
        @else
            H.A.M.D &amp; Co. &nbsp;•&nbsp; Chartered Accountants
        @endif
    </div>

    <div style="page-break-after:always;"></div>

    {{-- Page 2: The Firm + Services Provided --}}
    <div class="inner-header">
        <span>{{ $doc->firm_name }}</span>
        <span>Professional Services &nbsp;•&nbsp; Agreement</span>
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
                    <li>Any Letter impacting eligibility or continuity of exemption under section 100C.</li>
                    <li>Application for rectification and penalty waiver request where required.</li>
                    <li>To represent client before offices of FBR, KPRA, BRA, SRB and AJ&amp;K.</li>
                    <li>Tax Opinions.</li>
                </ul>
            </div>

            <div class="svc-card">
                <div class="svc-card-title">Audit &amp; Assurance Services</div>
                <ul>
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

    <div style="page-break-after:always;"></div>

    {{-- Page 3: Engagement Agreement --}}
    <div class="inner-header">
        <span>{{ $doc->firm_name }}</span>
        <span>Professional Services &nbsp;•&nbsp; Agreement</span>
    </div>

    <div class="section">
        <div class="section-title">Engagement Agreement</div>
        <p class="firm-intro" style="margin-bottom:14px;">
            This Agreement formalizes the engagement of <strong>{{ $doc->firm_name }}</strong> (the "Firm") by
            <strong style="color:#c8860a;">{{ $doc->client_name }}</strong> (the "Client")
            @if($doc->start_date && $doc->end_date)
                for the period from <strong style="color:#c8860a;">{{ $doc->start_date->format('F j, Y') }}</strong>
                to <strong style="color:#c8860a;">{{ $doc->end_date->format('F j, Y') }}</strong>.
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
        <p class="clause"><strong>Confidentiality.</strong> &nbsp; Each Party undertakes to treat all information received from the other Party in the course of this engagement as strictly confidential. Such information shall not be disclosed to any third party without prior written consent, except where required by applicable law, regulation or court order.</p>
        <p class="clause"><strong>Liability.</strong> &nbsp; The Firm's aggregate liability under this Agreement shall not exceed the total fees paid by the Client in the twelve months preceding the event giving rise to the claim. The Firm shall not be liable for any indirect, consequential or special loss.</p>
        <p class="clause"><strong>Term &amp; Termination.</strong> &nbsp; This engagement commences on the Start Date and continues until completion of services or termination by either Party upon thirty (30) days' written notice. Outstanding fees for work completed up to the date of termination remain payable.</p>
        <p class="clause"><strong>Governing Law.</strong> &nbsp; This Agreement is governed by the laws of Pakistan. All disputes shall be subject to the exclusive jurisdiction of the courts of Islamabad.</p>
    </div>

    @if($doc->notes)
    <div class="section">
        <div class="section-title">Additional Terms / Notes</div>
        <div class="notes-box">{{ $doc->notes }}</div>
    </div>
    @endif

    <div style="page-break-after:always;"></div>

    {{-- Page 4: Acceptance / Signatures --}}
    <div style="display:flex;flex-direction:column;min-height:900px;">

        <div class="inner-header">
            <span>{{ $doc->firm_name }}</span>
            <span>Professional Services &nbsp;•&nbsp; Agreement</span>
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
                <div style="height:80px;border-bottom:1px solid #333;margin-bottom:12px;"></div>
                <div class="sig-name">Muhammad Asif Raza (FCA)</div>
                <div class="sig-role">Partner</div>
                @if($doc->firm == 0)
                    <div class="sig-org">Asif Associates Chartered Accountants</div>
                @else
                    <div class="sig-org">H.A.M.D &amp; Co Chartered Accountants</div>
                @endif
                <div class="sig-date" style="margin-top:16px;">Date: &nbsp;<span class="sig-date-line"></span></div>
            </div>

            <div>
                <div class="sig-label">F O R &nbsp; T H E &nbsp; C L I E N T</div>
                <div style="height:80px;border-bottom:1px solid #333;margin-bottom:12px;"></div>
                <div class="sig-name" style="color:#c8860a;">{{ $doc->client_name }}</div>
                <div class="sig-role">Authorized Signatory</div>
                <div class="sig-date" style="margin-top:16px;">Date: &nbsp;<span class="sig-date-line"></span></div>
            </div>
        </div>

        <div style="margin-top:auto;"></div>

    </div>

</div>
</body>
</html>
