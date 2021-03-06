<?php

/*
Section: Easy Pie Chart
Author: Enrique Chavez
Author URI: http://tmeister.net
Version: 1.1
Description: Easy pie chart is a section to show simple pie charts for single values. These charts are highly customizable, very easy to implement, scale to the resolution of the display of the client to provide sharp charts even on retina displays
Class Name: SOPieChart
V3: true
Filter: misc
*/

/**
*
*/
class SOPieChart extends PageLinesSection {

    var $section_name    = 'Easy Pie Chart';
    var $section_version = '1.1';
    var $option_name     = 'easy_pie_chart_license_key';
    var $section_key;
    var $chavezShop;

    function section_persistent(){
        $this->verify_license();
        add_filter('pl_sorted_settings_array', array(&$this, 'add_global_panel'));
    }

    function verify_license(){
        if( !class_exists( 'chavezShopVerifier' ) ) {
            include( dirname( __FILE__ ) . '/inc/chavezshop_verifier.php' );
        }
        $this->chavezShop = new chavezShopVerifier( $this->section_name, $this->section_version, $this->opt($this->option_name) );
    }

    function add_global_panel($settings){
        $valid = "";
        if( get_option( $this->section_key."_activated" ) ){
            $valid = ( $this->chavezShop->check_license() ) ? ' - Your license is valid' : ' - Your license is invalid';
        }

        if( !isset( $settings['eChavez'] ) ){
            $settings['eChavez'] = array(
                'name' => 'Enrique Chavez Shop',
                'icon' => 'icon-shopping-cart',
                'opts' => array()
            );
        }

        $opts = array(
            'key'   => $this->option_name,
            'type'  => 'text',
            'title' => '<i class="icon-shopping-cart"></i> ' . __('Easy Pie Chart License Key', 'easy-pie-chart') . $valid,
            'label' => __('License Key', 'easy-pie-chart'),
            'help'  => __('The section is fully functional whitout a key license, this license is used only get access to autoupdates within your admin.', 'easy-pie-chart')

        );

        array_push($settings['eChavez']['opts'], $opts);
        return $settings;

    }

	function section_styles(){
		wp_enqueue_script( 'easy-pie-chart', $this->base_url . '/js/jquery.easy-pie-chart.js', array( 'jquery' ), '1.2.3', true );
	}
	function section_head(){
        $bar_color = ($this->opt('pie_bar_color') ) ? pl_hashify( $this->opt('pie_bar_color')) : '#ef1e25';
        $track_color = ($this->opt('pie_track_color') ) ? pl_hashify( $this->opt('pie_track_color')) : '#f2f2f2';
        $scale_color = ($this->opt('pie_scale_color') ) ? pl_hashify( $this->opt('pie_scale_color')) : '#f2f2f2';
        $pie_line_width = ($this->opt('pie_line_width')) ? $this->opt('pie_line_width') : 10;
        $boxes = $this->opt('pie_boxes');
	?>
		<script type="text/javascript">
            jQuery(document).ready(function($) {

                var easyAnimation = false;

                jQuery(window).bind("load", function(event) {

                   if( easyAnimation){return;}

                   jQuery(".section-easy-pie-chart:in-viewport").each(function() {
                        createChart<?php echo $this->meta["clone"] ?>();
                        easyAnimation = true;
                    });

                });

                createChart<?php echo $this->meta["clone"] ?> = function(){
                    jQuery('<?php echo ".". $this->meta["clone"] ?> .percentage').easyPieChart({
                        animate: 1000,
                        size: 200,
                        barColor: '<?php echo pl_hashify( $bar_color ) ?>',
                        trackColor : '<?php echo pl_hashify( $track_color ) ?>',
                        scaleColor : '<?php echo pl_hashify( $scale_color ) ?>',
                        lineWidth : <?php echo $pie_line_width ?>,
                        lineCap: "square",
                        onStep: function(value) {
                            this.$el.find('span').text(Math.ceil( value )) ;
                        }
                    });
                }
            });
        </script>
	<?php
	}

    function section_opts(){
        $opts = array(
            array(
                'key'           => 'box-setup',
                'type'          => 'multi',
                'title'         => 'Pie Chart Configuration',
                'label'         => 'Pie Chart Configuration',
                'opts' => array(
                    array(
                        'key' => "pie_boxes",
                        'type' => 'count_select',
                        'count_start'   => 1,
                        'count_number'  => 12,
                        'label' => 'Number of chart boxes to configure'
                    ),
                    array(
                        'key' => 'pie_span',
                        'type' => 'count_select',
                        'count_start'   => 1,
                        'count_number'  => 12,
                        'label' => 'Number of Columns for each box (12 Col Grid) '
                    ),
                    array(
                        'key' => 'pie_track_color',
                        'type' => 'color',
                        'default' => '#f2f2f2',
                        'label' => 'The color of the track bar.'
                    ),
                    array(
                        'key' => 'pie_bar_color',
                        'type' => 'color',
                        'default' => '#ef1e25',
                        'label' => 'The color of the curcular bar.'
                    ),
                    array(
                        'key' => 'pie_scale_color',
                        'type' => 'color',
                        'default' => '#f2f2f2',
                        'label' => 'The color of the scale lines.'
                    ),
                    array(
                        'key' => 'pie_line_width',
                        'type' => 'count_select',
                        'count_start'   => 1,
                        'count_number'  => 10,
                        'label' => 'Bar line width'
                    )
                )
            )
        );
        $opts = $this->create_box_settings($opts);
        return $opts;
    }

    function create_box_settings($opts){
        $loopCount = (  $this->opt('pie_boxes') ) ? $this->opt('pie_boxes') : 0;
        for ($i=0; $i < $loopCount; $i++) {
            $box = array(
                'key' => 'pie_box_'.$i,
                'type' =>  'multi',
                'title' => 'Pie Box ' . ($i+1) .' settings',
                'label' => 'Settings',
                'opts' => array(
                    array(
                        'key' => 'box_percent_' .$i,
                        'type' => 'text',
                        'label' => 'Percent to show 1-100',
                    ),
                    array(
                        'key' => 'box_label_' .$i,
                        'type' => 'text',
                        'label' => 'Label to show',
                    ),

                )
            );

            array_push($opts, $box);

        }
        return $opts;
    }

	function section_template(){
        $boxes = $this->opt('pie_boxes');
        if( $boxes == false){
            if( !$this->opt('stabs_count') ){
                echo setup_section_notify($this, __('Please start adding some content.', 'easy-pie-chart'));
                return;
            }
        }
	?>
        <div class="row <?php echo $this->meta['clone'] ?>">
            <?php for ($i=0; $i<$boxes; $i++): ?>
                <div class="span<?php echo $this->opt('pie_span') ?>">
                    <div class="chart" id="chart-box-<?php echo $i ?>">
                        <div class="percentage" data-percent="<?php echo $this->opt('box_percent_' .$i)?>">
                            <span><?php echo $this->opt('box_percent_' .$i) ?></span>%
                        </div>
                        <div class="pie-label" data-sync="<?php echo 'box_label_' .$i ?>">
                            <?php echo $this->opt('box_label_' .$i) ?>
                        </div>
                    </div>
                </div>
            <?php endfor ?>
        </div>
	<?php
	}
}