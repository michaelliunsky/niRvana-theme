import { Table, Card, Button, Space } from 'antd';
import { __ } from '@wordpress/i18n';
import {
	TextField,
	TextareaField,
	NumberField,
	SliderField,
	ToggleField,
	ColorField,
	UploadField,
	MultiUploadField,
	RadioField,
	SelectField,
	CategoryField,
	SingleSelectField,
	SubControl,
} from './base-controls';

function MultiTypesField( { field, value, onChange, categories } ) {
	const items = Array.isArray( value ) ? value : [];
	const types = field.types || [];
	const setItem = ( index, key, val ) => {
		const next = items.map( ( item, i ) => ( i === index ? { ...item, [ key ]: val } : item ) );
		onChange( next );
	};
	const move = ( index, dir ) => {
		const next = [ ...items ];
		const target = index + dir;
		[ next[ index ], next[ target ] ] = [ next[ target ], next[ index ] ];
		onChange( next );
	};
	const remove = ( index ) => onChange( items.filter( ( _, i ) => i !== index ) );
	const add = () => {
		const empty = {};
		types.forEach( ( t ) => ( empty[ t.name ] = '' ) );
		onChange( [ ...items, empty ] );
	};

	if ( field.displayAsTable ) {
		const columns = [
			{
				title: '#',
				key: 'index',
				width: 55,
				render: ( _, record ) => record.__index + 1,
			},
			...types.map( ( t ) => ( {
				title: t.label || t.name,
				key: t.name,
				render: ( _, record ) => (
					<SubControl type={ t } value={ record[ t.name ] } onChange={ ( v ) => setItem( record.__index, t.name, v ) } categories={ categories } />
				),
			} ) ),
			{
				title: __( '操作' ),
				key: 'actions',
				width: 150,
				render: ( _, record ) => (
					<Space size={ 4 }>
						<Button size="small" disabled={ record.__index === 0 } onClick={ () => move( record.__index, -1 ) }>
							↑
						</Button>
						<Button size="small" disabled={ record.__index === items.length - 1 } onClick={ () => move( record.__index, 1 ) }>
							↓
						</Button>
						<Button size="small" danger onClick={ () => remove( record.__index ) }>
							{ __( '删除' ) }
						</Button>
					</Space>
				),
			},
		];
		const dataSource = items.map( ( item, index ) => ( { ...item, __index: index, key: index } ) );
		return (
			<div>
				<Table columns={ columns } dataSource={ dataSource } pagination={ false } size="small" bordered />
				<Button type="primary" size="small" style={ { marginTop: 8 } } onClick={ add }>
					{ field.subLabel || __( '添加' ) }
				</Button>
			</div>
		);
	}

	return (
		<div>
			{ items.map( ( item, index ) => (
				<Card
					key={ index }
					size="small"
					title={ `${ field.subLabel || '' } ${ index + 1 }` }
					extra={
						<Space size={ 4 }>
							<Button size="small" disabled={ index === 0 } onClick={ () => move( index, -1 ) }>
								{ __( '上移' ) }
							</Button>
							<Button size="small" disabled={ index === items.length - 1 } onClick={ () => move( index, 1 ) }>
								{ __( '下移' ) }
							</Button>
							<Button size="small" danger onClick={ () => remove( index ) }>
								{ __( '移除' ) }
							</Button>
						</Space>
					}
					style={ { marginBottom: 12 } }
				>
					{ types.map( ( t ) => (
						<div key={ t.name } className="nirvana-multitypes-row">
							{ t.label ? <div className="nirvana-multitypes-label">{ t.label }</div> : null }
							<SubControl type={ t } value={ item[ t.name ] } onChange={ ( v ) => setItem( index, t.name, v ) } categories={ categories } />
						</div>
					) ) }
				</Card>
			) ) }
			<Button type="primary" onClick={ add }>
				{ field.subLabel || __( '添加' ) }
			</Button>
		</div>
	);
}

export function SchemaField( { field, value, onChange, categories, onImport, onExport, onClear } ) {
	switch ( field.type ) {
		case 'input':
			return <TextField field={ field } value={ value } onChange={ onChange } />;
		case 'textarea':
			return <TextareaField field={ field } value={ value } onChange={ onChange } />;
		case 'inputNumber':
			return <NumberField field={ field } value={ value } onChange={ onChange } />;
		case 'slider':
			return <SliderField field={ field } value={ value } onChange={ onChange } />;
		case 'switch':
			return <ToggleField field={ field } value={ value } onChange={ onChange } />;
		case 'colorPicker':
			return <ColorField field={ field } value={ value } onChange={ onChange } />;
		case 'uploader':
			return <UploadField field={ field } value={ value } onChange={ onChange } />;
		case 'multi_uploader':
			return <MultiUploadField field={ field } value={ value } onChange={ onChange } />;
		case 'radio':
			return <RadioField field={ field } value={ value } onChange={ onChange } />;
		case 'select':
			return <SelectField field={ field } value={ value } onChange={ onChange } />;
		case 'categorySelect':
		case 'categoriesSelect':
			return <CategoryField field={ field } value={ value } onChange={ onChange } categories={ categories } />;
		case 'singleSelect':
			return <SingleSelectField field={ field } value={ value } onChange={ onChange } />;
		case 'multitypes':
			return <MultiTypesField field={ field } value={ value } onChange={ onChange } categories={ categories } />;
		case 'uploadData':
			return (
				<Button onClick={ onImport }>
					{ __( '上传配置' ) }
				</Button>
			);
		case 'downloadData':
			return (
				<Button onClick={ onExport }>
					{ __( '下载配置' ) }
				</Button>
			);
		case 'clearData':
			return (
				<Button danger onClick={ onClear }>
					{ __( '清空' ) }
				</Button>
			);
		default:
			return null;
	}
}
