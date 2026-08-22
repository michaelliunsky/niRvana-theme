import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { Button, Disabled, Modal, PanelBody, Spinner, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	const { post_id, align } = attributes;
	const [ showModal, setShowModal ] = useState( false );
	const [ searchText, setSearchText ] = useState( '' );
	const [ list, setList ] = useState( [] );
	const [ isSearching, setIsSearching ] = useState( false );

	const search = () => {
		setIsSearching( true );
		window.jQuery.ajax( {
			url: window.pandastudio_framework.route + 'pandastudio/framework/wp_query',
			type: 'POST',
			beforeSend: ( request ) => {
				request.setRequestHeader( 'X-WP-Nonce', window.pandastudio_framework.nonce );
			},
			data: JSON.stringify( { keyword: searchText } ),
		} )
			.done( ( result ) => setList( result ) )
			.fail( () => alert( __( '网络异常' ) ) )
			.always( () => setIsSearching( false ) );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '设置文章ID' ) }>
					<Button variant="primary" onClick={ () => setShowModal( true ) }>
						{ post_id ? __( '更改文章ID' ) : __( '选择文章ID' ) }
					</Button>
					{ post_id && (
						<p style={ { fontStyle: 'italic', marginTop: '5px' } }>
							{ __( '已选文章ID：' ) + post_id }
						</p>
					) }
					{ showModal && (
						<Modal title={ __( '选择文章ID' ) } onRequestClose={ () => setShowModal( false ) }>
							<div className="clearfix">
								<div className="floatL">
									<TextControl
										value={ searchText }
										onChange={ setSearchText }
										placeholder={ __( '请输入文章关键字...' ) }
										__next40pxDefaultSize
									/>
								</div>
								<div className="floatR marginT2 marginL5">
									<Button variant="secondary" onClick={ search }>
										{ __( '检索文章' ) }
									</Button>
								</div>
							</div>
							<div className="marginT10 marginB10">
								{ isSearching ? (
									<Spinner />
								) : list.length > 0 ? (
									<div>
										{ __( '请选择：' ) }
										{ list.map( ( item ) => (
											<div className="marginB5 marginT5" key={ item.label + item.value }>
												<Button
													variant={ item.value == post_id ? 'primary' : 'secondary' }
													onClick={ () => setAttributes( { post_id: item.value } ) }
												>
													{ item.label }
												</Button>
											</div>
										) ) }
									</div>
								) : (
									__( '暂无文章' )
								) }
							</div>
						</Modal>
					) }
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<Disabled>
					<ServerSideRender block="pandastudio/single" attributes={ { post_id, align } } />
				</Disabled>
			</div>
		</>
	);
}
