import { __ } from '@wordpress/i18n';
import './edit.scss';

import { SelectControl } from '@wordpress/components';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {

    const { color } = attributes;

    return (
        <div {...useBlockProps()}>

            <h3>{__('StartGo Contact', 'sgc')}</h3>

            <SelectControl
                label={__('Select form color', 'sgc')}
                value={color}
                options={[
                    { label: __('White', 'sgc'), value: 'white' },
                    { label: __('Gray', 'sgc'), value: 'gray' },
                    { label: __('Blue', 'sgc'), value: 'blue' }
                ]}
                onChange={(value) => setAttributes({ color: value })}
            />
        </div>
    );
}
