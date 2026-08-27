<?php
/**
 * Sidebar compartido por las vistas de "dashboard" (runner, vendedor,
 * productos, panelAdministrador). Espera en scope:
 *   $nombre, $apellido, $rol       -> de components/session.php
 *   $navItems                       -> array de items del menú, cada uno:
 *       ['href' => '#', 'icon' => 'fas fa-...', 'label' => 'Texto',
 *        'active' => bool, 'badge' => '56' (opcional),
 *        'onclick' => "js..." (opcional, para tabs en vez de links),
 *        'dataTab' => 'eventos' (opcional, usado junto con onclick)]
 *   $sidebarExtra (opcional)        -> HTML crudo extra dentro de .sidebar-user
 *                                      (ej. el input hidden #userId)
 *   $logoBadgeLetter                -> ya calculado, primera letra del nombre
 */
$navItems = $navItems ?? [];
$sidebarExtra = $sidebarExtra ?? '';
$logoutHref = $logoutHref ?? '../api/logout';
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-head">
    <a href="index.html" class="sidebar-brand">
      <img src="../public/assets/img/icon-black.jpg" alt="RunningWeb">
      <span class="hide-on-collapse">RunningWeb</span>
    </a>
    <button class="sidebar-toggle" id="toggle-sidebar" aria-label="Colapsar menú">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <div class="sidebar-user">
    <div class="avatar-chip"><?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?></div>
    <?php echo $sidebarExtra; ?>
    <div class="hide-on-collapse">
      <h3><?php echo htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido); ?></h3>
      <p><?php echo htmlspecialchars($rol); ?></p>
    </div>
  </div>

  <nav class="sidebar-nav">
    <?php foreach ($navItems as $item): ?>
    <a href="<?php echo htmlspecialchars($item['href'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>"
       <?php echo !empty($item['active']) ? ' class="active"' : ''; ?>
       <?php echo !empty($item['onclick']) ? ' onclick="' . htmlspecialchars($item['onclick'], ENT_QUOTES, 'UTF-8') . '"' : ''; ?>
       <?php echo !empty($item['dataTab']) ? ' data-tab="' . htmlspecialchars($item['dataTab'], ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
      <i class="<?php echo htmlspecialchars($item['icon'] ?? 'fas fa-circle', ENT_QUOTES, 'UTF-8'); ?>"></i>
      <span class="hide-on-collapse"><?php echo htmlspecialchars($item['label'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
      <?php if (!empty($item['badge'])): ?>
      <span class="nav-badge hide-on-collapse"><?php echo htmlspecialchars((string) $item['badge'], ENT_QUOTES, 'UTF-8'); ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-foot">
    <a href="<?php echo htmlspecialchars($logoutHref, ENT_QUOTES, 'UTF-8'); ?>">
      <i class="fas fa-arrow-right-from-bracket"></i> <span class="hide-on-collapse">Cerrar sesión</span>
    </a>
  </div>
</aside>
