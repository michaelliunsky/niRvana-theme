import { __ } from '@wordpress/i18n';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { content, titleClass, titleInnerTag } = attributes;
	const blockProps = useBlockProps();
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '区块设置', 'niRvana' ) }>
					<SelectControl
						label={ __( '标题样式', 'niRvana' ) }
						value={ titleClass }
						options={ [
							{ label: __( '样式一', 'niRvana' ), value: 'title_style_01' },
							{ label: __( '样式二', 'niRvana' ), value: 'title_style_02' },
						] }
						onChange={ ( value ) => setAttributes( { titleClass: value } ) }
						__next40pxDefaultSize
					/>
					<SelectControl
						label={ __( '标签', 'niRvana' ) }
						value={ titleInnerTag }
						options={ [
							{ label: 'p', value: 'p' },
							{ label: 'h2', value: 'h2' },
							{ label: 'h3', value: 'h3' },
						] }
						onChange={ ( value ) => setAttributes( { titleInnerTag: value } ) }
						__next40pxDefaultSize
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<div className={ titleClass }>
					<RichText
						tagName={ titleInnerTag }
						value={ content }
						onChange={ ( value ) => setAttributes( { content: value.replace( /<br>/g, '' ) } ) }
						placeholder={ __( '请输入...', 'niRvana' ) }
					/>
				</div>
			</div>
		</>
	);
}
