import { __ } from '@wordpress/i18n';
// import './edit.scss';

import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {

    return (
        <div {...useBlockProps()}>
            <h3>{__('StartGo Contact List', 'sgc')}</h3>
        </div>
    );
}
