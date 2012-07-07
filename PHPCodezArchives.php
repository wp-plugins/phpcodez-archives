<?php
/**
* Plugin Name: PHPCodez Archives
* Plugin URI: http://phpcodez.com/
* Description: A Widget That Displays Archives
* Version: 0.1
* Author: PHPCodez
* Author URI: http://phpcodez.com/
*/

add_action( 'widgets_init', 'wpc_archives_widgets' );

function wpc_archives_widgets() {
	register_widget( 'wpcarchivesWidget' );
}


class wpcarchivesWidget extends WP_Widget {

function wpcarchivesWidget() {
	$widget_ops = array( 'classname' => 'wpcClass', 'description' => __('A Widget That Displays Archives', 'wpcClass') );
	$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wpc-archives' );
	$this->WP_Widget( 'wpc-archives', __('PHPCodez Archives', ''), $widget_ops, $control_ops );
}

function getArchivePosts($current_month,$current_year){
	 $archivePosts=get_posts("year=$current_year&monthnum=$current_month");
	 echo sizeof($archivePosts);
}	
	
function widget( $args, $instance ) {
	extract( $args );
	global $wpdb;
	if($instance['archive_count']) $limit = "LIMIT 0,".$instance['archive_count'];
	$archiveQry= "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts  FROM $wpdb->posts  GROUP BY YEAR(post_date), MONTH(post_date)  ORDER BY post_date DESC $limit";
	$archivesData = $wpdb->get_results($archiveQry);
?>
	<div style="margin-top:10px; margin-top:10px;">
		<?php if($instance['archive_title']) { ?>
			<div class="side_hd">
				<h2><?php echo $instance['archive_title']; ?></h2>
			</div>
		<?php } ?>
		<div class="sider_mid">
			<ul>
				<?php foreach($archivesData as $key=>$archive) {  ?>
					<li>
						<a href=" <?php echo get_month_link( $archive->year, $archive->month ); ?> ">
							<?php echo date("F", mktime(0, 0, 0,$archive->month, 1, $archive->year));  ?> <?php echo $archive->year  ?> 
							<?php if($instance['archive_posts']) {?>(<?php echo $this->getArchivePosts($archive->month,$archive->year);  ?>) <?php } ?>
						</a>
					</li>
				<?php } ?>
			</ul>	
		</div>
	</div>
<?php
	}
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['archive_title'] =  $new_instance['archive_title'] ;
	$instance['archive_posts'] =  $new_instance['archive_posts'] ;
	$instance['archive_count'] =  $new_instance['archive_count'] ;
	return $instance;
}
function form( $instance ) {?>
	<p>
		<label for="<?php echo $this->get_field_id( 'archive_title' ); ?>"><?php _e('Disable Header Title', 'wpclass'); ?></label>
		<input id="<?php echo $this->get_field_id( 'archive_title' ); ?>" name="<?php echo $this->get_field_name( 'archive_title' ); ?>" value="<?php echo $instance['archive_title'] ?>"  type="text" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'archive_count' ); ?>"><?php _e('Number of Archives . for "0" or "No Value" It will List All The Archives', 'wpclass'); ?></label>
		<input id="<?php echo $this->get_field_id( 'archive_count' ); ?>" name="<?php echo $this->get_field_name( 'archive_count' ); ?>" value="<?php echo $instance['archive_count'] ?>"  type="text" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'archive_posts' ); ?>"><?php _e('Display Post Count', 'wpclass'); ?></label>
		<input id="<?php echo $this->get_field_id( 'archive_posts' ); ?>" name="<?php echo $this->get_field_name( 'archive_posts' ); ?>" value="1" <?php if($instance['archive_posts']) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	
<?php
	}
}
?>