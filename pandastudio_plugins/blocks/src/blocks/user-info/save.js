import { RichText, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { name, description, img } = attributes;
	const blockProps = useBlockProps.save( { className: 'pf_user_info' } );
	return (
		<div { ...blockProps }>
			{ img && (
				<div
					className="authorImg"
					style={ { backgroundImage: `url(${ img })` } }
				/>
			) }
			<div className="meta">
				<div className="name">{ name }</div>
				<RichText.Content
					tagName="div"
					className="description"
					value={ description }
				/>
			</div>
		</div>
	);
}
