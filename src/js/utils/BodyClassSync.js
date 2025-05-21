import { useEffect } from "@wordpress/element";
import { useSelect } from "@wordpress/data";

/**
 * Synchronisiert die Klasse "editor-post-is-highlighted" mit dem Beitragsstatus:
 * - Fügt sie immer dem Haupt-body hinzu
 * - Wenn ein Gutenberg-iFrame vorhanden ist, auch dort
 * - Stoppt automatisch nach 10 Sekunden
 */
const BodyClassSync = () => {
	const isHighlighted = useSelect((select) => {
		const meta = select("core/editor").getEditedPostAttribute("meta");
		return meta?._is_highlighted === "1";
	});

	useEffect(() => {
		document.body.classList.toggle("editor-post-is-highlighted", isHighlighted);

		let interval = null;
		let timeout = null;

		const applyClassToIframe = () => {
			const iframe = document.querySelector('iframe[name="editor-canvas"]');
			if (!iframe?.contentDocument?.body) return;

			iframe.contentDocument.body.classList.toggle("editor-post-is-highlighted", isHighlighted);
			clearInterval(interval);
			clearTimeout(timeout);
		};

		interval = setInterval(applyClassToIframe, 100);
		timeout = setTimeout(() => clearInterval(interval), 10000);

		return () => {
			document.body.classList.remove("editor-post-is-highlighted");

			const iframe = document.querySelector('iframe[name="editor-canvas"]');
			if (iframe?.contentDocument?.body) {
				iframe.contentDocument.body.classList.remove("editor-post-is-highlighted");
			}

			clearInterval(interval);
			clearTimeout(timeout);
		};
	}, [isHighlighted]);

	return null;
};

export default BodyClassSync;
