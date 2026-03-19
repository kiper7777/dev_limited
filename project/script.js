// Mobile navigation toggle
const navToggle = document.querySelector('.nav-toggle');
const navLinks = document.querySelector('.nav-links');

if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
        const isOpen = navLinks.classList.toggle('open');
        navToggle.classList.toggle('open');
        navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    navLinks.addEventListener('click', (e) => {
        const target = e.target;
        if (target.tagName.toLowerCase() === 'a') {
            navLinks.classList.remove('open');
            navToggle.classList.remove('open');
            navToggle.setAttribute('aria-expanded', 'false');
        }
    });
}

// Smooth scroll for internal links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
        const targetId = this.getAttribute('href');
        const targetEl = document.querySelector(targetId);

        if (targetEl) {
            e.preventDefault();
            const headerOffset = 70;
            const rect = targetEl.getBoundingClientRect();
            const offsetTop = rect.top + window.scrollY - headerOffset;

            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth',
            });
        }
    });
});

// Header shadow on scroll
const header = document.querySelector('.site-header');

window.addEventListener('scroll', () => {
    if (!header) return;

    if (window.scrollY > 12) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Dynamic year in footer
const yearSpan = document.getElementById('year');
if (yearSpan) {
    yearSpan.textContent = new Date().getFullYear();
}

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("authModal");
    const overlay = document.getElementById("authOverlay");
    const openBtn = document.getElementById("openSignIn");
    const closeBtn = document.getElementById("closeModal");

    const signInFormBlock = document.getElementById("signInForm");
    const signUpFormBlock = document.getElementById("signUpForm");

    const switchToSignUp = document.getElementById("switchToSignUp");
    const switchToSignIn = document.getElementById("switchToSignIn");

    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");

    const authMessage = document.getElementById("authMessage");

    const loginEmail = document.getElementById("loginEmail");
    const loginPassword = document.getElementById("loginPassword");

    const registerName = document.getElementById("registerName");
    const registerEmail = document.getElementById("registerEmail");
    const registerPassword = document.getElementById("registerPassword");

    let lastFocusedElement = null;

    function showGlobalMessage(message, type = "success") {
        if (!authMessage) return;

        authMessage.textContent = message;
        authMessage.className = "auth-message show " + type;

        setTimeout(() => {
            authMessage.className = "auth-message";
            authMessage.textContent = "";
        }, 4000);
    }

    function showError(input, message) {
        if (!input) return;
        const error = document.getElementById(input.id + "Error");
        if (error) error.textContent = message;
        input.setAttribute("aria-invalid", "true");
    }

    function clearError(input) {
        if (!input) return;
        const error = document.getElementById(input.id + "Error");
        if (error) error.textContent = "";
        input.removeAttribute("aria-invalid");
    }

    function clearAllErrors() {
        [loginEmail, loginPassword, registerName, registerEmail, registerPassword].forEach((input) => {
            if (input) clearError(input);
        });
    }

    function openModal() {
        if (!modal || !overlay) return;

        lastFocusedElement = document.activeElement;
        modal.classList.add("open");
        overlay.classList.add("open");
        modal.setAttribute("aria-hidden", "false");
        overlay.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";

        if (loginEmail) loginEmail.focus();
    }

    function closeModal() {
        if (!modal || !overlay) return;

        modal.classList.remove("open");
        overlay.classList.remove("open");
        modal.setAttribute("aria-hidden", "true");
        overlay.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";

        clearAllErrors();

        if (lastFocusedElement) {
            lastFocusedElement.focus();
        }
    }

    function switchToSignupForm() {
        if (!signInFormBlock || !signUpFormBlock) return;

        signInFormBlock.hidden = true;
        signUpFormBlock.hidden = false;
        clearAllErrors();

        if (registerName) registerName.focus();
    }

    function switchToSigninForm() {
        if (!signInFormBlock || !signUpFormBlock) return;

        signUpFormBlock.hidden = true;
        signInFormBlock.hidden = false;
        clearAllErrors();

        if (loginEmail) loginEmail.focus();
    }

    function validateLoginForm() {
        let valid = true;

        if (!loginEmail.value.trim()) {
            showError(loginEmail, "Email is required.");
            valid = false;
        } else {
            clearError(loginEmail);
        }

        if (!loginPassword.value.trim()) {
            showError(loginPassword, "Password is required.");
            valid = false;
        } else {
            clearError(loginPassword);
        }

        return valid;
    }

    function validateRegisterForm() {
        let valid = true;

        if (!registerName.value.trim()) {
            showError(registerName, "Full name is required.");
            valid = false;
        } else if (registerName.value.trim().length < 2) {
            showError(registerName, "Name must be at least 2 characters.");
            valid = false;
        } else {
            clearError(registerName);
        }

        if (!registerEmail.value.trim()) {
            showError(registerEmail, "Email is required.");
            valid = false;
        } else {
            clearError(registerEmail);
        }

        if (!registerPassword.value.trim()) {
            showError(registerPassword, "Password is required.");
            valid = false;
        } else if (registerPassword.value.length < 6) {
            showError(registerPassword, "Password must be at least 6 characters.");
            valid = false;
        } else {
            clearError(registerPassword);
        }

        return valid;
    }

    async function handleRegister(e) {
        e.preventDefault();

        if (!validateRegisterForm()) return;

        const formData = new FormData();
        formData.append("name", registerName.value.trim());
        formData.append("email", registerEmail.value.trim());
        formData.append("password", registerPassword.value);

        try {
            const response = await fetch("register.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showGlobalMessage(result.message, "success");
                registerForm.reset();
                switchToSigninForm();
            } else {
                showGlobalMessage(result.message, "error");
            }
        } catch (error) {
            showGlobalMessage("Registration failed. Please check your PHP server and database.", "error");
        }
    }

    async function handleLogin(e) {
        e.preventDefault();

        if (!validateLoginForm()) return;

        const formData = new FormData();
        formData.append("email", loginEmail.value.trim());
        formData.append("password", loginPassword.value);

        try {
            const response = await fetch("login.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showGlobalMessage(result.message, "success");
                loginForm.reset();

                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                showGlobalMessage(result.message, "error");
            }
        } catch (error) {
            showGlobalMessage("Login failed. Please check your PHP server and database.", "error");
        }
    }

    function trapFocus(e) {
        if (!modal || !modal.classList.contains("open")) return;
        if (e.key !== "Tab") return;

        const focusable = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );

        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (e.shiftKey) {
            if (document.activeElement === first) {
                e.preventDefault();
                last.focus();
            }
        } else if (document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    }

    if (openBtn) openBtn.addEventListener("click", openModal);
    if (closeBtn) closeBtn.addEventListener("click", closeModal);
    if (overlay) overlay.addEventListener("click", closeModal);
    if (switchToSignUp) switchToSignUp.addEventListener("click", switchToSignupForm);
    if (switchToSignIn) switchToSignIn.addEventListener("click", switchToSigninForm);
    if (registerForm) registerForm.addEventListener("submit", handleRegister);
    if (loginForm) loginForm.addEventListener("submit", handleLogin);

    document.addEventListener("keydown", (e) => {
        if (!modal) return;
        if (e.key === "Escape" && modal.classList.contains("open")) {
            closeModal();
        }
    });

    if (modal) {
        modal.addEventListener("keydown", trapFocus);
    }
});



document.addEventListener("DOMContentLoaded", () => {
    const chatbotToggle = document.getElementById("chatbotToggle");
    const chatbotWindow = document.getElementById("chatbotWindow");
    const chatbotClose = document.getElementById("chatbotClose");
    const chatbotMinimize = document.getElementById("chatbotMinimize");
    const chatbotMessages = document.getElementById("chatbotMessages");
    const chatbotForm = document.getElementById("chatbotForm");
    const chatbotInput = document.getElementById("chatbotInput");
    const quickActions = document.querySelectorAll(".quick-action");

    if (!chatbotToggle || !chatbotWindow || !chatbotMessages || !chatbotForm || !chatbotInput) {
        return;
    }

    let chatbotOpenedOnce = false;

    function openChatbot() {
        chatbotWindow.hidden = false;
        chatbotToggle.setAttribute("aria-expanded", "true");
        setTimeout(() => chatbotInput.focus(), 50);

        if (!chatbotOpenedOnce) {
            chatbotOpenedOnce = true;
            setTimeout(() => {
                addBotMessage("You can ask me about website development, client dashboards, CRM features, SEO setup, booking systems, admin panels, eCommerce, or project timelines.");
            }, 600);
        }
    }

    function closeChatbot() {
        chatbotWindow.hidden = true;
        chatbotToggle.setAttribute("aria-expanded", "false");
        chatbotToggle.focus();
    }

    chatbotToggle.addEventListener("click", () => {
        if (chatbotWindow.hidden) {
            openChatbot();
        } else {
            closeChatbot();
        }
    });

    chatbotClose.addEventListener("click", closeChatbot);
    chatbotMinimize.addEventListener("click", closeChatbot);

    function addMessage(text, type = "bot") {
        const wrapper = document.createElement("div");
        wrapper.className = `chat-message ${type}`;

        const bubble = document.createElement("div");
        bubble.className = "message-bubble";
        bubble.innerHTML = text;

        wrapper.appendChild(bubble);
        chatbotMessages.appendChild(wrapper);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function addUserMessage(text) {
        addMessage(escapeHtml(text), "user");
    }

    function addBotMessage(text) {
        addMessage(text, "bot");
    }

    function addTypingIndicator() {
        const wrapper = document.createElement("div");
        wrapper.className = "chat-message bot";
        wrapper.id = "typingIndicator";

        const bubble = document.createElement("div");
        bubble.className = "message-bubble";
        bubble.innerHTML = `
            <div class="chatbot-typing" aria-label="Assistant is typing">
                <span></span><span></span><span></span>
            </div>
        `;

        wrapper.appendChild(bubble);
        chatbotMessages.appendChild(wrapper);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function removeTypingIndicator() {
        const typing = document.getElementById("typingIndicator");
        if (typing) typing.remove();
    }

    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    function getBotReply(message) {
        const text = message.toLowerCase().trim();

        if (text.includes("hello") || text.includes("hi") || text.includes("hey")) {
            return "Hello and welcome to <strong>Dev Limited</strong>. How can I help with your website or digital product today?";
        }

        if (text.includes("price") || text.includes("pricing") || text.includes("cost") || text.includes("budget")) {
            return `
                Pricing depends on project scope. Typical requests include:
                <ul class="bot-list">
                    <li>Landing page website</li>
                    <li>Corporate multi-page website</li>
                    <li>Client dashboard / portal</li>
                    <li>CRM-integrated business platform</li>
                    <li>eCommerce website</li>
                </ul>
                You can submit your requirements in your dashboard and select exact features for a tailored estimate.
            `;
        }

        if (text.includes("dashboard") || text.includes("client portal")) {
            return `
                We can build a custom dashboard with:
                <ul class="bot-list">
                    <li>user authentication</li>
                    <li>profile management</li>
                    <li>project request forms</li>
                    <li>quotes and invoices</li>
                    <li>file uploads</li>
                    <li>status tracking</li>
                    <li>CRM messaging</li>
                </ul>
            `;
        }

        if (text.includes("crm")) {
            return `
                Our CRM features can include:
                <ul class="bot-list">
                    <li>lead management</li>
                    <li>project pipeline tracking</li>
                    <li>client notes</li>
                    <li>internal comments</li>
                    <li>message history</li>
                    <li>quote management</li>
                </ul>
            `;
        }

        if (text.includes("timeline") || text.includes("deadline") || text.includes("how long")) {
            return "Project timelines usually depend on complexity. A landing page may take 1–2 weeks, while a custom dashboard or CRM platform can take several weeks or more depending on features and integrations.";
        }

        if (text.includes("seo")) {
            return "Yes, we can include SEO-friendly structure, metadata, semantic HTML, speed optimisation, responsive design, and accessibility improvements as part of the project.";
        }

        if (text.includes("booking")) {
            return "Yes, we can build booking systems with availability selection, admin management, email notifications, payment integration, and client dashboards.";
        }

        if (text.includes("ecommerce") || text.includes("shop") || text.includes("store")) {
            return "We can develop eCommerce websites with product management, categories, checkout, customer accounts, payment integration, shipping logic, and admin reporting.";
        }

        if (text.includes("admin") || text.includes("admin panel")) {
            return "Yes, we can create a secure admin panel for content management, users, project requests, CRM data, leads, quotes, and analytics.";
        }

        if (text.includes("features") || text.includes("functions") || text.includes("options")) {
            return `
                Popular website options include:
                <ul class="bot-list">
                    <li>authentication system</li>
                    <li>user dashboard</li>
                    <li>admin panel</li>
                    <li>CRM integration</li>
                    <li>contact forms</li>
                    <li>live chat</li>
                    <li>blog / CMS</li>
                    <li>analytics</li>
                    <li>payment integration</li>
                    <li>booking functionality</li>
                    <li>file uploads</li>
                    <li>multi-language support</li>
                </ul>
            `;
        }

        if (text.includes("consultation") || text.includes("call") || text.includes("meeting")) {
            return "You can book a consultation through the contact section or submit a project request in your client dashboard with your business goals, website type, and preferred features.";
        }

        if (text.includes("contact")) {
            return "You can reach Dev Limited through the contact section on the website, submit a request form, or use your dashboard after login to send a direct project enquiry.";
        }

        if (text.includes("login") || text.includes("sign in") || text.includes("register")) {
            return "You can create an account through Sign Up, verify your email, and then access your dashboard to submit website requests, choose features, upload files, and communicate with the team.";
        }

        if (text.includes("webflow") || text.includes("design") || text.includes("ui") || text.includes("saas")) {
            return "Our design approach combines premium SaaS-style UI, strong visual hierarchy, responsive layouts, dark modern aesthetics, smooth animations, and conversion-focused user flows.";
        }

        if (text.includes("thank")) {
            return "You’re welcome. I’m here if you want help choosing the right feature set for your website.";
        }

        return `
            I can help with:
            <ul class="bot-list">
                <li>website pricing</li>
                <li>dashboard features</li>
                <li>CRM options</li>
                <li>project timelines</li>
                <li>admin panels</li>
                <li>SEO and integrations</li>
            </ul>
            Try asking: <em>“What dashboard features can you build?”</em>
        `;
    }

    function handleBotResponse(message) {
        addTypingIndicator();

        setTimeout(() => {
            removeTypingIndicator();
            const reply = getBotReply(message);
            addBotMessage(reply);
        }, 650);
    }

    chatbotForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const message = chatbotInput.value.trim();
        if (!message) return;

        addUserMessage(message);
        chatbotInput.value = "";
        handleBotResponse(message);
    });

    quickActions.forEach((button) => {
        button.addEventListener("click", () => {
            const text = button.textContent.trim();
            addUserMessage(text);
            handleBotResponse(text);
        });
    });
});