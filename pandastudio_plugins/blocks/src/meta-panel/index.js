import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { useSelect, dispatch } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { ConfigProvider } from 'antd';
import zhCN from 'antd/locale/zh_CN';
import {
	TextField,
	TextareaField,
	NumberField,
	ToggleField,
	ColorField,
	UploadField,
	MultiUploadField,
} from '../admin/base-controls';

function SiteViewPreview( { meta } ) {
	const color = meta.site_color || '#4c7dfe';
	const description = meta.site_description || '';
	const icon = meta.site_icon || '';
	const width = meta.site_icon_width ? Number( meta.site_icon_width ) : 65;
	const height = meta.site_icon_height ? Number( meta.site_icon_height ) : 65;
	return (
		<div
			style={ {
				borderTopWidth: 3,
				borderTopStyle: 'solid',
				borderTopColor: color,
				lineHeight: 1.43,
				boxShadow: '0 1px 5px rgba(215,221,230,.25)',
				backgroundColor: '#fff',
				height: 130,
				width: 250,
				padding: '20px 15px',
				textAlign: 'center',
				position: 'relative',
				boxSizing: 'border-box',
			} }
		>
			<div style={ { fontSize: 15, color: '#475669' } }>站点标题</div>
			<div style={ { fontSize: 13, color: '#99a9bf' } }>{ description }</div>
			<div
				style={ {
					position: 'absolute',
					width: 65,
					height: 65,
					color: '#fff',
					fontSize: 25,
					display: 'flex',
					alignItems: 'center',
					justifyContent: 'center',
					borderRadius: '33px',
					left: '50%',
					marginLeft: -33,
					bottom: -20,
					backgroundColor: color,
					boxShadow: `0 3px 8px ${ color }`,
					backgroundImage: icon ? `url(${ icon })` : 'none',
					backgroundSize: `${ width }px ${ height }px`,
					backgroundRepeat: 'no-repeat',
					backgroundPosition: 'center',
				} }
			>
				{ icon ? '' : '站' }
			</div>
		</div>
	);
}

function MetaField( { field, value, onChange } ) {
	switch ( field.type ) {
		case 'input':
			return <TextField field={ field } value={ value } onChange={ onChange } />;
		case 'textarea':
			return <TextareaField field={ field } value={ value } onChange={ onChange } />;
		case 'inputNumber':
			return <NumberField field={ field } value={ value } onChange={ onChange } />;
		case 'switch':
			return <ToggleField field={ field } value={ value } onChange={ onChange } />;
		case 'colorPicker':
			return <ColorField field={ field } value={ value } onChange={ onChange } />;
		case 'uploader':
			return <UploadField field={ field } value={ value } onChange={ onChange } />;
		case 'multi_uploader':
			return <MultiUploadField field={ field } value={ value } onChange={ onChange } />;
		default:
			return null;
	}
}

function MetaPanel() {
	const postType = useSelect( ( select ) => select( 'core/editor' ).getCurrentPostType(), [] );
	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
	const [ tabs, setTabs ] = useState( [] );

	useEffect( () => {
		if ( ! postType ) {
			return;
		}
		apiFetch( { path: 'pandastudio/framework/get_posttype_and_meta_json' } ).then( ( data ) => {
			const matched = ( data.meta || [] ).filter( ( tab ) => tab.screen.includes( postType ) );
			setTabs( matched );
			if ( matched.length ) {
				dispatch( 'core/edit-post' ).openGeneralSidebar( 'edit-post/document' );
			}
		} );
	}, [ postType ] );

	if ( ! tabs.length ) {
		return null;
	}

	const metaValue = meta || {};
	const setField = ( name ) => ( value ) => setMeta( { ...metaValue, [ name ]: value } );

	return (
		<ConfigProvider locale={ zhCN }>
			<div className="nirvana-ui nirvana-meta">
				{ tabs.map( ( tab, tabIndex ) => (
					<PluginDocumentSettingPanel key={ tabIndex } name={ `nirvana-meta-${ tabIndex }` } title={ tab.title }>
					{ tab.content.map( ( field, fieldIndex ) => {
						if ( field.type === 'view' ) {
							return <SiteViewPreview key={ fieldIndex } meta={ metaValue } />;
						}
						if ( field.name === undefined || field.name === null ) {
							return (
								<div key={ fieldIndex } className="nirvana-settings-field">
									{ field.label ? <div className="nirvana-field-label" dangerouslySetInnerHTML={ { __html: field.label } } /> : null }
									{ field.decoration ? <div className="nirvana-decoration" dangerouslySetInnerHTML={ { __html: field.decoration } } /> : null }
								</div>
							);
						}
						return (
							<div key={ fieldIndex } className="nirvana-settings-field">
								<div className="nirvana-field">
									{ field.label ? <div className="nirvana-field-label" dangerouslySetInnerHTML={ { __html: field.label } } /> : null }
									<MetaField field={ field } value={ metaValue[ field.name ] } onChange={ setField( field.name ) } />
									{ field.decoration ? <div className="nirvana-decoration" dangerouslySetInnerHTML={ { __html: field.decoration } } /> : null }
								</div>
							</div>
						);
					} ) }
				</PluginDocumentSettingPanel>
			) ) }
		</div>
		</ConfigProvider>
	);
}

registerPlugin( 'nirvana-meta-panel', { render: MetaPanel } );
