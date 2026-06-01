/**
 * Journey Karo — Main JavaScript
 * Handles navigation, scroll effects, forms, animations, FAQ, gallery, etc.
 */

'use strict';

/* =========================================================
   DOM READY
   ========================================================= */
document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initMobileMenu();
  initScrollReveal();
  initFAQ();
  initGallery();
  initForms();
  initCounterAnimation();
  initSmoothScroll();
  initTabs();
  setActiveNavLink();
  initDateInputs();
});

/* =========================================================
   HEADER — scroll effects
   ========================================================= */
function initHeader() {
  const header = document.querySelector('.site-header');
  if (!header) return;

  const onScroll = () => {
    if (window.scrollY > 60) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

/* =========================================================
   MOBILE MENU
   ========================================================= */
function initMobileMenu() {
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  const overlay    = document.getElementById('menu-overlay');

  if (!hamburger || !mobileMenu) return;

  const toggle = (open) => {
    const isOpen = open !== undefined ? open : !mobileMenu.classList.contains('open');
    hamburger.classList.toggle('open', isOpen);
    mobileMenu.classList.toggle('open', isOpen);
    document.body.style.overflow = isOpen ? 'hidden' : '';
    if (overlay) overlay.style.display = isOpen ? 'block' : 'none';
    hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  };

  hamburger.addEventListener('click', () => toggle());

  // Close on overlay click
  if (overlay) overlay.addEventListener('click', () => toggle(false));

  // Close on nav link click
  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => toggle(false));
  });

  // Close on ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') toggle(false);
  });
}

/* =========================================================
   ACTIVE NAV LINK
   ========================================================= */
function setActiveNavLink() {
  const path = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
    const href = link.getAttribute('href') || '';
    if (href && href !== '#' && path.includes(href.replace('.php','').replace('.html',''))) {
      link.classList.add('active');
    }
  });
}

/* =========================================================
   SMOOTH SCROLL
   ========================================================= */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const headerHeight = document.querySelector('.site-header')?.offsetHeight || 72;
        const top = target.getBoundingClientRect().top + window.scrollY - headerHeight - 16;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });
}

/* =========================================================
   SCROLL REVEAL (Intersection Observer)
   ========================================================= */
function initScrollReveal() {
  const elements = document.querySelectorAll('[data-reveal]');
  if (!elements.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        // Staggered delay based on data-delay attr
        const delay = parseInt(entry.target.dataset.delay || 0);
        setTimeout(() => {
          entry.target.classList.add('revealed');
        }, delay);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

  elements.forEach(el => observer.observe(el));
}

/* =========================================================
   FAQ ACCORDION
   ========================================================= */
function initFAQ() {
  document.querySelectorAll('.faq-item').forEach(item => {
    const btn = item.querySelector('.faq-question');
    if (!btn) return;

    btn.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');

      // Close all
      document.querySelectorAll('.faq-item.open').forEach(openItem => {
        openItem.classList.remove('open');
        openItem.querySelector('.faq-question')?.setAttribute('aria-expanded', 'false');
      });

      // Open clicked (if it was closed)
      if (!isOpen) {
        item.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });
}

/* =========================================================
   GALLERY & LIGHTBOX
   ========================================================= */
function initGallery() {
  const lightbox    = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightbox-img');
  const lightboxClose = document.getElementById('lightbox-close');

  if (!lightbox) return;

  document.querySelectorAll('.gallery-item').forEach(item => {
    item.addEventListener('click', () => {
      const src = item.querySelector('img')?.src;
      const alt = item.querySelector('img')?.alt || '';
      if (src) {
        lightboxImg.src = src;
        lightboxImg.alt = alt;
        lightbox.classList.add('open');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  const closeLightbox = () => {
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
  };

  lightboxClose?.addEventListener('click', closeLightbox);
  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) closeLightbox();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeLightbox();
  });
}

/* =========================================================
   COUNTER ANIMATION
   ========================================================= */
function initCounterAnimation() {
  const counters = document.querySelectorAll('[data-count]');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el     = entry.target;
        const target = parseInt(el.dataset.count);
        const suffix = el.dataset.suffix || '';
        const duration = 1800;
        const step = target / (duration / 16);
        let current = 0;

        const update = () => {
          current = Math.min(current + step, target);
          el.textContent = Math.floor(current).toLocaleString('en-IN') + suffix;
          if (current < target) requestAnimationFrame(update);
        };

        requestAnimationFrame(update);
        observer.unobserve(el);
      }
    });
  }, { threshold: 0.5 });

  counters.forEach(el => observer.observe(el));
}

/* =========================================================
   TABS
   ========================================================= */
function initTabs() {
  document.querySelectorAll('.tabs').forEach(tabContainer => {
    const buttons = tabContainer.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        const target = btn.dataset.target;

        // Deactivate all tabs in same group
        const group = btn.closest('[data-tab-group]');
        if (group) {
          group.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
          group.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
          group.querySelector(`#${target}`)?.classList.add('active');
        } else {
          buttons.forEach(b => b.classList.remove('active'));
          const panelContainer = tabContainer.closest('[data-tab-group]') || tabContainer.nextElementSibling;
          panelContainer?.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        }

        btn.classList.add('active');
      });
    });
  });
}

/* =========================================================
   DATE INPUTS — set min date to today
   ========================================================= */
function initDateInputs() {
  const today = new Date().toISOString().split('T')[0];
  document.querySelectorAll('input[type="date"]').forEach(input => {
    input.setAttribute('min', today);
  });
}

/* =========================================================
   FORMS — Inquiry, Contact, Custom Tour
   ========================================================= */
function initForms() {
  // General inquiry form
  const inquiryForm = document.getElementById('inquiry-form');
  if (inquiryForm) {
    inquiryForm.addEventListener('submit', handleInquirySubmit);
  }

  // Contact form
  const contactForm = document.getElementById('contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', handleContactSubmit);
  }

  // Custom tour planner form
  const tourForm = document.getElementById('custom-tour-form');
  if (tourForm) {
    tourForm.addEventListener('submit', handleCustomTourSubmit);
  }

  // Input format helpers
  initPhoneFormatting();
}

async function handleInquirySubmit(e) {
  e.preventDefault();
  const form = e.target;
  const btn  = form.querySelector('[type="submit"]');
  const originalText = btn.innerHTML;

  if (!validateForm(form)) return;

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner" style="width:18px;height:18px;border-width:2px;display:inline-block"></span> Sending…';

  const data = new FormData(form);

  try {
    const resp = await fetch('/api/submit-inquiry.php', { method: 'POST', body: data });
    const json = await resp.json();

    if (json.success) {
      showToast('🎉 Thank you! We\'ll contact you within 2 hours via WhatsApp & Email.', 'success');
      form.reset();
      // Auto-redirect to WhatsApp
      setTimeout(() => {
        const name = data.get('name') || 'Traveler';
        const dest = data.get('destination') || 'Gujarat';
        const waMsg = `Hello Journey Karo!%20My%20name%20is%20${encodeURIComponent(name)}%20and%20I%20am%20interested%20in%20${encodeURIComponent(dest)}%20tour.%20Please%20share%20details.`;
        window.open(`https://wa.me/919586605635?text=${waMsg}`, '_blank');
      }, 1500);
    } else {
      showToast(json.message || 'Something went wrong. Please call us directly.', 'error');
    }
  } catch {
    showToast('Network error. Please try again or WhatsApp us directly.', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
}

async function handleContactSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const btn  = form.querySelector('[type="submit"]');
  const originalText = btn.innerHTML;

  if (!validateForm(form)) return;

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner" style="width:18px;height:18px;border-width:2px;display:inline-block"></span> Sending…';

  const data = new FormData(form);

  try {
    const resp = await fetch('/api/submit-inquiry.php', { method: 'POST', body: data });
    const json = await resp.json();

    if (json.success) {
      showToast('Message sent! We\'ll get back to you shortly.', 'success');
      form.reset();
    } else {
      showToast(json.message || 'Failed to send message.', 'error');
    }
  } catch {
    showToast('Network error. Please email us at booking@journeykaro.com', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
}

async function handleCustomTourSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const btn  = form.querySelector('[type="submit"]');

  if (!validateForm(form)) return;

  btn.disabled = true;
  btn.textContent = 'Submitting…';

  const data = new FormData(form);

  try {
    const resp = await fetch('/api/submit-inquiry.php', { method: 'POST', body: data });
    const json = await resp.json();

    if (json.success) {
      showToast('Your custom tour request has been submitted! Our team will call you soon.', 'success');
      form.reset();
    } else {
      showToast(json.message || 'Failed to submit.', 'error');
    }
  } catch {
    showToast('Network error. Please WhatsApp us directly.', 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Submit Custom Tour Request';
  }
}

/* =========================================================
   FORM VALIDATION
   ========================================================= */
function validateForm(form) {
  let valid = true;

  // Clear previous errors
  form.querySelectorAll('.field-error').forEach(el => el.remove());
  form.querySelectorAll('.form-control.error').forEach(el => el.classList.remove('error'));

  form.querySelectorAll('[required]').forEach(field => {
    const value = field.value.trim();

    if (!value) {
      markFieldError(field, 'This field is required.');
      valid = false;
    } else if (field.type === 'email' && !isValidEmail(value)) {
      markFieldError(field, 'Please enter a valid email address.');
      valid = false;
    } else if (field.type === 'tel' && !isValidPhone(value)) {
      markFieldError(field, 'Please enter a valid 10-digit phone number.');
      valid = false;
    }
  });

  if (!valid) {
    form.querySelector('.form-control.error')?.focus();
  }

  return valid;
}

function markFieldError(field, message) {
  field.classList.add('error');
  field.style.borderColor = 'var(--color-error)';
  const err = document.createElement('span');
  err.className = 'field-error';
  err.style.cssText = 'color:var(--color-error);font-size:0.75rem;display:block;margin-top:4px;';
  err.textContent = message;
  field.parentNode.insertBefore(err, field.nextSibling);

  field.addEventListener('input', () => {
    field.classList.remove('error');
    field.style.borderColor = '';
    err.remove();
  }, { once: true });
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isValidPhone(phone) {
  return /^[6-9]\d{9}$/.test(phone.replace(/[\s\-+]/g, ''));
}

/* =========================================================
   PHONE FORMATTING
   ========================================================= */
function initPhoneFormatting() {
  document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', (e) => {
      let val = e.target.value.replace(/\D/g, '').slice(0, 10);
      e.target.value = val;
    });
  });
}

/* =========================================================
   TOAST NOTIFICATIONS
   ========================================================= */
function showToast(message, type = 'info', duration = 5000) {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };

  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `
    <span style="font-size:1.25rem;flex-shrink:0">${icons[type] || '📢'}</span>
    <div>
      <p style="font-size:0.875rem;font-weight:600;color:var(--color-text);margin:0">${message}</p>
    </div>
    <button onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--color-text-light);font-size:1.1rem;cursor:pointer;margin-left:auto;flex-shrink:0" aria-label="Close">×</button>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.style.animation = 'toast-in 0.3s ease reverse';
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

/* =========================================================
   WHATSAPP INQUIRY — Direct CTA
   ========================================================= */
function openWhatsApp(message = '') {
  const defaultMsg = message || 'Hello Journey Karo! I am interested in booking a Gujarat tour package. Please guide me.';
  const url = `https://wa.me/919586605635?text=${encodeURIComponent(defaultMsg)}`;
  window.open(url, '_blank', 'noopener,noreferrer');
}

/* =========================================================
   PACKAGE / DESTINATION QUICK INQUIRY
   ========================================================= */
function inquirePackage(packageName, price) {
  const form = document.getElementById('inquiry-form');
  if (form) {
    const destField = form.querySelector('[name="destination"]') || form.querySelector('[name="package"]');
    if (destField) {
      destField.value = packageName;
      form.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  // Also offer direct WhatsApp
  const msg = `Hi Journey Karo! I'm interested in the *${packageName}* package (₹${price ? price.toLocaleString('en-IN') : ''}). Please share more details.`;
  showQuickModal(packageName, price, msg);
}

function showQuickModal(packageName, price, waMsg) {
  const existing = document.getElementById('quick-modal');
  if (existing) existing.remove();

  const modal = document.createElement('div');
  modal.id = 'quick-modal';
  modal.style.cssText = `
    position:fixed;inset:0;background:rgba(0,0,0,0.65);z-index:9999;
    display:flex;align-items:center;justify-content:center;padding:1rem;
    backdrop-filter:blur(4px);animation:fadeIn 0.2s ease;
  `;

  modal.innerHTML = `
    <div style="background:white;border-radius:1.5rem;padding:2rem;max-width:480px;width:100%;box-shadow:0 25px 60px rgba(0,0,0,0.25);">
      <h3 style="font-size:1.2rem;font-weight:800;color:var(--color-primary);margin-bottom:0.5rem">🏖️ ${packageName}</h3>
      ${price ? `<p style="color:var(--color-gold);font-weight:700;font-size:1.1rem;margin-bottom:1rem">₹${price.toLocaleString('en-IN')} / person</p>` : ''}
      <p style="color:var(--color-text-muted);font-size:0.9rem;margin-bottom:1.5rem">Get instant details on WhatsApp or fill in the inquiry form below — our team will call you within 2 hours.</p>
      <div style="display:flex;gap:0.75rem;flex-wrap:wrap">
        <button onclick="window.open('https://wa.me/919586605635?text=${encodeURIComponent(waMsg)}','_blank');document.getElementById('quick-modal').remove()" style="flex:1;padding:0.75rem;background:#25D366;color:white;border:none;border-radius:0.75rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L3 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6z"/></svg>
          WhatsApp Now
        </button>
        <button onclick="document.getElementById('inquiry-form')?.scrollIntoView({behavior:'smooth',block:'center'});document.getElementById('quick-modal').remove()" style="flex:1;padding:0.75rem;background:var(--color-primary);color:white;border:none;border-radius:0.75rem;font-weight:700;cursor:pointer">
          Fill Inquiry Form
        </button>
      </div>
      <button onclick="document.getElementById('quick-modal').remove()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--color-text-muted)">×</button>
    </div>
  `;

  modal.style.position = 'fixed';
  modal.addEventListener('click', (e) => { if (e.target === modal) modal.remove(); });
  document.body.appendChild(modal);
}

/* =========================================================
   HERO SEARCH QUICK FILTER
   ========================================================= */
function quickSearch(destination) {
  const destInput = document.getElementById('destination-input') || document.querySelector('[name="destination"]');
  if (destInput) {
    destInput.value = destination;
    destInput.closest('form')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // Scroll to packages and highlight
  const packagesSection = document.getElementById('packages-section');
  if (packagesSection) {
    setTimeout(() => {
      packagesSection.scrollIntoView({ behavior: 'smooth' });
    }, 300);
  }
}

/* =========================================================
   BOOKING SUMMARY CALCULATOR
   ========================================================= */
function updateBookingSummary() {
  const pkgSelect = document.getElementById('booking-package');
  const guests    = parseInt(document.getElementById('booking-guests')?.value) || 1;
  const insurance = document.getElementById('booking-insurance')?.checked;

  if (!pkgSelect) return;

  const selectedOpt = pkgSelect.options[pkgSelect.selectedIndex];
  const pricePerPerson = parseInt(selectedOpt.dataset.price) || 0;
  const baseTotal = pricePerPerson * guests;
  const insuranceCost = insurance ? 999 * guests : 0;
  const taxRate = 0.05;
  const tax = Math.round((baseTotal + insuranceCost) * taxRate);
  const grandTotal = baseTotal + insuranceCost + tax;

  const fmt = (n) => '₹' + n.toLocaleString('en-IN');

  const el = (id) => document.getElementById(id);

  if (el('summary-guests-count')) el('summary-guests-count').textContent = guests;
  if (el('summary-base-price'))   el('summary-base-price').textContent   = fmt(baseTotal);
  if (el('summary-tax'))          el('summary-tax').textContent          = fmt(tax);
  if (el('summary-total'))        el('summary-total').textContent        = fmt(grandTotal);

  // Insurance row
  const insRow = el('summary-insurance-row');
  if (insRow) {
    insRow.style.display = insurance ? 'flex' : 'none';
    if (el('summary-insurance-price')) el('summary-insurance-price').textContent = fmt(insuranceCost);
    if (el('summary-insurance-count')) el('summary-insurance-count').textContent = guests;
  }
}

// Expose globally
window.updateBookingSummary = updateBookingSummary;
window.inquirePackage = inquirePackage;
window.quickSearch = quickSearch;
window.openWhatsApp = openWhatsApp;
window.showToast = showToast;

/* =========================================================
   COOKIE CONSENT (simple)
   ========================================================= */
(function initCookieBanner() {
  if (localStorage.getItem('jk_cookie_consent')) return;

  const banner = document.createElement('div');
  banner.id = 'cookie-banner';
  banner.style.cssText = `
    position:fixed;bottom:0;left:0;right:0;z-index:888;
    background:var(--color-primary-dark);color:rgba(255,255,255,0.85);
    padding:1rem 1.5rem;display:flex;align-items:center;
    justify-content:space-between;gap:1rem;flex-wrap:wrap;
    font-size:0.8rem;
  `;
  banner.innerHTML = `
    <p style="margin:0;flex:1">
      🍪 We use cookies to improve your experience. By using Journey Karo, you agree to our 
      <a href="privacy-policy.php" style="color:var(--color-gold-light);text-decoration:underline">Privacy Policy</a>.
    </p>
    <button onclick="localStorage.setItem('jk_cookie_consent','yes');document.getElementById('cookie-banner').remove()" 
      style="background:var(--color-gold);color:white;border:none;padding:0.5rem 1.25rem;border-radius:0.5rem;font-weight:700;cursor:pointer;white-space:nowrap">
      Accept & Close
    </button>
  `;
  document.body.appendChild(banner);
})();
