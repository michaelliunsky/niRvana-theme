import { __ } from '@wordpress/i18n';
import { applyFormat, removeFormat, registerFormatType } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Button, ButtonGroup, Popover, TextControl } from '@wordpress/components';
import { getRectangleFromRange } from '@wordpress/dom';
import { useRef, useState } from '@wordpress/element';

const DESCRIPTION_FORMAT = 'pandastudio/description';

const confirmIcon = (
	<svg
		aria-hidden="true"
		role="img"
		focusable="false"
		className="dashicon dashicons-editor-break"
		xmlns="http://www.w3.org/2000/svg"
		width="20"
		height="20"
		viewBox="0 0 20 20"
	>
		<path d="M16 4h2v9H7v3l-5-4 5-4v3h9V4z" />
	</svg>
);

function Edit( { isActive, value, onChange, activeAttributes } ) {
	const [ visible, setVisible ] = useState( false );
	const [ text, setText ] = useState( '' );
	const [ placement, setPlacement ] = useState( 'top' );
	const anchor = useRef( null );

	const wordsSelected = () => value.start !== value.end;

	const setPopoverAnchor = () => {
		const selection = window.getSelection();
		const range = selection.rangeCount > 0 ? selection.getRangeAt( 0 ) : null;
		anchor.current = getRectangleFromRange( range );
	};

	const showModal = () => {
		setText( activeAttributes.text || '' );
		setPlacement( activeAttributes.placement || 'top' );
		setVisible( true );
		setPopoverAnchor();
	};

	const apply = ( textValue, placementValue ) => {
		window.setTimeout( () => {
			if ( textValue ) {
				onChange(
					applyFormat( value, {
						type: DESCRIPTION_FORMAT,
						attributes: { text: textValue, placement: placementValue },
					} )
				);
			} else {
				onChange( removeFormat( value, DESCRIPTION_FORMAT ) );
			}
		}, 0 );
	};

	const setPlacementValue = ( next ) => {
		setPlacement( next );
		apply( text, next );
	};

	const keyDown = ( event ) => {
		if ( event.keyCode === 13 ) {
			apply( text, placement );
			setVisible( false );
		} else if ( event.keyCode === 27 ) {
			setVisible( false );
		}
	};

	return (
		<>
			<RichTextToolbarButton
				icon="admin-comments"
				title={ __( '弹出提示' ) }
				onClick={ showModal }
				isActive={ isActive }
				shortcutType="primary"
				isDisabled={ ! wordsSelected() }
			/>
			{ visible && (
				<Popover
					anchorRect={ anchor.current }
					position="bottom center"
					className="pandastudio_format_description_popover"
					onClickOutside={ () => setVisible( false ) }
				>
					<div style={ { padding: '10px' } }>
						<div className="inputerWrapper">
							<div className="inputer">
								<TextControl
									value={ text }
									placeholder={ __( '请输入提示语...' ) }
									onKeyDown={ keyDown }
									onChange={ setText }
									__next40pxDefaultSize
								/>
							</div>
							<button
								className="confirm_button"
								onClick={ () => {
									apply( text, placement );
									setVisible( false );
								} }
							>
								{ confirmIcon }
							</button>
						</div>
						{ text && (
							<ButtonGroup>
								<Button
									onClick={ () => setPlacementValue( 'top' ) }
									variant={ placement === 'top' ? 'primary' : 'secondary' }
								>
									{ __( '顶部' ) }
								</Button>
								<Button
									onClick={ () => setPlacementValue( 'bottom' ) }
									variant={ placement === 'bottom' ? 'primary' : 'secondary' }
								>
									{ __( '底部' ) }
								</Button>
								<Button
									onClick={ () => setPlacementValue( 'left' ) }
									variant={ placement === 'left' ? 'primary' : 'secondary' }
								>
									{ __( '左侧' ) }
								</Button>
								<Button
									onClick={ () => setPlacementValue( 'right' ) }
									variant={ placement === 'right' ? 'primary' : 'secondary' }
								>
									{ __( '右侧' ) }
								</Button>
							</ButtonGroup>
						) }
					</div>
				</Popover>
			) }
		</>
	);
}

registerFormatType( DESCRIPTION_FORMAT, {
	title: __( '弹出提示' ),
	tagName: 'span',
	className: 'pandastudio_format_description',
	attributes: {
		text: 'data-description',
		placement: 'data-placement',
	},
	edit: Edit,
} );
