import { __ } from '@wordpress/i18n';
import { InspectorControls, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

const TYPES = [
	{ label: 'default', value: 'btn-default' },
	{ label: 'info', value: 'btn-primary' },
	{ label: 'success', value: 'btn-success' },
	{ label: 'worning', value: 'btn-warning' },
	{ label: 'error', value: 'btn-danger' },
];

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { id, btn_type, btn_label } = attributes;
	const current = btn_type || 'btn-primary';

	useEffect( () => {
		if ( ! id ) {
			setAttributes( { id: `uuid-timestamp-${ new Date().getTime() }` } );
		}
	}, [] );

	const setType = ( event ) => {
		setAttributes( { btn_type: event.target.attributes.btntype.value } );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '区块设置', 'niRvana' ) }>
					<div className="pf_inspectorTitle">{ __( '按钮颜色', 'niRvana' ) }</div>
					<div className="panda tipSelector">
						{ TYPES.map( ( type ) => (
							<button
								key={ type.value }
								className={ type.label }
								onClick={ setType }
								btntype={ type.value }
								typeselected={ current === type.value ? 'selected' : '' }
							/>
						) ) }
					</div>
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<div className="collapse_wrap">
					<span className={ `btn ${ current }` }>{ btn_label || __( '按钮未定义', 'niRvana' ) }</span>
					{ isSelected && (
						<TextControl
							label={ __( '按钮名称', 'niRvana' ) }
							value={ btn_label || '' }
							placeholder={ __( '请输入...', 'niRvana' ) }
							onChange={ ( value ) => setAttributes( { btn_label: value } ) }
							__next40pxDefaultSize
						/>
					) }
					<div className="collapse_innerBlocks">
						<InnerBlocks />
					</div>
				</div>
			</div>
		</>
	);
}
