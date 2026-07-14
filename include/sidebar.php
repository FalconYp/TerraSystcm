<?php
/**
 * Shared sidebar partial.
 *
 * Before including this file, the parent page should set:
 *   $sidebarRole   - display label, e.g. "Committee", "DO Officer"
 *   $sidebarNav    - array of ['key','label','href','icon']
 *   $sidebarActive - key of the currently active nav item
 *
 * All role dashboards live one folder below the project root,
 * so the logout link is always "../logout.php".
 */

$sidebarRole   = $sidebarRole   ?? '';
$sidebarNav    = $sidebarNav    ?? [];
$sidebarActive = $sidebarActive ?? '';

$displayName = trim(($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? ''));
if ($displayName === '') {
    $displayName = 'User';
}
?>

<div class="app-sidebar">

    <div class="brand">TerraSystcm</div>
    <div class="role-tag"><?php echo htmlspecialchars($sidebarRole); ?></div>

    <div class="user-box">
        <div class="u-label">Signed in as</div>
        <div class="u-name"><?php echo htmlspecialchars($displayName); ?></div>
    </div>

    <nav>
        <?php foreach ($sidebarNav as $item): ?>
        <a href="<?php echo htmlspecialchars($item['href']); ?>"
           class="<?php echo ($item['key'] === $sidebarActive) ? 'active' : ''; ?>">
            <i class="bi <?php echo htmlspecialchars($item['icon']); ?>"></i>
            <?php echo htmlspecialchars($item['label']); ?>
        </a>
        <?php endforeach; ?>
    </nav>

    <a href="../logout.php" class="logout-link">
        <i class="bi bi-box-arrow-right"></i>
        Logout
    </a>

</div>
