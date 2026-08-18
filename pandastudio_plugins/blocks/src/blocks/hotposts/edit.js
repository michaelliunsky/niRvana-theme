import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl, TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	const { title, number, filter } = attributes;
	const blockProps = useBlockProps();
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '热门文章设置', 'niRvana' ) }>
					<TextControl
						label={ __( '标题', 'niRvana' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						__next40pxDefaultSize
					/>
					<SelectControl
						label={ __( '文章筛选', 'niRvana' ) }
						value={ filter }
						options={ [
							{ label: __( '点赞最多', 'niRvana' ), value: 'most_likes' },
							{ label: __( '评论最多', 'niRvana' ), value: 'most_comments' },
						] }
						onChange={ ( value ) => setAttributes( { filter: value } ) }
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
					block="pandastudio/hotposts"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
