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


//chat bot

document.addEventListener("DOMContentLoaded", () => {
    const chatbotToggle = document.getElementById("chatbotToggle");
    const chatbotWindow = document.getElementById("chatbotWindow");
    const chatbotClose = document.getElementById("chatbotClose");
    const chatbotMinimize = document.getElementById("chatbotMinimize");
    const chatbotMessages = document.getElementById("chatbotMessages");
    const chatbotForm = document.getElementById("chatbotForm");
    const chatbotInput = document.getElementById("chatbotInput");
    const quickActions = document.querySelectorAll(".quick-action");
    const switchLiveChat = document.getElementById("switchLiveChat");
    const chatbotModeLabel = document.getElementById("chatbotModeLabel");

    if (!chatbotToggle || !chatbotWindow || !chatbotMessages || !chatbotForm || !chatbotInput) {
        return;
    }

    let chatbotOpenedOnce = false;
    let liveMode = false;
    let pollingInterval = null;
    let chatSessionId = localStorage.getItem("dev_chat_session_id") || "";
    const storageKey = "dev_limited_chat_history";

    //quiz
    let quizState = {
    active: false,
    step: 0,
    data: {
        website_type: "",
        budget_range: "",
        timeline: "",
        required_features: "",
        full_name: "",
        email: "",
        phone: "",
        company_name: "",
        message: ""
    }
};

const quizSteps = [
    { key: "website_type", question: "What type of website do you need? Example: landing page, business website, dashboard, eCommerce." },
    { key: "budget_range", question: "What is your budget range?" },
    { key: "timeline", question: "What is your preferred timeline?" },
    { key: "required_features", question: "What features do you need? Example: admin panel, CRM, payments, chat, booking." },
    { key: "full_name", question: "What is your full name?" },
    { key: "email", question: "What is your email address?" },
    { key: "phone", question: "What is your phone number?" },
    { key: "company_name", question: "What is your company name?" },
    { key: "message", question: "Anything else you'd like to add?" }
];

async function fetchFaqReply(message) {
    const response = await fetch("api/chatbot_reply.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message })
    });
    return response.json();
}

async function saveQuizLead() {
    const response = await fetch("api/create_lead_from_quiz.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(quizState.data)
    });

    return response.json();
}

function startQuiz() {
    quizState.active = true;
    quizState.step = 0;
    addBotMessage("Great. Let’s build your website brief together.");
    addBotMessage(quizSteps[0].question);
}

async function handleQuizAnswer(text) {
    const currentStep = quizSteps[quizState.step];
    quizState.data[currentStep.key] = text;

    quizState.step += 1;

    if (quizState.step < quizSteps.length) {
        addBotMessage(quizSteps[quizState.step].question);
        return;
    }



    if (quizState.active) {
    await handleQuizAnswer(message);
    return;
    }

    if (message.toLowerCase() === "start website quiz" || message.toLowerCase() === "create project request") {
        startQuiz();
        return;
    }

    if (message.toLowerCase() === "talk to manager") {
        if (!liveMode) {
            enableLiveMode();
            await ensureLiveSession();
            await pollLiveMessages();
        }
        addBotMessage("You are now connected to live operator mode.");
        return;
    }

    if (liveMode) {
        await sendLiveMessage(message);
        addBotMessage("Your message has been sent to a live operator.");
    } else {
        const result = await fetchFaqReply(message);
        addBotMessage(result.answer);
    }






    quizState.active = false;
    addBotMessage("Thank you. I’m saving your project lead now...");

    const result = await saveQuizLead();
    if (result.success) {
        addBotMessage("Your project request has been saved successfully. A manager can review it soon.");
    } else {
        addBotMessage("I couldn’t save your lead. Please try again later.");
    }
}



    function saveHistory() {
        localStorage.setItem(storageKey, chatbotMessages.innerHTML);
    }

    function loadHistory() {
        const history = localStorage.getItem(storageKey);
        if (history) {
            chatbotMessages.innerHTML = history;
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }
    }

    function openChatbot() {
        chatbotWindow.hidden = false;
        chatbotWindow.classList.remove("closing");
        requestAnimationFrame(() => {
            chatbotWindow.classList.add("open");
        });
        chatbotWindow.setAttribute("aria-hidden", "false");
        chatbotToggle.setAttribute("aria-expanded", "true");

        setTimeout(() => chatbotInput.focus(), 120);

        if (!chatbotOpenedOnce) {
            chatbotOpenedOnce = true;
            setTimeout(() => {
                addBotMessage("You can ask me about website development, client dashboards, CRM features, SEO setup, booking systems, admin panels, eCommerce, or project timelines.");
            }, 500);
        }
    }

    function closeChatbot() {
        chatbotWindow.classList.remove("open");
        chatbotWindow.classList.add("closing");
        chatbotWindow.setAttribute("aria-hidden", "true");
        chatbotToggle.setAttribute("aria-expanded", "false");

        setTimeout(() => {
            chatbotWindow.hidden = true;
            chatbotWindow.classList.remove("closing");
        }, 200);
    }

    function addMessage(text, type = "bot") {
        const wrapper = document.createElement("div");
        wrapper.className = `chat-message ${type}`;

        const bubble = document.createElement("div");
        bubble.className = "message-bubble";
        bubble.innerHTML = text;

        wrapper.appendChild(bubble);
        chatbotMessages.appendChild(wrapper);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        saveHistory();
    }

    function addUserMessage(text) {
        addMessage(escapeHtml(text), "user");
    }

    function addBotMessage(text) {
        addMessage(text, "bot");
    }

    function addOperatorMessage(text) {
        addMessage(text, "operator");
    }

    function addTypingIndicator() {
        removeTypingIndicator();

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
            return `Pricing depends on project scope. We can build landing pages, corporate websites, dashboards, CRM systems and eCommerce platforms. Submit your requirements in your dashboard for a tailored estimate.`;
        }

        if (text.includes("dashboard")) {
            return `We can build custom dashboards with user authentication, profile management, project request forms, file uploads, status tracking and CRM messaging.`;
        }

        if (text.includes("crm")) {
            return `CRM features can include lead management, project pipeline tracking, notes, message history, quote management and client updates.`;
        }

        if (text.includes("timeline") || text.includes("deadline")) {
            return `A landing page may take around 1–2 weeks, while a full dashboard or CRM platform can take several weeks depending on complexity.`;
        }

        if (text.includes("book consultation") || text.includes("consultation")) {
            return `You can book a consultation through the contact section or submit a project request in your dashboard with your goals and requested features.`;
        }

        return `I can help with website pricing, dashboard features, CRM options, timelines, SEO, admin panels and project planning.`;
        
    }

    function handleBotResponse(message) {
        addTypingIndicator();

        setTimeout(() => {
            removeTypingIndicator();
            addBotMessage(getBotReply(message));
        }, 650);
    }

    async function ensureLiveSession() {
        if (chatSessionId) return chatSessionId;

        try {
            const response = await fetch("chat_create_session.php", {
                method: "POST"
            });
            const result = await response.json();

            if (result.success) {
                chatSessionId = result.session_id;
                localStorage.setItem("dev_chat_session_id", chatSessionId);
            }
        } catch (error) {
            console.error(error);
        }

        return chatSessionId;
    }

    async function sendLiveMessage(message) {
        const sessionId = await ensureLiveSession();
        if (!sessionId) {
            addBotMessage("Live chat is currently unavailable. Please try again later.");
            return;
        }

        try {
            await fetch("chat_send_message.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    sender_type: "client",
                    message: message
                })
            });
        } catch (error) {
            console.error(error);
        }
    }

    async function pollLiveMessages() {
        if (!liveMode || !chatSessionId) return;

        try {
            const response = await fetch(`chat_fetch_messages.php?session_id=${encodeURIComponent(chatSessionId)}`);
            const result = await response.json();

            if (result.success && Array.isArray(result.messages)) {
                const existing = new Set(
                    Array.from(chatbotMessages.querySelectorAll("[data-message-id]")).map(el => el.dataset.messageId)
                );

                result.messages.forEach((msg) => {
                    if (existing.has(String(msg.id))) return;

                    const wrapper = document.createElement("div");
                    wrapper.className = `chat-message ${msg.sender_type === "operator" ? "operator" : "user"}`;
                    wrapper.dataset.messageId = msg.id;

                    const bubble = document.createElement("div");
                    bubble.className = "message-bubble";
                    bubble.textContent = msg.message;

                    wrapper.appendChild(bubble);
                    chatbotMessages.appendChild(wrapper);
                });

                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                saveHistory();
            }
        } catch (error) {
            console.error(error);
        }
    }

    function enableLiveMode() {
        liveMode = true;
        chatbotModeLabel.textContent = "Live operator mode";
        addBotMessage("You are now connected to live operator mode. Your messages will be sent to the Dev Limited admin panel.");

        if (!pollingInterval) {
            pollingInterval = setInterval(pollLiveMessages, 3000);
        }
    }

    function disableLiveMode() {
        liveMode = false;
        chatbotModeLabel.textContent = "Assistant mode";

        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    chatbotToggle.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (chatbotWindow.hidden) {
            openChatbot();
        } else {
            closeChatbot();
        }
    });

    if (chatbotClose) {
        chatbotClose.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            closeChatbot();
        });
    }

    if (chatbotMinimize) {
        chatbotMinimize.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            closeChatbot();
        });
    }

    if (switchLiveChat) {
        switchLiveChat.addEventListener("click", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (!liveMode) {
                enableLiveMode();
                await ensureLiveSession();
                await pollLiveMessages();
            } else {
                disableLiveMode();
                addBotMessage("Switched back to assistant mode.");
            }
        });
    }

    chatbotForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        e.stopPropagation();

        const message = chatbotInput.value.trim();
        if (!message) return;

        addUserMessage(message);
        chatbotInput.value = "";

        if (liveMode) {
            await sendLiveMessage(message);
            addBotMessage("Your message has been sent to a live operator.");
        } else {
            handleBotResponse(message);
        }
    });

    quickActions.forEach((button) => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const text = button.dataset.message || button.textContent.trim();
            addUserMessage(text);

            if (liveMode) {
                await sendLiveMessage(text);
                addBotMessage("Your message has been sent to a live operator.");
            } else {
                handleBotResponse(text);
            }
        });
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && !chatbotWindow.hidden) {
            closeChatbot();
        }
    });

    loadHistory();
});

