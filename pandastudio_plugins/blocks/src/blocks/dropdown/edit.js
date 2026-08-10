import { __, sprintf } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { Button, PanelBody, TextControl } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

const TYPES = [
	{ label: 'default', value: 'btn-default' },
	{ label: 'info', value: 'btn-primary' },
	{ label: 'success', value: 'btn-success' },
	{ label: 'worning', value: 'btn-warning' },
	{ label: 'error', value: 'btn-danger' },
];

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { id, btn_type, btn_label, lists } = attributes;
	const current = btn_type || 'btn-default';
	const items = lists || [];

	useEffect( () => {
		if ( ! id ) {
			setAttributes( { id: `uuid-timestamp-${ new Date().getTime() }` } );
		}
	}, [] );

	const setType = ( event ) => {
		setAttributes( { btn_type: event.target.attributes.btntype.value } );
	};

	const updateList = ( index, key, value ) => {
		const next = JSON.parse( JSON.stringify( items ) );
		next[ index ][ key ] = value;
		setAttributes( { lists: next } );
	};

	const removeItem = ( index ) => {
		const next = JSON.parse( JSON.stringify( items ) );
		next.splice( index, 1 );
		setAttributes( { lists: next } );
	};

	const addItem = () => {
		const next = JSON.parse( JSON.stringify( items ) );
		next.push( { label: '', href: '' } );
		setAttributes( { lists: next } );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '区块设置' ) }>
					<div className="pf_inspectorTitle">{ __( '按钮颜色' ) }</div>
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
					<div className="pf_inspectorTitle">{ __( '下拉列表' ) }</div>
					{ items.map( ( item, index ) => (
						<div key={ index }>
							<TextControl
								label={ sprintf( __( '项目%s：' ), index + 1 ) }
								value={ item.label || '' }
								placeholder={ __( '请输入名称' ) }
								onChange={ ( value ) => updateList( index, 'label', value ) }
								__next40pxDefaultSize
							/>
							<TextControl
								value={ item.href || '' }
								placeholder="https://"
								onChange={ ( value ) => updateList( index, 'href', value ) }
								__next40pxDefaultSize
							/>
							<div style={ { textAlign: 'right' } }>
								<Button variant="secondary" isSmall onClick={ () => removeItem( index ) }>
									{ __( '移除' ) }
								</Button>
							</div>
						</div>
					) ) }
					<Button variant="primary" isSmall onClick={ addItem }>
						{ __( '增加列表项' ) }
					</Button>
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<div className="collapse_wrap">
					<span className={ `btn ${ current }` }>{ btn_label || __( '按钮未定义' ) }</span>
					{ isSelected && (
						<TextControl
							label={ __( '按钮名称' ) }
							value={ btn_label || '' }
							placeholder={ __( '请输入...' ) }
							onChange={ ( value ) => setAttributes( { btn_label: value } ) }
							__next40pxDefaultSize
						/>
					) }
				</div>
			</div>
		</>
	);
}
