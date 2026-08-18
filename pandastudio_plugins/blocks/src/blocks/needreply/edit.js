import { __ } from '@wordpress/i18n';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="needreply_wrap">
				<div className="needreply_tip">{ __( '此模块内容回复可见', 'niRvana' ) }</div>
				<InnerBlocks />
			</div>
		</div>
	);
}
