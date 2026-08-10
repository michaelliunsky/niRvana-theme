import { __ } from '@wordpress/i18n';
import { MediaUpload, useBlockProps } from '@wordpress/block-editor';
import { Button, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { images } = attributes;
	const current = images || [];
	const [ userInput, setUserInput ] = useState( '' );

	const removeImage = ( index ) => {
		const next = JSON.parse( JSON.stringify( current ) );
		next.splice( index, 1 );
		setAttributes( { images: next } );
	};

	const insertExternal = () => {
		const next = JSON.parse( JSON.stringify( current ) );
		next.push( userInput );
		setAttributes( { images: next } );
		setUserInput( '' );
	};

	return (
		<div { ...useBlockProps() }>
			{ current.map( ( url, index ) => (
				<div
					key={ index }
					style={ { backgroundImage: `url(${ url })` } }
					className="panda_album_img"
				>
					<span className="remove" onClick={ () => removeImage( index ) }>
						<i className="fas fa-times" /> 移除
					</span>
				</div>
			) ) }
			{ ( isSelected || current.length < 1 ) && (
				<div className="panda_gallery_inputform">
					<MediaUpload
						onSelect={ ( selected ) => {
							const next = JSON.parse( JSON.stringify( current ) );
							selected.map( ( image ) => next.push( image.url ) );
							setAttributes( { images: next } );
						} }
						allowedTypes="image"
						multiple
						render={ ( { open } ) => (
							<Button variant="secondary" onClick={ open }>
								{ __( '媒体库' ) }
							</Button>
						) }
					/>
					<br />
					<div className="panda_image_input marginT10">
						<TextControl
							value={ userInput }
							onChange={ setUserInput }
							placeholder="https://图片地址"
							__next40pxDefaultSize
						/>
					</div>
					<Button label="外链" icon="edit" variant="secondary" onClick={ insertExternal }>
						{ __( '插入' ) }
					</Button>
				</div>
			) }
		</div>
	);
}
