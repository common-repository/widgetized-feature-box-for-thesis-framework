<?php 

function display_feature_box_before_content() {
?>
<?php $widfb_option = widfb_get_global_options();  ?>
<?php if( $widfb_option['widfb_select_input_feature_box_widgets']  == '1') { ?>
                <div class="feature-box-widget-selected-1">
		<?php dynamic_sidebar('feature box Widget 1'); ?>
		</div>
<?php } elseif( $widfb_option['widfb_select_input_feature_box_widgets']  == '2') { ?>
                <div class="feature-box-widget-selected-2a">
		<?php dynamic_sidebar('feature box Widget 1'); ?>
		</div>
		<div class="feature-box-widget-selected-2b">
		<?php dynamic_sidebar('feature box Widget 2'); ?>
		</div>
<?php } elseif( $widfb_option['widfb_select_input_feature_box_widgets']  == '3') { ?>
                <div class="feature-box-widget-selected-3a">
		<?php dynamic_sidebar('feature box Widget 1'); ?>
		</div>
		<div class="feature-box-widget-selected-3b">
		<?php dynamic_sidebar('feature box Widget 2'); ?>
		</div>
		<div class="feature-box-widget-selected-3c">
		<?php dynamic_sidebar('feature box Widget 3'); ?>
		</div>
<?php } elseif( $widfb_option['widfb_select_input_feature_box_widgets']  == '4') { ?>
                <div class="feature-box-widget1">
		<?php dynamic_sidebar('feature box Widget 1'); ?>
		</div>
		<div class="feature-box-widget2">
		<?php dynamic_sidebar('feature box Widget 2'); ?>
		</div>
		<div class="feature-box-widget3">
		<?php dynamic_sidebar('feature box Widget 3'); ?>
		</div>
		<div class="feature-box-widget4">
		<?php dynamic_sidebar('feature box Widget 4'); ?>
		</div>
<?php }  // end if/else ?>
 <?php } 

add_action('display_feature_box', 'display_feature_box_before_content', 7);
?>