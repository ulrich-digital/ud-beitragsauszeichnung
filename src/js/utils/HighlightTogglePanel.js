import { PluginDocumentSettingPanel } from "@wordpress/editor";
import { ToggleControl } from "@wordpress/components";
import { useSelect, useDispatch } from "@wordpress/data";

/**
 * Einfache Metabox zur Steuerung der "Hervorhebung" eines Beitrags.
 */
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

export default HighlightTogglePanel;
