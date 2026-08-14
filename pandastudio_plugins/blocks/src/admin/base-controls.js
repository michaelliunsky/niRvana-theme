import { useState, useEffect, useRef } from '@wordpress/element';
import { Input, InputNumber, Slider, Switch, ColorPicker, Button, Select, Radio, Space } from 'antd';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { openMedia } from './media';

export function TextField( { field, value, onChange } ) {
	return <Input value={ value || '' } onChange={ ( e ) => onChange( e.target.value ) } placeholder={ field.placeholder } />;
}

export function TextareaField( { field, value, onChange } ) {
	return (
		<Input.TextArea
			value={ value || '' }
			onChange={ ( e ) => onChange( e.target.value ) }
			rows={ field.rows || 3 }
			placeholder={ field.placeholder }
		/>
	);
}

export function NumberField( { field, value, onChange } ) {
	return (
		<InputNumber
			value={ value === '' || value === undefined || value === null ? null : Number( value ) }
			min={ field.min }
			max={ field.max }
			step={ field.step }
			onChange={ ( v ) => onChange( v === null || v === undefined ? '' : v ) }
			style={ { width: 160 } }
		/>
	);
}

export function SliderField( { field, value, onChange } ) {
	return (
		<Slider
			value={ Number( value ) || 0 }
			min={ field.min }
			max={ field.max }
			step={ field.step }
			onChange={ onChange }
			style={ { maxWidth: 420 } }
		/>
	);
}

export function ToggleField( { field, value, onChange } ) {
	return (
		<Switch checked={ value === 'checked' } onChange={ ( checked ) => onChange( checked ? 'checked' : '' ) } />
	);
}

export function ColorField( { field, value, onChange } ) {
	return (
		<ColorPicker
			value={ value || null }
			showText
			allowClear
			onChange={ ( color ) => onChange( color ? color.toHexString() : '' ) }
		/>
	);
}

export function UploadField( { field, value, onChange } ) {
	const pick = async () => {
		const url = await openMedia( { multiple: false } );
		onChange( url );
	};
	return (
		<div className="nirvana-uploader">
			<Space.Compact style={ { width: '100%' } }>
				<Input
					value={ value || '' }
					onChange={ ( e ) => onChange( e.target.value ) }
					placeholder={ field.placeholder || '点击按钮上传或在此处粘贴外链地址' }
				/>
				<Button onClick={ pick }>{ __( '上传' ) }</Button>
				{ value ? <Button danger onClick={ () => onChange( '' ) }>{ __( '移除' ) }</Button> : null }
			</Space.Compact>
			{ value && field.showImage !== false ? (
				<img className="nirvana-uploader-preview" src={ value } alt="" />
			) : null }
		</div>
	);
}

export function MultiUploadField( { field, value, onChange } ) {
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
			<Space wrap>
				<Button onClick={ addExternal }>{ __( '输入外链' ) }</Button>
				<Button onClick={ pick }>{ __( '批量上传' ) }</Button>
				{ images.length > 1 ? (
					<Button danger onClick={ () => onChange( [] ) }>{ __( '全部移除' ) }</Button>
				) : null }
			</Space>
		</div>
	);
}

export function RadioField( { field, value, onChange } ) {
	return (
		<Radio.Group value={ value } onChange={ ( e ) => onChange( e.target.value ) }>
			{ ( field.radios || [] ).map( ( radio ) => (
				<Radio key={ radio.value } value={ radio.value }>
					{ radio.label }
				</Radio>
			) ) }
		</Radio.Group>
	);
}

export function SelectField( { field, value, onChange } ) {
	const selects = field.selects || [];
	const options = selects.map( ( s ) => ( {
		label: s.description ? `${ s.label } — ${ s.description }` : s.label,
		value: s.value,
	} ) );
	if ( field.multiple ) {
		return (
			<Select
				mode="multiple"
				value={ Array.isArray( value ) ? value : [] }
				options={ options }
				onChange={ onChange }
				placeholder={ field.placeholder }
				style={ { minWidth: 260 } }
			/>
		);
	}
	return (
		<Select
			value={ value || undefined }
			options={ options }
			onChange={ onChange }
			placeholder={ field.placeholder }
			allowClear
			style={ { minWidth: 220 } }
		/>
	);
}

function categoryOptions( categories ) {
	return categories.map( ( c ) => ( { label: c.name, value: c.id } ) );
}

export function CategoryField( { field, value, onChange, categories } ) {
	const options = categoryOptions( categories );
	if ( field.type === 'categoriesSelect' ) {
		return (
			<Select
				mode="multiple"
				value={ Array.isArray( value ) ? value : [] }
				options={ options }
				onChange={ ( v ) => onChange( ( v || [] ).map( Number ) ) }
				placeholder={ field.placeholder || '请选择至少一个文章分类' }
				style={ { minWidth: 260 } }
			/>
		);
	}
	return (
		<Select
			value={ value || undefined }
			options={ options }
			onChange={ ( v ) => onChange( v === undefined ? '' : Number( v ) ) }
			allowClear
			style={ { minWidth: 220 } }
		/>
	);
}

export function SingleSelectField( { field, value, onChange } ) {
	const [ options, setOptions ] = useState( [] );
	const didInit = useRef( false );

	useEffect( () => {
		if ( didInit.current || ! value ) {
			return;
		}
		didInit.current = true;
		apiFetch( { path: 'pandastudio/framework/wp_query', method: 'POST', data: { keyword: value } } )
			.then( ( result ) => {
				if ( result && result[ 0 ] ) {
					setOptions( [ { label: result[ 0 ].label, value: result[ 0 ].value } ] );
				}
			} );
	}, [] );

	const filter = ( keyword ) => {
		if ( ! keyword ) {
			setOptions( [] );
			return;
		}
		apiFetch( { path: 'pandastudio/framework/wp_query', method: 'POST', data: { keyword } } )
			.then( ( result ) => {
				setOptions( ( result || [] ).map( ( item ) => ( { label: item.label, value: item.value } ) ) );
			} );
	};

	return (
		<Select
			showSearch
			filterOption={ false }
			onSearch={ filter }
			value={ value || undefined }
			options={ options }
			onChange={ ( v ) => onChange( v === undefined ? '' : Number( v ) ) }
			allowClear
			placeholder={ field.placeholder || '输入关键字来筛选文章' }
			style={ { minWidth: 220 } }
		/>
	);
}

export function SubControl( { type, value, onChange, categories } ) {
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
