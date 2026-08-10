import { __ } from '@wordpress/i18n';
import { applyFormat, removeFormat, registerFormatType } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { ColorIndicator, ColorPicker, Popover, TabPanel, ToggleControl } from '@wordpress/components';
import { getRectangleFromRange } from '@wordpress/dom';
import { useRef, useState } from '@wordpress/element';

const MARK_FORMAT = 'pandastudio/mark';

const PALETTE = [
	'#1f2d3d', '#20a0ff', '#13ce66', '#f7ba2a', '#ff4949', '#eef1f7',
	'#b5e8ff', '#a9f3d5', '#fdeebb', '#ffcaca', '#d5dae0', '#9bd9ff',
	'#8fecbf', '#fce3a1', '#ffb2b2', '#b0b7c1', '#70bcff', '#68db99',
	'#f9cd77', '#ff8586', '#8892a0', '#418fff', '#3fbf66', '#f3a845',
	'#ff5655', '#677384', '#1e5bff', '#1d9832', '#e9781e', '#ff2828',
	'#49576a', '#0026ea', '#016e09', '#e04b01', '#dd0000', '#3e4b5b',
	'#0122db', '#015f07', '#bf3f01', '#db0303', '#2d3743', '#0119a0',
	'#014505', '#8d2f01', '#a10202', '#000', '#fff',
];

const markIcon = (
	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
		<path
			id="pandastudio_mark"
			className="cls-1"
			d="M10.511,16.2l-6.5,2.789V4.557A2.494,2.494,0,0,1,6.448,2.011h8.125a2.494,2.494,0,0,1,2.438,2.547V18.989Zm1.121-8.07L10.511,4.557,9.389,8.13,5.739,8.123l2.957,2.2L7.562,13.892l2.949-2.212,2.949,2.212-1.135-3.568,2.957-2.2Z"
			transform="translate(-0.5 -0.5)"
		/>
	</svg>
);

function parseStyle( styleString ) {
	const el = document.createElement( 'div' );
	el.setAttribute( 'style', styleString || '' );
	return { color: el.style.color, background: el.style.backgroundColor };
}

function Edit( { isActive, value, onChange, activeAttributes } ) {
	const [ visible, setVisible ] = useState( false );
	const [ textColor, setTextColor ] = useState( '' );
	const [ bgColor, setBgColor ] = useState( '' );
	const [ block, setBlock ] = useState( false );
	const [ tab, setTab ] = useState( 'color' );
	const [ currentColor, setCurrentColor ] = useState( '' );
	const [ showPicker, setShowPicker ] = useState( true );
	const anchor = useRef( null );

	const stateRef = useRef( { textColor, bgColor, block } );
	stateRef.current = { textColor, bgColor, block };

	const wordsSelected = () => value.start !== value.end;

	const setPopoverAnchor = () => {
		const selection = window.getSelection();
		const range = selection.rangeCount > 0 ? selection.getRangeAt( 0 ) : null;
		anchor.current = getRectangleFromRange( range );
	};

	const reloadPicker = () => {
		setShowPicker( false );
		window.setTimeout( () => setShowPicker( true ), 0 );
	};

	const showModal = () => {
		const parsed = parseStyle( activeAttributes.style );
		setBlock( activeAttributes.block === 'true' );
		setTab( 'color' );
		setCurrentColor( parsed.color );
		setTextColor( parsed.color );
		setBgColor( parsed.background );
		setVisible( true );
		setPopoverAnchor();
	};

	const applyMark = () => {
		let styleStr = '';
		if ( stateRef.current.textColor ) styleStr += `color: ${ stateRef.current.textColor };`;
		if ( stateRef.current.bgColor ) styleStr += `background-color: ${ stateRef.current.bgColor };`;
		const attributes = { style: styleStr };
		if ( stateRef.current.block ) attributes.block = 'true';
		onChange( applyFormat( value, { type: MARK_FORMAT, attributes } ) );
	};

	const setColor = ( color, reload = false ) => {
		setCurrentColor( color );
		if ( tab === 'color' ) setTextColor( color );
		else setBgColor( color );
		if ( reload ) reloadPicker();
		window.setTimeout( applyMark, 0 );
	};

	const resetColor = () => {
		onChange( removeFormat( value, MARK_FORMAT ) );
		setVisible( false );
	};

	const tabChanged = ( next ) => {
		setTab( next );
		setCurrentColor( next === 'color' ? stateRef.current.textColor : stateRef.current.bgColor );
		reloadPicker();
	};

	const blockChanged = ( checked ) => {
		setBlock( checked );
		setColor( currentColor );
	};

	return (
		<>
			<RichTextToolbarButton
				icon={ markIcon }
				title={ __( '标记' ) }
				onClick={ showModal }
				isActive={ isActive }
				shortcutType="primary"
				isDisabled={ ! wordsSelected() }
			/>
			{ visible && (
				<Popover
					anchorRect={ anchor.current }
					position="bottom center"
					className="pandastudio_mark_popover"
					onClickOutside={ () => setVisible( false ) }
				>
					<TabPanel
						className="pandastudio_mark_tab"
						activeClass="active-tab"
						onSelect={ tabChanged }
						tabs={ [
							{ name: 'color', title: __( '文字' ), className: 'tab-color' },
							{ name: 'background', title: __( '背景' ), className: 'tab-background' },
						] }
					>
						{ () => (
							<div>
								{ __( '显示为块' ) }
								<ToggleControl checked={ block } onChange={ blockChanged } />
								<button className="enter-button" onClick={ resetColor }>
									<svg
										aria-hidden="true"
										focusable="false"
										data-prefix="far"
										data-icon="undo-alt"
										role="img"
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 512 512"
										className="svg-inline--fa fa-undo-alt fa-w-16 fa-2x"
									>
										<path
											fill="currentColor"
											d="M28.485 28.485L80.65 80.65C125.525 35.767 187.515 8 255.999 8 392.66 8 504.1 119.525 504 256.185 503.9 393.067 392.905 504 256 504c-63.926 0-122.202-24.187-166.178-63.908-5.113-4.618-5.353-12.561-.482-17.433l19.738-19.738c4.498-4.498 11.753-4.785 16.501-.552C160.213 433.246 205.895 452 256 452c108.321 0 196-87.662 196-196 0-108.321-87.662-196-196-196-54.163 0-103.157 21.923-138.614 57.386l54.128 54.129c7.56 7.56 2.206 20.485-8.485 20.485H20c-6.627 0-12-5.373-12-12V36.971c0-10.691 12.926-16.045 20.485-8.486z"
											className=""
										/>
									</svg>
								</button>
							</div>
						) }
					</TabPanel>
					<div className="my_color_selector">
						<div className="palette">
							{ PALETTE.map( ( color ) => (
								<ColorIndicator
									key={ color }
									colorValue={ color }
									onClick={ () => setColor( color, true ) }
								/>
							) ) }
							<span className="component-color-indicator" onClick={ () => setColor( '', true ) } />
						</div>
						<div className="picker">
							{ showPicker && (
								<ColorPicker
									color={ currentColor }
									disableAlpha
									onChange={ ( color ) => setColor( color.hex ) }
								/>
							) }
						</div>
					</div>
				</Popover>
			) }
		</>
	);
}

registerFormatType( MARK_FORMAT, {
	title: __( '标记' ),
	tagName: 'span',
	className: 'pandastudio-mark',
	attributes: {
		style: 'style',
		block: 'inline-block',
	},
	edit: Edit,
} );
