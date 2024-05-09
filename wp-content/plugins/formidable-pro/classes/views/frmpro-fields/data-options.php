<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

// Check if field is read only
$disabled = ( FrmField::is_read_only( $field ) && ! FrmAppHelper::is_admin() ) ? ' disabled="disabled"' : '';

// Dynamic Dropdowns
if ( $field['data_type'] === 'select' ) {
    if ( ! empty( $field['options'] ) ) {
    	if ( $disabled ) {
    ?>
<select disabled="disabled" <?php do_action( 'frm_field_input_html', $field ); ?>>
<?php
		} else {
			?>
<select name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $html_id ); ?>" <?php do_action( 'frm_field_input_html', $field ); ?>>
<?php
		}

		if ( $field['options'] ) {
			$placeholder = FrmField::get_option( $field, 'placeholder' );
			foreach ( $field['options'] as $opt_key => $opt ) {
				$option_params  = array();
				$is_placeholder = $opt == $placeholder;
				if ( $is_placeholder && $field['autocom'] && ! FrmProAppHelper::use_chosen_js() ) {
					$option_params['data-placeholder'] = 'true';
				}

				$selected               = $opt_key !== '' && ( $field['value'] == $opt_key || in_array( $opt_key, (array) $field['value'] ) );
				$option_params['value'] = $opt_key;

				FrmProHtmlHelper::echo_dropdown_option( $opt == '' ? ' ' : $opt, $selected, $option_params );
			}
		}
?>
</select>
<?php
    }
} else if ( $field['data_type'] == 'data' && is_numeric( $field['hide_opt'] ) && is_numeric( $field['form_select'] ) ) {
	$value = FrmEntryMeta::get_entry_meta_by_field($field['hide_opt'], $field['form_select']);
	echo wp_kses_post( $value );
	?>
    <input type="hidden" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $html_id ); ?>" />
<?php
} elseif ( $field['data_type'] == 'data' && is_numeric( $field['hide_field'] ) && is_numeric( $field['form_select'] ) ) {
	$get_id = FrmAppHelper::simple_get( 'id' );
	if ( $_POST && isset( $_POST['item_meta'] ) ) {
		$observed_field_val = $_POST['item_meta'][ $field['hide_field'] ];
	} else if ( $get_id ) {
		$observed_field_val = FrmEntryMeta::get_entry_meta_by_field( $get_id, $field['hide_field'] );
	}

    if ( isset( $observed_field_val ) && is_numeric( $observed_field_val ) ) {
        $value = FrmEntryMeta::get_entry_meta_by_field($observed_field_val, $field['form_select']);
	} else {
        $value = '';
	}
?>
<p><?php echo wp_kses_post( $value ); ?></p>
<input type="hidden" value="<?php echo esc_attr($value); ?>" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $html_id ); ?>" />
<?php } else if ( $field['data_type'] == 'data' && ! is_array($field['value']) ) { ?>
<p><?php echo wp_kses_post( $field['value'] ); ?></p>
<input type="hidden" value="<?php echo esc_attr( $field['value'] ); ?>" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $html_id ); ?>" />
<?php
} elseif ( $field['data_type'] == 'text' && is_numeric( $field['form_select'] ) ) {
	$get_id = FrmAppHelper::simple_get( 'id' );
	if ( $_POST && isset( $_POST['item_meta'] ) ) {
		$observed_field_val = $_POST['item_meta'][ $field['hide_field'] ];
	} else if ( $get_id ) {
		$observed_field_val = FrmEntryMeta::get_entry_meta_by_field( $get_id, $field['hide_field'] );
	}

	if ( isset( $observed_field_val ) && is_numeric( $observed_field_val ) ) {
        $value = FrmEntryMeta::get_entry_meta_by_field($observed_field_val, $field['form_select']);
	} else {
        $value = '';
	}
?>
<input type="text" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $html_id ); ?>" />

<?php
} else if ( $field['data_type'] == 'checkbox' ) {
    $checked_values = $field['value'];

    if ( ! empty($field['options']) ) {
		$option_index = 0;
		foreach ( $field['options'] as $opt_key => $opt ) {
            $checked = ( ( ! is_array( $field['value'] ) && $field['value'] == $opt_key ) || ( is_array( $field['value'] ) && in_array( $opt_key, $field['value'] ) ) ) ? ' checked="checked"' : '';
			?>
<div class="<?php echo esc_attr( apply_filters( 'frm_checkbox_class', 'frm_checkbox', $field, $opt_key ) ); ?>">
	<label for="<?php echo esc_attr( $html_id . '-' . $opt_key ); ?>">
		<input type="checkbox" name="<?php echo esc_attr( $field_name ); ?>[]" id="<?php echo esc_attr( $html_id . '-' . $opt_key ); ?>" value="<?php echo esc_attr( $opt_key ); ?>" <?php
	echo $checked . $disabled . ' ';

	if ( 0 === $option_index && FrmField::is_required( $field ) ) {
		echo ' aria-required="true" ';
	}

    do_action( 'frm_field_input_html', $field );
?> /> <?php echo $opt; ?>
	</label>
</div>
<?php
			++$option_index;
		}
	}
} else if ( $field['data_type'] == 'radio' ) {
    if ( ! empty($field['options']) ) {
        foreach ( $field['options'] as $opt_key => $opt ) {
			$checked = checked( $field['value'] !== '' && in_array( $opt_key, (array) $field['value'] ), 1, false );
			?>
<div class="<?php echo esc_attr( apply_filters( 'frm_radio_class', 'frm_radio', $field, $opt_key ) ); ?>">
	<label for="<?php echo esc_attr( $html_id . '-' . $opt_key ); ?>">
		<input type="radio" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $html_id . '-' . $opt_key ); ?>" value="<?php echo esc_attr( $opt_key ); ?>" <?php
    echo $checked . $disabled . ' ';
    do_action( 'frm_field_input_html', $field );
?> /> <?php echo $opt; ?>
	</label>
</div>
<?php
        }
	}
}
