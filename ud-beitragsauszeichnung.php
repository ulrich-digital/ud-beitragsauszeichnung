<?php

/**
 * Plugin Name:     UD Block-Erweiterung: Beitragsauszeichnung
 * Description:     Markiert Beiträge, Seiten und CPTs als „Hervorgehoben“. Fügt eine Sidebar-Option hinzu und ergänzt automatisch die Klasse „is-highlighted__ud“ im Frontend.
 * Version:         1.2.0
 * Author:          ulrich.digital gmbh
 * Author URI:      https://ulrich.digital/
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     beitragsauszeichnung-ud
 */


defined('ABSPATH') || exit;

require_once __DIR__ . '/includes/enqueue.php';
require_once __DIR__ . '/includes/meta.php';
require_once __DIR__ . '/includes/render.php';
require_once __DIR__ . '/includes/admin.php';