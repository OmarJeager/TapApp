
@extends('legal.layout')

@section('title', 'Privacy Policy')
@section('meta_description', 'Learn how TapApp handles account information, maintenance data, and security records.')
@section('breadcrumb', 'Privacy Policy')

@section('hero_title')
    Your Privacy. <span>Our Priority.</span>
@endsection

@section('hero_description')
    Understand what information TapApp may handle, why it is needed,
    how access is controlled, and how to raise a privacy concern.
@endsection

@section('intro')
    This Privacy Policy describes the personal information and operational data
    that may be processed when you use TapApp. The final practices depend on the
    application's configuration, the organization operating it, and applicable law.
    Review this document together with the Terms of Service.
@endsection

@section('toc')
    <a href="#overview"><span>01</span> Overview and Responsibility</a>
    <a href="#information"><span>02</span> Information We Handle</a>
    <a href="#collection"><span>03</span> How Information Is Collected</a>
    <a href="#purposes"><span>04</span> Why Information Is Used</a>
    <a href="#approvals"><span>05</span> Accounts and Emails</a>
    <a href="#maintenance-data"><span>06</span> Maintenance Records</a>
    <a href="#activity"><span>07</span> Activity and Security Logs</a>
    <a href="#sharing"><span>08</span> Access and Sharing</a>
    <a href="#security"><span>09</span> Security Measures</a>
    <a href="#retention"><span>10</span> Data Retention and Deletion</a>
    <a href="#rights"><span>11</span> Privacy Rights and Requests</a>
    <a href="#cookies"><span>12</span> Cookies and Browser Storage</a>
    <a href="#transfers"><span>13</span> Hosting and Transfers</a>
    <a href="#minors"><span>14</span> Children and Intended Users</a>
    <a href="#changes"><span>15</span> Policy Changes</a>
    <a href="#contact"><span>16</span> Contact and Complaints</a>
@endsection

@section('content')

<section class="legal-section" id="overview">
    <div class="section-title">
        <span class="section-number">01</span>
        <h2>Overview and Responsibility</h2>
    </div>

    <p>TapApp is a maintenance-management application that supports preventive maintenance records, checklists, equipment information, reports, and administrative workflows.</p>

    <p>Depending on the deployment, TapApp may be operated by an organization for its employees and authorized personnel. The organization responsible for deciding why and how personal information is processed should be identified before this policy is published.</p>

    <p>In applicable privacy terminology, that organization may act as the data controller, while hosting providers, email services, and other technology providers may process information on its behalf. Their actual roles depend on the arrangements in place.</p>

    <p>This policy applies to information processed through the TapApp application and related account workflows covered by the service. It does not automatically cover external websites or services that have their own privacy policies.</p>
</section>

<section class="legal-section" id="information">
    <div class="section-title">
        <span class="section-number">02</span>
        <h2>Information We Handle</h2>
    </div>

    <p>Depending on the features you use and the configuration of TapApp, the following categories of information may be processed.</p>

    <p><strong>Account and identity information</strong></p>
    <ul>
        <li>Name, matricule or employee identifier, and email address.</li>
        <li>Assigned role, account approval status, and account activation status.</li>
        <li>Profile picture and electronic signature, if you provide or upload them.</li>
        <li>Email-verification or account-management status, where applicable.</li>
    </ul>

    <p><strong>Authentication and security information</strong></p>
    <ul>
        <li>Authentication-related records and timestamps for relevant account events.</li>
        <li>Technical request information, such as IP address or browser user-agent, if collected by enabled logging or infrastructure features.</li>
        <li>Security and administrative events associated with account or system activity.</li>
    </ul>

    <p><strong>Maintenance and operational information</strong></p>
    <ul>
        <li>Equipment identifiers, asset descriptions, job references, schedules, frequencies, and intervention details.</li>
        <li>Checklist answers, comments, observations, completion details, and related records.</li>
        <li>Reports, imported data, printed tickets, and other records created through authorized workflows.</li>
    </ul>

    <p><strong>Communications and support information</strong></p>
    <ul>
        <li>Messages related to registration approval, rejection, email verification, password recovery, or account status, where those functions are enabled.</li>
        <li>Information you provide when reporting an account or application issue.</li>
    </ul>

    <p>TapApp should not collect information that is unnecessary for its stated purposes. Users should not enter unrelated personal, confidential, or sensitive information into maintenance fields unless explicitly authorized and genuinely required.</p>
</section>

<section class="legal-section" id="collection">
    <div class="section-title">
        <span class="section-number">03</span>
        <h2>How Information Is Collected</h2>
    </div>

    <p>Information may be obtained from several sources:</p>

    <ul>
        <li><strong>Directly from you:</strong> When you register, update your profile, complete a checklist, add an observation, or contact support.</li>
        <li><strong>From authorized administrators:</strong> When an administrator approves a registration, assigns a role, updates account status, or manages maintenance records.</li>
        <li><strong>Through operational workflows:</strong> When authorized personnel import maintenance data, generate reports, or record intervention activity.</li>
        <li><strong>Automatically through the application or infrastructure:</strong> When enabled features or server logs record relevant technical and security events.</li>
    </ul>

    <p>Some account information may be required to register or provide access. If required information is not provided, the relevant function may be unavailable or the registration request may not be processed.</p>
</section>

<section class="legal-section" id="purposes">
    <div class="section-title">
        <span class="section-number">04</span>
        <h2>Why Information Is Used</h2>
    </div>

    <p>Personal information may be used for purposes such as:</p>

    <ul>
        <li>Creating, reviewing, approving, maintaining, and managing accounts.</li>
        <li>Authenticating users and enforcing role-based permissions.</li>
        <li>Delivering maintenance records, checklists, tickets, dashboards, and reports.</li>
        <li>Communicating account approval, rejection, verification, or relevant service information.</li>
        <li>Investigating technical problems, suspected misuse, and security incidents.</li>
        <li>Maintaining appropriate accountability for authorized changes to operational records.</li>
        <li>Meeting applicable legal, contractual, workplace, and recordkeeping obligations.</li>
    </ul>

    <p>Where required by applicable law, the responsible organization should identify the appropriate legal basis for each processing purpose, such as performance of a contract, a legitimate interest, a legal obligation, or consent where consent is the appropriate basis.</p>

    <p>Information should not be repurposed in a materially incompatible way without an appropriate legal basis and any required notice or consent.</p>
</section>

<section class="legal-section" id="approvals">
    <div class="section-title">
        <span class="section-number">05</span>
        <h2>Registration, Approval, and Email Communications</h2>
    </div>

    <p>Where TapApp's registration approval workflow is enabled, a registration request may be reviewed before an active account is created or access is granted. The request may be approved or rejected by an authorized reviewer.</p>

    <p>Relevant information may be processed to make and record the decision, manage the user's access, and send account-related notifications. Depending on the configured workflow, those notifications may include approval, rejection, verification, login instructions, or password recovery messages.</p>

    <p>Email delivery may involve a configured email service provider. The recipient address and message content necessary to deliver a notification may be processed by that provider under its applicable terms and privacy commitments.</p>

    <p>TapApp should not include passwords, authentication tokens, or other secrets in ordinary notification emails. Users should verify that account-related messages are genuine and report suspicious messages to the designated administrator.</p>
</section>

<section class="legal-section" id="maintenance-data">
    <div class="section-title">
        <span class="section-number">06</span>
        <h2>Maintenance Records and Confidential Operational Data</h2>
    </div>

    <p>TapApp may process maintenance and equipment information that is not personal information by itself but may be confidential or commercially sensitive to the organization.</p>

    <p>This may include:</p>

    <ul>
        <li>Equipment and asset identifiers, descriptions, and technical information.</li>
        <li>Preventive maintenance schedules, job references, frequencies, and intervention details.</li>
        <li>Checklist responses, observations, comments, and completion records.</li>
        <li>Imported spreadsheets, generated tickets, barcodes, reports, and exported documents.</li>
    </ul>

    <p>Where a maintenance record contains a name, signature, employee identifier, or other information relating to an identifiable person, that portion of the record may also constitute personal information.</p>

    <p>Access to operational information should be limited to authorized users who need it for their work. Printed reports and exported files should be stored, shared, and disposed of according to the organization's confidentiality and document-control requirements.</p>
</section>

<section class="legal-section" id="activity">
    <div class="section-title">
        <span class="section-number">07</span>
        <h2>Activity Logs and Security Monitoring</h2>
    </div>

    <p>Where activity tracking is enabled, TapApp may record actions relevant to security, accountability, and system administration. These may include:</p>

    <ul>
        <li>Sign-in and sign-out events.</li>
        <li>Creation, modification, or deletion of authorized records.</li>
        <li>Checklist status changes and other maintenance-related updates.</li>
        <li>Imports, exports, report generation, or printing events.</li>
        <li>Profile changes and administrative account actions.</li>
        <li>Technical information associated with an event, such as the time, route, or request metadata, where configured.</li>
    </ul>

    <p>Such information may be reviewed by appropriately authorized personnel to investigate incidents, diagnose problems, protect accounts, and verify compliance with internal procedures.</p>

    <p>Activity logging should be proportionate to these purposes. Passwords, password hashes, session cookies, authentication tokens, and other authentication secrets should not be included in ordinary audit records.</p>
</section>

<section class="legal-section" id="sharing">
    <div class="section-title">
        <span class="section-number">08</span>
        <h2>Who May Access or Receive Information</h2>
    </div>

    <p>TapApp information may be accessible to the following categories of recipients where necessary and authorized:</p>

    <ul>
        <li><strong>Authorized users:</strong> Personnel who need specific information to perform their assigned maintenance or operational duties.</li>
        <li><strong>Administrators:</strong> Authorized personnel responsible for account approval, access management, troubleshooting, or system oversight.</li>
        <li><strong>Technology providers:</strong> Hosting, database, email delivery, and infrastructure providers used by the actual deployment.</li>
        <li><strong>Professional advisers or public authorities:</strong> Where disclosure is necessary and lawful, for example to comply with a binding legal obligation.</li>
    </ul>

    <p>Information should not be sold to unrelated third parties as part of the intended operation of TapApp. Any sharing must have an appropriate purpose and legal basis.</p>

    <p>Access should follow least-privilege principles. The fact that a person is an administrator does not justify unrelated access or disclosure of personal information.</p>
</section>

<section class="legal-section" id="security">
    <div class="section-title">
        <span class="section-number">09</span>
        <h2>Security Measures</h2>
    </div>

    <p>Protecting TapApp information requires appropriate technical and organizational safeguards. Depending on the deployment, relevant safeguards may include:</p>

    <ul>
        <li>Authentication and role-based authorization.</li>
        <li>Secure password hashing and appropriate credential handling.</li>
        <li>Request validation and protections against common application vulnerabilities.</li>
        <li>Restricted access to databases, uploaded files, exports, and administrative functions.</li>
        <li>Security monitoring, backups, and incident-response procedures where configured.</li>
        <li>Appropriate protection of communications and data in transit and at rest.</li>
    </ul>

    <p>These are security objectives and examples, not a guarantee that every control is enabled in every deployment. The responsible organization should verify its actual configuration and security practices before representing a particular safeguard as implemented.</p>

    <p>No internet-connected service can guarantee absolute security. If you believe an account or personal information has been compromised, contact the designated administrator promptly.</p>
</section>

<section class="legal-section" id="retention">
    <div class="section-title">
        <span class="section-number">10</span>
        <h2>Data Retention, Correction, and Deletion</h2>
    </div>

    <p>Personal information should be kept only for as long as reasonably necessary for the purposes for which it was collected, subject to applicable legal requirements and legitimate operational needs.</p>

    <p>Different categories of information may require different retention periods. For example, account information, registration decisions, maintenance records, security logs, and backups may be governed by different organizational or legal requirements.</p>

    <p>When information is no longer required, the responsible organization should delete it, anonymize it, or otherwise handle it in accordance with its retention policy and applicable law.</p>

    <p>Deleting or deactivating an account may not automatically delete every related maintenance record, audit entry, email record, or backup. Some information may need to be retained for operational continuity, security, dispute resolution, or legal compliance.</p>

    <p>The organization operating TapApp should establish and communicate actual retention periods and a process for handling deletion requests. This policy does not promise a specific deletion timeline unless one is formally adopted.</p>
</section>

<section class="legal-section" id="rights">
    <div class="section-title">
        <span class="section-number">11</span>
        <h2>Your Privacy Rights and Requests</h2>
    </div>

    <p>Depending on the applicable law and the circumstances of processing, you may have rights concerning your personal information. These may include:</p>

    <ul>
        <li>Requesting access to personal information about you.</li>
        <li>Requesting correction of inaccurate or incomplete information.</li>
        <li>Requesting deletion or restriction of processing where legally applicable.</li>
        <li>Objecting to certain processing or requesting portability where those rights apply.</li>
        <li>Withdrawing consent where processing is based on consent, without affecting the lawfulness of prior processing.</li>
        <li>Submitting a complaint to the competent data-protection authority.</li>
    </ul>

    <p>These rights are subject to applicable legal conditions, exemptions, and the rights of other people. Some maintenance or audit records may need to be retained even when a request is made.</p>

    <p>To submit a request, contact the organization responsible for TapApp through its designated privacy or administrative contact. The organization may need to verify your identity before responding.</p>

    <p>If TapApp is operated in Morocco, the responsible organization should assess its obligations under applicable Moroccan data-protection law, including Law No. 09-08 and any applicable requirements administered by the CNDP. Other laws may apply depending on the people and organizations involved.</p>
</section>

<section class="legal-section" id="cookies">
    <div class="section-title">
        <span class="section-number">12</span>
        <h2>Cookies and Browser Storage</h2>
    </div>

    <p>TapApp may use browser cookies or similar technologies required for functions such as maintaining an authenticated session, protecting requests, and supporting application operation.</p>

    <p>Depending on the authentication setup, these technologies may be necessary for the application to function correctly. Disabling essential cookies may cause login, session, or other protected features to stop working.</p>

    <p>This page also includes optional interface preferences such as a theme selector and language selector. The current page implementation changes these preferences in the browser; it does not itself save them persistently.</p>

    <p>The responsible organization should update this section if analytics, advertising, third-party tracking, or additional persistent browser storage is introduced. Any consent required by applicable law must be obtained appropriately.</p>
</section>

<section class="legal-section" id="transfers">
    <div class="section-title">
        <span class="section-number">13</span>
        <h2>Hosting, Service Providers, and International Transfers</h2>
    </div>

    <p>Information may be stored or processed on the servers and infrastructure used to host TapApp. Depending on the actual deployment, the service may also rely on external providers for email delivery, backups, or technical operations.</p>

    <p>Where providers process personal information, the responsible organization should assess their security, confidentiality, contractual commitments, and access arrangements.</p>

    <p>If personal information is transferred across national borders, the organization must determine whether applicable law imposes additional conditions, safeguards, authorizations, or disclosures.</p>

    <p>The actual hosting locations, providers, and transfer arrangements should be identified and verified before this policy is published. This template does not assume a particular hosting country or guarantee that no international transfer occurs.</p>
</section>

<section class="legal-section" id="minors">
    <div class="section-title">
        <span class="section-number">14</span>
        <h2>Children and Intended Users</h2>
    </div>

    <p>TapApp is designed primarily for authorized personnel performing maintenance-management and related organizational duties, rather than as a consumer service for children.</p>

    <p>Access should be granted only to individuals authorized by the organization operating the application. If information relating to a child is collected unexpectedly or without an appropriate basis, the responsible organization should assess the situation and take any action required by applicable law.</p>
</section>

<section class="legal-section" id="changes">
    <div class="section-title">
        <span class="section-number">15</span>
        <h2>Changes to This Privacy Policy</h2>
    </div>

    <p>This policy may be updated when TapApp introduces new features, changes its data practices, adopts new service providers, or needs to reflect legal or organizational requirements.</p>

    <p>The revision date should be updated whenever a substantive change is made. Where required, affected users should receive appropriate notice, and consent should be obtained if legally necessary.</p>

    <p>Users should review this page periodically. If a change materially affects how information is handled, the responsible organization should explain the change through an appropriate communication channel.</p>
</section>

<section class="legal-section" id="contact">
    <div class="section-title">
        <span class="section-number">16</span>
        <h2>Contact and Privacy Complaints</h2>
    </div>

    <p>For questions about your personal information, access to records, correction requests, or privacy concerns, contact the organization responsible for operating TapApp through its designated privacy contact.</p>

    <p>Before publication, identify the responsible legal entity, its official postal or business address, and an email address or other suitable channel for privacy requests. Provide any applicable complaint procedure and response expectations.</p>

    <p>Where applicable, you may also contact the competent data-protection authority in accordance with the law governing the processing.</p>

    <div class="notice">
        <i class="fa-solid fa-shield-halved"></i>
        <div><strong>Privacy commitment:</strong> Access to personal information should be limited to legitimate, authorized purposes. Requests and complaints should be handled securely, fairly, and in accordance with applicable law.</div>
    </div>
</section>

@endsection

@section('terms_link')
    <a class="bottom-link" href="{{ url('/terms') }}">
        <i class="fa-solid fa-file-contract"></i> Terms of Service
    </a>
@endsection
