<?php

/**
 * Plugin Name: Beitragsauszeichnung
 * Description: Ermöglicht das Markieren von Beiträgen als „Hervorgehoben“. Fügt im Editor eine Sidebar-Option hinzu und ergänzt automatisch die Klasse „is-highlighted“ im Frontend-Markup. Im Loop muss das äussere HTML-Element article, section oder li sein, damit die Klasse korrekt hinzugefügt werden kann.
 * Version: 1.2.0
 * Author: ulrich.digital
 * Author URI: https://ulrich.digital/
 * Text Domain: beitragsauszeichnung
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;

foreach (glob(__DIR__ . '/includes/*.php') as $file) {
    require_once $file;
}
