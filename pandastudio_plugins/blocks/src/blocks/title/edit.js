import { __ } from '@wordpress/i18n';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { content, titleClass, titleInnerTag } = attributes;
	const blockProps = useBlockProps();
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '区块设置' ) }>
					<SelectControl
						label={ __( '标题样式' ) }
						value={ titleClass }
						options={ [
							{ label: '样式一', value: 'title_style_01' },
							{ label: '样式二', value: 'title_style_02' },
						] }
						onChange={ ( value ) => setAttributes( { titleClass: value } ) }
					/>
					<SelectControl
						label={ __( '标签' ) }
						value={ titleInnerTag }
						options={ [
							{ label: 'p', value: 'p' },
							{ label: 'h2', value: 'h2' },
							{ label: 'h3', value: 'h3' },
						] }
						onChange={ ( value ) => setAttributes( { titleInnerTag: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<div className={ titleClass }>
					<RichText
						tagName={ titleInnerTag }
						value={ content }
						onChange={ ( value ) => setAttributes( { content: value } ) }
						placeholder={ __( '请输入...' ) }
					/>
				</div>
			</div>
		</>
	);
}
