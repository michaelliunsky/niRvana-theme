import { __ } from '@wordpress/i18n';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';

const COLORS = [ 'info', 'success', 'worning', 'error' ];

export default function Edit( { attributes, setAttributes } ) {
	const { content, typeClass } = attributes;
	const current = typeClass || 'tip info';
	const blockProps = useBlockProps( { className: current } );

	const setColor = ( event ) => {
		const cls = event.target.className;
		setAttributes( {
			typeClass: current.includes( 'inlineBlock' ) ? `tip ${ cls } inlineBlock` : `tip ${ cls }`,
		} );
	};

	const toggleInline = () => {
		setAttributes( {
			typeClass: current.includes( 'inlineBlock' )
				? current.replace( ' inlineBlock', '' ).replace( 'inlineBlock', '' ).replace( '  ', ' ' )
				: `${ current } inlineBlock`,
		} );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '区块设置', 'niRvana' ) }>
					<div className="pf_inspectorTitle">{ __( '选择颜色', 'niRvana' ) }</div>
					<div className="panda tipSelector">
						{ COLORS.map( ( color ) => (
							<button
								key={ color }
								className={ color }
								onClick={ setColor }
								typeselected={ current.includes( color ) ? 'selected' : '' }
							/>
						) ) }
					</div>
					<div className="pf_inspectorTitle">{ __( '全宽样式', 'niRvana' ) }</div>
					<div className="panda tipSelector">
						<button
							className="inline"
							onClick={ toggleInline }
							typeinline={ current.includes( 'inlineBlock' ) ? 'true' : 'false' }
						/>
					</div>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<RichText
					onChange={ ( value ) => setAttributes( { content: value } ) }
					value={ content }
					placeholder={ __( '请输入...', 'niRvana' ) }
				/>
			</div>
		</>
	);
}
