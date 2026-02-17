<!-- Product Landing Page -->
<style>
    html {
        scroll-behavior: smooth;
    }

    :root {
        --font-sans: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        --bg: #f8fafc;
        --surface: #ffffff;
        --text: #0f172a;
        --muted: #475569;
        --border: #e2e8f0;
        --primary: #2563eb;
        --primary-strong: #1d4ed8;
        --accent: #0ea5e9;
        --ring: rgba(37, 99, 235, 0.35);
        --shadow-sm: 0 1px 2px rgba(2, 6, 23, 0.05), 0 1px 1px rgba(2, 6, 23, 0.03);
        --shadow-md: 0 10px 30px rgba(2, 6, 23, 0.08);
        --radius: 12px;
        --radius-sm: 10px;
        --space-1: 8px;
        --space-2: 16px;
        --space-3: 24px;
        --space-4: 32px;
        --space-5: 48px;
        --space-6: 64px;
        --space-7: 80px;
        --container: 1120px;
    }

    .product-landing {
        font-family: var(--font-sans);
        color: var(--text);
        background: var(--bg);
        line-height: 1.6;
    }

    .container {
        width: min(var(--container), 100% - 2rem);
        margin-inline: auto;
    }

    .section {
        padding-block: var(--space-6);
    }

    section[id] {
        scroll-margin-top: 40px;
    }

    .title {
        margin: 0;
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .lead {
        margin: var(--space-2) 0 0;
        max-width: 64ch;
        color: var(--muted);
        font-size: 1.05rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.22s ease, box-shadow 0.22s ease, background-color 0.22s ease, color 0.22s ease, border-color 0.22s ease;
    }

    .btn:focus-visible,
    .nav-link:focus-visible {
        outline: none;
        box-shadow: 0 0 0 4px var(--ring);
    }

    .btn-primary {
        color: #fff;
        background: linear-gradient(180deg, var(--primary), var(--primary-strong));
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
    }

    .btn-ghost {
        color: var(--text);
        background: var(--surface);
        border-color: var(--border);
    }

    .btn-ghost:hover {
        border-color: #93c5fd;
        box-shadow: var(--shadow-sm);
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        transition: border-color 0.22s ease, box-shadow 0.22s ease, transform 0.22s ease;
    }

    .card:hover {
        border-color: #93c5fd;
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.12);
        transform: translateY(-2px);
    }

    /* Header */
    .site-header {
        position: sticky;
        top: 0;
        z-index: 50;
        background: rgba(248, 250, 252, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border);
    }

    .header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 72px;
    }

    .brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--text);
        font-weight: 700;
    }

    .brand-mark {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
        box-shadow: var(--shadow-sm);
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .nav {
        display: none;
        align-items: center;
        gap: 20px;
    }

    .nav-link {
        color: var(--muted);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .nav-link:hover {
        color: var(--text);
    }

    /* Hero */
    .hero {
        position: relative;
        overflow: clip;
    }

    .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 10% 10%, rgba(14, 165, 233, 0.15), transparent 40%),
            radial-gradient(circle at 90% 20%, rgba(37, 99, 235, 0.12), transparent 30%);
        pointer-events: none;
    }

    .hero-grid {
        position: relative;
        display: grid;
        gap: var(--space-4);
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: var(--space-2);
        padding: 6px 12px;
        border-radius: 999px;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        background: #eff6ff;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-2);
        margin-top: var(--space-3);
    }

    .hero-panel {
        padding: var(--space-3);
    }

    .hero-metric {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-2);
        margin-top: var(--space-3);
    }

    .metric strong {
        display: block;
        font-size: 1.2rem;
        line-height: 1.2;
    }

    .metric span {
        font-size: 0.85rem;
        color: var(--muted);
    }

    /* How It Works */
    .workflow-grid {
        margin-top: var(--space-4);
        display: grid;
        gap: var(--space-2);
    }

    .workflow-step {
        padding: var(--space-3);
    }

    .workflow-step .step-no {
        display: inline-flex;
        width: 28px;
        height: 28px;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #dbeafe;
        color: #1e40af;
        font-size: 0.82rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .workflow-step h3 {
        margin: 0;
        font-size: 1.02rem;
    }

    .workflow-step p {
        margin: 8px 0 0;
        color: var(--muted);
        font-size: 0.95rem;
    }

    /* Features */
    .feature-grid {
        margin-top: var(--space-4);
        display: grid;
        gap: var(--space-2);
    }

    .feature-card {
        padding: var(--space-3);
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: var(--primary);
        background: #eff6ff;
        margin-bottom: 12px;
        transition: transform 0.22s ease, background-color 0.22s ease, color 0.22s ease;
    }

    .card:hover .feature-icon {
        transform: translateY(-1px);
        background: #dbeafe;
        color: #1d4ed8;
    }

    .feature-card h3 {
        margin: 0;
        font-size: 1.05rem;
    }

    .feature-card p {
        margin: 8px 0 0;
        color: var(--muted);
        font-size: 0.95rem;
    }

    /* Reliability */
    .reliability-grid {
        margin-top: var(--space-4);
        display: grid;
        gap: var(--space-2);
    }

    .reliability-card {
        padding: var(--space-3);
    }

    .reliability-card p {
        margin: 0;
        color: #0b1324;
    }

    .reliability-card footer {
        margin-top: 14px;
        color: var(--muted);
        font-size: 0.9rem;
    }

    /* About Product */
    .about-grid {
        margin-top: var(--space-4);
        display: grid;
        gap: var(--space-2);
        align-items: stretch;
    }

    .plan {
        padding: var(--space-3);
        display: flex;
        flex-direction: column;
        gap: 14px;
        height: 100%;
    }

    .plan.featured {
        border-color: #93c5fd;
        box-shadow: var(--shadow-md);
    }

    .plan h3 {
        margin: 0;
        font-size: 1.1rem;
        line-height: 1.3;
    }

    .price {
        margin: 10px 0;
        display: flex;
        align-items: baseline;
        gap: 6px;
    }

    .price strong {
        font-size: 2rem;
        line-height: 1;
    }

    .plan ul {
        margin: 0;
        padding-left: 18px;
        list-style: disc;
        color: var(--muted);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .plan li + li {
        margin-top: 8px;
    }

    /* Final CTA */
    .cta {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: #e2e8f0;
    }

    .cta .container {
        text-align: center;
    }

    .cta .title {
        color: #fff;
    }

    .cta .lead {
        color: #cbd5e1;
        margin-inline: auto;
    }

    /* Footer */
    .site-footer {
        border-top: 1px solid var(--border);
        background: #fff;
        padding: var(--space-3) 0;
        color: var(--muted);
        font-size: 0.9rem;
    }

    .footer-inner {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Responsive */
    @media (min-width: 768px) {
        .section {
            padding-block: var(--space-7);
        }

        .nav {
            display: inline-flex;
        }

        .hero-grid {
            grid-template-columns: 1.1fr 0.9fr;
            align-items: center;
        }

        .workflow-grid,
        .feature-grid,
        .reliability-grid,
        .about-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .workflow-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .footer-inner {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }
</style>

<main class="product-landing">
    <!-- Header -->
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="<?= $this->urlFor('landing') ?>">
                <span class="brand-mark" aria-hidden="true">P</span>
                <span>Passito</span>
            </a>
            <nav class="nav" aria-label="Primary">
                <a class="nav-link" href="#workflow">How It Works</a>
                <a class="nav-link" href="#features">Features</a>
                <a class="nav-link" href="#reliability">Reliability</a>
                <a class="nav-link" href="#about">About Product</a>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero section" aria-labelledby="hero-title">
        <div class="container hero-grid">
            <div>
                <p class="eyebrow">Built for Institutions</p>
                <h1 id="hero-title" class="title">Manage hostel outpasses<br>with speed and clarity.</h1>
                <p class="lead">Passito brings outpass requests, parent verification, approvals, gate checks and notifications into one secure system so institutions can run daily operations with less manual effort.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#workflow">See How It Works</a>
                    <a class="btn btn-ghost" href="#features">View Core Features</a>
                </div>
            </div>
            <aside class="card hero-panel" aria-label="Key metrics">
                <h2 style="margin:0;font-size:1.1rem;">Platform Snapshot</h2>
                <p style="margin:8px 0 0;color:var(--muted);font-size:0.95rem;">Focused on paperless operations, faster approvals and clear daily tracking.</p>
                <div class="hero-metric">
                    <div class="metric"><strong>100%</strong><span>No Paper Forms</span></div>
                    <div class="metric"><strong>&lt;2 Min</strong><span>Avg. Approval Time</span></div>
                    <div class="metric"><strong>24/7</strong><span>Request Visibility</span></div>
                </div>
            </aside>
        </div>
    </section>

    <!-- How It Works -->
    <section id="workflow" class="section" aria-labelledby="workflow-title">
        <div class="container">
            <h2 id="workflow-title" class="title">How Passito works day to day</h2>
            <p class="lead">A clear 4-step process that removes confusion and keeps everyone aligned.</p>
            <div class="workflow-grid">
                <article class="card workflow-step">
                    <span class="step-no">1</span>
                    <h3>Student raises request</h3>
                    <p>Outpass requests are submitted online instead of manual paper forms.</p>
                </article>
                <article class="card workflow-step">
                    <span class="step-no">2</span>
                    <h3>Approval and parent check</h3>
                    <p>Wardens review requests, with parent verification added when required.</p>
                </article>
                <article class="card workflow-step">
                    <span class="step-no">3</span>
                    <h3>Pass is issued</h3>
                    <p>After approval, pass details are generated with secure verification support.</p>
                </article>
                <article class="card workflow-step">
                    <span class="step-no">4</span>
                    <h3>Gate entry and exit</h3>
                    <p>Gate staff verify quickly and movement records are updated automatically.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="section" aria-labelledby="features-title">
        <div class="container">
            <h2 id="features-title" class="title">Everything your institution needs in one place</h2>
            <p class="lead">Built for wardens, administrators, students and gate staff with a simple and consistent experience.</p>
            <div class="feature-grid">
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">✓</div>
                    <h3>Quick request approvals</h3>
                    <p>Set approval rules once, then process outpass requests faster with clear stage-by-stage updates.</p>
                </article>
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">◈</div>
                    <h3>Parent approval when needed</h3>
                    <p>Include parent verification for selected cases before the final outpass is issued.</p>
                </article>
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">⛁</div>
                    <h3>Less paperwork and better flow</h3>
                    <p>Replace manual forms with a smoother digital process from request submission to gate verification.</p>
                </article>
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">⚡</div>
                    <h3>Automatic email updates</h3>
                    <p>Students and staff get instant email updates for approvals, rejections and status changes.</p>
                </article>
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">☰</div>
                    <h3>Outpass records and logbook</h3>
                    <p>Manage outpass records and maintain a clear check-in/check-out logbook for student movements.</p>
                </article>
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">✉</div>
                    <h3>Automated daily reports</h3>
                    <p>Share daily movement and late-arrival summaries with stakeholders through scheduled email reports.</p>
                </article>
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">▣</div>
                    <h3>Tamper-resistant QR verification</h3>
                    <p>QR pass information is secured so verification data cannot be easily altered, copied, or misused.</p>
                </article>
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">⌁</div>
                    <h3>Manual or automated verifiers</h3>
                    <p>Configure verification mode anytime and run with manual verifiers or automated verifier setup.</p>
                </article>
                <article class="card feature-card">
                    <div class="feature-icon" aria-hidden="true">◧</div>
                    <h3>Custom outpass templates</h3>
                    <p>Create, customize and manage outpass templates to match your institution’s approval and travel rules.</p>
                </article>

            </div>
        </div>
    </section>

    <!-- Reliability -->
    <section id="reliability" class="section" aria-labelledby="reliability-title">
        <div class="container">
            <h2 id="reliability-title" class="title">Built to be reliable every day</h2>
            <p class="lead">Passito is made for everyday campus operations where speed, clarity and consistency matter.</p>
            <div class="reliability-grid">
                <article class="card reliability-card">
                    <p>Run it inside your institution’s own server environment to keep control in your hands.</p>
                    <footer>Institution-owned deployment</footer>
                </article>
                <article class="card reliability-card">
                    <p>Everyone sees what they need to do, so work moves smoothly and decisions are easier to track.</p>
                    <footer>Clear role-based workflow</footer>
                </article>
                <article class="card reliability-card">
                    <p>Even during busy hours, teams can process requests quickly with clear records.</p>
                    <footer>Handles day-to-day campus load</footer>
                </article>
                <article class="card reliability-card">
                    <p>Verifier operations stay dependable with support for both automated and manual verification workflows.</p>
                    <footer>Flexible verification model</footer>
                </article>
                <article class="card reliability-card">
                    <p>Daily reports for movement and late arrivals keep management updated without manual follow-up work.</p>
                    <footer>Consistent daily visibility</footer>
                </article>
                <article class="card reliability-card">
                    <p>Outpass records and check-in/check-out logbook entries stay organized for review, audits and decision-making.</p>
                    <footer>Clean operational history</footer>
                </article>
            </div>
        </div>
    </section>

    <!-- About Product -->
    <section id="about" class="section" aria-labelledby="about-title">
        <div class="container">
            <h2 id="about-title" class="title">Why institutions choose Passito</h2>
            <p class="lead">Passito delivers practical value: faster processing, fewer manual steps, clear communication and stronger operational control.</p>
            <div class="about-grid">
                <article class="card plan featured">
                    <h3>Business Value</h3>
                    <ul>
                        <li>Save staff time with a streamlined digital process</li>
                        <li>Reduce delays using faster approvals and parent verification flow</li>
                        <li>Keep stakeholders informed with built-in email notifications</li>
                    </ul>
                </article>
                <article class="card plan">
                    <h3>Institution Fit</h3>
                    <ul>
                        <li>Built for hostel and campus operations</li>
                        <li>Supports coordination across students, wardens, verifiers and admins</li>
                        <li>Secure system design with clear records for daily decisions</li>
                    </ul>
                </article>
                <article class="card plan">
                    <h3>Built for Daily Use</h3>
                    <ul>
                        <li>Easy for staff to adopt with minimal training</li>
                        <li>Clear process from request to final gate verification</li>
                        <li>Helps institutions maintain consistent operations every day</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="cta section" aria-labelledby="cta-title">
        <div class="container">
            <h2 id="cta-title" class="title">Ready to improve your outpass process?</h2>
            <p class="lead">Adopt a modern system that helps your institution work faster, safer and with better clarity.</p>
            <div class="hero-actions" style="justify-content:center;">
                <a class="btn btn-primary" href="#about">Learn More</a>
                <a class="btn btn-ghost" href="#features">Explore Features</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container footer-inner">
            <p>&copy; <?= date('Y') ?> Passito. All rights reserved.</p>
            <p>High-trust infrastructure for campus access workflows.</p>
        </div>
    </footer>
</main>
