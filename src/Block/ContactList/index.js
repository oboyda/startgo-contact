// import './index.scss';
import metadata from './block.json';

import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';

registerBlockType(metadata.name, {
    edit: Edit,
    save: (props) => { return null }
});
