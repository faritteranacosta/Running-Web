<?php
$pageTitle = $pageTitle ?? 'RunningWeb';
$pageStyles = $pageStyles ?? [];
$pageScripts = $pageScripts ?? [];
$activePage = $activePage ?? '';
$showCart = $showCart ?? false;
$content = $content ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
<link rel="icon" href="../public/assets/img/icon.ico" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../public/css/base.css">
<?php foreach ($pageStyles as $style): ?>
<link rel="stylesheet" href="<?php echo htmlspecialchars($style, ENT_QUOTES, 'UTF-8'); ?>">
<?php endforeach; ?>
</head>
<body>
<?php require __DIR__ . '/../components/navbar.php'; ?>
<?php echo $content; ?>
<?php require __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php foreach ($pageScripts as $script): ?>
<script<?php echo !empty($script['type']) ? ' type="' . htmlspecialchars($script['type'], ENT_QUOTES, 'UTF-8') . '"' : ''; ?> src="<?php echo htmlspecialchars($script['src'], ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php endforeach; ?>
<script src="../public/js/navegacionLogo.js"></script>
<script>
  const menuBtn = document.getElementById("mobile-menu-button");
  const mobileMenu = document.getElementById("mobile-menu");
  menuBtn.addEventListener("click", function () {
    const isOpen = mobileMenu.classList.toggle("open");
    menuBtn.setAttribute("aria-expanded", isOpen);
  });
</script>
</body>
</html>
