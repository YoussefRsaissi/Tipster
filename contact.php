<!DOCTYPE html>
<html lang="cs" class="contact-page">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontakt — TipsterAi</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>
    .contact-page body{margin:0;font-family:'Poppins',sans-serif;background:#f9fafb;color:#111}
    .contact-page header{background:#6d28d9;color:#fff;padding:12px 0;position:fixed;top:0;left:0;right:0;z-index:1000}
    .contact-page .nav-wrap{max-width:1100px;margin:0 auto;padding:0 20px;display:flex;align-items:center;justify-content:space-between}
    .contact-page .logo{font-weight:700}
    .contact-page nav ul{display:flex;gap:20px;list-style:none;margin:0;padding:0}
    .contact-page nav a{color:#fff;text-decoration:none}
    .contact-page .burger{display:none;font-size:26px;cursor:pointer;color:#fff}
    @media(max-width:768px){
      .contact-page nav ul{display:none;flex-direction:column;position:absolute;top:56px;right:20px;background:#6d28d9;border-radius:0 0 10px 10px;padding:10px;width:220px}
      .contact-page nav ul.show{display:flex}
      .contact-page .burger{display:block}
    }
    .contact-page .hero{background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;text-align:center;padding:120px 20px 70px;margin-top:56px}
    .contact-page .hero h1{margin:0;font-size:34px;font-weight:800}
    .contact-page .section{padding:50px 20px;max-width:800px;margin:0 auto}
    .contact-page form{background:#fff;padding:24px;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,.1)}
    .contact-page input,.contact-page textarea{width:100%;padding:12px;margin-bottom:12px;border:1px solid #ddd;border-radius:8px;font-family:inherit}
    .contact-page button{background:#3b82f6;color:#fff;border:none;padding:12px 20px;border-radius:10px;cursor:pointer;font-weight:600}
    .contact-page button:hover{background:#2563eb}
    .contact-page footer{background:#1f2937;color:#fff;text-align:center;padding:28px;margin-top:40px}
    .contact-page footer a{color:#93c5fd;text-decoration:none;margin:0 8px}
  </style>
</head>
<body>
<header>
  <div class="nav-wrap">
    <div class="logo">TipsterAi</div>
    <nav>
      <div class="burger" onclick="toggleMenu()">☰</div>
      <ul id="menu">
        <li><a href="index.php">Domů</a></li>
        <li><a href="about.php">O nás</a></li>
        <li><a href="contact.php" class="active">Kontakt</a></li>
      </ul>
    </nav>
  </div>
</header>

<section class="hero">
  <h1>Kontaktujte nás</h1>
  <p>Ozvěte se nám s dotazy nebo nápady 👋</p>
</section>

<section class="section">
  <form method="POST" action="send_contact.php">
    <input type="text" name="name" placeholder="Vaše jméno" required>
    <input type="email" name="email" placeholder="Váš e-mail" required>
    <textarea name="message" placeholder="Vaše zpráva" rows="5" required></textarea>
    <button type="submit">Odeslat</button>
  </form>
</section>

<footer>
  <p>&copy; 2025 TipsterAi</p>
  <p><a href="about.php">O nás</a> | <a href="terms.php">Podmínky</a> | <a href="contact.php">Kontakt</a></p>
</footer>

<script>
  function toggleMenu(){document.getElementById('menu').classList.toggle('show')}
</script>
</body>
</html>
