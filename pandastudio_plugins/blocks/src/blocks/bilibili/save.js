import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { iframe } = attributes;
	return (
		<div { ...useBlockProps.save() }>
			<div className="bilibili_video_wrap">
				<figure dangerouslySetInnerHTML={ { __html: iframe || '' } } />
			</div>
		</div>
	);
}
