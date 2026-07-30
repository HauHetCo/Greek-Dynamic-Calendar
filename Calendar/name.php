<?php include("html.php"); ?>
<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';

$months = greekMonths();
$daysFull = greekDaysFull();
$today = new DateTime('now', new DateTimeZone('Europe/Athens'));

$search = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$searchResults = $search !== '' ? searchNameday($pdo, $search) : [];

$siteName = 'Δυναμικό Εορτολόγιο';
$baseUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://')
    . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$canonical = $baseUrl . '/name.php';

if ($search !== '') {
    $canonical .= '?q=' . urlencode($search);
}

$pageTitle = $search !== ''
    ? 'Πότε γιορτάζει το όνομα ' . $search . ' | ' . $siteName
    : 'Αναζήτηση ονόματος | ' . $siteName;

$metaDescription = $search !== ''
    ? 'Δες πότε γιορτάζει το όνομα ' . $search . ', μαζί με σχετικές εγγραφές από το εορτολόγιο.'
    : 'Αναζήτησε πότε γιορτάζει ένα όνομα στο δυναμικό εορτολόγιο.';

$robotsContent = ($search !== '' && empty($searchResults)) ? 'noindex,follow' : 'index,follow';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="el" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="cleartype" content="on" />
  <meta name="MobileOptimized" content="width" />
  <meta name="HandheldFriendly" content="true" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="author" content="HauHet®"/>
  <meta name="generator" content="hauhet.co">
  <meta name="description" content="<?php echo e($metaDescription); ?>">
  <meta name="robots" content="<?php echo e($robotsContent); ?>">
  <link rel="canonical" href="<?php echo e($canonical); ?>">

  <meta property="og:locale" content="el_GR">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?php echo e($pageTitle); ?>">
  <meta property="og:description" content="<?php echo e($metaDescription); ?>">
  <meta property="og:url" content="<?php echo e($canonical); ?>">
  <meta property="og:site_name" content="<?php echo e($siteName); ?>">

  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="<?php echo e($pageTitle); ?>">
  <meta name="twitter:description" content="<?php echo e($metaDescription); ?>">

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/fontawesome.min.css" integrity="sha512-M5Kq4YVQrjg5c2wsZSn27Dkfm/2ALfxmun0vUE3mPiJyK53hQBHYCVAtvMYEC7ZXmYLg8DVG4tF8gD27WmDbsg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/brands.min.css" integrity="sha512-WxpJXPm/Is1a/dzEdhdaoajpgizHQimaLGL/QqUIAjIihlQqlPQb1V9vkGs9+VzXD7rgI6O+UsSKl4u5K36Ydw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/fontawesome.min.js" integrity="sha512-obFNtQ1JKCrxPBPLmYDUevlriATl5EhvwU3CFtdW/HKOkeAe0bbsyZfHO44/f1QyndrZJ464TQvrRP9ZjyXSSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="top.js"></script>
  <link href="style.css" rel="stylesheet">

  <title><?php echo e($pageTitle); ?></title>
</head>
<body>
  <header class="header">
    <div class="container header-row">
      <div class="brand">
        <svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><rect x="10" y="14" width="44" height="40" rx="10" stroke="currentColor" stroke-width="4"/><path d="M20 10v12M44 10v12M10 24h44" stroke="currentColor" stroke-width="4" stroke-linecap="round"/><path d="M22 34h8M34 34h8M22 44h8M34 44h8" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
        <div><strong>Δυναμικό Εορτολόγιο</strong><span>HauHet plc.</span></div>
      </div>
      <button class="theme-toggle" type="button" data-theme-toggle aria-label="Εναλλαγή θέματος"></button>
    </div>
  </header>

  <main class="main">
    <section class="hero">
      <div class="container hero-grid">
        <div class="panel hero-copy">
          <span class="eyebrow"><?php echo $daysFull[(int)$today->format('N') - 1] . ' ' . $months[(int)$today->format('n')] . ' ' . $today->format('Y'); ?></span>
          <h1>
            <?php echo $search !== '' ? 'Αποτελέσματα για το όνομα: ' . e($search) : 'Αναζήτηση ονόματος'; ?>
          </h1>
          <p class="muted">
            <?php echo $search !== ''
              ? 'Δες όλες τις σχετικές εγγραφές του εορτολογίου για το συγκεκριμένο όνομα.'
              : 'Γράψε ένα όνομα για να δεις πότε γιορτάζει και ποιες σχετικές εγγραφές υπάρχουν.'; ?>
          </p>

          <form class="search-form" method="get" action="name.php">
            <input
              type="text"
              name="q"
              placeholder="Αναζήτηση ονόματος, π.χ. Μαρία"
              value="<?php echo e($search); ?>"
            >
            <button class="btn btn-primary" type="submit">Αναζήτηση</button>
          </form>

          <section class="search-results-box">
            <h3>Αποτελέσματα αναζήτησης</h3>
            <div class="results-list">
              <?php if ($search === ''): ?>
                <p class="muted">Γράψε ένα όνομα για να δεις όλες τις αντίστοιχες εγγραφές.</p>
              <?php elseif (!$searchResults): ?>
                <p class="muted">Δεν βρέθηκαν αποτελέσματα για το συγκεκριμένο όνομα.</p>
              <?php else: foreach ($searchResults as $row): ?>
                <div class="result">
                  <span>
                    <strong><?php echo e((string)$row['person_name']); ?></strong><br>
                    <small class="muted">
                      <?php echo e((string)($row['notes'] ?: 'Χωρίς σημείωση')); ?>
                    </small>
                  </span>
                  <span>
                    <?php echo sprintf('%02d/%02d', $row['day_of_month'], $row['month_of_year']); ?>
                  </span>
                </div>
              <?php endforeach; endif; ?>
            </div>
          </section>

          <p style="margin-top:1rem">
            <a class="btn btn-secondary" href="index.php">Επιστροφή στο ημερολόγιο</a>
          </p>
        </div>

        <aside class="panel today-card">
          <div class="muted" style="text-transform:uppercase;letter-spacing:.08em;margin-bottom:.45rem">Σήμερα</div>
          <div class="today-number"><?php echo $today->format('d'); ?></div>
          <p class="muted" style="margin-bottom:1rem">
            <?php echo $daysFull[(int)$today->format('N') - 1] . ' ' . $months[(int)$today->format('n')] . ' ' . $today->format('Y'); ?>
          </p>
          <div class="tag-list">
            <?php if ($todayNamedays): foreach ($todayNamedays as $person): ?>
              <span class="tag"><?php echo htmlspecialchars($person, ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endforeach; else: ?>
              <span class="tag">Δεν βρέθηκε εγγραφή</span>
            <?php endif; ?>
          </div>
        </aside>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container footer-inner">
      <p>HauHet plc. &reg; &copy; 2023 - <?php echo date("Y"); ?> All rights reserved.</p>
      <p>Made With <i class="fas fa-heart"></i> <a href="https://hauhet.co/" target="_blank" rel="noopener noreferrer">HauHet plc.</a></p>
    </div>
  </footer>

  <button id="scrollToTop" title="Scroll to Top">
    <i class="fa-solid fa-arrow-up"></i>
  </button>

  <script>
    (function(){
      const t=document.querySelector('[data-theme-toggle]'),r=document.documentElement;
      let d=matchMedia('(prefers-color-scheme:dark)').matches?'dark':'light';
      const icon=m=>m==='dark'
        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>'
        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
      const apply=m=>{r.setAttribute('data-theme',m);t.innerHTML=icon(m)};
      apply(d);
      t.addEventListener('click',()=>{d=d==='dark'?'light':'dark';apply(d);});
    })();

    $('#scrollToTop').click(function() {
      $('html, body').animate({ scrollTop: 0 }, 800);
      return false;
    });
  </script>
</body>
</html>