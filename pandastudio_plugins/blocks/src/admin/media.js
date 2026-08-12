export function openMedia( { multiple = false, title = '上传', buttonText = '插入' } = {} ) {
	const frame = window.wp.media( {
		title,
		button: { text: buttonText },
		multiple,
	} );
	return new Promise( ( resolve ) => {
		frame.on( 'select', () => {
			const selection = frame.state().get( 'selection' );
			if ( multiple ) {
				resolve( selection.toJSON().map( ( item ) => item.url ) );
			} else {
				resolve( selection.first().toJSON().url );
			}
		} );
		frame.open();
	} );
}
