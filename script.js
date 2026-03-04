// Mobile navigation toggle
const navToggle = document.querySelector('.nav-toggle');
const navLinks = document.querySelector('.nav-links');

if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
        navToggle.classList.toggle('open');
        navLinks.classList.toggle('open');
    });

    // Close nav on link click (mobile)
    navLinks.addEventListener('click', (e) => {
        if (e.target.tagName.toLowerCase() === 'a') {
            navToggle.classList.remove('open');
            navLinks.classList.remove('open');
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

  const signInForm = document.getElementById("signInForm");
  const signUpForm = document.getElementById("signUpForm");

  const switchToSignUp = document.getElementById("switchToSignUp");
  const switchToSignIn = document.getElementById("switchToSignIn");

  let lastFocusedElement;

  function openModal() {
    lastFocusedElement = document.activeElement;
    modal.classList.add("open");
    overlay.classList.add("open");
    document.body.style.overflow = "hidden";
    document.getElementById("loginEmail").focus();
  }

  function closeModal() {
    modal.classList.remove("open");
    overlay.classList.remove("open");
    document.body.style.overflow = "";
    if (lastFocusedElement) lastFocusedElement.focus();
  }

  if (openBtn) openBtn.addEventListener("click", openModal);
  if (closeBtn) closeBtn.addEventListener("click", closeModal);
  if (overlay) overlay.addEventListener("click", closeModal);

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && modal.classList.contains("open")) {
      closeModal();
    }
  });

  if (switchToSignUp) {
    switchToSignUp.addEventListener("click", function () {
      signInForm.classList.add("hidden");
      signUpForm.classList.remove("hidden");
      document.getElementById("registerName").focus();
    });
  }

  if (switchToSignIn) {
    switchToSignIn.addEventListener("click", function () {
      signUpForm.classList.add("hidden");
      signInForm.classList.remove("hidden");
      document.getElementById("loginEmail").focus();
    });
  }
});
