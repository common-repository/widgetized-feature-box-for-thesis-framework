<?php
/*
Plugin Name: Widgetized Feature Box Thesis Framework
Plugin URI: http://diywpblog.com/widgetized-feature-box-for-thesis-framework/
Description: adds a widgetized feature box to the Thesis Framework. Can be used to display recommended products, pictures, videos, notifications and more. Select 1 - 4 widgets, title and news box aswell as selecting which pages it should be displayed on. Does not work with Non Thesis themes.
Version: 1.0.0.0
Author: Matthew Horne
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8K3Z6N6KXPJYU
Author URI: http://diywpblog.com
License: GPLv2 or later
*/

/*  Copyright 2012  Matthew Horne  (email : mattjhorne@hotmail.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// page settings sections & fields  
require_once('lib/widgetized-feature-box-options.php');  
// i can has custom hook
function display_feature_box() {
	do_action('display_feature_box');
}
include_once 'lib/feature-box.php'; 
/* 
* Define Constants 
*/  
define('WIDGETIZED_FB_SHORTNAME', 'widfb');
define('WIDGETIZED_FB_PAGE_BASENAME', 'widfb-settings');

/* 
 * Specify Hooks/Filters 
 */  
add_action( 'admin_menu', 'widfb_add_menu' );  
add_action( 'admin_init', 'widfb_register_settings' );

function widfb_settings_scripts(){  
    wp_enqueue_style('widfb_theme_settings_css', WP_PLUGIN_URL . '/widgetized-feature-box-thesis/css/widfb_admin_style.css');    
}

function widfb_add_menu(){  
  
    $widfb_settings_page = add_options_page(__('Widgetized FB Options'), __('Widgetized FB Settings','widfb_textdomain'), 'manage_options', WIDGETIZED_FB_PAGE_BASENAME, 'widfb_settings_page_fn'); 
    
    add_action( 'load-'. $widfb_settings_page, 'widfb_settings_scripts' ); 
} 
  
    function widfb_get_settings() {  
      
        $output = array();  
      
        // put together the output array  
        $output['widfb_option_name']       = 'widfb_options';
        $output['widfb_page_title']        = __( 'Widgetized Feature Box for Thesis Settings','widfb_textdomain');
        $output['widfb_page_sections']     = widfb_options_page_sections();
        $output['widfb_page_fields']       = widfb_options_page_fields();
      
    return $output;  
    }

    function widfb_create_settings_field( $args = array() ) {  
        // default array to overwrite when calling the function  
        $defaults = array(  
            'id'      => 'default_field', 
            'title'   => 'Default Field', 
            'desc'    => 'This is a default description.', 
            'std'     => '',  
            'type'    => 'text', 
            'section' => 'main_section', 
            'choices' => array(),  
            'class'   => '' 
        );  
      
        extract( wp_parse_args( $args, $defaults ) );  
      
        $field_args = array(  
            'type'      => $type,  
            'id'        => $id,  
            'desc'      => $desc,  
            'std'       => $std,  
            'choices'   => $choices,  
            'label_for' => $id,  
            'class'     => $class  
        );  
      
        add_settings_field( $id, $title, 'widfb_form_field_fn', __FILE__, $section, $field_args );  
      
    }     
      
    function widfb_register_settings(){  
      
        $settings_output    = widfb_get_settings();  
        $widfb_option_name = $settings_output['widfb_option_name'];  
        register_setting($widfb_option_name, $widfb_option_name, 'widfb_validate_options' );   
        if(!empty($settings_output['widfb_page_sections'])){  
            // call the "add_settings_section" for each!  
            foreach ( $settings_output['widfb_page_sections'] as $id => $title ) {  
                add_settings_section( $id, $title, 'widfb_section_fn', __FILE__);  
            }  
        }  
      
        if(!empty($settings_output['widfb_page_fields'])){  
            // call the "add_settings_field" for each!  
            foreach ($settings_output['widfb_page_fields'] as $option) {  
                widfb_create_settings_field($option);  
            }  
        }  
    }         
 
    function widfb_form_field_fn($args = array()) {  
      
        extract( $args );  
      
        // get the settings sections array  
        $settings_output    = widfb_get_settings();  
      
        $widfb_option_name = $settings_output['widfb_option_name'];  
        $options            = get_option($widfb_option_name);  
      
        // pass the standard value if the option is not yet set in the database  
        if ( !isset( $options[$id] ) && 'type' != 'checkbox' ) {  
            $options[$id] = $std;  
        }  
      
        // additional field class. output only if the class is defined in the create_setting arguments  
        $field_class = ($class != '') ? ' ' . $class : '';  
      
        // switch html display based on the setting type.  
        switch ( $type ) {
           
          
            case 'text':  
                $options[$id] = stripslashes($options[$id]);  
                $options[$id] = esc_attr( $options[$id]);  
                echo "<input class='regular-text$field_class' type='text' id='$id' name='" . $widfb_option_name . "[$id]' value='$options[$id]' />";  
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
            break;  
      

      
            case 'textarea':  
                $options[$id] = stripslashes($options[$id]);  
                $options[$id] = esc_html( $options[$id]);  
                echo "<textarea class='textarea$field_class' type='text' id='$id' name='" . $widfb_option_name . "[$id]' rows='3' cols='50'>$options[$id]</textarea>";  
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
            break;  
      
            case 'select':
			echo "<select id='$id' class='select$field_class' name='" . $widfb_option_name . "[$id]'>";
				foreach($choices as $item) {
					$value 	= esc_attr($item, 'widfb_textdomain');
					$item 	= esc_html($item, 'widfb_textdomain');
					
					$selected = ($options[$id]==$value) ? 'selected="selected"' : '';
					echo "<option value='$value' $selected>$item</option>";
				}
			echo "</select>";
			echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : ""; 
		break;
            case 'checkbox':  
                echo "<input class='checkbox$field_class' type='checkbox' id='$id' name='" . $widfb_option_name . "[$id]' value='1' " . checked( $options[$id], 1, false ) . " />";  
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
            break;  
      
            case "multi-checkbox":  
                foreach($choices as $item) {  
      
                    $item = explode("|",$item);  
                    $item[0] = esc_html($item[0], 'widfb_textdomain');  
      
                    $checked = '';  
      
                    if ( isset($options[$id][$item[1]]) ) {  
                        if ( $options[$id][$item[1]] == 'true') {  
                            $checked = 'checked="checked"';  
                        }  
                    }  
      
                    echo "<input class='checkbox$field_class' type='checkbox' id='$id|$item[1]' name='" . $widfb_option_name . "[$id|$item[1]]' value='1' $checked /> $item[0] <br/>";  
                }  
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
            break;  
        }  

    }   

function  widfb_section_fn($desc) {  
    echo "<p>" . __('Thank you for downloading the widgetized Feature Box for Thesis. It has been an area of thesis in which we have all enjoyed and out of that I decided to create this plugin to give people that little extra help in applying the Thesis Feature Box.</br></br>Below are some of the options that I have added so far and plan to add more options along the way.</br></br>If you have a suggestion then please feel free to contact me via <a href="http://diywpblog.com/contact/" target="_blank">DIY WP Blog contact page</a></br></br>Like this Plugin? <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8K3Z6N6KXPJYU" target="_blank">Consider a donation</a>','widfb_textdomain') . "</p>";  
}

function widfb_settings_page_fn() {  
// get the settings sections array  
    $settings_output = widfb_get_settings();  
?>  
       
        <h2 class="widfb_admin_title"><?php echo $settings_output['widfb_page_title']; ?></h2>  
        <div class="widfb_options_page">
        <form action="options.php" method="post">  
            <?php  
            // http://codex.wordpress.org/Function_Reference/settings_fields  
            settings_fields($settings_output['widfb_option_name']);   
  
            // http://codex.wordpress.org/Function_Reference/do_settings_sections  
            do_settings_sections(__FILE__);  
            ?>  
            <p class="submit">  
                <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes','widfb_textdomain'); ?>" />  
            </p>  
  
        </form>  
    </div><!-- wrap -->  
<?php }  

    function widfb_validate_options($input) {  

        $valid_input = array();  

            $settings_output = widfb_get_settings();  
      
            $options = $settings_output['widfb_page_fields'];  
      
            foreach ($options as $option) {  
      
                switch ( $option['type'] ) {  
                    case 'text':  

                        switch ( $option['class'] ) {  
                            case 'numeric':  
                                $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace  
                                $valid_input[$option['id']] = (is_numeric($input[$option['id']])) ? $input[$option['id']] : 'Expecting a Numeric value!';  
      
                                // register error  
                                if(is_numeric($input[$option['id']]) == FALSE) {  
                                    add_settings_error(  
                                        $option['id'], // setting title  
                                        WIDGETIZED_FB_SHORTNAME . '_txt_numeric_error', // error ID  
                                        __('Expecting a Numeric value! Please fix.','widfb_textdomain'), // error message  
                                        'error' // type of message  
                                    );  
                                }  
                            break;  
      
                            //for multi-numeric values (separated by a comma)  
                            case 'multinumeric':  
                                //accept the input only when the numeric values are comma separated  
                                $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace  
      
                                if($input[$option['id']] !=''){  
                                    // /^-?\d+(?:,\s?-?\d+)*$/ matches: -1 | 1 | -12,-23 | 12,23 | -123, -234 | 123, 234  | etc.  
                                    $valid_input[$option['id']] = (preg_match('/^-?\d+(?:,\s?-?\d+)*$/', $input[$option['id']]) == 1) ? $input[$option['id']] : __('Expecting comma separated numeric values','widfb_textdomain');  
                                }else{  
                                    $valid_input[$option['id']] = $input[$option['id']];  
                                }  
      
                                // register error  
                                if($input[$option['id']] !='' && preg_match('/^-?\d+(?:,\s?-?\d+)*$/', $input[$option['id']]) != 1) {  
                                    add_settings_error(  
                                        $option['id'], // setting title  
                                        WIDGETIZED_FB_SHORTNAME . '_txt_multinumeric_error', // error ID  
                                        __('Expecting comma separated numeric values! Please fix.','widfb_textdomain'), // error message  
                                        'error' // type of message  
                                    );  
                                }  
                            break;  
      
                            // a "cover-all" fall-back when the class argument is not set  
                            default:  
                                // accept only a few inline html elements  
                                $allowed_html = array(  
                                    'a' => array('href' => array (),'title' => array ()),  
                                    'b' => array(),  
                                    'em' => array (),  
                                    'i' => array (),  
                                    'strong' => array()  
                                );  
      
                                $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace  
                                $input[$option['id']]       = force_balance_tags($input[$option['id']]); // find incorrectly nested or missing closing tags and fix markup  
                                $input[$option['id']]       = wp_kses( $input[$option['id']], $allowed_html); // need to add slashes still before sending to the database  
                                $valid_input[$option['id']] = addslashes($input[$option['id']]);  
                            break;  
                        }  
                    break;  
      
                    case "multi-text":  
                        // this will hold the text values as an array of 'key' => 'value'  
                        unset($textarray);  
      
                        $text_values = array();  
                        foreach ($option['choices'] as $k => $v ) {  
                            // explode the connective  
                            $pieces = explode("|", $v);  
      
                            $text_values[] = $pieces[1];  
                        }  
      
                        foreach ($text_values as $v ) {       
      
                            // Check that the option isn't empty  
                            if (!empty($input[$option['id'] . '|' . $v])) { 
                                // If it's not null, make sure it's sanitized, add it to an array 
                                switch ($option['class']) { 
                                    // different sanitation actions based on the class create you own cases as you need them 
     
                                    //for numeric input 
                                    case 'numeric': 
                                        //accept the input only if is numberic! 
                                        $input[$option['id'] . '|' . $v]= trim($input[$option['id'] . '|' . $v]); // trim whitespace 
                                        $input[$option['id'] . '|' . $v]= (is_numeric($input[$option['id'] . '|' . $v])) ? $input[$option['id'] . '|' . $v] : ''; 
                                    break; 
     
                                    // a "cover-all" fall-back when the class argument is not set 
                                    default: 
                                        // strip all html tags and white-space. 
                                        $input[$option['id'] . '|' . $v]= sanitize_text_field($input[$option['id'] . '|' . $v]); // need to add slashes still before sending to the database 
                                        $input[$option['id'] . '|' . $v]= addslashes($input[$option['id'] . '|' . $v]); 
                                    break; 
                                } 
                                // pass the sanitized user input to our $textarray array 
                                $textarray[$v] = $input[$option['id'] . '|' . $v]; 
     
                            } else { 
                                $textarray[$v] = ''; 
                            } 
                        } 
                        // pass the non-empty $textarray to our $valid_input array 
                        if (!empty($textarray)) { 
                            $valid_input[$option['id']] = $textarray; 
                        } 
                    break; 
     
                    case 'textarea': 
                        //switch validation based on the class! 
                        switch ( $option['class'] ) { 
                            //for only inline html 
                            case 'inlinehtml': 
                                // accept only inline html 
                                $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace 
                                $input[$option['id']]       = force_balance_tags($input[$option['id']]); // find incorrectly nested or missing closing tags and fix markup 
                                $input[$option['id']]       = addslashes($input[$option['id']]); //wp_filter_kses expects content to be escaped! 
                                $valid_input[$option['id']] = wp_filter_kses($input[$option['id']]); //calls stripslashes then addslashes 
                            break; 
     
                            //for no html 
                            case 'nohtml': 
                                //accept the input only after stripping out all html, extra white space etc! 
                                $input[$option['id']]       = sanitize_text_field($input[$option['id']]); // need to add slashes still before sending to the database 
                                $valid_input[$option['id']] = addslashes($input[$option['id']]); 
                            break; 
     
                            //for allowlinebreaks 
                            case 'allowlinebreaks': 
                                //accept the input only after stripping out all html, extra white space etc! 
                                $input[$option['id']]       = wp_strip_all_tags($input[$option['id']]); // need to add slashes still before sending to the database 
                                $valid_input[$option['id']] = addslashes($input[$option['id']]); 
                            break; 
     
                            // a "cover-all" fall-back when the class argument is not set 
                            default: 
                                // accept only limited html 
                                //my allowed html 
                                $allowed_html = array( 
                                    'a'             => array('href' => array (),'target' => array (),'title' => array ()), 
                                    'b'             => array(), 
                                    'blockquote'    => array('cite' => array ()), 
                                    'br'            => array(), 
                                    'dd'            => array(), 
                                    'dl'            => array(), 
                                    'dt'            => array(), 
                                    'em'            => array (), 
                                    'i'             => array (), 
                                    'li'            => array(), 
                                    'ol'            => array(), 
                                    'p'             => array(), 
                                    'q'             => array('cite' => array ()), 
                                    'strong'        => array(), 
                                    'ul'            => array(), 
                                    'h1'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                    'h2'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                    'h3'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                    'h4'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                    'h5'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                    'h6'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()) 
                                ); 
     
                                $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace 
                                $input[$option['id']]       = force_balance_tags($input[$option['id']]); // find incorrectly nested or missing closing tags and fix markup 
                                $input[$option['id']]       = wp_kses( $input[$option['id']], $allowed_html); // need to add slashes still before sending to the database 
                                $valid_input[$option['id']] = stripslashes($input[$option['id']]); 
                            break; 
                        } 
                    break; 
     
                    case 'select': 
                        // check to see if the selected value is in our approved array of values! 
                        $valid_input[$option['id']] = (in_array( $input[$option['id']], $option['choices']) ? $input[$option['id']] : '' ); 
                    break; 
     
                    case 'select2': 
                        // process $select_values 
                            $select_values = array(); 
                            foreach ($option['choices'] as $k => $v) { 
                                // explode the connective 
                                $pieces = explode("|", $v); 
     
                                $select_values[] = $pieces[1]; 
                            } 
                        // check to see if selected value is in our approved array of values! 
                        $valid_input[$option['id']] = (in_array( $input[$option['id']], $select_values) ? $input[$option['id']] : '' ); 
                    break; 
     
                    case 'checkbox': 
                        // if it's not set, default to null!  
                        if (!isset($input[$option['id']])) {  
                            $input[$option['id']] = null;  
                        }  
                        // Our checkbox value is either 0 or 1  
                        $valid_input[$option['id']] = ( $input[$option['id']] == 1 ? 1 : 0 );  
                    break;  
      
                    case 'multi-checkbox':  
                        unset($checkboxarray);  
                        $check_values = array();  
                        foreach ($option['choices'] as $k => $v ) {  
                            // explode the connective  
                            $pieces = explode("|", $v);  
      
                            $check_values[] = $pieces[1];  
                        }  
      
                        foreach ($check_values as $v ) {          
      
                            // Check that the option isn't null  
                            if (!empty($input[$option['id'] . '|' . $v])) { 
                                // If it's not null, make sure it's true, add it to an array 
                                $checkboxarray[$v] = 'true'; 
                            } 
                            else { 
                                $checkboxarray[$v] = 'false'; 
                            } 
                        } 
                        // Take all the items that were checked, and set them as the main option 
                        if (!empty($checkboxarray)) { 
                            $valid_input[$option['id']] = $checkboxarray;  
                        }  
                    break;  
      
                }  
            }  
    return $valid_input; // return validated input  
    }  

function widfb_get_global_options(){
	
	$widfb_option = array();

	// collect option names as declared in widfb_get_settings()
	$widfb_option_names = array (
		'widfb_options', 
		'widfb_options_feature_box',
		'widfb_options_txt_input_feature_box_title',
		'widfb_options_txt_input_featured_news'
	);

	// loop for get_option
	foreach ($widfb_option_names as $widfb_option_name) {
		if (get_option($widfb_option_name)!= FALSE) {
			$option 	= get_option($widfb_option_name);
			
			// now merge in main $socialise_option array!
			$widfb_option = array_merge($widfb_option, $option);
		}
	}	
	
return $widfb_option;
}
 

function widfb_show_msg($message, $msgclass = 'info') {
	echo "<div id='message' class='$msgclass'>$message</div>";
}


function widgetized_feature_box_script()  
{  
    // Register the style like this for a plugin:  
    wp_register_style( 'custom-style', plugins_url( '/css/feature-box-style.css', __FILE__ ), array(), '20120208', 'all' ); 

    // For either a plugin or a theme, you can then enqueue the style:  
    wp_enqueue_style( 'custom-style' );  
}  
add_action( 'wp_enqueue_scripts', 'widgetized_feature_box_script' ); 




function conditional_feature_box(){
if (is_front_page()){ ?>
     
              <?php $widfb_option = widfb_get_global_options();  ?>
              <?php if( $widfb_option['widfb_feature_box']['frontpage']  == 'true') { ?>
              <?php if( $widfb_option['widfb_checkbox_input_feature_box_title'] == '1') { ?>
            <h2 id="featured_title"><?php echo $widfb_option['widfb_txt_input_feature_box_title']; ?></h2>
              <?php }  // end if ?>  
		<?php display_feature_box(); ?>
	      <?php if( $widfb_option['widfb_checkbox_input_feature_box_news'] == '1') { ?>
         <div class="news-bar"><h3><?php echo $widfb_option['widfb_txt_input_featured_news']; ?></h3></div>
              <?php }  // end if ?>
    <?php }  // end if ?>  
	
 <?php }  elseif (is_page()){ ?>
     
              <?php $widfb_option = widfb_get_global_options();  ?>
              <?php if( $widfb_option['widfb_feature_box']['mainpages']  == 'true') { ?>
              <?php if( $widfb_option['widfb_checkbox_input_feature_box_title'] == '1') { ?>
            <h2 id="featured_title"><?php echo $widfb_option['widfb_txt_input_feature_box_title']; ?></h2>
              <?php }  // end if ?>  
		<?php display_feature_box(); ?>
	      <?php if( $widfb_option['widfb_checkbox_input_feature_box_news'] == '1') { ?>
         <div class="news-bar"><h3><?php echo $widfb_option['widfb_txt_input_featured_news']; ?></h3></div>
              <?php }  // end if ?>
    <?php }  // end if ?>  
	
<?php }  elseif (is_single()){ ?>

 <?php $widfb_option = widfb_get_global_options();  ?>
              <?php if( $widfb_option['widfb_feature_box']['postpage']  == 'true') { ?>
              <?php if( $widfb_option['widfb_checkbox_input_feature_box_title'] == '1') { ?>
            <h2 id="featured_title"><?php echo $widfb_option['widfb_txt_input_feature_box_title']; ?></h2>
              <?php }  // end if ?>  
		<?php display_feature_box(); ?>
	      <?php if( $widfb_option['widfb_checkbox_input_feature_box_news'] == '1') { ?>
         <div class="news-bar"><h3><?php echo $widfb_option['widfb_txt_input_featured_news']; ?></h3></div>
              <?php }  // end if ?>
    <?php }  // end if ?>  
	
<?php }  elseif (is_archive()){ ?>

 <?php $widfb_option = widfb_get_global_options();  ?>
              <?php if( $widfb_option['widfb_feature_box']['archivepage']  == 'true') { ?>
              <?php if( $widfb_option['widfb_checkbox_input_feature_box_title'] == '1') { ?>
            <h2 id="featured_title"><?php echo $widfb_option['widfb_txt_input_feature_box_title']; ?></h2>
              <?php }  // end if ?>  
		<?php display_feature_box(); ?>
	      <?php if( $widfb_option['widfb_checkbox_input_feature_box_news'] == '1') { ?>
         <div class="news-bar"><h3><?php echo $widfb_option['widfb_txt_input_featured_news']; ?></h3></div>
              <?php }  // end if ?>
    <?php }  // end if ?> 
<?php }		
}
add_action('thesis_hook_feature_box','conditional_feature_box');    

function self_deprecating_sidebar_registration(){
  register_sidebar( 'register_sidebar' );
        register_sidebar(array (
		'name'=>'Feature box widget 1',
		'id'=>'feature-box-widget1',
			'before_widget' => '<li class="widget %2$s" id="%1$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>'
		));
	register_sidebar(array (
		'name'=>'Feature box widget 2',
		'id'=>'feature-box-widget2',
			'before_widget' => '<li class="widget %2$s" id="%1$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>'
		));
	register_sidebar(array (
		'name'=>'Feature box widget 3',
		'id'=>'feature-box-widget3',
			'before_widget' => '<li class="widget %2$s" id="%1$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>'
		));
	register_sidebar(array (
		'name'=>'Feature box widget 4',
		'id'=>'feature-box-widget4',
			'before_widget' => '<li class="widget %2$s" id="%1$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>'
		));
  
}

add_action( 'wp_loaded', 'self_deprecating_sidebar_registration' );
?>