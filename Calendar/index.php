<?php include("html.php"); ?>
<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';

$months = greekMonths();
$daysShort = greekDaysShort();
$daysFull = greekDaysFull();
$today = new DateTime('now', new DateTimeZone('Europe/Athens'));
$year = isset($_GET['year']) ? max(1900, min(2100, (int)$_GET['year'])) : (int)$today->format('Y');
$month = isset($_GET['month']) ? max(1, min(12, (int)$_GET['month'])) : (int)$today->format('n');
$search = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

$current = DateTimeImmutable::createFromFormat('Y-n-j', "$year-$month-1", new DateTimeZone('Europe/Athens'));
$prev = $current->modify('-1 month');
$next = $current->modify('+1 month');
$grid = getMonthGrid($year, $month);
$monthNamedays = fetchMonthNamedays($pdo, $month);
$todayNamedays = fetchTodayNamedays($pdo, (int)$today->format('n'), (int)$today->format('j'));
$holidays = fetchFixedHolidays($pdo, $month);
$searchResults = $search !== '' ? searchNameday($pdo, $search) : [];
?>
<!DOCTYPE html>

<!-- <meta name="generator" content="HauHet" /> -->
<!-- <meta name="keywords" content="HauHet"> /> -->
<!-- header('x-powered-by: HauHet'); -->

<!-- 
//////////////////////////////////////////////////////

HauHet plc.
hauhet.co

We are HauHet
AI Energy Sector
Advanced Distribution Management System

Headquarters
HauHet plc. – Xolo Go OU
Private Limited Company

Kalasadama 4
10415 – Tallinn
Estonia

//////////////////////////////////////////////////////
-->

<html lang="el" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Meta description, aim for about 150 characters-->
  <meta http-equiv="cleartype" content="on" />
  <meta name="MobileOptimized" content="width" />
  <meta name="HandheldFriendly" content="true" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="author" content="HauHet®"/>
  <meta name="generator" content="hauhet.co">
  <meta name="generator" content="hauhet.co">

      <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
     <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/fontawesome.min.css" integrity="sha512-M5Kq4YVQrjg5c2wsZSn27Dkfm/2ALfxmun0vUE3mPiJyK53hQBHYCVAtvMYEC7ZXmYLg8DVG4tF8gD27WmDbsg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/brands.min.css" integrity="sha512-WxpJXPm/Is1a/dzEdhdaoajpgizHQimaLGL/QqUIAjIihlQqlPQb1V9vkGs9+VzXD7rgI6O+UsSKl4u5K36Ydw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/fontawesome.min.js" integrity="sha512-obFNtQ1JKCrxPBPLmYDUevlriATl5EhvwU3CFtdW/HKOkeAe0bbsyZfHO44/f1QyndrZJ464TQvrRP9ZjyXSSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Custom JS -->
    <script src="top.js"></script>
      <!-- CSS -->
    <link href="style.css" rel="stylesheet">

<?php
$baseUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://')
    . ($_SERVER['HTTP_HOST'] ?? 'localhost');

$indexCanonical = $baseUrl . '/index.php?month=' . urlencode((string)$month) . '&year=' . urlencode((string)$year);
$indexTitle = $months[$month] . ' ' . $year . ' | Δυναμικό Εορτολόγιο';
$indexDescription = 'Δες το εορτολόγιο για τον μήνα ' . $months[$month] . ' ' . $year . ', μαζί με γιορτές και αργίες.';
?>
<meta name="description" content="<?php echo htmlspecialchars($indexDescription, ENT_QUOTES, 'UTF-8'); ?>">
<link rel="canonical" href="<?php echo htmlspecialchars($indexCanonical, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="robots" content="index,follow">
<title><?php echo htmlspecialchars($indexTitle, ENT_QUOTES, 'UTF-8'); ?></title>

</head>
<body>
  <header class="header">
  <div class="container header-row">
    <div class="brand">
      <svg viewBox="0 0 64 64" fill="none" aria-hidden="true">
        <rect x="10" y="14" width="44" height="40" rx="10" stroke="currentColor" stroke-width="4"/>
        <path d="M20 10v12M44 10v12M10 24h44" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
        <path d="M22 34h8M34 34h8M22 44h8M34 44h8" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
      </svg>
      <div>
        <strong>Δυναμικό Εορτολόγιο</strong>
        <span>HauHet plc.</span>
      </div>
    </div>

    <div class="header-actions">
      <button
        class="theme-toggle"
        type="button"
        data-theme-toggle
        aria-label="Εναλλαγή θέματος">
      </button>
    </div>
  </div>
</header>

  <main class="main">
    <section class="hero">
      <div class="container hero-grid">
        <div class="panel hero-copy">
          <span class="eyebrow">Δυναμική βάση δεδομένων</span>
          <h1>Εορτολόγιο με αναζήτηση ονόματος.</h1>
          <p class="muted">Η εφαρμογή φορτώνει γιορτές και αργίες από DataBase</p>
          <form class="search-form" method="get" action="name.php">
          <input type="text" name="q" placeholder="Αναζήτηση ονόματος, π.χ. Μαρία" value="">
          <button class="btn btn-primary" type="submit">Αναζήτηση</button>
          </form>

        </div>

        <aside class="panel today-card">
          <div class="muted" style="text-transform:uppercase;letter-spacing:.08em;margin-bottom:.45rem">Σήμερα</div>
          <div class="today-number"><?php echo $today->format('d'); ?></div>
          <p class="muted" style="margin-bottom:1rem"><?php echo $daysFull[(int)$today->format('N') - 1] . ' ' . $months[(int)$today->format('n')] . ' ' . $today->format('Y'); ?></p>
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

    <section class="month-toolbar-wrap">
      <div class="container">
        <nav class="month-toolbar">
          <a href="?month=<?php echo $prev->format('n'); ?>&year=<?php echo $prev->format('Y'); ?>">&larr; Προηγούμενος</a>
          <a href="?month=<?php echo $today->format('n'); ?>&year=<?php echo $today->format('Y'); ?>">Σήμερα</a>
          <a href="?month=<?php echo $next->format('n'); ?>&year=<?php echo $next->format('Y'); ?>">Επόμενος &rarr;</a>
        </nav>
      </div>
    </section>

    <section>
      <div class="container main-grid">
        <section class="panel calendar-card">
          <div class="calendar-head">
            <div>
              <h2><?php echo $months[$month] . ' ' . $year; ?></h2>
              <p class="muted">Τα δεδομένα του μήνα φορτώνονται από τη βάση με grouped queries.</p>
            </div>
          </div>
          <div class="month-grid">
            <?php foreach ($daysShort as $label): ?><div class="weekday"><?php echo $label; ?></div><?php endforeach; ?>
            <?php foreach ($grid as $week): foreach ($week as $day): ?>
              <?php if ($day === null): ?>
                <div class="day empty"></div>
              <?php else: $dateObj = DateTime::createFromFormat('Y-n-j', "$year-$month-$day", new DateTimeZone('Europe/Athens')); $isWeekend = (int)$dateObj->format('N') >= 6; $isToday = $today->format('Y-n-j') === "$year-$month-$day"; ?>
                <article class="day <?php echo $isWeekend ? 'weekend' : ''; ?> <?php echo $isToday ? 'today' : ''; ?>" data-date="<?php echo $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $day); ?>">
                  <div class="day-number"><?php echo $day; ?></div>
                  <?php if (isset($holidays[$day])): ?><div class="note holiday"><?php echo htmlspecialchars($holidays[$day], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                  <?php if (isset($monthNamedays[$day])): ?><div class="note"><?php echo htmlspecialchars($monthNamedays[$day], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                </article>
              <?php endif; ?>
            <?php endforeach; endforeach; ?>
          </div>
        </section>

        <section class="panel card months-card">
          <h3>Μήνες</h3>
          <div class="months">
            <?php foreach ($months as $m => $name): ?>
              <a href="?month=<?php echo $m; ?>&year=<?php echo $year; ?>"><span><?php echo $name; ?></span><span><?php echo $year; ?></span></a>
            <?php endforeach; ?>
          </div>
        </section>
        
     <section class="panel card legend-card">
          <h3>Υπόμνημα</h3>
          <div class="legend">
            <div class="legend-item"><span class="dot p"></span><span>Σημερινή ημέρα</span></div>
            <div class="legend-item"><span class="dot h"></span><span>Αργία από βάση</span></div>
            <div class="legend-item"><span class="dot w"></span><span>Σαββατοκύριακο</span></div>
          </div>
        </section>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container footer-inner">
      <p>HauHet plc. &reg; &copy; 2023 - <?php echo date("Y"); ?>  All rights reserved.</p>
      <p> Made With <i class="fas fa-heart"></i> <a href="https://hauhet.co/">HauHet plc.</a></p>
    </div>
  </footer>
  
      <!-- Scroll to Top Button -->
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

    document.querySelectorAll('.day').forEach(day => {
      if (!day.classList.contains('empty')) {
        day.addEventListener('click', function () {
          document.querySelectorAll('.day.selected').forEach(el => el.classList.remove('selected'));
          this.classList.add('selected');
        });
      }
    });
	
	        // Scroll to Top Button Functionality
            $('#scrollToTop').click(function() {
                $('html, body').animate({ scrollTop: 0 }, 800);
                return false;
            });
  </script>
</body>
</html>