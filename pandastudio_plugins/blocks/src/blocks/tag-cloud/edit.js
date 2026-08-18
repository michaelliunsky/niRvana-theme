import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { Button, PanelBody, SelectControl, TextControl } from '@wordpress/components';

const TARGET_OPTIONS = [
	{ label: __( '当前窗口', 'niRvana' ), value: '_self' },
	{ label: __( '新窗口', 'niRvana' ), value: '_blank' },
];

export default function Edit( { attributes, setAttributes } ) {
	const { title, displayType, tags } = attributes;
	const blockProps = useBlockProps( { className: 'pf_tag_cloud' } );

	const updateTag = ( index, key, value ) => {
		const next = ( tags || [] ).map( ( tag, i ) =>
			i === index ? { ...tag, [ key ]: value } : tag
		);
		setAttributes( { tags: next } );
	};

	const removeTag = ( index ) => {
		setAttributes( { tags: ( tags || [] ).filter( ( _, i ) => i !== index ) } );
	};

	const addTag = () => {
		setAttributes( {
			tags: [ ...( tags || [] ), { text: '', url: '', target: '_blank' } ],
		} );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '标签与链接设置', 'niRvana' ) }>
					<TextControl
						label={ __( '标题', 'niRvana' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						__next40pxDefaultSize
					/>
					<SelectControl
						label={ __( '显示形式', 'niRvana' ) }
						value={ displayType }
						options={ [
							{ label: __( '标签', 'niRvana' ), value: 'tag' },
							{ label: __( '链接', 'niRvana' ), value: 'link' },
							{ label: __( '垂直导航', 'niRvana' ), value: 'navigator' },
						] }
						onChange={ ( value ) => setAttributes( { displayType: value } ) }
						__next40pxDefaultSize
					/>
				</PanelBody>
				<PanelBody title={ __( '列表', 'niRvana' ) } initialOpen={ false }>
					{ ( tags || [] ).map( ( tag, i ) => (
						<div
							key={ i }
							style={ {
								borderBottom: '1px solid #e2e4e7',
								paddingBottom: 12,
								marginBottom: 12,
							} }
						>
							<TextControl
								label={ __( '文本', 'niRvana' ) }
								value={ tag.text }
								onChange={ ( value ) => updateTag( i, 'text', value ) }
								__next40pxDefaultSize
							/>
							<TextControl
								label={ __( '地址', 'niRvana' ) }
								value={ tag.url }
								onChange={ ( value ) => updateTag( i, 'url', value ) }
								placeholder="https://example.com/"
								__next40pxDefaultSize
							/>
							<SelectControl
								label={ __( '打开方式', 'niRvana' ) }
								value={ tag.target }
								options={ TARGET_OPTIONS }
								onChange={ ( value ) => updateTag( i, 'target', value ) }
								__next40pxDefaultSize
							/>
							<Button
								variant="tertiary"
								isDestructive
								onClick={ () => removeTag( i ) }
								__next40pxDefaultSize
							>
								{ __( '删除此项', 'niRvana' ) }
							</Button>
						</div>
					) ) }
					<Button
						variant="secondary"
						onClick={ addTag }
						__next40pxDefaultSize
					>
						{ __( '添加一项', 'niRvana' ) }
					</Button>
				</PanelBody>
			</InspectorControls>
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
								{ tag.text || __( '(空)', 'niRvana' ) }
							</a>
						</li>
					) ) }
				</ul>
			</div>
		</>
	);
}
