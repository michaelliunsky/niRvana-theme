import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { href, need_reply } = attributes;
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '区块设置' ) }>
					<TextControl
						label={ __( '下载地址' ) }
						placeholder="https://"
						value={ href || '' }
						onChange={ ( value ) => setAttributes( { href: value } ) }
						__next40pxDefaultSize
					/>
					<ToggleControl
						label={ __( '需评论下载' ) }
						checked={ need_reply || false }
						onChange={ ( value ) => setAttributes( { need_reply: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<div className="download_wrap">
					<div className="download_btn" />
				</div>
			</div>
		</>
	);
}
