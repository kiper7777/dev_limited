<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dev Limited — Development of Innovative Solutions</title>
    <meta name="description"
        content="Dev Limited — development of innovative digital solutions for modern businesses. Custom software, automation, and data platforms." />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css" />
</head>

<body>

<?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Hero -->
    <main>
        <section class="hero" id="hero">
            <div class="container hero-inner">
                <div class="hero-content">
                    <p class="eyebrow">Dev Limited</p>
                    <h1>We design innovative digital solutions for fast-growing businesses.</h1>
                    <p class="hero-subtitle">
                        Innovative digital products, process automation, and analytics platforms that help companies
                        grow faster and make data-driven decisions.
                    </p>
                    <div class="hero-actions">
                        <a href="#contact" class="btn btn-primary">Book a free consultation</a>
                        <a href="#cases" class="btn btn-ghost">View case studies</a>
                    </div>
                    <div class="hero-metrics">
                        <div class="metric">
                            <span class="metric-value">3+ yrs</span>
                            <span class="metric-label">in software development</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value">10+</span>
                            <span class="metric-label">projects delivered</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value">4 continents</span>
                            <span class="metric-label">clients’ geography</span>
                        </div>
                    </div>
                </div>
                <div class="hero-panel">
                    <div class="hero-card">
                        <h2>Transform ideas into products</h2>
                        <p>From discovery to launch, we support your digital product lifecycle end-to-end.</p>
                        <ul class="hero-list">
                            <li>Product discovery & prototyping</li>
                            <li>Custom web & mobile development</li>
                            <li>Process automation & AI integration</li>
                            <li>Data platforms & analytics dashboards</li>
                        </ul>
                        <div class="hero-badge">
                            <span class="badge-dot"></span>
                            <span>Available for new projects Q1–Q2 2026</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="section" id="services">
            <div class="container">
                <div class="section-header">
                    <p class="eyebrow">Services</p>
                    <h2>What we build for your business</h2>
                    <p class="section-subtitle">
                        We combine product thinking, engineering excellence, and clear communication
                        to ship reliable digital solutions.
                    </p>
                </div>

                <div class="grid grid-3 service-grid">
                    <article class="card service-card">
                        <h3>Custom Software Development</h3>
                        <p>
                            Web applications, internal portals, and mobile products tailored to your business's specific
                            needs and processes.
                        </p>
                        <ul class="tag-list">
                            <li>Web & Mobile Apps</li>
                            <li>B2B Platforms</li>
                            <li>Client Portals</li>
                        </ul>
                    </article>

                    <article class="card service-card">
                        <h3>Automation & AI Enablement</h3>
                        <p>
                            Automation of manual processes, integration of AI models and chatbots,
                            which reduce costs and speed up operations.
                        </p>
                        <ul class="tag-list">
                            <li>Workflow Automation</li>
                            <li>AI Assistants</li>
                            <li>API Integrations</li>
                        </ul>
                    </article>

                    <article class="card service-card">
                        <h3>Data Platforms & Analytics</h3>
                        <p>
                            Centralized data warehouses, BI dashboards, and analytical solutions for more accurate
                            management decisions.
                        </p>
                        <ul class="tag-list">
                            <li>Data Warehouses</li>
                            <li>Reporting Dashboards</li>
                            <li>Realtime Analytics</li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>

        <!-- Solutions / Industries -->
        <section class="section section-alt" id="solutions">
            <div class="container">
                <div class="section-header">
                    <p class="eyebrow">Solutions</p>
                    <h2>Designed for modern digital-first industries</h2>
                    <p class="section-subtitle">
                        We focus on domains where thoughtful UX and robust engineering
                        directly impact revenue and operational efficiency.
                    </p>
                </div>

                <div class="grid grid-4 solutions-grid">
                    <article class="pill-card">
                        <h3>FinTech & Payments</h3>
                        <p>Client portals, onboarding flows, KYC/KYB tools, and secure payment workflows.</p>
                    </article>
                    <article class="pill-card">
                        <h3>Retail & eCommerce</h3>
                        <p>Multi-channel platforms, inventory automation, and personalised shopping experiences.</p>
                    </article>
                    <article class="pill-card">
                        <h3>Professional Services</h3>
                        <p>CRMs, client workspaces, project and resource management solutions.</p>
                    </article>
                    <article class="pill-card">
                        <h3>SaaS & Startups</h3>
                        <p>MVPs, prototypes and scalable architectures ready for growth and investment.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Process -->
        <section class="section" id="process">
            <div class="container">
                <div class="section-header">
                    <p class="eyebrow">Process</p>
                    <h2>From idea to launch — transparent and predictable</h2>
                </div>

                <ol class="process-steps">
                    <li class="process-step">
                        <div class="step-index">01</div>
                        <div class="step-content">
                            <h3>Discovery & Alignment</h3>
                            <p>
                                Workshops, stakeholder interviews, and analysis of current processes to form a
                                comprehensive picture of the task and goals.
                            </p>
                        </div>
                    </li>
                    <li class="process-step">
                        <div class="step-index">02</div>
                        <div class="step-content">
                            <h3>UX / UI & Solution Architecture</h3>
                            <p>
                                We develop user scenarios, create prototypes and solution architecture, and coordinate
                                the roadmap.
                            </p>
                        </div>
                    </li>
                    <li class="process-step">
                        <div class="step-index">03</div>
                        <div class="step-content">
                            <h3>Agile Development</h3>
                            <p>
                                We develop in iterations, show demos every 1-2 weeks, record the results, and adjust
                                priorities together with you.
                            </p>
                        </div>
                    </li>
                    <li class="process-step">
                        <div class="step-index">04</div>
                        <div class="step-content">
                            <h3>Launch & Continuous Improvement</h3>
                            <p>
                                We support the release, monitor metrics, and help evolve the product based on real data
                                and feedback.
                            </p>
                        </div>
                    </li>
                </ol>
            </div>
        </section>

        <!-- Case Studies -->
        <section class="section section-alt" id="cases">
            <div class="container">
                <div class="section-header">
                    <p class="eyebrow">Case Studies</p>
                    <h2>Selected projects & outcomes</h2>
                    <p class="section-subtitle">
                        Each project is unique, but our priority is always the same —
                        measurable impact on your business.
                    </p>
                </div>

                <div class="grid grid-3 card-grid">
                    <article class="card case-card">
                        <div class="chip">FinTech</div>
                        <h3>Digital onboarding platform for B2B payments</h3>
                        <p>
                            We built a fully digital onboarding flow with automated checks and
                            integrations with KYC providers.
                        </p>
                        <ul class="case-metrics">
                            <li><strong>–40%</strong> onboarding time</li>
                            <li><strong>+25%</strong> conversion to live accounts</li>
                        </ul>
                    </article>

                    <article class="card case-card">
                        <div class="chip">Retail</div>
                        <h3>Inventory automation for omnichannel retailer</h3>
                        <p>
                            Custom integration layer and real-time stock sync across online and offline channels.
                        </p>
                        <ul class="case-metrics">
                            <li><strong>–60%</strong> manual stock operations</li>
                            <li><strong>+15%</strong> order accuracy</li>
                        </ul>
                    </article>

                    <article class="card case-card">
                        <div class="chip">SaaS</div>
                        <h3>Analytics dashboard for subscription platform</h3>
                        <p>
                            Embedded analytics and self-service reports for non-technical stakeholders.
                        </p>
                        <ul class="case-metrics">
                            <li><strong>+3x</strong> reporting speed</li>
                            <li><strong>+18%</strong> MRR in 9 months</li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>

        <!-- About & Technologies -->
        <section class="section" id="about">
            <div class="container about-layout">
                <div class="about-text">
                    <div class="section-header">
                        <p class="eyebrow">About</p>
                        <h2>Dev Limited in a few words</h2>
                    </div>
                    <p>
                        Dev Limited is a team of product owners, architects, and engineers who help companies create
                        sustainable digital solutions.
                        We work with distributed teams across Europe and the UK.
                    </p>
                    <p>
                        We don't just "write code." We analyze the business context,
                        help formulate the right KPIs, and build solutions
                        that scale with your growth.
                    </p>
                </div>

                <div class="about-panel">
                    <h3>Technologies & stack</h3>
                    <p>We select the right tools for your goals and existing infrastructure.</p>
                    <div class="tech-tags">
                        <span>TypeScript</span>
                        <span>React</span>
                        <span>Next.js</span>
                        <span>Node.js</span>
                        <span>PHP / Laravel</span>
                        <span>Python</span>
                        <span>REST / GraphQL</span>
                        <span>PostgreSQL</span>
                        <span>MySQL</span>
                        <span>MongoDB</span>
                        <span>Docker</span>
                        <span>Kubernetes</span>
                        <span>AWS / Azure</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="section section-alt" id="contact">
            <div class="container contact-layout">
                <div class="contact-text">
                    <div class="section-header">
                        <p class="eyebrow">Contact</p>
                        <h2>Let’s discuss your next project</h2>
                    </div>
                    <p>
                        Describe your task in a few sentences, and we'll get back to you with a proposal
                        on collaboration formats and approximate timeframes within 1-2 business days.
                    </p>

                    <div class="contact-details">
                        <div>
                            <h3>Email</h3>
                            <a href="mailto:hello@dev_limited.com">hello@dev_limited.com</a>
                        </div>
                        <div>
                            <h3>LinkedIn</h3>
                            <a href="#" target="_blank" rel="noopener">Dev Limited</a>
                        </div>
                        <div>
                            <h3>HQ</h3>
                            <p>London, United Kingdom</p>
                        </div>
                    </div>
                </div>

                <div class="card contact-form-card">
                    <h3>Project inquiry</h3>
                    <form class="contact-form" id="contactForm" novalidate>
                        <div class="form-field">
                            <label for="name">Full name</label>
                            <input type="text" id="name" name="name" placeholder="Anna Smith" required />
                        </div>
                        <div class="form-field">
                            <label for="email">Work email</label>
                            <input type="email" id="email" name="email" placeholder="you@company.com" required />
                        </div>
                        <div class="form-field">
                            <label for="phone">Phone number</label>
                            <input type="text" id="phone" name="phone" placeholder="+44 7..." />
                        </div>
                        <div class="form-field">
                            <label for="company">Company</label>
                            <input type="text" id="company" name="company" placeholder="Company name" />
                        </div>
                        <div class="form-field">
                            <label for="budget">Approx. budget (optional)</label>
                            <select id="budget" name="budget">
                                <option value="">Select range</option>
                                <option value="10-25k">£10k–£25k</option>
                                <option value="25-50k">£25k–£50k</option>
                                <option value="50-100k">£50k–£100k</option>
                                <option value="100k+">£100k+</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="message">Project details</label>
                            <textarea id="message" name="message" rows="4"
                                placeholder="Briefly describe your idea, current challenges, and timeline."
                                required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full">Send request</button>
                        <p class="form-note">By submitting this form, you agree to be contacted regarding your request.
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-left">
                <div class="footer-brand">
                    <span class="logo-mark">Dev.in</span><span class="logo-text">solutions</span>
                </div>
                <p class="footer-copy">
                    © <span id="year"></span> Dev Limited. All rights reserved.
                </p>
            </div>
            <div class="footer-right">
                <a href="#top" class="footer-link">Back to top</a>
            </div>
        </div>
    </footer>

     <div id="authMessage" class="auth-message" aria-live="polite" role="status"></div>

    <div class="modal-overlay" id="authOverlay" aria-hidden="true"></div>

    <div
        class="modal"
        id="authModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="authTitle"
        aria-describedby="authDescription"
        aria-hidden="true"
    >
        <div class="modal-content">
            <button class="modal-close" id="closeModal" aria-label="Close dialog" type="button">
                &times;
            </button>

            <div id="signInForm">
                <h2 id="authTitle">Sign In</h2>
                <p id="authDescription" class="modal-description">
                    Enter your email and password to access your Dev Limited account.
                </p>

                <form id="loginForm" novalidate>
                    <div class="form-field">
                        <label for="loginEmail">Email</label>
                        <input type="email" id="loginEmail" name="loginEmail" autocomplete="email" required aria-describedby="loginEmailError">
                        <span class="error-message" id="loginEmailError" aria-live="polite"></span>
                    </div>

                    <div class="form-field">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="loginPassword" autocomplete="current-password" required aria-describedby="loginPasswordError">
                        <span class="error-message" id="loginPasswordError" aria-live="polite"></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Sign In</button>
                </form>

                <p class="switch-text">
                    Not registered yet?
                    <button class="link-button" id="switchToSignUp" type="button">
                       Click here
                    </button>
                </p>
            </div>


            <div id="signUpForm" hidden>
                <h2>Sign Up</h2>
                <p class="modal-description">
                    Create your Dev Limited account to access our digital solutions platform.
                </p>

                <form id="registerForm" novalidate>
                    <div class="form-field">
                        <label for="registerName">Full Name</label>
                        <input type="text" id="registerName" name="registerName" autocomplete="name" required aria-describedby="registerNameError">
                        <span class="error-message" id="registerNameError" aria-live="polite"></span>
                    </div>

                    <div class="form-field">
                        <label for="registerEmail">Email</label>
                        <input type="email" id="registerEmail" name="registerEmail" autocomplete="email" required aria-describedby="registerEmailError">
                        <span class="error-message" id="registerEmailError" aria-live="polite"></span>
                    </div>

                    <div class="form-field">
                        <label for="registerPassword">Password</label>
                        <input type="password" id="registerPassword" name="registerPassword" autocomplete="new-password" required minlength="6" aria-describedby="registerPasswordError">
                        <span class="error-message" id="registerPasswordError" aria-live="polite"></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Create Account</button>
                </form>

                <p class="switch-text">
                    Already have an account?
                    <button class="link-button" id="switchToSignIn" type="button">
                        Sign In
                    </button>
                </p>
            </div>
        </div>
    </div>


   <!-- Chatbot Toggle -->
<button
    class="chatbot-toggle"
    id="chatbotToggle"
    type="button"
    aria-label="Open chat assistant"
    aria-expanded="false"
    aria-controls="chatbotWindow"
>
    <span class="chatbot-toggle-icon">💬</span>
</button>

<!-- Chatbot Window -->
<section
    class="chatbot-window"
    id="chatbotWindow"
    role="dialog"
    aria-modal="false"
    aria-labelledby="chatbotTitle"
    aria-hidden="true"
    hidden
>
    <div class="chatbot-header">
        <div class="chatbot-brand">
            <div class="chatbot-avatar">DL</div>
            <div>
                <h2 id="chatbotTitle">Dev Limited Assistant</h2>
                <p class="chatbot-status">
                    <span class="status-dot"></span>
                    <span id="chatbotModeLabel">Assistant mode</span>
                </p>
            </div>
        </div>

        <div class="chatbot-header-actions">
            <button class="chatbot-icon-btn" id="switchLiveChat" type="button" aria-label="Switch to live operator">
                Live
            </button>
            <button class="chatbot-icon-btn" id="chatbotMinimize" type="button" aria-label="Minimize chat">
                –
            </button>
            <button class="chatbot-icon-btn" id="chatbotClose" type="button" aria-label="Close chat">
                ×
            </button>
        </div>
    </div>

    <div class="chatbot-body" id="chatbotMessages" aria-live="polite" aria-label="Chat messages">
        <div class="chat-message bot">
            <div class="message-bubble">
                Hello! I’m the Dev Limited assistant. I can help you with pricing, features, CRM, dashboards, admin panels and project planning.
            </div>
        </div>
    </div>

    <div class="chatbot-quick-actions" id="chatbotQuickActions">
        <button type="button" class="quick-action" data-message="Website pricing">Website pricing</button>
        <button type="button" class="quick-action" data-message="Dashboard features">Dashboard features</button>
        <button type="button" class="quick-action" data-message="CRM options">CRM options</button>
        <button type="button" class="quick-action" data-message="Project timeline">Project timeline</button>
        <button type="button" class="quick-action" data-message="Book consultation">Book consultation</button>

        <button type="button" class="quick-action" data-message="Create project request">Create project request</button>
        <button type="button" class="quick-action" data-message="Talk to manager">Talk to manager</button>
        <button type="button" class="quick-action" data-message="Start website quiz">Start website quiz</button>
    </div>

    <form class="chatbot-form" id="chatbotForm" novalidate>
        <label for="chatbotInput" class="visually-hidden">Type your message</label>
        <input
            type="text"
            id="chatbotInput"
            class="chatbot-input"
            placeholder="Ask about pricing, features, CRM, dashboard..."
            autocomplete="off"
            maxlength="500"
            required
        />
        <button class="chatbot-send" id="chatbotSend" type="submit">Send</button>
    </form>
</section>


    <!-- Scripts -->
    <script>
        window.BASE_URL = "<?php echo BASE_URL; ?>";
    </script>
    <script src="<?php echo BASE_URL; ?>/script.js?v=2"></script>

    <script>
        document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const response = await fetch('<?php echo BASE_URL; ?>/api/save_contact.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        alert(result.message);

        if (result.success) {
            this.reset();
        }
        });
    </script>

</body>

</html>