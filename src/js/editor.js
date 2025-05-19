import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from "@wordpress/editor"; // bewusst nicht 'edit-post'
import { ToggleControl } from "@wordpress/components";
import { useSelect, useDispatch } from "@wordpress/data";
import { useEffect } from "@wordpress/element";

// Fügt die Klasse "editor-post-is-highlighted" dynamisch dem <body> im Editor-iFrame hinzu,
// da der Beitrag selbst in einem isolierten iFrame gerendert wird und nicht im Haupt-DOM.

const BodyClassSync = () => {
	const isHighlighted = useSelect((select) => {
		const meta = select("core/editor").getEditedPostAttribute("meta");
		return meta?._is_highlighted === "1";
	});

	useEffect(() => {
		const interval = setInterval(() => {
			const iframe = document.querySelector('iframe[name="editor-canvas"]');
			if (!iframe || !iframe.contentDocument) return;

			const iframeBody = iframe.contentDocument.body;
			if (iframeBody) {
				iframeBody.classList.toggle("editor-post-is-highlighted", isHighlighted);
				clearInterval(interval); // Stop checking
			}
		}, 100); // Wiederhole alle 100ms kurzzeitig, bis iframe verfügbar ist

		return () => clearInterval(interval);
	}, [isHighlighted]);

	return null;
};


const HighlightTogglePanel = () => {
	const meta = useSelect((select) =>
		select("core/editor").getEditedPostAttribute("meta"),
	);
	const { editPost } = useDispatch("core/editor");

	const isHighlighted = meta?._is_highlighted === "1";

	return (
		<PluginDocumentSettingPanel
			name="beitragsauszeichnung-panel"
			title="Beitragsauszeichnung"
			className="beitragsauszeichnung-panel"
		>
			<ToggleControl
				label="Beitrag hervorheben"
				checked={isHighlighted}
				onChange={(value) =>
					editPost({
						meta: {
							...meta,
							_is_highlighted: value ? "1" : "",
						},
					})
				}
				__nextHasNoMarginBottom={true}
			/>
		</PluginDocumentSettingPanel>
	);
};

// === Registrierung ===
registerPlugin("beitragsauszeichnung", {
	render: () => (
		<>
			<HighlightTogglePanel />
			<BodyClassSync />
		</>
	),
});
