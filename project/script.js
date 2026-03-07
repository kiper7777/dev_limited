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