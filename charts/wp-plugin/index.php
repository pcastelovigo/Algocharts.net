<?php
/**
 * Plugin Name:       AlgoCharts Widget
 * Plugin URI:        https://algocharts.net
 * Description:       Show price, USD price and price change widget for Algorand ASA
 * Version:           0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * License:           GPL v2 or later
 * Author:            Algocharts.net
 * Update URI:        https://algocharts.net/wp-plugin/latest.zip
 */

class algocharts_widget extends WP_Widget {

function __construct() {
parent::__construct(

'algocharts_widget',

__('Algocharts Widget', 'algocharts_widget_domain'),

array( 'description' => __( 'Price, USD price and price change widget for Algorand ASAs', 'algocharts_widget_domain' ), ) 
);
}
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$asa = apply_filters( 'widget_asa', $instance['asa'] );
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
$content = file_get_contents("https://algocharts.net/apiv2/?asset_in=".$instance['ASA']."&asset_out=0");
$result  = json_decode($content, true);
$algoprice = (float)$result['data'][0];
$change24 = (float)$result['data'][1];
$usd_price = (float)$result['data'][2];
$algotousd = (float)$result['data'][3];
echo "<p><b>Algo price: </b>".sprintf("%.6f",$algoprice)."</p>";
echo "<p><b>USD price: </b>".sprintf("%.3f",$usd_price)."</p>";
if ($change24 > 0) { echo "<p><b>24h change:</b> <font color=\"green\">".sprintf("%.2f",$change24)." %</font></p>"; } else { echo "<p><b>24h change: </b><font color=\"red\">".sprintf("%.2f",$change24)." %</font></p>"; }
echo "<small><a href=\"https://algocharts.net/chart.php?asset_in=".$instance['ASA']."&asset_out=0\" target=\"_blank\" >Data source</a></small>";
echo $args['after_widget'];
}
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; } else { $title = __( 'New title', 'algocharts_widget_domain' ); }
if ( isset( $instance[ 'ASA' ] ) ) { $asa = $instance[ 'ASA' ]; } else { $asa = __( 'Insert ASA Number', 'algocharts_widget_domain' ); }
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'asa' ); ?>"><?php _e( 'ASA #:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'ASA' ); ?>" name="<?php echo $this->get_field_name( 'ASA' ); ?>" type="text" value="<?php echo esc_attr( $asa ); ?>" />
</p>

<?php
}
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['ASA'] = ( ! empty( $new_instance['ASA'] ) ) ? strip_tags( $new_instance['ASA'] ) : '';
return $instance;
}
}
function algocharts_load_widget() {
    register_widget( 'algocharts_widget' );
}
add_action( 'widgets_init', 'algocharts_load_widget' );
