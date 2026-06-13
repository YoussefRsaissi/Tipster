<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TipsterAi — Chytré tipy</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    /* Sekce layout */
    .section{max-width:1100px;margin:0 auto;padding:60px 20px}
    .section h2{text-align:center;margin-bottom:40px;font-size:32px;font-weight:700}

    /* Steps */
    .steps{display:flex;gap:20px;justify-content:space-around;flex-wrap:wrap}
    .step{flex:1;min-width:250px;background:#fff;padding:20px;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.1);text-align:center;transition:.3s}
    .step:hover{transform:translateY(-5px)}
    .step h3{margin-bottom:8px;font-weight:700}
    .step p{color:#374151}

    /* Pricing */
    .pricing{display:flex;gap:20px;justify-content:center;flex-wrap:wrap}
    .plan{flex:1;min-width:280px;background:#fff;padding:30px;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.1);text-align:center;transition:.3s}
    .plan:hover{transform:translateY(-5px)}
    .plan h3{margin-bottom:10px}
    .plan p.price{font-size:28px;font-weight:700;margin:10px 0;color:#2563eb}
    .plan button{padding:12px 24px;background:#3b82f6;color:#fff;border:none;border-radius:10px;cursor:pointer;transition:.3s}
    .plan button:hover{background:#2563eb}
    .free{color:#16a34a;font-weight:600;margin-bottom:8px}

    /* Testimonials */
    .testimonials{display:flex;gap:20px;justify-content:center;flex-wrap:wrap}
    .testimonial{flex:1;min-width:280px;background:#fff;padding:20px;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.1);font-style:italic}

    /* FAQ */
    .faq-item{background:#fff;margin-bottom:10px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.08);overflow:hidden}
    .faq-question{padding:15px 20px;cursor:pointer;font-weight:600;display:flex;justify-content:space-between;align-items:center}
    .faq-question:hover{background:#f3f4f6}
    .faq-answer{display:none;padding:0 20px 15px 20px;color:#374151}
    .faq-item.active .faq-answer{display:block}

    /* Footer */
    footer{margin-top:40px;padding:30px;background:#1f2937;color:#fff;text-align:center}
    footer a{color:#93c5fd;text-decoration:none;margin:0 10px}

    /* Modal */
    .modal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;z-index:2000}
    .modal.show{display:flex}
    .modal .overlay{position:absolute;inset:0;background:rgba(0,0,0,.45)}
    .modal .dialog{
      position:relative;z-index:10;background:#fff;width:100%;max-width:420px;
      border-radius:16px;box-shadow:0 30px 80px rgba(2,6,23,.25);padding:22px
    }
    .modal .dialog h3{margin:0 0 12px;font-size:22px}
    .modal .close{position:absolute;right:10px;top:10px;border:none;background:transparent;font-size:22px;cursor:pointer}
    .form-group{margin-bottom:12px}
    .form-group input, .form-group select{
      width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:10px;font-family:'Poppins',sans-serif
    }
    .btn-primary{
      width:100%;padding:12px;background:#3b82f6;color:#fff;border:none;border-radius:10px;
      font-weight:700;cursor:pointer;transition:.2s
    }
    .btn-primary:hover{background:#2563eb}
    .muted{font-size:13px;color:#6b7280;text-align:center;margin-top:8px}
    .modal .switch{text-align:center;margin-top:10px}
    .link{color:#3b82f6;text-decoration:none}
  </style>
</head>
<body>
<header>
  <div class="container nav">
    <div class="logo">TipsterAi</div>
    <nav>
      <ul>
        <li><a href="#" onclick="openPopup('login')">Login</a></li>
        <li><a href="#" onclick="openPopup('register')">Registrace</a></li>
        <li><a href="#pricing">Ceník</a></li>
      </ul>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="hero-inner">
    <h1>Chytré tipy<br>v reálném čase</h1>
    <a class="cta" href="#" onclick="openPopup('register')">Začni zdarma</a>
  </div>
</section>

<!-- Jak to funguje -->
<section class="section">
  <h2>Jak to funguje?</h2>
  <div class="steps">
    <div class="step">
      <h3>1️⃣ Registrace</h3>
      <p>Vytvoř si účet během minuty a získej 3 dny zdarma.</p>
    </div>
    <div class="step">
      <h3>2️⃣ Sleduj tipy</h3>
      <p>Okamžitý přístup k AI tipům a analýzám.</p>
    </div>
    <div class="step">
      <h3>3️⃣ Vyhrávej</h3>
      <p>Sleduj úspěšnost a využívej nejlepší doporučení.</p>
    </div>
  </div>
</section>

<!-- Ceník -->
<section id="pricing" class="section">
  <h2>Ceník</h2>
  <div class="pricing">
    <div class="plan">
      <h3>Start</h3>
      <p class="price">149 Kč / měsíc</p>
      <p>15 tipů měsíčně<br>Základní statistiky</p>
      <p class="free">3 dny zdarma</p>
      <button onclick="openPopup('register')">Začít zdarma</button>
    </div>
    <div class="plan" style="border:2px solid #3b82f6;">
      <h3>Premium</h3>
      <p class="price">399 Kč / měsíc</p>
      <p>Neomezeně tipů<br>Pokročilé statistiky<br>Doručení tipů (Telegram / e-mail)</p>
      <p class="free">3 dny zdarma</p>
      <button onclick="openPopup('register')">Začít zdarma</button>
    </div>
    <div class="plan">
      <h3>Roční Premium</h3>
      <p class="price">3990 Kč / rok</p>
      <p>Neomezeně tipů<br>Sleva ≈ 2 měsíce zdarma<br>Prioritní podpora</p>
      <p class="free">3 dny zdarma</p>
      <button onclick="openPopup('register')">Začít zdarma</button>
    </div>
  </div>
</section>

<!-- Reference -->
<section class="section">
  <h2>Co říkají naši uživatelé</h2>
  <div class="testimonials">
    <div class="testimonial">
      <p>"tipsterAi mi pomohl trefit tiket hned první týden!"</p>
      <p><strong>Jarda K., Praha</strong></p>
    </div>
    <div class="testimonial">
      <p>"Nejlepší AI tipy, co jsem kdy zkusil. Doporučuju."</p>
      <p><strong>Lukáš H., Brno</strong></p>
    </div>
    <div class="testimonial">
      <p>"Jednoduché použití a fakt to funguje."</p>
      <p><strong>Martin S., Ostrava</strong></p>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section faq">
  <h2>Často kladené dotazy</h2>
  <div class="faq-item">
    <div class="faq-question">Je služba legální? <span>+</span></div>
    <div class="faq-answer">Ano ✅ tipy poskytujeme jen jako doporučení. Sázení je vždy na vlastní odpovědnost.</div>
  </div>
  <div class="faq-item">
    <div class="faq-question">Jak funguje zkušební doba? <span>+</span></div>
    <div class="faq-answer">Po registraci získáš automaticky 3 dny plného přístupu zdarma bez závazků.</div>
  </div>
  <div class="faq-item">
    <div class="faq-question">Můžu kdykoliv zrušit členství? <span>+</span></div>
    <div class="faq-answer">Ano, své členství můžeš kdykoliv zrušit v nastavení. Žádné skryté poplatky.</div>
  </div>
  <div class="faq-item">
    <div class="faq-question">Co když nebudu spokojen? <span>+</span></div>
    <div class="faq-answer">Naším cílem je spokojenost. Pokud nebudeš spokojen, můžeš přestat používat kdykoliv – žádný problém.</div>
  </div>
</section>

<footer>
  <p>&copy; 2025 tipsterAi. Všechna práva vyhrazena.</p>
  <p><a href="about.php">O nás</a> | <a href="terms.php">Podmínky</a> | <a href="contact.php">Kontakt</a></p>
</footer>

<!-- ========== POPUP: LOGIN ========== -->
<div id="modal-login" class="modal" aria-hidden="true">
  <div class="overlay" onclick="closePopup()"></div>
  <div class="dialog">
    <button class="close" onclick="closePopup()" aria-label="Zavřít">×</button>
    <h3>Přihlášení</h3>
    <form method="POST" action="login.php">
      <div class="form-group">
        <input type="text" name="username" placeholder="Uživatelské jméno" required>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Heslo" required>
      </div>
      <button type="submit" class="btn-primary">Přihlásit se</button>
      <p class="muted"><a class="link" href="forgot.php">Zapomněli jste heslo?</a></p>
      <p class="switch">Nemáte účet? <a href="#" class="link" onclick="switchPopup('register')">Registrace</a></p>
    </form>
  </div>
</div>

<!-- ========== POPUP: REGISTRACE ========== -->
<div id="modal-register" class="modal" aria-hidden="true">
  <div class="overlay" onclick="closePopup()"></div>
  <div class="dialog">
    <button class="close" onclick="closePopup()" aria-label="Zavřít">×</button>
    <h3>Registrace</h3>
    <form id="register-form" method="POST" action="register.php">
      <div class="form-group">
        <input type="text" name="username" placeholder="Uživatelské jméno" required>
      </div>
      <div class="form-group">
        <input type="email" name="email" placeholder="E-mail" required>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Heslo" required>
      </div>
      <div class="form-group">
        <label for="plan">Vyberte plán:</label>
        <select name="plan" required>
          <option value="start">⭐ Start – 149 Kč/měsíc</option>
          <option value="premium">🚀 Premium – 399 Kč/měsíc</option>
          <option value="premium_year">👑 Premium Rok – 3990 Kč/rok</option>
        </select>
      </div>
      <div class="form-group">
        <label for="card-element">Platební karta:</label>
        <div id="card-element" style="padding:12px;border:1px solid #ccc;border-radius:8px;"></div>
        <div id="card-errors" role="alert" style="color:red;margin-top:8px;"></div>
      </div>
      <button type="submit" class="btn-primary">Vytvořit účet</button>
      <p class="muted">Registrací souhlasíte s <a class="link" href="#">podmínkami</a>.</p>
      <p class="switch">Máte účet? <a href="#" class="link" onclick="switchPopup('login')">Přihlásit se</a></p>
    </form>
  </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe("pk_test_51S7glB48JuMWRqF15jRCWA1ltP4L1KNRhyq64E94nu8RstkhrQbjXDWVOTgtl6cBZwvrRWA4ejOK8a1L3hrvybiQ00yWhPe3eJ");
const elements = stripe.elements();
const card = elements.create("card");
card.mount("#card-element");

const form = document.getElementById("register-form");
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const {paymentMethod, error} = await stripe.createPaymentMethod({type: "card", card: card});
  if (error) {
    document.getElementById("card-errors").textContent = error.message;
  } else {
    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "paymentMethod";
    hiddenInput.value = paymentMethod.id;
    form.appendChild(hiddenInput);
    form.submit();
  }
});
</script>

<script>
  // FAQ toggle
  document.querySelectorAll('.faq-question').forEach(q=>{
    q.addEventListener('click',()=>q.parentNode.classList.toggle('active'));
  });

  // Modal logic
  let currentModal=null;
  function openPopup(which){
    const id = which==='login' ? 'modal-login' : 'modal-register';
    closePopup();
    currentModal=document.getElementById(id);
    if(currentModal){
      currentModal.classList.add('show');
      document.body.style.overflow='hidden';
      currentModal.setAttribute('aria-hidden','false');
    }
  }
  function closePopup(){
    if(currentModal){
      currentModal.classList.remove('show');
      currentModal.setAttribute('aria-hidden','true');
      document.body.style.overflow='';
      currentModal=null;
    }
  }
  function switchPopup(which){ openPopup(which); }
  window.addEventListener('keydown',e=>{ if(e.key==='Escape'){ closePopup(); } });
</script>
</body>
</html>
