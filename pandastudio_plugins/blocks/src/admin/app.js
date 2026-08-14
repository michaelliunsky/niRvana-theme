import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { ConfigProvider, Tabs, Form, Button, Spin, Alert } from 'antd';
import zhCN from 'antd/locale/zh_CN';
import { SchemaField } from './controls';

function collectNames( tabs ) {
	const names = {};
	( tabs || [] ).forEach( ( tab ) =>
		tab.content.forEach( ( field ) => {
			if ( field.name !== null && field.name !== undefined ) {
				names[ field.name ] = '';
			}
		} )
	);
	return names;
}

function normalizeValues( tabs, current ) {
	const out = {};
	( tabs || [] ).forEach( ( tab ) =>
		tab.content.forEach( ( field ) => {
			if ( field.name === null || field.name === undefined ) {
				return;
			}
			const raw = current[ field.name ];
			switch ( field.type ) {
				case 'slider':
				case 'inputNumber':
					out[ field.name ] = raw ? parseFloat( raw ) : 0;
					break;
				case 'singleSelect':
				case 'categorySelect':
					out[ field.name ] = raw === '' || raw === undefined || raw === null ? '' : Number( raw );
					break;
				case 'categoriesSelect':
					out[ field.name ] = Array.isArray( raw ) ? raw.map( Number ) : [];
					break;
				case 'multitypes':
					out[ field.name ] = Array.isArray( raw ) ? raw : [];
					break;
				case 'select':
					out[ field.name ] = raw === undefined || raw === null ? ( field.multiple ? [] : '' ) : raw;
					break;
				default:
					out[ field.name ] = raw === undefined || raw === null ? '' : raw;
			}
		} )
	);
	return out;
}

function gearShow( field, values ) {
	const gn = field.gear_name;
	const gv = field.gear_value;
	if ( gn === null || gn === undefined || gn === '' || gv === null || gv === undefined ) {
		return true;
	}
	return values[ gn ] === gv;
}

export default function SettingsApp() {
	const [ tabs, setTabs ] = useState( [] );
	const [ values, setValues ] = useState( {} );
	const [ categories, setCategories ] = useState( [] );
	const [ status, setStatus ] = useState( 'loading' );
	const [ error, setError ] = useState( '' );
	const [ notice, setNotice ] = useState( null );

	useEffect( () => {
		let cancelled = false;
		async function load() {
			try {
				const schema = await apiFetch( { path: 'pandastudio/framework/get_option_json' } );
				const names = collectNames( schema );
				const current = await apiFetch( { path: 'pandastudio/framework/get_option', method: 'POST', data: names } );
				const selector = window.pandastudio_framework && window.pandastudio_framework.categorySelector;
				if ( cancelled ) {
					return;
				}
				setTabs( schema || [] );
				setValues( normalizeValues( schema, current ) );
				setCategories( ( selector || [] ).map( ( c ) => ( { id: Number( c.value ), name: c.label } ) ) );
				setStatus( 'ready' );
			} catch ( e ) {
				if ( ! cancelled ) {
					setStatus( 'error' );
					setError( e && e.message ? e.message : __( '数据加载失败' ) );
				}
			}
		}
		load();
		return () => {
			cancelled = true;
		};
	}, [] );

	const setField = ( name ) => ( value ) => {
		setValues( ( prev ) => ( { ...prev, [ name ]: value } ) );
	};

	const updateOption = async () => {
		setNotice( null );
		try {
			const res = await apiFetch( { path: 'pandastudio/framework/update_option', method: 'POST', data: values } );
			if ( res && res.state ) {
				setNotice( { type: 'success', text: __( '已保存' ) } );
			} else {
				setNotice( { type: 'error', text: __( '保存失败' ) } );
			}
		} catch ( e ) {
			setNotice( { type: 'error', text: __( '保存失败' ) } );
		}
	};

	const exportData = async () => {
		try {
			const names = {};
			Object.keys( values ).forEach( ( name ) => ( names[ name ] = '' ) );
			const current = await apiFetch( { path: 'pandastudio/framework/get_option', method: 'POST', data: names } );
			const blob = new Blob( [ JSON.stringify( current ) ] );
			const url = URL.createObjectURL( blob );
			const a = document.createElement( 'a' );
			a.download = `${ window.nirvanaSettings.blogName }_settings.json`;
			a.href = url;
			a.click();
			URL.revokeObjectURL( url );
		} catch ( e ) {
			setNotice( { type: 'error', text: __( '数据下载失败' ) } );
		}
	};

	const importData = () => {
		const input = document.createElement( 'input' );
		input.type = 'file';
		input.accept = '.json';
		input.onchange = () => {
			const file = input.files[ 0 ];
			if ( ! file ) {
				return;
			}
			const reader = new FileReader();
			reader.onload = async () => {
				try {
					const data = JSON.parse( reader.result );
					const payload = {};
					Object.keys( values ).forEach( ( name ) => {
						payload[ name ] = data[ name ] === undefined ? '' : data[ name ];
					} );
					const res = await apiFetch( { path: 'pandastudio/framework/update_option', method: 'POST', data: payload } );
					if ( res && res.state ) {
						setNotice( { type: 'success', text: __( '导入成功，正在刷新...' ) } );
						window.setTimeout( () => window.location.reload(), 500 );
					} else {
						setNotice( { type: 'error', text: __( '导入失败' ) } );
					}
				} catch ( e ) {
					setNotice( { type: 'error', text: __( '数据类型错误' ) } );
				}
			};
			reader.readAsText( file );
		};
		input.click();
	};

	const clearData = async () => {
		const payload = {};
		Object.keys( values ).forEach( ( name ) => ( payload[ name ] = '' ) );
		try {
			const res = await apiFetch( { path: 'pandastudio/framework/update_option', method: 'POST', data: payload } );
			if ( res && res.state ) {
				setNotice( { type: 'success', text: __( '已清空，正在刷新...' ) } );
				window.setTimeout( () => window.location.reload(), 500 );
			}
		} catch ( e ) {
			setNotice( { type: 'error', text: __( '清空数据失败' ) } );
		}
	};

	if ( status === 'loading' ) {
		return (
			<div className="nirvana-ui">
				<Spin style={ { marginTop: 40 } } />
			</div>
		);
	}
	if ( status === 'error' ) {
		return (
			<div className="nirvana-ui">
				<Alert type="error" showIcon message={ error } />
			</div>
		);
	}

	const tabsConfig = tabs.map( ( tab, index ) => ( {
		key: String( index ),
		label: tab.title,
		children: (
			<Form layout="horizontal" labelCol={ { style: { width: 130 } } } wrapperCol={ { style: { flex: 1 } } }>
				{ tab.content
					.filter( ( field ) => gearShow( field, values ) )
					.map( ( field, fieldIndex ) => {
						const hasName = field.name !== null && field.name !== undefined;
						if ( ! hasName && ! field.type ) {
							return (
								<div key={ fieldIndex } className="nirvana-settings-section">
									{ field.label ? <div className="nirvana-field-label" dangerouslySetInnerHTML={ { __html: field.label } } /> : null }
									{ field.decoration ? <div className="nirvana-decoration" dangerouslySetInnerHTML={ { __html: field.decoration } } /> : null }
								</div>
							);
						}
						return (
							<Form.Item
								key={ fieldIndex }
								label={ field.label ? <span dangerouslySetInnerHTML={ { __html: field.label } } /> : ' ' }
							>
								<SchemaField
									field={ field }
									value={ hasName ? values[ field.name ] : undefined }
									onChange={ hasName ? setField( field.name ) : () => {} }
									categories={ categories }
									onImport={ importData }
									onExport={ exportData }
									onClear={ clearData }
								/>
								{ field.decoration ? (
									<div className="nirvana-decoration" dangerouslySetInnerHTML={ { __html: field.decoration } } />
								) : null }
							</Form.Item>
						);
					} ) }
				<Form.Item label=" ">
					<div className="nirvana-settings-save">
						<Button type="primary" onClick={ updateOption }>
							{ __( '保存全部' ) }
						</Button>
						{ notice ? <Alert type={ notice.type } message={ notice.text } showIcon style={ { margin: 0 } } /> : null }
					</div>
				</Form.Item>
			</Form>
		),
	} ) );

	return (
		<ConfigProvider locale={ zhCN }>
			<div className="nirvana-ui">
				<Tabs items={ tabsConfig } />
			</div>
		</ConfigProvider>
	);
}
