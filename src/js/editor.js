/**
 * editor.js
 *
 * JavaScript für den Block-Editor (Gutenberg).
 * Wird ausschließlich im Backend geladen.
 *
 * Hinweis:
 * Diese Datei enthält editor-spezifische Interaktionen oder React-Komponenten.
 * Wird über webpack zu editor.js gebündelt und in block.json oder enqueue.php eingebunden.
 */

import { registerPlugin } from "@wordpress/plugins";
import HighlightTogglePanel from "./utils/HighlightTogglePanel";
import BodyClassSync from "./utils/BodyClassSync";

registerPlugin("beitragsauszeichnung", {
	render: () => (
		<>
			<HighlightTogglePanel />
			<BodyClassSync />
		</>
	),
});
