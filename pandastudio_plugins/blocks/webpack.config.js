const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );
const path = require( 'path' );

const isArray = Array.isArray( defaultConfig );
const configs = isArray ? defaultConfig : [ defaultConfig ];

const result = configs.map( ( config ) => {
	if ( config.experiments?.outputModule ) {
		return config;
	}
	return {
		...config,
		entry: {
			index: path.resolve( __dirname, 'src/index.js' ),
			admin: path.resolve( __dirname, 'src/admin/index.js' ),
			'meta-panel': path.resolve( __dirname, 'src/meta-panel/index.js' ),
		},
	};
} );

module.exports = isArray ? result : result[ 0 ];
