import { useEffect } from "@wordpress/element";
import { useSelect } from "@wordpress/data";

/**
 * Synchronisiert die Klasse "is-highlighted" mit dem Beitragsstatus
 * direkt am besten passenden Block im Gutenberg-Editor.
 */
const BodyClassSync = () => {
	const isHighlighted = useSelect((select) => {
		const meta = select("core/editor").getEditedPostAttribute("meta");
		return meta?._is_highlighted == true;
	});

	useEffect(() => {
		const applyClassToEditorBlock = () => {
			const doc = document;
			const iframe = doc.querySelector('iframe[name="editor-canvas"]');
			const targetDoc = iframe?.contentDocument || doc;

			// Priorisierte Suche nach passenden Blöcken
			const target =
				targetDoc.querySelector(".wp-block-ud-news-loop-content") ||
				targetDoc.querySelector(".wp-block-ud-event-loop-content") ||
				targetDoc.querySelector(".wp-block-group") ||
				targetDoc.querySelector(".wp-block");

			if (target) {
				target.classList.toggle("is-highlighted", isHighlighted);
			}
		};

		// Initial + Verzögerung für iFrame (Editor)
		applyClassToEditorBlock();
		const interval = setInterval(applyClassToEditorBlock, 300);
		const timeout = setTimeout(() => clearInterval(interval), 5000);

		return () => {
			clearInterval(interval);
			clearTimeout(timeout);

			const iframe = document.querySelector('iframe[name="editor-canvas"]');
			const targetDoc = iframe?.contentDocument || document;
			const blocks = targetDoc.querySelectorAll(".is-highlighted");
			blocks.forEach((el) => el.classList.remove("is-highlighted"));
		};
	}, [isHighlighted]);

	return null;
};

export default BodyClassSync;
