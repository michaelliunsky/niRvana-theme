import { RichText, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { content, titleClass, titleInnerTag } = attributes;
	return (
		<div { ...useBlockProps.save() }>
			<div className={ titleClass }>
				<RichText.Content tagName={ titleInnerTag } value={ content } />
			</div>
		</div>
	);
}
