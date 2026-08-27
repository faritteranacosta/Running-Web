<?php
/**
 * Layout para las vistas de "dashboard" (sidebar + topbar), análogo a
 * layouts/main.php pero para las vistas internas de runner/vendedor/
 * productos/admin en vez de las públicas.
 *
 * Variables esperadas en scope:
 *   $pageTitle, $pageStyles, $pageScripts  -> igual que en layouts/main.php
 *   $navItems, $sidebarExtra, $logoutHref  -> pasados tal cual a components/sidebar.php
 *   $topbarTitle                            -> texto del <h1> del topbar
 *   $topbarTitleId (opcional)               -> id del <h1>, para páginas que lo cambian por JS (admin)
 *   $topbarActions (opcional)               -> HTML crudo extra en vez de las campanitas por defecto
 *   $content                                 -> HTML del <main class="dash-content">
 *   $inlineScript (opcional)                -> <script> crudo antes de los pageScripts
 */
$pageTitle = $pageTitle ?? 'RunningWeb';
$pageStyles = $pageStyles ?? [];
$pageScripts = $pageScripts ?? [];
$topbarTitle = $topbarTitle ?? '';
$topbarTitleId = $topbarTitleId ?? '';
$topbarActions = $topbarActions ?? null;
$content = $content ?? '';
$showFooter = $showFooter ?? false;
$inlineScript = $inlineScript ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
<link rel="icon" href="../public/assets/img/icon.ico" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../public/css/base.css">
<link rel="stylesheet" href="../public/css/vendedor.css">
<?php foreach ($pageStyles as $style): ?>
<link rel="stylesheet" href="<?php echo htmlspecialchars($style, ENT_QUOTES, 'UTF-8'); ?>">
<?php endforeach; ?>
</head>
<body class="dash">

<div class="dash-shell">

  <?php require __DIR__ . '/../components/sidebar.php'; ?>

  <div class="dash-main">
    <header class="dash-topbar">
      <h1<?php echo $topbarTitleId ? ' id="' . htmlspecialchars($topbarTitleId, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>><?php echo $topbarTitle; ?></h1>
      <?php if ($topbarActions !== null): ?>
        <?php echo $topbarActions; ?>
      <?php else: ?>
      <div class="topbar-actions">
        <button class="icon-btn" aria-label="Notificaciones">
          <i class="fas fa-bell"></i><span class="dot">3</span>
        </button>
        <button class="icon-btn" aria-label="Ayuda">
          <i class="fas fa-circle-question"></i>
        </button>
      </div>
      <?php endif; ?>
    </header>

    <main class="dash-content">
      <?php echo $content; ?>
    </main>
    <?php if ($showFooter): ?>
    <?php require __DIR__ . '/../components/footer.php'; ?>
    <?php endif; ?>
  </div>
</div>

<?php if ($inlineScript !== ''): ?>
<script>
<?php echo $inlineScript; ?>
</script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php foreach ($pageScripts as $script): ?>
<script<?php echo !empty($script['type']) ? ' type="' . htmlspecialchars($script['type'], ENT_QUOTES, 'UTF-8') . '"' : ''; ?> src="<?php echo htmlspecialchars($script['src'], ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php endforeach; ?>
<script>
  document.getElementById('toggle-sidebar').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  });
</script>
<script src="../public/js/navegacionLogo.js"></script>
</body>
</html>
