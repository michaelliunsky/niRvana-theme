import { useState, useEffect, useRef } from '@wordpress/element';
import {
	TextControl,
	TextareaControl,
	RangeControl,
	ToggleControl,
	ColorPalette,
	Button,
	SelectControl,
	RadioControl,
	ComboboxControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { openMedia } from './media';

function TextField( { field, value, onChange } ) {
	return <TextControl value={ value || '' } onChange={ onChange } placeholder={ field.placeholder } />;
}

function TextareaField( { field, value, onChange } ) {
	return (
		<TextareaControl
			value={ value || '' }
			onChange={ onChange }
			rows={ field.rows || 3 }
			placeholder={ field.placeholder }
		/>
	);
}

function NumberField( { field, value, onChange } ) {
	return (
		<TextControl
			type="number"
			value={ value === '' || value === undefined || value === null ? '' : String( value ) }
			min={ field.min }
			max={ field.max }
			step={ field.step }
			onChange={ ( v ) => onChange( v === '' ? '' : Number( v ) ) }
		/>
	);
}

function SliderField( { field, value, onChange } ) {
	return (
		<RangeControl value={ value || 0 } min={ field.min } max={ field.max } step={ field.step } onChange={ onChange } />
	);
}

function ToggleField( { field, value, onChange } ) {
	return (
		<ToggleControl checked={ value === 'checked' } onChange={ ( checked ) => onChange( checked ? 'checked' : '' ) } />
	);
}

function ColorField( { field, value, onChange } ) {
	return <ColorPalette value={ value || undefined } onChange={ onChange } />;
}

function UploadField( { field, value, onChange } ) {
	const pick = async () => {
		const url = await openMedia( { multiple: false } );
		onChange( url );
	};
	return (
		<div className="nirvana-uploader">
			<TextControl
				value={ value || '' }
				onChange={ onChange }
				placeholder={ field.placeholder || '点击按钮上传或在此处粘贴外链地址' }
			/>
			<div className="nirvana-uploader-actions">
				<Button variant="secondary" onClick={ pick }>
					{ __( '上传' ) }
				</Button>
				{ value ? (
					<Button variant="tertiary" isDestructive onClick={ () => onChange( '' ) }>
						{ __( '移除' ) }
					</Button>
				) : null }
			</div>
			{ value && field.showImage !== false ? (
				<img className="nirvana-uploader-preview" src={ value } alt="" />
			) : null }
		</div>
	);
}

function MultiUploadField( { field, value, onChange } ) {
	const images = Array.isArray( value ) ? value : [];
	const pick = async () => {
		const urls = await openMedia( { multiple: true } );
		onChange( images.concat( urls ) );
	};
	const addExternal = () => {
		const url = window.prompt( '请输入图片地址', '' );
		if ( url ) {
			onChange( images.concat( url ) );
		}
	};
	return (
		<div className="nirvana-multi-uploader">
			<div className="nirvana-multi-thumbs">
				{ images.map( ( src, index ) => (
					<div
						key={ index }
						className="nirvana-multi-thumb"
						style={ {
							backgroundImage: `url(${ src })`,
							width: 100,
							height: 100,
							borderRadius: 4,
							backgroundSize: 'cover',
							backgroundPosition: 'center',
							cursor: 'pointer',
							border: '1px solid #dcdcde',
						} }
						onClick={ () => onChange( images.filter( ( _, i ) => i !== index ) ) }
					/>
				) ) }
			</div>
			<div className="nirvana-multi-actions">
				<Button variant="secondary" onClick={ addExternal }>
					{ __( '输入外链' ) }
				</Button>
				<Button variant="secondary" onClick={ pick }>
					{ __( '批量上传' ) }
				</Button>
				{ images.length > 1 ? (
					<Button variant="tertiary" isDestructive onClick={ () => onChange( [] ) }>
						{ __( '全部移除' ) }
					</Button>
				) : null }
			</div>
		</div>
	);
}

function RadioField( { field, value, onChange } ) {
	return (
		<RadioControl
			selected={ value }
			options={ ( field.radios || [] ).map( ( radio ) => ( { label: radio.label, value: radio.value } ) ) }
			onChange={ onChange }
		/>
	);
}

function SelectField( { field, value, onChange } ) {
	const options = ( field.selects || [] ).map( ( select ) => ( {
		label: select.description ? `${ select.label } — ${ select.description }` : select.label,
		value: select.value,
	} ) );
	const multiple = !! field.multiple;
	return (
		<SelectControl
			value={ value === undefined || value === null ? ( multiple ? [] : '' ) : value }
			options={ options }
			onChange={ onChange }
			multiple={ multiple }
			placeholder={ field.placeholder }
		/>
	);
}

function categoryOptions( categories ) {
	return categories.map( ( c ) => ( { label: c.name, value: c.id } ) );
}

function CategoryField( { field, value, onChange, categories } ) {
	const multiple = field.type === 'categoriesSelect';
	return (
		<SelectControl
			value={ multiple ? ( Array.isArray( value ) ? value : [] ) : value || '' }
			options={ categoryOptions( categories ) }
			onChange={
				multiple
					? ( v ) => onChange( v.map( Number ) )
					: ( v ) => onChange( v === '' ? '' : Number( v ) )
			}
			multiple={ multiple }
		/>
	);
}

function SingleSelectField( { field, value, onChange } ) {
	const [ options, setOptions ] = useState( [] );
	const [ loading, setLoading ] = useState( false );
	const didInit = useRef( false );

	useEffect( () => {
		if ( didInit.current || ! value ) {
			return;
		}
		didInit.current = true;
		setLoading( true );
		apiFetch( { path: 'pandastudio/framework/wp_query', method: 'POST', data: { keyword: value } } )
			.then( ( result ) => {
				if ( result && result[ 0 ] ) {
					setOptions( [ { label: result[ 0 ].label, value: String( result[ 0 ].value ) } ] );
				}
			} )
			.finally( () => setLoading( false ) );
	}, [] );

	const filter = ( keyword ) => {
		if ( ! keyword ) {
			setOptions( [] );
			return;
		}
		setLoading( true );
		apiFetch( { path: 'pandastudio/framework/wp_query', method: 'POST', data: { keyword } } )
			.then( ( result ) => {
				setOptions( ( result || [] ).map( ( item ) => ( { label: item.label, value: String( item.value ) } ) ) );
			} )
			.finally( () => setLoading( false ) );
	};

	return (
		<ComboboxControl
			value={ value ? String( value ) : null }
			options={ options }
			onFilterValueChange={ filter }
			onChange={ ( v ) => onChange( v === null || v === '' ? '' : Number( v ) ) }
			allowReset
		/>
	);
}

function SubControl( { type, value, onChange, categories } ) {
	switch ( type.type ) {
		case 'input':
			return <TextField field={ type } value={ value } onChange={ onChange } />;
		case 'textarea':
			return <TextareaField field={ type } value={ value } onChange={ onChange } />;
		case 'uploader':
			return <UploadField field={ type } value={ value } onChange={ onChange } />;
		case 'radio':
			return <RadioField field={ type } value={ value } onChange={ onChange } />;
		case 'select':
			return <SelectField field={ type } value={ value } onChange={ onChange } />;
		case 'categorySelect':
		case 'categoriesSelect':
			return <CategoryField field={ type } value={ value } onChange={ onChange } categories={ categories } />;
		case 'singleSelect':
			return <SingleSelectField field={ type } value={ value } onChange={ onChange } />;
		case 'switch':
			return <ToggleField field={ type } value={ value } onChange={ onChange } />;
		default:
			return null;
	}
}

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
		return (
			<div>
				<table className="widefat striped nirvana-multitypes-table">
					<thead>
						<tr>
							<th style={ { width: '55px' } }>#</th>
							{ types.map( ( t ) => (
								<th key={ t.name }>{ t.label || t.name }</th>
							) ) }
							<th style={ { width: '130px' } }>{ __( '操作' ) }</th>
						</tr>
					</thead>
					<tbody>
						{ items.map( ( item, index ) => (
							<tr key={ index }>
								<td>{ index + 1 }</td>
								{ types.map( ( t ) => (
									<td key={ t.name }>
										<SubControl type={ t } value={ item[ t.name ] } onChange={ ( v ) => setItem( index, t.name, v ) } categories={ categories } />
									</td>
								) ) }
								<td>
									<Button variant="secondary" onClick={ () => move( index, -1 ) } disabled={ index === 0 }>
										↑
									</Button>
									<Button variant="secondary" onClick={ () => move( index, 1 ) } disabled={ index === items.length - 1 }>
										↓
									</Button>
									<Button variant="tertiary" isDestructive onClick={ () => remove( index ) }>
										{ __( '删除' ) }
									</Button>
								</td>
							</tr>
						) ) }
					</tbody>
				</table>
				<Button variant="primary" onClick={ add }>
					{ field.subLabel || __( '添加' ) }
				</Button>
			</div>
		);
	}

	return (
		<div>
			{ items.map( ( item, index ) => (
				<div key={ index } className="nirvana-multitypes-card">
					<div className="nirvana-multitypes-card-head">
						<strong>
							{ field.subLabel } { index + 1 }
						</strong>
						<span>
							<Button variant="secondary" onClick={ () => move( index, -1 ) } disabled={ index === 0 }>
								{ __( '上移' ) }
							</Button>
							<Button variant="secondary" onClick={ () => move( index, 1 ) } disabled={ index === items.length - 1 }>
								{ __( '下移' ) }
							</Button>
							<Button variant="tertiary" isDestructive onClick={ () => remove( index ) }>
								{ __( '移除' ) }
							</Button>
						</span>
					</div>
					{ types.map( ( t ) => (
						<div key={ t.name } className="nirvana-multitypes-row">
							{ t.label ? <div className="nirvana-multitypes-label">{ t.label }</div> : null }
							<SubControl type={ t } value={ item[ t.name ] } onChange={ ( v ) => setItem( index, t.name, v ) } categories={ categories } />
						</div>
					) ) }
				</div>
			) ) }
			<Button variant="primary" onClick={ add }>
				{ field.subLabel || __( '添加' ) }
			</Button>
		</div>
	);
}

export function SchemaField( { field, value, onChange, categories, onImport, onExport, onClear } ) {
	let control = null;
	switch ( field.type ) {
		case 'input':
			control = <TextField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'textarea':
			control = <TextareaField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'inputNumber':
			control = <NumberField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'slider':
			control = <SliderField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'switch':
			control = <ToggleField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'colorPicker':
			control = <ColorField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'uploader':
			control = <UploadField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'multi_uploader':
			control = <MultiUploadField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'radio':
			control = <RadioField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'select':
			control = <SelectField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'categorySelect':
		case 'categoriesSelect':
			control = <CategoryField field={ field } value={ value } onChange={ onChange } categories={ categories } />;
			break;
		case 'singleSelect':
			control = <SingleSelectField field={ field } value={ value } onChange={ onChange } />;
			break;
		case 'multitypes':
			control = <MultiTypesField field={ field } value={ value } onChange={ onChange } categories={ categories } />;
			break;
		case 'uploadData':
			control = (
				<Button variant="secondary" onClick={ onImport }>
					{ __( '上传配置' ) }
				</Button>
			);
			break;
		case 'downloadData':
			control = (
				<Button variant="secondary" onClick={ onExport }>
					{ __( '下载配置' ) }
				</Button>
			);
			break;
		case 'clearData':
			control = (
				<Button variant="tertiary" isDestructive onClick={ onClear } icon="trash">
					{ __( '清空' ) }
				</Button>
			);
			break;
		default:
			break;
	}
	const label = field.label ? (
		<div className="nirvana-field-label" dangerouslySetInnerHTML={ { __html: field.label } } />
	) : null;
	const decoration = field.decoration ? (
		<div className="nirvana-decoration" dangerouslySetInnerHTML={ { __html: field.decoration } } />
	) : null;
	if ( ! control ) {
		return (
			<>
				{ label }
				{ decoration }
			</>
		);
	}
	return (
		<div className="nirvana-field">
			{ label }
			{ control }
			{ decoration }
		</div>
	);
}
