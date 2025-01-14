<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProEntriesHelper {

	/**
	 * Check if form should automatically be in edit mode (limited to one, has draft).
	 *
	 * @param string   $action
	 * @param stdClass $form
	 * @return string
	 */
	public static function allow_form_edit( $action, $form ) {
		if ( $action !== 'new' ) {
			// make sure there is an entry id in the url if the action is being set in the url
			$entry_id = FrmAppHelper::simple_get( 'entry', 'sanitize_title', 0 );
			if ( empty( $entry_id ) && ( ! $_POST || ! isset( $_POST['frm_action'] ) ) ) {
				$action = 'new';
			}
		}

		$user_ID = get_current_user_id();
		if ( ! $form || ! $user_ID ) {
			return $action;
		}

		if ( ! $form->editable ) {
			$action = 'new';
		}

		if ( $action === 'destroy' ) {
			return $action;
		}

		$is_draft = false;

		global $wpdb, $frm_vars;
		if ( ( $form->editable && FrmProFormsHelper::check_single_entry_type( $form->options, 'user' ) || ! empty( $form->options['save_draft'] ) ) ) {
			if ( $action === 'update' && $form->id == FrmAppHelper::get_param( 'form_id', '', 'get', 'absint' ) ) {
				// Don't change the action if this is the wrong form.
			} else {
				$checking_drafts = ! empty( $form->options['save_draft'] ) && ( ! $form->editable || ! FrmProFormsHelper::check_single_entry_type( $form->options, 'user' ) );
				$meta            = self::check_for_user_entry( $user_ID, $form, $checking_drafts );

				if ( $meta ) {
					if ( $checking_drafts ) {
						$frm_vars['edit_draft'] = $form->id;
						$is_draft               = true;
					}

					$action = 'edit';
				}
			}
		}

		//do not allow editing if user does not have permission
		if ( $action != 'edit' || $is_draft ) {
			return $action;
		}

		$entry = FrmAppHelper::get_param( 'entry', 0, 'get', 'sanitize_text_field' );

		if ( ! self::user_can_edit( $entry, $form ) ) {
			$action = 'new';
		}

		return $action;
	}

	/**
	 * Check if the current user already has an entry
	 *
	 * @since 2.0
	 * @return array|false
	 */
	public static function check_for_user_entry( $user_ID, $form, $is_draft ) {
		$query = array( 'user_id' => $user_ID, 'form_id' => $form->id );
		if ( $is_draft ) {
			$query['is_draft'] = 1;
		}

		return FrmDb::get_col( 'frm_items', $query );
	}

	public static function user_can_edit( $entry, $form = false ) {
		if ( empty( $form ) ) {
			FrmEntry::maybe_get_entry( $entry );

			if ( is_object( $entry ) ) {
				$form = $entry->form_id;
			}
		}

		FrmForm::maybe_get_form( $form );

		self::maybe_get_parent_form_and_entry( $form, $entry );

		$allowed = self::user_can_edit_check( $entry, $form );
		return apply_filters( 'frm_user_can_edit', $allowed, compact( 'entry', 'form' ) );
	}

	/**
	* If a form is a child form, get the parent form. Then if the entry is a child entry, get the parent entry.
	*
	* @since 2.0.13
	* @param int|object $form - pass by reference
	* @param int|object $entry - pass by reference
	*/
	private static function maybe_get_parent_form_and_entry( &$form, &$entry ) {
		// If form is a child form, refer to parent form's settings
		if ( $form && $form->parent_form_id ) {
			$form = FrmForm::getOne( $form->parent_form_id );

			// Make sure we're also checking the parent entry's permissions
			FrmEntry::maybe_get_entry( $entry );
			if ( $entry->parent_item_id ) {
				$entry = FrmEntry::getOne( $entry->parent_item_id );
			}
		}
	}

	public static function user_can_edit_check( $entry, $form ) {
		$user_ID = get_current_user_id();

		if ( ! $user_ID || empty( $form ) || ( is_object( $entry ) && $entry->form_id != $form->id ) ) {
			return false;
		}

		if ( is_object( $entry ) ) {
			if ( ( $entry->is_draft && $entry->user_id == $user_ID ) || self::user_can_edit_others( $form ) ) {
				//if editable and user can edit this entry
				return true;
			}
		}

		$where = array( 'fr.id' => $form->id );

		if ( self::user_can_only_edit_draft( $form ) ) {
			//only allow editing of drafts
			$where['user_id'] = $user_ID;
			$where['is_draft'] = 1;
		}

		if ( ! self::user_can_edit_others( $form ) ) {
			$where['user_id'] = $user_ID;

			if ( is_object( $entry ) && $entry->user_id != $user_ID ) {
				return false;
			}

			// Check if open_editable_role and editable_role is set for reverse compatibility
			if ( $form->editable && isset( $form->options['open_editable_role'] ) && ! FrmProFieldsHelper::user_has_permission( $form->options['open_editable_role'] ) && isset( $form->options['editable_role'] ) && ! FrmProFieldsHelper::user_has_permission( $form->options['editable_role'] ) ) {
				// make sure user cannot edit their own entry, even if a higher user role can unless it's a draft
				if ( is_object( $entry ) && ! $entry->is_draft ) {
					return false;
				} else if ( ! is_object( $entry ) ) {
					$where['is_draft'] = 1;
				}
			}
		} else if ( $form->editable && $user_ID && empty( $entry ) ) {
			// make sure user is editing their own draft by default, even if they have permission to edit others' entries
		   $where['user_id'] = $user_ID;
		}

		if ( self::should_check_for_draft( $form, $entry ) ) {
			$where['is_draft'] = 1;

			if ( is_object( $entry ) && ! $entry->is_draft ) {
				return false;
			}
		}

		// If entry object, and we made it this far, then don't do another db call
		if ( is_object( $entry ) ) {
			return true;
		}

		if ( ! empty( $entry ) ) {
			$where_key = is_numeric( $entry ) ? 'it.id' : 'item_key';
			$where[ $where_key ] = $entry;
		}

		return FrmEntry::getAll( $where, ' ORDER BY created_at DESC', 1, true );
	}

	/**
	 * When loading up an entry, check if it should be a draft.
	 *
	 * @since 6.8.1
	 *
	 * @param object       $form The form object.
	 * @param object|false $entry The entry object.
	 * @return boolean True if the entry should be a draft.
	 */
	private static function should_check_for_draft( $form, $entry ) {
		$check_draft = ! $form->editable;
		if ( $form->editable && empty( $entry ) && is_user_logged_in() ) {
			global $frm_vars;
			// Check if the form is expecting a draft entry.
			$check_draft = ! empty( $frm_vars['edit_draft'] ) && (int) $frm_vars['edit_draft'] === (int) $form->id;
		}

		return $check_draft;
	}

	/**
	 * Check if this user can edit entry from another user
	 *
	 * @param object $form
	 * @return boolean True if user can edit
	 */
	public static function user_can_edit_others( $form ) {
		$open_editable = $form->editable && isset( $form->options['open_editable_role'] );
		if ( ! $open_editable ) {
			return false;
		}

		return FrmProFieldsHelper::user_has_permission( $form->options['open_editable_role'] );
	}

	/**
	 * Only allow editing of drafts
	 *
	 * @param object $form
	 * @return boolean True if editing is not allowed.
	 */
	public static function user_can_only_edit_draft( $form ) {
		return ! self::maybe_user_can_edit_entries( $form );
	}

	/**
	 * Before checking the database for entries, know which entries we should retrieve.
	 *
	 * @since 4.07
	 * @param object $form
	 * @return boolean True if editing is enabled in the form and user has correct role.
	 */
	private static function maybe_user_can_edit_entries( $form ) {
		$can_edit_own = $form->editable && FrmProFieldsHelper::user_has_permission( $form->options['editable_role'] );
		if ( $can_edit_own ) {
			// User can edit their own entries if any exist.
			return true;
		}

		return self::user_can_edit_others( $form );
	}

	public static function user_can_delete( $entry ) {
		FrmEntry::maybe_get_entry( $entry );
		if ( ! $entry ) {
			return false;
		}

		if ( current_user_can( 'frm_delete_entries' ) ) {
			$allowed = true;
		} else {
			$allowed = self::user_can_edit( $entry );
			if ( ! empty( $allowed ) ) {
				$allowed = true;
			}
		}

		return apply_filters( 'frm_allow_delete', $allowed, $entry );
	}

	/**
	 * @since 4.0
	 */
	private static function show_list_entry_buttons( $form, $args = array() ) {
		$form_id = is_numeric( $form ) ? $form : $form->id;
		echo '<div class="actions alignleft frm-button-group">';
		self::insert_download_csv_button( $form_id );
		self::delete_all_button( $form_id, $args );
		echo '</div>';
	}

	public static function show_new_entry_button( $form ) {
		echo self::new_entry_button( $form );
	}

	public static function new_entry_button( $form ) {
		if ( ! current_user_can( 'frm_create_entries' ) ) {
			return;
		}

		$link = '<a href="?page=formidable-entries&frm_action=new';
		if ( $form ) {
			$form_id = is_numeric( $form ) ? $form : $form->id;
			$link .= '&form=' . $form_id;
		}
		$link .= '" class="button-primary frm-button-primary frm-with-plus">';
		$link .= FrmProAppHelper::icon_by_class( 'frmfont frm_plus_icon frm_svg15', array( 'echo' => false ) );
		$link .= __( 'Add New', 'formidable-pro' ) . '</a>';

		return $link;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function show_duplicate_link( $entry ) {
		_deprecated_function( __METHOD__, '3.0' );
		echo self::duplicate_link( $entry );
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function duplicate_link( $entry ) {
		if ( current_user_can( 'frm_create_entries' ) ) {
			_deprecated_function( __METHOD__, '3.0' );
			return '<a href="' . esc_url( '?page=formidable-entries&frm_action=duplicate&form=' . $entry->form_id . '&id=' . $entry->id ) . '" class="button-secondary">' .
				esc_html__( 'Duplicate', 'formidable-pro' ) .
				'</a>';
		}
	}

	/**
	 * Add links to the entry actions including edit and resend emails.
	 *
	 * @since 3.0
	 * @param array $actions
	 * @param array $atts Includes 'id' and 'entry'.
	 */
	public static function add_actions_dropdown( $actions, $atts ) {
		$entry = $atts['entry'];
		$page  = FrmAppHelper::get_param( 'frm_action' );

		if ( 'edit' === $page || 'update' === $page ) {
			// Add the "Add Entry" button as the first sidebar action.
			$actions = array(
				'frm_add' => array(
					'url'   => admin_url( 'admin.php?page=formidable-entries&frm_action=new&form=' . $entry->form_id ),
					'label' => __( 'Add Entry', 'formidable' ),
					'icon'  => 'frm_icon_font frm_plus_icon',
				),
			) + $actions;
		}

		$actions['frm_resend'] = array(
			'url'   => '#',
			'id'    => 'frm_resend_email',
			'label' => __( 'Resend Emails', 'formidable' ),
			'icon'  => $actions['frm_resend']['icon'],
			'data'  => array(
				'eid' => $entry->id,
				'fid' => $entry->form_id,
			),
		);

		if ( $page !== 'edit' && current_user_can( 'frm_edit_entries' ) ) {
			$actions['frm_edit'] = array(
				'url'   => FrmProEntry::admin_edit_link( $entry->id ),
				'label' => __( 'Edit Entry', 'formidable' ),
				'icon'  => $actions['frm_edit']['icon'],
			);
		} else {
			unset( $actions['frm_edit'] );
		}

		if ( current_user_can( 'frm_delete_entries' ) ) {
			if ( ! empty( $entry->post_id ) ) {
				$actions['frm_delete_post'] = array(
					'url'   => wp_nonce_url( admin_url( 'admin.php?page=formidable-entries&frm_action=destroy&keep_post=1&id=' . $entry->id . '&form=' . $entry->form_id ) ),
					'label' => __( 'Delete without Post' ),
					'icon'  => 'frm_icon_font frm_delete_icon',
					'data'  => array( 'frmverify' => __( 'Really delete?', 'formidable' ) ),
				);
			}
		}

		if ( current_user_can( 'frm_create_entries' ) ) {
			$actions['frm_duplicate'] = array(
				'url'   => wp_nonce_url( admin_url( 'admin.php?page=formidable-entries&frm_action=duplicate&id=' . $entry->id . '&form=' . $entry->form_id ) ),
				'label' => __( 'Duplicate', 'formidable-pro' ),
				'icon'  => 'frm_icon_font frm_clone_icon',
			);
		}

		return $actions;
	}

	public static function resend_email_links( $entry_id, $form_id, $args = array() ) {
		$defaults = array(
			'label' => __( 'Resend Email Notifications', 'formidable-pro' ),
			'echo' => true,
		);

		$args = wp_parse_args( $args, $defaults );

		$link = '<a href="#" data-eid="' . esc_attr( $entry_id ) . '" data-fid="' . esc_attr( $form_id ) . '" id="frm_resend_email" title="' . esc_attr( $args['label'] ) . '">' . $args['label'] . '</a>';
		if ( $args['echo'] ) {
			echo $link;
		}
		return $link;
	}

	public static function before_table( $footer, $form_id = false, $args = array() ) {
		if ( FrmAppHelper::simple_get( 'page', 'sanitize_title' ) != 'formidable-entries' || ! $form_id ) {
			return;
		}

		if ( ! $footer ) {
			self::show_list_entry_buttons( $form_id, $args );
			do_action( 'frm_before_entries_table', $form_id );
		}
	}

	/**
	 * @since 4.0
	 * @param int $form_id
	 */
	private static function delete_all_button( $form_id, $args = array() ) {
		if ( ! apply_filters( 'frm_show_delete_all', current_user_can( 'frm_delete_entries' ), $form_id ) ) {
			return;
		}

		$entries_count = ( isset( $args['entries_count'] ) ? $args['entries_count'] : 0 );
		$verify        = ( isset( $args['bulk_delete_confirmation_message'] ) ? $args['bulk_delete_confirmation_message'] : '' );

		?>
		<span class="frm_uninstall">
			<a href="<?php echo esc_url( wp_nonce_url( '?page=formidable-entries&frm_action=destroy_all' . ( $form_id ? '&form=' . absint( $form_id ) : '' ) ) ); ?>" class="button frm-button-secondary" data-loaded-from="entries-list" data-total-entries="<?php echo esc_attr( $entries_count ); ?>" data-frmverify="<?php echo esc_attr( $verify ); ?>" data-frmverify-btn="frm-button-red">
				<?php esc_html_e( 'Delete All Entries', 'formidable-pro' ); ?>
			</a>
		</span>
		<?php
	}

	private static function insert_download_csv_button( $form_id ) {
		$page_params = array( 'frm_action' => 0, 'action' => 'frm_entries_csv', 'form' => $form_id );

		$s = FrmAppHelper::get_param( 's', '', 'request', 'sanitize_text_field' );
		if ( $s ) {
			$page_params['s'] = $s;
		}

		$search = FrmAppHelper::get_param( 'search', '', 'request', 'sanitize_text_field' );
		if ( $search ) {
			$page_params['search'] = $search;
		}

		$fid = trim( FrmAppHelper::get_param( 'fid', '', 'request', 'sanitize_text_field' ) );
		if ( $fid ) {
			$page_params['fid'] = $fid;
		}

		?>
		<a href="<?php echo esc_url( add_query_arg( $page_params, admin_url( 'admin-ajax.php' ) ) ); ?>" class="button frm-button-secondary">
			<?php esc_html_e( 'Download CSV', 'formidable-pro' ); ?>
		</a>
		<?php
	}

	// check if entry being updated just switched draft status
	public static function is_new_entry( $entry ) {
		FrmEntry::maybe_get_entry( $entry );

		// this function will only be correct if the entry has already gone through FrmProEntriesController::check_draft_status
		return ( $entry->created_at == $entry->updated_at );
	}

	public static function get_field( $field, $id ) {
		$entry = FrmDb::check_cache( $id, 'frm_entry' );
		if ( $entry && isset( $entry->$field ) ) {
			return $entry->{$field};
		}

		$var = FrmDb::get_var( 'frm_items', array( 'id' => $id ), $field );

		return $var;
	}

	/**
	* Get the values for Dynamic List fields based on the conditional logic settings
	*
	* @since 2.0.08
	* @param object $field
	* @param object $entry
	* @param string|array|int $field_value, pass by reference
	*/
	public static function get_dynamic_list_values( $field, $entry, &$field_value ) {
		// Exit now if a value is already set, field type is not Dynamic List, or conditional logic is not set
		if ( $field_value || $field->type != 'data' || ! FrmProField::is_list_field( $field ) || ! isset( $field->field_options['hide_field'] ) ) {
			return;
		}

		$field_value = array();
		foreach ( (array) $field->field_options['hide_field'] as $hfield ) {
			if ( isset( $entry->metas[ $hfield ] ) ) {
				// Check if field in conditional logic is a Dynamic field
				$cl_field_type = FrmField::get_type( $hfield );
				if ( $cl_field_type == 'data' ) {
					$cl_field_val = $entry->metas[ $hfield ];
					FrmProAppHelper::unserialize_or_decode( $cl_field_val );
					if ( is_array( $cl_field_val ) ) {
						$field_value += $cl_field_val;
					} else {
						$field_value[] = $cl_field_val;
					}
				}
			}
		}
	}

	public static function get_search_str( $where_clause, $search_str, $form_id = 0, $fid = '' ) {
		if ( ! is_array( $search_str ) ) {
			$search_str = self::explode_search_terms( $search_str );
		}

		$add_where = self::get_where_clause_for_entries_search( $fid, $form_id, $search_str );

		self::add_where_to_query( $add_where, $where_clause );

		return $where_clause;
	}

	/**
	 * Explode with spaces as the separator but keep items in double quotes in groups.
	 * This also trims the terms and strips the double quotes.
	 *
	 * @since 6.8.4
	 *
	 * @param string $string
	 * @return array
	 */
	private static function explode_search_terms( $string ) {
		preg_match_all( '/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $string, $matches );
		return array_map(
			/**
			 * @param string $term
			 * @return string
			 */
			function ( $term ) {
				return trim( trim( $term ), '"' );
			},
			$matches[0]
		);
	}

	/**
	 * Generate the where clause for an entry search - used in back-end entries tab
	 *
	 * @since 2.02.01
	 * @param int $fid
	 * @param int $form_id
	 * @param array $search_param
	 * @return array
	 */
	private static function get_where_clause_for_entries_search( $fid, $form_id, $search_param ) {
		if ( trim( $fid ) === '' ) {
			// General query submitted
			$where = self::get_where_arguments_for_general_entry_query( $form_id, $search_param );
		} else if ( is_numeric( $fid ) ) {
			// Specific field searched
			$where = self::get_where_arguments_for_specific_field_query( $fid, $search_param );
		} else {
			// Specific frm_items column searched
			$where = self::get_where_arguments_for_frm_items_column( $fid, $search_param );
		}

		return $where;
	}

	/**
	 * Set up the where arguments for a general entry query in the back-end Entries tab
	 *
	 * @since 2.02.01
	 * @param int $form_id
	 * @param array $search_param
	 * @return array
	 */
	private static function get_where_arguments_for_general_entry_query( $form_id, $search_param ) {
		$where = array(
			'or' => 1,
			'it.name like'        => $search_param,
			'it.ip like'          => $search_param,
			'it.item_key like'    => $search_param,
			'it.description like' => $search_param,
			'it.created_at like'  => implode( ' ', $search_param ),
		);

		$ids_in_search_param = array_filter( $search_param, 'is_numeric' );

		$ids_from_field_searches = self::search_entry_metas_for_value( $form_id, $search_param );

		$where['it.id'] = array_merge( $ids_in_search_param, $ids_from_field_searches );

		self::append_entry_ids_for_matching_posts( $form_id, $search_param, $where );

		if ( empty( $where['it.id'] ) ) {
			$where['it.id'] = 0;
		}

		return $where;
	}

	/**
	 * Search the whole entry metas table for a matching value and return entry IDs
	 *
	 * @since 2.02.01
	 * @param int $form_id
	 * @param array $search_param
	 * @return array
	 */
	private static function search_entry_metas_for_value( $form_id, $search_param ) {
		$where_args = array(
			'fi.form_id' => $form_id,
		);

		$dynamic_field_query = self::get_linked_field_query( $form_id, $search_param );
		if ( empty( $dynamic_field_query ) ) {
			$where_args['meta_value like'] = $search_param;
		} else {
			$where_args[] = array(
				'meta_value like' => $search_param,
				'or' => 1,
				$dynamic_field_query,
			);
		}

		return FrmEntryMeta::getEntryIds( $where_args, '', '', true, array( 'is_draft' => 'both' ) );
	}

	/**
	 * Generate query for entry IDs in dynamic fields
	 *
	 * @since 2.05
	 *
	 * @param int|string $form_id
	 * @param string|array $search_param
	 *
	 * @return array
	 */
	private static function get_linked_field_query( $form_id, $search_param ) {
		$dynamic_fields = FrmProFormsHelper::has_field( 'data', $form_id, false );
		if ( empty( $dynamic_fields ) ) {
			// this form has no Dynamic fields
			return array();
		}

		$linked_field_ids  = array();
		$dynamic_field_ids = array();

		// Get linked field IDs
		foreach ( (array) $dynamic_fields as $dynamic_field ) {
			FrmProFieldsHelper::get_subform_ids( $linked_field_ids, $dynamic_field );
			$dynamic_field_ids[] = $dynamic_field->id;
		}
		unset( $dynamic_field );

		if ( empty( $linked_field_ids ) ) {
			return array();
		}

		$dynamic_field_query = array();

		$linked_form_ids = FrmDb::get_col( 'frm_fields', array( 'id' => $linked_field_ids ), 'form_id' );
		if ( $linked_form_ids ) {
			$linked_entry_ids = FrmEntryMeta::getEntryIds( array( 'fi.form_id' => $linked_form_ids, 'meta_value LIKE' => $search_param ), '', '', true, array( 'is_draft' => 'both' ) );

			if ( ! empty( $linked_entry_ids ) ) {
				if ( count( $linked_entry_ids ) == 1 ) {
					$dynamic_field_query['meta_value like'] = reset( $linked_entry_ids );
				} else {
					$dynamic_field_query['meta_value'] = $linked_entry_ids;
				}
				$dynamic_field_query['field_id'] = $dynamic_field_ids;
			}
		}

		return $dynamic_field_query;
	}

	/**
	 * Search connected posts when a general search is submitted
	 *
	 * @param $form_id
	 * @param $search_param
	 * @param $where
	 */
	private static function append_entry_ids_for_matching_posts( $form_id, $search_param, &$where ) {
		// Check if form has a post action
		$post_action = FrmFormAction::get_action_for_form( $form_id, 'wppost' );
		if ( ! $post_action ) {
			return;
		}

		// Search all posts on site
		$post_query        = array(
			'post_title LIKE'   => $search_param,
			'post_content LIKE' => $search_param,
			'or'                => 1,
		);
		$matching_posts = FrmDb::get_col( 'posts', $post_query, 'ID' );

		$action = reset( $post_action );
		if ( ! empty( $action->post_content['post_custom_fields'] ) ) {
			$post_meta_post_ids = self::search_post_meta_for_custom_fields( $action->post_content['post_custom_fields'], $search_param );
			if ( $post_meta_post_ids ) {
				$matching_posts = array_unique( array_merge( $matching_posts, $post_meta_post_ids ) );
			}
		}

		// If there are any posts matching the query, retrieve entry IDs for those posts
		if ( $matching_posts ) {
			$entry_ids = FrmDb::get_col( 'frm_items', array( 'post_id' => $matching_posts, 'form_id' => $form_id ) );
			if ( $entry_ids ) {
				$where['it.id'] = array_merge( $where['it.id'], $entry_ids );
			}
		}
	}

	/**
	 * @param array  $custom_fields
	 * @param string $search_param
	 * @return array post ids that match search.
	 */
	private static function search_post_meta_for_custom_fields( $custom_fields, $search_param ) {
		$meta_keys      = array_column( $custom_fields, 'meta_name' );
		$postmeta_query = array(
			'meta_key'        => $meta_keys,
			'meta_value LIKE' => $search_param,
		);
		return FrmDb::get_col( 'postmeta', $postmeta_query, 'post_id' );
	}

	/**
	 * Set up the it.id argument for the WHERE clause when searching for a specific field value
	 *
	 * @since 2.02.01
	 * @param int $fid
	 * @param array $search_param
	 * @return array
	 */
	private static function get_where_arguments_for_specific_field_query( $fid, $search_param ) {
		$args = array( 'comparison_type' => 'like', 'is_draft' => 'both' );

		if ( $fid === 0 || $fid === '0' ) {
			$field = 0;
		} else {
			$field = FrmField::getOne( $fid );
			if ( $field->type == 'data' && is_numeric( $field->field_options['form_select'] ) ) {
				$linked_field = FrmField::getOne( $field->field_options['form_select'] );
				$linked_entry_ids = FrmProEntryMeta::get_entry_ids_for_field_and_value( $linked_field, $search_param, $args );
				$search_param = array_merge( $search_param, $linked_entry_ids );
			}
		}

		$entry_ids = FrmProEntryMeta::get_entry_ids_for_field_and_value( $field, $search_param, $args );

		if ( empty( $entry_ids ) ) {
			$entry_ids = 0;
		}

		return array( 'it.id' => $entry_ids );
	}

	/**
	 * Get the where argument for the specific frm_items column that was searched on back-end Entries tab
	 *
	 * @since 2.02.01
	 * @param int $fid
	 * @param array $search_param
	 * @return array
	 */
	private static function get_where_arguments_for_frm_items_column( $fid, $search_param ) {
		if ( 'user_id' == $fid ) {
			$search_param = self::replace_search_param_with_user_ids( $search_param );
			$where = array( 'it.' . $fid => $search_param );
		} else if ( 'created_at' == $fid || 'updated_at' == $fid ) {
			$search_param = implode( ' ', $search_param );
			$where = array( 'it.' . $fid . ' like' => $search_param );
		} else {
			$where = array( 'it.' . $fid => $search_param );
			$where = apply_filters(
				'frm_filter_admin_entries',
				$where,
				array(
					'field_id' => $fid,
					'search'   => $search_param,
				)
			);
		}

		return $where;
	}

	/**
	 * Create an array of user IDs from an array of search parameters
	 *
	 * @since 2.02.01
	 * @param array $search_param
	 * @return array $user_ids
	 */
	private static function replace_search_param_with_user_ids( $search_param ) {
		$user_ids = array_filter( $search_param, 'is_numeric' );

		$add_user_ids = self::search_users( $search_param );
		if ( ! empty( $add_user_ids ) ) {
			$user_ids = array_merge( $user_ids, $add_user_ids );
		}

		if ( empty( $user_ids ) ) {
			// prevent all results from being returned when there are no matches
			$user_ids = array( 'none' );
		}

		return $user_ids;
	}

	/**
	 * @since 3.03.03
	 *
	 * @param array $search
	 * @return array
	 */
	private static function search_users( $search ) {
		global $wpdb;

		$single_value = implode( ' ', $search );
		if ( is_numeric( $single_value ) ) {
			// don't search the user record for the id
			return array();
		}

		$query = array(
			'or' => 1,
			'user_login like'    => $single_value,
			'user_email like'    => $single_value,
			'user_nicename like' => $single_value,
			'display_name like'  => $single_value,
		);
		return FrmDb::get_col( $wpdb->users, $query, 'ID' );
	}

	/**
	 * @since 2.0.8
	 */
	private static function add_where_to_query( $add_where, &$where_clause ) {
		if ( is_array( $where_clause ) ) {
			$where_clause[] = $add_where;
		} else {
			global $wpdb;
			$where = '';
			$values = array();
			FrmDb::parse_where_from_array( $add_where, '', $where, $values );
			FrmDb::get_where_clause_and_values( $add_where );
			$where_clause .= ' AND (' . $wpdb->prepare( $where, $values ) . ')';
		}
	}

	/**
	 * @return bool
	 */
	private static function free_plugin_updated_to_support_child_form_search() {
		return is_callable( 'FrmEntryMeta::get_top_level_entry_ids' );
	}

	/**
	 * @param int $form_id
	 * @return array
	 */
	private static function get_repeater_form_ids( $form_id ) {
		return array_reduce(
			FrmField::get_all_types_in_form( $form_id, 'divider' ),
			function ( $total, $divider ) {
				if ( FrmField::is_repeating_field( $divider ) && ! empty( $divider->field_options['form_select'] ) ) {
					$total[] = $divider->field_options['form_select'];
				}

				return $total;
			},
			array()
		);
	}

	/**
	 * @param int $form_id
	 * @return array
	 */
	private static function get_embedded_form_ids( $form_id ) {
		return array_map(
			function ( $embed ) {
				return $embed->field_options['form_select'];
			},
			FrmField::get_all_types_in_form( $form_id, 'form' )
		);
	}

	/**
	 * @param int $form_id
	 * @return array
	 */
	private static function get_searchable_form_ids( $form_id ) {
		if ( ! self::free_plugin_updated_to_support_child_form_search() ) {
			return array( $form_id );
		}

		return array_merge(
			array( $form_id ),
			self::get_embedded_form_ids( $form_id ),
			self::get_repeater_form_ids( $form_id )
		);
	}

	/**
	 * Search ex: "term1 term2"        Will match the full string but will not match for just term1 or term2.
	 * Search ex: term1 term2          Will match match for either term1 or term2.
	 * Search ex: "term1 term2", term3 Will match for the full string "term1 term2", or for term3.
	 *
	 * @param string $s Search term.
	 * @param int    $form_id
	 * @param array  $args
	 * @return array|false
	 */
	public static function get_search_ids( $s, $form_id, $args = array() ) {
		global $wpdb;

		if ( ! $s ) {
			return false;
		}

		$search_terms = self::explode_search_terms( $s );
		$spaces       = '';
		$e_ids        = array();
		$p_search     = array();
		$pmeta_search = array();
		$search       = array();

		$and_or = apply_filters( 'frm_search_any_terms', true, $s );
		if ( $and_or ) {
			$search['or'] = 1;
		}

		$data_field = FrmProFormsHelper::has_field( 'data', $form_id, false );

		foreach ( $search_terms as $term ) {
			$p_search[] = array(
				$spaces . $wpdb->posts . '.post_title like' => $term,
				$spaces . $wpdb->posts . '.post_content like' => $term,
				'or' => 1, // search with an OR
			);

			$pmeta_search[] = array( $wpdb->postmeta . '.meta_value like' => $term );

			$search[ $spaces . 'meta_value like' ] = $term;
			$spaces .= ' '; // add a space to keep the array keys unique

			if ( is_numeric( $term ) ) {
				$e_ids[] = (int) $term;
			}

			if ( $data_field ) {
				$linked_field_ids = array();

				//search the joined entry too
				foreach ( (array) $data_field as $df ) {
					FrmProFieldsHelper::get_subform_ids( $linked_field_ids, $df );
				}
				unset( $df );

				if ( $linked_field_ids ) {
					$data_form_ids = FrmDb::get_col( $wpdb->prefix . 'frm_fields', array( 'id' => $linked_field_ids ), 'form_id' );
					unset( $linked_field_ids );

					if ( $data_form_ids ) {
						$where = array(
							'fi.form_id'      => $data_form_ids,
							'meta_value like' => $term,
						);
						$data_entry_ids = FrmEntryMeta::getEntryIds( $where );

						if ( $data_entry_ids ) {
							if ( ! isset( $search['meta_value'] ) ) {
								$search['meta_value'] = array();
							}
							$search['meta_value'] = array_merge( $search['meta_value'], $data_entry_ids );
						}
					}
				}

				unset( $data_form_ids );
			}
		}

		$p_ids = array( $search, 'or' => 1 );
		self::search_form_posts( $form_id, $p_search, $pmeta_search, $p_ids );

		// track the entry ids from posts search to include in search results.
		$item_ids_from_posts = ! empty( $p_ids['item_id'] ) ? $p_ids['item_id'] : array();

		if ( ! empty( $e_ids ) ) {
			if ( isset( $p_ids['item_id'] ) ) {
				$p_ids['item_id'] = array_merge( (array) $e_ids, (array) $p_ids['item_id'] );
			} else {
				$p_ids['item_id'] = $e_ids;
			}
		}

		$searchable_form_ids = self::get_searchable_form_ids( $form_id );
		$query               = array( 'fi.form_id' => $searchable_form_ids );
		$query[]             = $p_ids;

		if ( count( $searchable_form_ids ) === 1 || ! self::free_plugin_updated_to_support_child_form_search() ) {
			$entry_ids = FrmEntryMeta::getEntryIds( $query, '', '', true, $args );
			// post data does not rely on entry meta, so we have to add it to the results.
			// self::search_form_posts function checks search terms as well, so this is fine.
			if ( $item_ids_from_posts ) {
				$entry_ids = array_unique( array_merge( $entry_ids, $item_ids_from_posts ) );
			}
			return $entry_ids;
		}

		return FrmEntryMeta::get_top_level_entry_ids( $query, $args );
	}

	/**
	 * @param int        $form_id
	 * @param array<int> $dynamic_field_form_ids
	 * @return array<string>
	 */
	private static function get_post_meta_keys_for_all_forms_in_search( $form_id, $dynamic_field_form_ids ) {
		$all_form_ids = array_unique( array_merge( array( $form_id ), $dynamic_field_form_ids ) );
		$meta_keys    = array();
		foreach ( $all_form_ids as $current_form_id ) {
			$meta_keys    = array_unique( array_merge( $meta_keys, self::get_post_meta_keys( $current_form_id ) ) );
		}
		return $meta_keys;
	}

	/**
	 * @param int        $form_id
	 * @return array
	 */
	private static function get_post_meta_keys( $form_id ) {
		$post_action = FrmFormAction::get_action_for_form( $form_id, 'wppost' );
		if ( ! $post_action ) {
			return array();
		}

		$action = reset( $post_action );
		if ( empty( $action->post_content['post_custom_fields'] ) ) {
			return array();
		}

		$meta_keys = array();
		foreach ( $action->post_content['post_custom_fields'] as $meta ) {
			$meta_keys[] = $meta['meta_name'];
		}

		return $meta_keys;
	}

	/**
	 * @param int   $form_id
	 * @param array $p_search query for searching the posts table.
	 * @param array $pmeta_search query for seatching the postmeta table.
	 * @param array $p_ids passed by reference. The 'item_id' is updated when posts are found.
	 */
	private static function search_form_posts( $form_id, $p_search, $pmeta_search, &$p_ids ) {
		global $wpdb;

		$post_ids = FrmDb::get_col( 'frm_items', array( 'form_id' => (int) $form_id ), 'post_id' );
		if ( ! $post_ids ) {
			return;
		}

		$matching_posts = FrmDb::get_col( $wpdb->posts, $p_search, 'ID' );
		list( $dynamic_field_ids, $target_form_ids, $dynamic_post_ids ) = self::get_dynamic_field_search_info( $form_id );

		$meta_keys = self::get_post_meta_keys_for_all_forms_in_search( $form_id, $target_form_ids );
		if ( $meta_keys ) {
			$pmeta_search   = array(
				array(
					$wpdb->postmeta . '.meta_key' => $meta_keys,
				),
				$pmeta_search,
			);
			$matching_posts = array_unique( array_merge( $matching_posts, FrmDb::get_col( $wpdb->postmeta, $pmeta_search, 'post_id' ) ) );
		}

		if ( $dynamic_post_ids ) {
			$post_ids = array_merge( $post_ids, $dynamic_post_ids );
		}

		$matching_posts = array_intersect( $matching_posts, $post_ids );
		if ( ! $matching_posts ) {
			return;
		}

		$post_entries     = FrmDb::get_results( 'frm_items', array( 'post_id' => $matching_posts ), 'id, form_id' );
		$item_ids         = array();
		$dynamic_item_ids = array();

		foreach ( $post_entries as $row ) {
			if ( in_array( $row->form_id, $target_form_ids, true ) ) {
				$dynamic_item_ids[] = $row->id;
			}
			$item_ids[] = $row->id;
		}

		if ( $dynamic_item_ids ) {
			$item_ids = array_unique( array_merge( $item_ids, self::get_entry_ids_for_dynamic_field_matches( $dynamic_field_ids, $dynamic_item_ids ) ) );
		}

		if ( $item_ids ) {
			$p_ids['item_id'] = $item_ids;
		}
	}

	/**
	 * @param int $form_id
	 * @return array<array<int>>
	 */
	private static function get_dynamic_field_search_info( $form_id ) {
		$dynamic_fields    = FrmField::get_all_types_in_form( $form_id, 'data' );
		$dynamic_field_ids = array();
		$target_form_ids   = array();
		$post_ids          = array();
		$target_field_ids  = array();

		foreach ( $dynamic_fields as $field ) {
			if ( ! empty( $field->field_options['form_select'] ) ) {
				$dynamic_field_ids[] = $field->id;
				$target_field_ids[]  = $field->field_options['form_select'];
			}
		}

		if ( $target_field_ids ) {
			$target_form_ids = FrmDb::get_col( 'frm_fields', array( 'id' => $target_field_ids ), 'form_id' );
			if ( $target_form_ids ) {
				$post_ids = FrmDb::get_col( 'frm_items', array( 'form_id' => $target_form_ids ), 'post_id' );
			}
		}

		return array( $dynamic_field_ids, $target_form_ids, $post_ids );
	}

	/**
	 * If there are matches for dynamic fields, we need to get the item id for this form rather than the original item id.
	 *
	 * @param array<int> $dynamic_field_ids
	 * @param array<int> $dynamic_item_ids
	 * @return array<int>
	 */
	private static function get_entry_ids_for_dynamic_field_matches( $dynamic_field_ids, $dynamic_item_ids ) {
		return FrmDb::get_col(
			'frm_item_metas',
			array(
				'meta_value' => $dynamic_item_ids,
				'field_id'   => $dynamic_field_ids,
			),
			'item_id'
		);
	}

	/**
	 * @since 4.0
	 */
	public static function edit_button( $entry = array() ) {
		_deprecated_function( __METHOD__, '4.0' );
		if ( ! current_user_can( 'frm_edit_entries' ) ) {
			return;
		}
		?>
		<div id="publishing-action">
			<a href="<?php echo esc_url( add_query_arg( 'frm_action', 'edit' ) ); ?>" class="button-primary">
				<?php esc_html_e( 'Edit', 'formidable-pro' ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Outputs navigation links for a given current entry post.
	 *
	 * This function generates the HTML for the "previous" and "next" navigation
	 * buttons for a given set of entries and the currently selected entry.
	 *
	 * @since 6.4.1
	 *
	 * @param int    $current_entry_id The ID of the current entry.
	 * @param int    $form_id          The ID of the form that the entries belong to.
	 * @param string $action           The action to determine the base URL for navigation.
	 * @return void
	 */
	public static function get_entry_navigation( $current_entry_id, $form_id, $action ) {
		// Get all the ids from the entries of the form.
		$entry_ids = (array) FrmDb::get_col( 'frm_items', array( 'form_id' => $form_id ), 'id', array( 'order_by' => 'created_at DESC' ) );

		// Get the count of all entries.
		$total_entries_count = count( $entry_ids );

		// Find the current position of the current entry within the array.
		$current_entry_position = array_search( $current_entry_id, $entry_ids );

		// If the current entry is not found, return.
		if ( $current_entry_position === false ) {
			return;
		}

		// Determine the IDs for the previous and next entries.
		$previous_entry_id = $current_entry_position !== 0 ? $entry_ids[ $current_entry_position - 1 ] : null;
		$next_entry_id     = ( $current_entry_position < $total_entries_count - 1 ) ? $entry_ids[ $current_entry_position + 1 ] : null;

		// Determine the base URL for the specified action.
		$base_url = admin_url( 'admin.php?page=formidable-entries&frm_action=' . $action . '&id=' );

		// Include the navigation view.
		require FrmProAppHelper::plugin_path() . '/classes/views/frmpro-entries/navigation.php';
	}
}
