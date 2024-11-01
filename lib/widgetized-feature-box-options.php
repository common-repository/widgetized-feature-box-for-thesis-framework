<?php

    function widfb_options_page_sections() {  
      
        $sections = array();  
        // $sections[$id]       = __($title, 'widfb_textdomain');  
        $sections['feature_box_section'] = __('Feature Box Options', 'widfb_feature_box');      
        return $sections;  
    }  
    
    function widfb_options_page_fields() {  
	
			$options[] = array(
				"section" => "feature_box_section",
				"id"      => WIDGETIZED_FB_SHORTNAME . "_feature_box",
				"title"   => __( 'Feature Box', 'widfb_textdomain' ),
				"desc"    => __( 'Select where to display the feature box', 'widfb_textdomain' ),
				"type"    => "multi-checkbox",
				"std"     => 0,
				"choices" => array( __('Front Page','widfb_textdomain') . "|frontpage", __('Post pages','widfb_textdomain') . "|postpage", __('Main Pages','widfb_textdomain') . "|mainpages", __('Archive Pages','widfb_textdomain') . "|archivepage")	
			);
			
			$options[] = array(
				"section" => "feature_box_section",
				"id"      => WIDGETIZED_FB_SHORTNAME . "_select_input_feature_box_widgets",
				"title"   => __( 'Enable the Feature Box Widgets', 'widfb_textdomain' ),
				"desc"    => __( 'Select the numner of Widgets', 'widfb_textdomain' ),
				"type"    => "select",
				"std"    => "4",
				"choices" => array( "1", "2", "3", "4")
			);
			
			$options[] = array(
				"section" => "feature_box_section",
				"id"      => WIDGETIZED_FB_SHORTNAME . "_checkbox_input_feature_box_title",
				"title"   => __( 'Feature Box Ttitle', 'widfb_textdomain' ),
				"desc"    => __( 'Enable the feature box Title', 'widfb_textdomain' ),
				"type"    => "checkbox",
				"std"     => 1 // 0 for off
			);	

			$options[] = array(
				"section" => "feature_box_section",
				"id"      => WIDGETIZED_FB_SHORTNAME . "_txt_input_feature_box_title",
				"title"   => __( 'Feature Box Title', 'widfb_textdomain' ),
				"desc"    => __( 'A textarea for a block of text. HTML tags allowed!', 'widfb_textdomain' ),
				"type"    => "text",
				"std"     => __('Some default value','widfb_textdomain')
			);
			
			$options[] = array(
				"section" => "feature_box_section",
				"id"      => WIDGETIZED_FB_SHORTNAME . "_checkbox_input_feature_box_news",
				"title"   => __( 'Feature Box News Bar', 'widfb_textdomain' ),
				"desc"    => __( 'Enable the feature box news Bar', 'widfb_textdomain' ),
				"type"    => "checkbox",
				"std"     => 1 // 0 for off
			);

			$options[] = array(
				"section" => "feature_box_section",
				"id"      => WIDGETIZED_FB_SHORTNAME . "_txt_input_featured_news",
				"title"   => __( 'Feature Box News Bar message Box', 'widfb_textdomain' ),
				"desc"    => __( 'This will display on the news bar if enabled. Some HTML is allowed as are adding links including target="_blank" to open links in a new window.</br>For more information visit <a href="http://diywpblog.com">DIY WP Blog</a> to find out more.', 'widfb_textdomain' ),
				"type"    => "textarea",
				"std"     => __('Enter a message to display on the news bar.','widfb_textdomain'),
				"class"   => ""
			);	              
    return $options;    
    }
?>