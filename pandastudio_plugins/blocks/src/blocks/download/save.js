import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { href, need_reply } = attributes;
	const tag = need_reply ? 'reply2down' : 'download';
	return (
		<div { ...useBlockProps.save() }>
			{ `[${ tag }]` }
			{ href || '' }
			{ `[/${ tag }]` }
		</div>
	);
}
