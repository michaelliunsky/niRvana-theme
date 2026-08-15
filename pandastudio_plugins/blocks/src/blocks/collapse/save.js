import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { id, btn_type, btn_label } = attributes;
	const type = btn_type || 'btn-primary';
	const label = btn_label || '';
	return (
		<div { ...useBlockProps.save() }>
			{ `[collapse id="${ id }" btn_type="${ type }" btn_label="${ label }"]` }
			<div className="wp-block-column">
				<InnerBlocks.Content />
			</div>
			{ '[/collapse]' }
		</div>
	);
}
