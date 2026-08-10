import { RichText, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { content, typeClass } = attributes;
	const current = typeClass || 'tip info';
	const display = current.includes( 'inlineBlock' ) ? 'display="inlineBlock"' : '';
	const type = 'type="' + current.replace( /inlineBlock/g, '' ).replace( /tip/g, '' ).replace( / /g, '' ) + '"';
	return (
		<div { ...useBlockProps.save() }>
			{ `[tip ${ type } ${ display }]` }
			<RichText.Content value={ content } />
			{ '[/tip]' }
		</div>
	);
}
