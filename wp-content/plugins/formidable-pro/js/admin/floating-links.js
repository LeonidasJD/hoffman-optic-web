( function () {
	'use strict';

	/* global wp, frmDom */

	const { img } = frmDom;

	// Add the image to the inbox slide-in.
	// WordPress plugins in the repository are not allowed to load images via URL.
	// Because of this, the image is only shown in Pro.
	wp.hooks.addFilter(
		'frm_inbox_slidein_children',
		'formidable',
		children => {
			if ( frmGlobal.inboxSlideIn.image ) {
				children.unshift(
					img({ src: frmGlobal.inboxSlideIn.image })
				);
			}
			return children;
		}
	);

} )();
