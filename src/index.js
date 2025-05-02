import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { ToggleControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

// === Komponenten ===

const BodyClassSync = () => {
    const isHighlighted = useSelect((select) => {
        const meta = select('core/editor').getEditedPostAttribute('meta');
        return meta?._is_highlighted === '1';
    });

    useEffect(() => {
        document.body.classList.toggle('editor-post-is-highlighted', isHighlighted);
    }, [isHighlighted]);

    return null;
};

const HighlightTogglePanel = () => {
    const meta = useSelect((select) =>
        select('core/editor').getEditedPostAttribute('meta')
    );
    const { editPost } = useDispatch('core/editor');

    const isHighlighted = meta?._is_highlighted === '1';

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
                            _is_highlighted: value ? '1' : ''
                        }
                    })
                }
            />
        </PluginDocumentSettingPanel>
    );
};

// === Registrierung ===

registerPlugin('beitragsauszeichnung', {
    render: () => (
        <>
            <HighlightTogglePanel />
            <BodyClassSync />
        </>
    )
});
