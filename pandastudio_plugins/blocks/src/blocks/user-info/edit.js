import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { Button, PanelBody, TextControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { name, description, img } = attributes;
	const blockProps = useBlockProps( { className: 'pf_user_info' } );
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '博主信息设置', 'niRvana' ) }>
					<TextControl
						label={ __( '昵称', 'niRvana' ) }
						value={ name }
						onChange={ ( value ) => setAttributes( { name: value } ) }
						__next40pxDefaultSize
					/>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) => setAttributes( { img: media.url } ) }
							allowedTypes={ [ 'image' ] }
							value={ img }
							render={ ( { open } ) => (
								<Button
									onClick={ open }
									variant="secondary"
									__next40pxDefaultSize
								>
									{ img ? __( '更换头像', 'niRvana' ) : __( '选择头像', 'niRvana' ) }
								</Button>
							) }
						/>
					</MediaUploadCheck>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				{ img && (
					<div
						className="authorImg"
						style={ { backgroundImage: `url(${ img })` } }
					/>
				) }
				<div className="meta">
					<div className="name">{ name }</div>
					<RichText
						tagName="div"
						className="description"
						value={ description }
						onChange={ ( value ) => setAttributes( { description: value } ) }
						placeholder={ __( '描述...', 'niRvana' ) }
					/>
				</div>
			</div>
		</>
	);
}
