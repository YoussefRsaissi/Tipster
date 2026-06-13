<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>tipsterAi — Ceník</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
/* Cenik specifický styl */
.pricing-section {
    padding: 100px 20px 60px;
    text-align: center;
    background: #f3f4f6;
}
.pricing-section h2 {
    font-size: 42px;
    margin-bottom: 40px;
    color: #111827;
}
.pricing-cards {
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
}
.pricing-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    padding: 30px 20px;
    width: 280px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.pricing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}
.pricing-card h3 {
    font-size: 24px;
    margin-bottom: 12px;
}
.pricing-card .price {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #7c3aed;
}
.pricing-card ul {
    list-style: none;
    padding: 0;
    margin-bottom: 20px;
    text-align: left;
}
.pricing-card ul li {
    margin: 12px 0;
    padding-left: 20px;
    position: relative;
    font-size: 16px;
    color: #111827;
}
.pricing-card ul li::before {
    content: "✔";
    position: absolute;
    left: 0;
    color: #3b82f6;
}
.pricing-card .btn {
    display: inline-block;
    padding: 12px 24px;
    border-radius: 12px;
    background: #7c3aed;
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
}
.pricing-card .btn:hover {
    background: #5b21b6;
}
</style>
</head>
<body>

<header>
    <div class="container nav">
        <div class="logo">tipsterAi</div>
        <nav>
            <ul>
                <li><a href="index.php">Domů</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Registrace</a></li>
                <li><a href="cenik.php">Ceník</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="pricing-section">
    <h2>Náš Ceník</h2>
    <div class="pricing-cards">
        <!-- Basic -->
        <div class="pricing-card">
            <h3>Basic</h3>
            <div class="price">199 Kč/měsíc</div>
            <ul>
                <li>10 tipů měsíčně</li>
                <li>Základní statistiky</li>
                <li>Přístup k fóru</li>
            </ul>
            <a href="register.php?plan=basic&trial=3" class="btn">Vyzkoušet 3 dny zdarma</a>
        </div>

        <!-- Pro -->
        <div class="pricing-card">
            <h3>Pro</h3>
            <div class="price">399 Kč/měsíc</div>
            <ul>
                <li>30 tipů měsíčně</li>
                <li>Rozšířené statistiky</li>
                <li>Přístup k fóru a chatu</li>
                <li>Priority podpora</li>
            </ul>
            <a href="register.php?plan=pro&trial=3" class="btn">Vyzkoušet 3 dny zdarma</a>
        </div>

        <!-- Premium -->
        <div class="pricing-card">
            <h3>Premium</h3>
            <div class="price">699 Kč/měsíc</div>
            <ul>
                <li>Neomezené tipy</li>
                <li>Pokročilé statistiky a analýzy</li>
                <li>Přístup k fóru, chatu a webinářům</li>
                <li>VIP podpora 24/7</li>
            </ul>
            <a href="register.php?plan=premium&trial=3" class="btn">Vyzkoušet 3 dny zdarma</a>
        </div>
    </div>
</section>

<footer>
    <p>&copy; 2025 tipsterAi. Všechna práva vyhrazena.</p>
</footer>

<script src="js/script.js"></script>
</body>
</html>
