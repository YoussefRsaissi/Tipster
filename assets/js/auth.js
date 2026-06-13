// assets/js/auth.js
(function () {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

  const modals = {
    login: $('#modal-login'),
    register: $('#modal-register'),
    forgot: $('#modal-forgot')
  };

  function openModal(name) {
    Object.values(modals).forEach(m => m.classList.remove('is-open'));
    modals[name]?.classList.add('is-open');
  }
  function closeModals() {
    Object.values(modals).forEach(m => m.classList.remove('is-open'));
  }

  // Otevírače
  $$("[data-open]").forEach(a => {
    a.addEventListener('click', e => {
      e.preventDefault();
      const name = a.getAttribute('data-open');
      openModal(name);
    });
  });

  // Zavírání (X, backdrop)
  $$("[data-close]").forEach(el => el.addEventListener('click', closeModals));
  window.addEventListener('keydown', e => { if (e.key === 'Escape') closeModals(); });

  // ===== AJAX: Login =====
  const loginForm = $('#loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const msg = $('#login-msg');
      const btn = loginForm.querySelector('button');
      msg.className = 'form-msg'; msg.textContent = '';
      btn.disabled = true;

      try {
        const fd = new FormData(loginForm);
        const res = await fetch('auth_login.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        if (data.ok) {
          msg.classList.add('ok'); msg.textContent = 'Přihlašuji...';
          location.href = 'dashboard.php';
        } else {
          msg.classList.add('error'); msg.textContent = data.message || 'Chyba přihlášení';
        }
      } catch (err) {
        msg.classList.add('error'); msg.textContent = 'Server je nedostupný.';
      } finally {
        btn.disabled = false;
      }
    });
  }

  // ===== AJAX: Register =====
  const registerForm = $('#registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const msg = $('#register-msg');
      const btn = registerForm.querySelector('button');
      msg.className = 'form-msg'; msg.textContent = '';
      btn.disabled = true;

      try {
        const fd = new FormData(registerForm);
        const res = await fetch('auth_register.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        if (data.ok) {
          msg.classList.add('ok');
          msg.textContent = data.message || 'Registrace OK. Zkontrolujte email.';
        } else {
          msg.classList.add('error'); msg.textContent = data.message || 'Chyba registrace';
        }
      } catch (err) {
        msg.classList.add('error'); msg.textContent = 'Server je nedostupný.';
      } finally {
        btn.disabled = false;
      }
    });
  }

  // ===== AJAX: Forgot =====
  const forgotForm = $('#forgotForm');
  if (forgotForm) {
    forgotForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const msg = $('#forgot-msg');
      const btn = forgotForm.querySelector('button');
      msg.className = 'form-msg'; msg.textContent = '';
      btn.disabled = true;

      try {
        const fd = new FormData(forgotForm);
        const res = await fetch('auth_forgot.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        if (data.ok) {
          msg.classList.add('ok'); msg.textContent = data.message || 'Odkaz byl odeslán na email.';
        } else {
          msg.classList.add('error'); msg.textContent = data.message || 'Chyba odeslání';
        }
      } catch (err) {
        msg.classList.add('error'); msg.textContent = 'Server je nedostupný.';
      } finally {
        btn.disabled = false;
      }
    });
  }
})();
