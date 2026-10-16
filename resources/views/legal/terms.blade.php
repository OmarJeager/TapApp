
@extends('legal.layout')

@section('title', 'Terms of Service')
@section('meta_description', 'Terms governing the use of TapApp Maintenance Management.')
@section('breadcrumb', 'Terms of Service')

@section('hero_title')
    Terms of <span>Service</span>
@endsection

@section('hero_description')
    These terms explain how TapApp may be used, the responsibilities of users,
    and the rules that protect maintenance data, accounts, and operations.
@endsection

@section('intro')
    Please read these Terms of Service before using TapApp. By accessing or using
    the application, you acknowledge these terms. If you do not agree, do not use
    the application. Where applicable law requires explicit consent, that consent
    must be obtained separately.
@endsection

@section('toc')
    <a href="#acceptance"><span>01</span> Acceptance of Terms</a>
    <a href="#service"><span>02</span> About TapApp</a>
    <a href="#accounts"><span>03</span> Accounts and Approval</a>
    <a href="#roles"><span>04</span> Roles and Permissions</a>
    <a href="#responsibilities"><span>05</span> User Responsibilities</a>
    <a href="#maintenance"><span>06</span> Maintenance Data</a>
    <a href="#security"><span>07</span> Security and Access</a>
    <a href="#availability"><span>08</span> Availability and Changes</a>
    <a href="#intellectual-property"><span>09</span> Intellectual Property</a>
    <a href="#suspension"><span>10</span> Suspension and Termination</a>
    <a href="#liability"><span>11</span> Liability and Disclaimer</a>
    <a href="#privacy"><span>12</span> Privacy</a>
    <a href="#changes"><span>13</span> Changes to These Terms</a>
    <a href="#contact"><span>14</span> Contact and Questions</a>
@endsection

@section('content')

<section class="legal-section" id="acceptance">
    <div class="section-title">
        <span class="section-number">01</span>
        <h2>Acceptance of Terms</h2>
    </div>

    <p>These Terms of Service govern access to and use of TapApp, a maintenance-management application designed to help authorized personnel manage preventive maintenance (PPM), maintenance checklists, equipment information, reports, and related operational activities.</p>

    <p>By using TapApp, you agree to follow these terms, applicable workplace policies, and any lawful instructions issued by your organization. If you use TapApp on behalf of an organization, you confirm that you are authorized to do so.</p>

    <p>Additional organizational policies may apply to equipment, maintenance procedures, health and safety, and handling of confidential information. Those policies remain applicable alongside these terms.</p>
</section>

<section class="legal-section" id="service">
    <div class="section-title">
        <span class="section-number">02</span>
        <h2>About TapApp</h2>
    </div>

    <p>TapApp is a digital platform intended to support maintenance operations. Depending on your assigned permissions and the features enabled, it may allow you to:</p>

    <ul>
        <li>View and manage preventive maintenance records and scheduled interventions.</li>
        <li>Complete maintenance checklists and record answers, comments, observations, and other relevant details.</li>
        <li>Access equipment identifiers, asset descriptions, job references, and related maintenance information.</li>
        <li>Generate or print maintenance tickets, reports, and documents, including documents containing barcodes.</li>
        <li>Manage account profiles, signatures, and other information needed for authorized workflows.</li>
        <li>Access dashboards, records, and administrative functions according to your role.</li>
    </ul>

    <p>Available features may differ by user role, organization, configuration, and system updates. TapApp supports maintenance workflows but does not replace professional judgment, approved technical procedures, equipment manuals, or mandatory safety checks.</p>
</section>

<section class="legal-section" id="accounts">
    <div class="section-title">
        <span class="section-number">03</span>
        <h2>Accounts, Registration, and Approval</h2>
    </div>

    <p>Users must provide accurate, complete, and current registration information, including their name, matricule or employee identifier, email address, and other requested details.</p>

    <p><strong>Registration approval:</strong> Creating a registration request does not necessarily create an active account or grant access. Where the approval workflow is enabled, a designated administrator or CEO must review the request before access is granted. Requests may be approved or rejected according to organizational requirements.</p>

    <p>Where enabled, TapApp may send account-status notifications, approval messages, rejection messages, login instructions, or email-verification messages to the address supplied by the user.</p>

    <ul>
        <li>You must use an email address you are authorized to access.</li>
        <li>You must not impersonate another person or submit false employee information.</li>
        <li>You must keep your password confidential and must not share your account.</li>
        <li>You must notify the designated administrator if you suspect unauthorized access.</li>
        <li>You must keep your profile information accurate and report material changes where required.</li>
    </ul>

    <p>Account creation, email verification, approval, and activation are distinct processes. Access may remain restricted until all applicable requirements have been satisfied.</p>
</section>

<section class="legal-section" id="roles">
    <div class="section-title">
        <span class="section-number">04</span>
        <h2>User Roles and Permissions</h2>
    </div>

    <p>TapApp uses role-based permissions to limit access to the functions and information users need for their responsibilities. Depending on configuration, roles may include:</p>

    <ul>
        <li><strong>CEO:</strong> May oversee user administration, approval decisions, access status, and authorized audit or activity information.</li>
        <li><strong>Superadmin:</strong> May manage designated system-wide settings and administrative functions.</li>
        <li><strong>Admin:</strong> May manage maintenance records, imports, checklist configuration, reports, and other authorized operational functions.</li>
        <li><strong>User:</strong> May access assigned maintenance records, checklists, tickets, and other permitted operational functions.</li>
    </ul>

    <p>These descriptions are general. The permissions actually configured in TapApp determine what each role can do. Users must not attempt to bypass access controls, access another user's account, or use administrative functions without authorization.</p>

    <p>Administrators should assign permissions according to job responsibilities and applicable internal policies. Possessing an elevated role does not authorize access to information for an unrelated or unlawful purpose.</p>
</section>

<section class="legal-section" id="responsibilities">
    <div class="section-title">
        <span class="section-number">05</span>
        <h2>Acceptable Use and User Responsibilities</h2>
    </div>

    <p>You agree to use TapApp lawfully, responsibly, and only for authorized purposes. In particular, you must not:</p>

    <ul>
        <li>Enter knowingly false, misleading, fabricated, or unauthorized maintenance information.</li>
        <li>Alter, delete, export, or disclose records without the necessary permission.</li>
        <li>Attempt to bypass authentication, role restrictions, account approval, or other security measures.</li>
        <li>Upload malicious code or deliberately disrupt the application's operation.</li>
        <li>Use another person's credentials or attempt to obtain another person's private information.</li>
        <li>Use TapApp to violate applicable laws, confidentiality obligations, or workplace safety policies.</li>
        <li>Falsify checklist answers, completion dates, signatures, or maintenance reports.</li>
        <li>Use reports or records to misrepresent work that has not actually been completed.</li>
    </ul>

    <p>Users are responsible for reviewing their entries for accuracy and reporting mistakes promptly. Corrections should follow the organization's authorized record-correction process.</p>
</section>

<section class="legal-section" id="maintenance">
    <div class="section-title">
        <span class="section-number">06</span>
        <h2>Maintenance Records, Checklists, and Reports</h2>
    </div>

    <p>Maintenance records may include asset information, job identifiers, scheduled weeks, maintenance frequencies, checklist responses, observations, comments, intervention details, signatures, and generated tickets or reports.</p>

    <ul>
        <li><strong>Accuracy:</strong> Record actual work performed and observations made. Do not mark an intervention complete if it has not been completed.</li>
        <li><strong>Verification:</strong> Confirm asset identifiers, schedules, checklist questions, and report details before relying on generated documents.</li>
        <li><strong>Operational decisions:</strong> Authorized personnel remain responsible for maintenance decisions, inspections, and compliance with technical procedures.</li>
        <li><strong>Printing and export:</strong> Printed or exported documents may contain confidential operational or employee information and must be handled appropriately.</li>
        <li><strong>Record changes:</strong> Imports, edits, deletions, and checklist configuration changes must be performed only by authorized personnel.</li>
    </ul>

    <p>TapApp records and generated reports are intended to support operational work. Users must follow their organization's document-control, retention, and approval requirements.</p>

    <div class="notice">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div><strong>Safety reminder:</strong> A completed digital checklist is not, by itself, proof that equipment is safe to operate. Follow approved procedures, isolation requirements, inspections, and escalation rules.</div>
    </div>
</section>

<section class="legal-section" id="security">
    <div class="section-title">
        <span class="section-number">07</span>
        <h2>Security, Confidentiality, and Activity Records</h2>
    </div>

    <p>Users must protect their credentials, use TapApp only on authorized devices where required, and take reasonable precautions when viewing, printing, or exporting workplace information.</p>

    <p>Depending on the features enabled, the application may record security and operational events, such as sign-ins, sign-outs, record changes, checklist updates, imports, exports, printing actions, and administrative changes. Authorized personnel may review these records for security, troubleshooting, accountability, and operational oversight.</p>

    <p>Users must not disable, tamper with, or circumvent applicable security controls or audit mechanisms. Report suspected data exposure, account compromise, or suspicious activity to the designated administrator as soon as possible.</p>

    <p>No online application can guarantee absolute security or uninterrupted protection. Access controls and technical safeguards should be used together with responsible user behavior and appropriate organizational procedures.</p>
</section>

<section class="legal-section" id="availability">
    <div class="section-title">
        <span class="section-number">08</span>
        <h2>Availability, Maintenance, and Changes to the Service</h2>
    </div>

    <p>TapApp may occasionally require updates, maintenance, troubleshooting, or temporary restrictions. Features may be modified, suspended, or unavailable because of technical issues, infrastructure limitations, security concerns, or organizational decisions.</p>

    <p>Where reasonably practical, users should be informed of planned interruptions that materially affect their work. Emergency maintenance or security measures may be applied without advance notice when necessary.</p>

    <p>Users must follow approved business-continuity procedures and must not assume that a particular record, report, or feature will always be accessible. Organizations should maintain any independent records required by their operational or regulatory obligations.</p>
</section>

<section class="legal-section" id="intellectual-property">
    <div class="section-title">
        <span class="section-number">09</span>
        <h2>Intellectual Property and Organizational Content</h2>
    </div>

    <p>The TapApp application, its original design, branding, interface, and software components are protected by applicable intellectual-property laws to the extent those rights exist and are owned or licensed by the relevant rights holders.</p>

    <p>You may use the application only for its intended and authorized purposes. These terms do not transfer ownership of the application or grant a license beyond what is necessary for permitted use.</p>

    <p>Maintenance records, employee information, equipment data, and other content submitted to TapApp may belong to or be controlled by the organization or another lawful rights holder. Their use, access, and disclosure remain subject to applicable rights, agreements, and organizational policies.</p>
</section>

<section class="legal-section" id="suspension">
    <div class="section-title">
        <span class="section-number">10</span>
        <h2>Account Suspension, Deactivation, and Termination</h2>
    </div>

    <p>Access may be restricted, suspended, or terminated when an account is no longer authorized, the user leaves the organization, access approval is withdrawn, security risks arise, or a user violates applicable rules.</p>

    <p>Where configured, administrators may deactivate accounts. A deactivated user may be unable to sign in until authorized access is restored. Registration requests may also be rejected before an active account is created.</p>

    <p>Administrative decisions should follow applicable organizational procedures and legal requirements. Where appropriate, the user may contact the designated administrator to request clarification or review of an access decision.</p>

    <p>Suspending an account does not automatically mean that related maintenance records or audit records are deleted. Such information may be retained where necessary for operational continuity, security, legal compliance, or an applicable retention policy.</p>
</section>

<section class="legal-section" id="liability">
    <div class="section-title">
        <span class="section-number">11</span>
        <h2>Disclaimers and Limitation of Liability</h2>
    </div>

    <p>TapApp is provided as a tool to support maintenance-management activities. To the extent permitted by applicable law, the service is provided subject to its operational limitations, and no guarantee is made that it will always be uninterrupted, error-free, or suitable for every purpose.</p>

    <p>Users must independently verify critical information, including maintenance schedules, asset identifiers, safety instructions, checklist results, and intervention completion status.</p>

    <p>Nothing in these terms excludes or limits liability, warranties, or rights that cannot lawfully be excluded or limited. Any permitted limitation of liability must be interpreted in accordance with applicable law and any binding agreement governing the service.</p>
</section>

<section class="legal-section" id="privacy">
    <div class="section-title">
        <span class="section-number">12</span>
        <h2>Privacy and Personal Information</h2>
    </div>

    <p>TapApp may process personal information needed to manage accounts, authorize access, support maintenance workflows, communicate account status, and protect the application.</p>

    <p>Examples include names, matricules, email addresses, profile pictures, signatures, account roles, access status, and relevant activity records. The exact information collected depends on the features enabled and how the application is operated.</p>

    <p>Access to personal information should be limited to authorized purposes. Details about information handling, security, retention, and user requests are described in the <a class="inline-link" href="{{ url('/privacy') }}">Privacy Policy</a>.</p>
</section>

<section class="legal-section" id="changes">
    <div class="section-title">
        <span class="section-number">13</span>
        <h2>Changes to These Terms</h2>
    </div>

    <p>These terms may be revised to reflect changes to TapApp, its features, security requirements, organizational processes, or applicable legal obligations.</p>

    <p>The updated document will display its revision date. Where required, material changes will be communicated or presented for renewed acceptance. Continued use after changes take effect is subject to applicable law and any required consent process.</p>

    <p>Users should review this page periodically, especially after receiving notice of an important change.</p>
</section>

<section class="legal-section" id="contact">
    <div class="section-title">
        <span class="section-number">14</span>
        <h2>Contact and Questions</h2>
    </div>

    <p>For questions about these terms, access permissions, registration decisions, maintenance records, or application use, contact your organization's designated TapApp administrator.</p>

    <p>For a legal notice, formal complaint, or request that requires a specific response deadline, use the official contact channel designated by the organization responsible for TapApp.</p>

    <div class="notice">
        <i class="fa-solid fa-circle-info"></i>
        <div>Before publishing, replace this general contact guidance with the responsible organization's legal name and an official contact address or email, where appropriate.</div>
    </div>
</section>

@endsection

@section('privacy_link')
    <a class="bottom-link" href="{{ url('/privacy') }}">
        <i class="fa-solid fa-user-shield"></i> Privacy Policy
    </a>
@endsection
