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

const openBtn = document.getElementById('openSignIn');
const modal = document.getElementById('authModal');
const overlay = document.getElementById('authOverlay');
const closeBtn = document.getElementById('closeModal');

const signInForm = document.getElementById('signInForm');
const signUpForm = document.getElementById('signUpForm');

const switchToSignUp = document.getElementById('switchToSignUp');
const switchToSignIn = document.getElementById('switchToSignIn');

let lastFocusedElement;

// Open modal
openBtn.addEventListener('click', () => {
  lastFocusedElement = document.activeElement;
  modal.hidden = false;
  overlay.hidden = false;
  document.getElementById('loginEmail').focus();
});

// Close modal
function closeModal() {
  modal.hidden = true;
  overlay.hidden = true;
  lastFocusedElement.focus();
}

closeBtn.addEventListener('click', closeModal);
overlay.addEventListener('click', closeModal);

// ESC close
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && !modal.hidden) {
    closeModal();
  }
});

// Switch forms
switchToSignUp.addEventListener('click', () => {
  signInForm.hidden = true;
  signUpForm.hidden = false;
  document.getElementById('registerName').focus();
});

switchToSignIn.addEventListener('click', () => {
  signUpForm.hidden = true;
  signInForm.hidden = false;
  document.getElementById('loginEmail').focus();
});

// Validation helper
function showError(input, message) {
  const error = document.getElementById(input.id + "Error");
  error.textContent = message;
  input.setAttribute("aria-invalid", "true");
}

function clearError(input) {
  const error = document.getElementById(input.id + "Error");
  error.textContent = "";
  input.removeAttribute("aria-invalid");
}

// LOGIN
document.getElementById('loginForm').addEventListener('submit', function(e){
  e.preventDefault();
  
  const email = loginEmail;
  const password = loginPassword;

  let valid = true;

  if (!email.value) {
    showError(email, "Email is required");
    valid = false;
  } else {
    clearError(email);
  }

  if (!password.value) {
    showError(password, "Password is required");
    valid = false;
  } else {
    clearError(password);
  }

  if (valid) {
    alert("Signed in successfully (demo)");
    closeModal();
  }
});

// REGISTER
document.getElementById('registerForm').addEventListener('submit', function(e){
  e.preventDefault();
  
  const name = registerName;
  const email = registerEmail;
  const password = registerPassword;

  let valid = true;

  if (!name.value) {
    showError(name, "Name is required");
    valid = false;
  } else {
    clearError(name);
  }

  if (!email.value) {
    showError(email, "Email is required");
    valid = false;
  } else {
    clearError(email);
  }

  if (password.value.length < 6) {
    showError(password, "Password must be at least 6 characters");
    valid = false;
  } else {
    clearError(password);
  }

  if (valid) {
    alert("Account created successfully (demo)");
    closeModal();
  }
});

