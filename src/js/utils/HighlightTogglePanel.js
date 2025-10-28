import { PluginDocumentSettingPanel } from "@wordpress/editor";
import { ToggleControl } from "@wordpress/components";
import { useSelect, useDispatch } from "@wordpress/data";

/**
 * Einfache Metabox zur Steuerung der "Hervorhebung" eines Beitrags.
 */

const HighlightTogglePanel = () => {
	const postType = useSelect(
		(select) => select("core/editor").getCurrentPostType(),
		[],
	);

	const meta = useSelect((select) =>
		select("core/editor").getEditedPostAttribute("meta"),
	);


	if (!meta) {
		
		return null;
	}
	const { editPost } = useDispatch("core/editor");

	// Zugriff auf Optionen aus PHP
	const enabledPostTypes = window.udHighlightSettings?.enabledPostTypes || [];

	
	// Früher Ausstieg, wenn nicht aktiviert
	if (!enabledPostTypes.includes(postType)) return null;

	return (
		<PluginDocumentSettingPanel
			name="highlight-post-panel"
			title="Beitrag hervorheben"
			className="highlight-post-panel"
		>
			<ToggleControl
				label="Beitrag hervorheben"
				checked={!!meta._is_highlighted}
				onChange={(val) =>
					editPost({ meta: { ...meta, _is_highlighted: val } })
				}
				__nextHasNoMarginBottom={true}
			/>
		</PluginDocumentSettingPanel>
	);
};

export default HighlightTogglePanel;
