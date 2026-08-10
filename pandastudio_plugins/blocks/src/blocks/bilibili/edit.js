import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { iframe } = attributes;
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '区块设置' ) }>
					<div className="pf_inspectorTitle">{ __( '请粘贴嵌入代码' ) }</div>
					<TextControl
						value={ iframe || '' }
						onChange={ ( value ) => setAttributes( { iframe: value } ) }
						placeholder={ __( '点击视频下方的分享->复制“嵌入代码”' ) }
						help={ __( '注意：这里粘贴的任何html都将当做代码来执行，请不要填写不安全的代码！' ) }
						__next40pxDefaultSize
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<div className="bilibili_video_wrap" />
			</div>
		</>
	);
}
