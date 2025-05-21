/*
 * Diese Datei erweitert die Standardkonfiguration von @wordpress/scripts.
 * Aktuell wird nur ein zentraler Einstiegspunkt verwendet:
 * - src/js/index.js → enthält Editor-Logik und Frontend-JS
 *
 * Falls in Zukunft weitere Entry-Points nötig sind (z. B. media.js, post.js),
 * kann diese Konfiguration entsprechend erweitert werden.
 *
 */
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require("path");

module.exports = {
	...defaultConfig,
	entry: {
		// JavaScript-Einstiegspunkte
		'editor-script': path.resolve(__dirname, "src/js/editor.js"),
		'frontend-script': path.resolve(__dirname, "src/js/frontend.js"),

		// SCSS-Dateien → werden zu CSS kompiliert
		'editor-style': path.resolve(__dirname, "src/css/editor.scss"),
		'frontend-style': path.resolve(__dirname, "src/css/frontend.scss"),
	},
	output: {
		path: path.resolve(__dirname, "build"),
		filename: "[name].js", // .js für JS-Dateien, .css wird automatisch richtig erzeugt
	},
};
