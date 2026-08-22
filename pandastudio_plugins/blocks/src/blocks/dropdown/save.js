import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { id, btn_type, btn_label, lists } = attributes;
	const type = btn_type || 'btn-default';
	const label = btn_label || '';
	const items = lists || [];
	let listHtml = '';
	for ( let i = 0; i < items.length; i++ ) {
		listHtml += `[li href="${ items[ i ].href }"]${ items[ i ].label }[/li]`;
	}
	return (
		<div { ...useBlockProps.save() }>
			{ `[dropdown id="${ id }" btn_type="${ type }" btn_label="${ label }"]` }
			{ listHtml }
			{ '[/dropdown]' }
		</div>
	);
}
