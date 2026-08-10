import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function save() {
	return (
		<div { ...useBlockProps.save() }>
			{ '[need_reply]' }
			<div className="wp-block-column">
				<InnerBlocks.Content />
			</div>
			{ '[/need_reply]' }
		</div>
	);
}
