import './style.scss';

import './blocks/title';
import './blocks/tips';
import './blocks/download';
import './blocks/collapse';
import './blocks/dropdown';
import './blocks/modal';
import './blocks/gallery';
import './blocks/bilibili';
import './blocks/single';
import './blocks/needreply';
import './blocks/user-info';
import './blocks/tag-cloud';
import './blocks/microblog';
import './blocks/hotposts';

import './formats/description';

import { registerBlockStyle } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

registerBlockStyle( 'core/image', {
	name: 'border-round',
	label: __( '仅圆角', 'niRvana' ),
	isDefault: false,
} );
registerBlockStyle( 'core/image', {
	name: 'with-shadow',
	label: __( '仅阴影', 'niRvana' ),
	isDefault: false,
} );
registerBlockStyle( 'core/image', {
	name: 'border-round-and-with-shadow',
	label: __( '圆角与阴影', 'niRvana' ),
	isDefault: false,
} );
registerBlockStyle( 'core/paragraph', {
	name: 'text-indent-2em',
	label: __( '中文段落首行空2个字符', 'niRvana' ),
	isDefault: false,
} );
