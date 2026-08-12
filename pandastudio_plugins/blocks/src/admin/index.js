import domReady from '@wordpress/dom-ready';
import { render, createRoot } from '@wordpress/element';
import SettingsApp from './app';

domReady( () => {
	const root = document.getElementById( 'nirvana-settings-root' );
	if ( root ) {
		const element = <SettingsApp />;
		if ( createRoot ) {
			createRoot( root ).render( element );
		} else {
			render( element, root );
		}
	}
} );
