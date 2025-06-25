const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, RangeControl } = wp.components;
const { __ } = wp.i18n;
const { useBlockProps } = wp.blockEditor;
const { ServerSideRender } = wp.components;

registerBlockType('katie-bray/company-logos', {
    title: __('Company Logos', 'katie-bray'),
    icon: 'grid-view',
    category: 'design',
    keywords: [
        __('logos', 'katie-bray'),
        __('companies', 'katie-bray'),
        __('partners', 'katie-bray'),
    ],
    supports: {
        align: ['wide', 'full'],
    },
    attributes: {
        columns: {
            type: 'number',
            default: 4,
        },
    },

    edit: function(props) {
        const { attributes, setAttributes } = props;
        const blockProps = useBlockProps();

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody
                        title={__('Logo Grid Settings', 'katie-bray')}
                        initialOpen={true}
                    >
                        <RangeControl
                            label={__('Columns', 'katie-bray')}
                            value={attributes.columns}
                            onChange={(columns) => setAttributes({ columns })}
                            min={2}
                            max={6}
                        />
                    </PanelBody>
                </InspectorControls>

                <ServerSideRender
                    block="katie-bray/company-logos"
                    attributes={attributes}
                />

                <div className="block-description">
                    <p className="description">
                        {__('Company logos are managed in the Theme Settings → Company Logos section.', 'katie-bray')}
                    </p>
                </div>
            </div>
        );
    },

    save: function() {
        // Server-side rendering
        return null;
    },
});
