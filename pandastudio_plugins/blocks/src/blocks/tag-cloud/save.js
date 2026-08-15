import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { title, displayType, tags } = attributes;
	const blockProps = useBlockProps.save( { className: 'pf_tag_cloud' } );
	return (
		<div { ...blockProps }>
			{ title && <h2 className="widgettitle">{ title }</h2> }
			<ul className={ displayType || 'tag' }>
				{ ( tags || [] ).map( ( tag, i ) => (
					<li key={ i }>
						<a
							href={ tag.url || '#' }
							target={ tag.target === '_self' ? '_self' : '_blank' }
							rel="noopener noreferrer"
						>
							{ tag.text }
						</a>
					</li>
				) ) }
			</ul>
		</div>
	);
}
