( function() {
	wp.hooks.addAction(
		'frm_after_authorize',
		'formidable',
		function( msg ) {
			if ( msg.success ) {
				handleAfterLicenseAuthorizeSuccess();
			}
		}
	);

	function handleAfterLicenseAuthorizeSuccess() {
		const formData = new FormData();
		window.frmDom.ajax.doJsonPost( 'update_stylesheet', formData );
	}
}() );
