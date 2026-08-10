import { __ } from '@wordpress/i18n';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { id, btn_type, btn_label, title, close_label, href_label, href } = attributes;
	const type = btn_type || 'btn-primary';
	const label = btn_label || '';
	const modalId = id || `uuid-timestamp-${ new Date().getTime() }`;
	const modalTitle = title || __( '未标题' );
	const closeLabel = close_label || __( '关闭' );
	const hrefLabel = href_label || __( '跳转到' );
	const linkHref = href || '';
	return (
		<div { ...useBlockProps.save() }>
			{ `[modal id="${ modalId }" btn_type="${ type }" btn_label="${ label }" title="${ modalTitle }" close_label="${ closeLabel }" href_label="${ hrefLabel }" href="${ linkHref }"]` }
			<div className="wp-block-column">
				<InnerBlocks.Content />
			</div>
			{ '[/modal]' }
		</div>
	);
}
