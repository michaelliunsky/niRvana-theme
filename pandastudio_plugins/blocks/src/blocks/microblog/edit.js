import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	const { title, number } = attributes;
	const blockProps = useBlockProps();
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '轻博客设置', 'niRvana' ) }>
					<TextControl
						label={ __( '标题', 'niRvana' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						__next40pxDefaultSize
					/>
					<RangeControl
						label={ __( '显示数量', 'niRvana' ) }
						value={ number }
						min={ 1 }
						max={ 30 }
						onChange={ ( value ) => setAttributes( { number: value } ) }
						__next40pxDefaultSize
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block="pandastudio/microblog"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
