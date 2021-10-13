<?php
/*
Plugin Name: Co-Authors Widget
Description: The plugin add a widget and a shortcode in order to show authors of an article. It is compatible with Co-Authors Plus.
Version: 0.6
Author: Gianluigi Filippelli
Author URI: http://dropseaofulaula.blogspot.it/
Plugin URI: https://ulaulaman.github.io/#CoAuthorsWidget
License: GPLv2 or later
*/
/* ------------------------------------------------------ */
# ---------------------------------------------------------

// Load translations
add_action('plugins_loaded', 'caw_load_translations');
function caw_load_translations() {
	load_plugin_textdomain( 'widget-for-co-authors', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

# Shortcode to show authors
add_shortcode('blog-post-coauthors', 'blog_post_coauthors');
function blog_post_coauthors() {
  return coauthors_posts_links(", ", " & ", null, null, false);
}

# Widget to show authors' avatars
function blog_avatars() {
if ( function_exists( 'get_coauthors' ) ) {
  $coauthors = get_coauthors();
  $user_posts = get_author_posts_url( $coauthor->ID, $coauthor->user_nicename );
  $show_profile = __( 'Show profile', 'widget-for-co-authors' );
  $hide_profile = __( 'Hide profile', 'widget-for-co-authors' );
  $i = 0;
  foreach ( $coauthors as $coauthor ) {
    $i++;
    ?>
      <div class="block-item-text">
        <input type="checkbox" hidden class="read-more-state" id="<?php echo $i; ?>">
        <div class="read-more-wrap">
          <p><?php echo coauthors_get_avatar( $coauthor, 65 ); ?>
          <a href=<?php echo get_author_posts_url( $coauthor->ID, $coauthor->user_nicename ); ?>><?php echo $coauthor->display_name; ?></a></p>
          <p class="read-more-target">
            <?php echo $coauthor->description; ?>
          </p>
        </div>
        <label for="<?php echo $i; ?>" class="read-more-trigger_closed">
          <strong>+ <?php printf( $show_profile ); ?></strong>
        </label>
        <label for="<?php echo $i; ?>" class="read-more-trigger_opened">
          <strong>- <?php printf( $hide_profile ); ?></strong>
        </label>
      </div>
    <?php
  }
//
} else {
  
  ?>
    <div class="block-item-text">
		  <input type="checkbox" hidden class="read-more-state" id="<?php echo $i; ?>">
      <div class="read-more-wrap">
        <p><?php echo get_avatar( get_the_author_meta( 'user_email' ), 65 ); ?>
        <a href=<?php echo get_the_author_meta( 'user_url' ); ?>><?php echo get_the_author_meta( 'display_name' ); ?></a></p>
        <p class="read-more-target">
          <?php echo get_the_author_meta( 'description' ); ?>
        </p>
      </div>
      <label for="<?php echo $i; ?>" class="read-more-trigger_closed">
        <strong>+ <?php printf( $show_profile ); ?></strong>
      </label>
      <label for="<?php echo $i; ?>" class="read-more-trigger_opened">
        <strong>- <?php printf( $hide_profile ); ?></strong>
      </label>
    </div>
  <?php
}
}

function blog_enqueue() {
    wp_register_style( 'blog-spoiler-style', plugins_url('/blog-spoiler.css', __FILE__) );
    wp_enqueue_style( 'blog-spoiler-style' );
}
add_action( 'wp_enqueue_scripts', 'blog_enqueue' );

# Shortcode authors' avatars
add_shortcode('blog-coauthors-avatars', 'blog_coauthors_avatars');
function blog_coauthors_avatars() {
   return blog_avatars();
}

// Widget register
function blog_load_widget() {
    register_widget( 'blog_widget' );
}
add_action( 'widgets_init', 'blog_load_widget' );
 
// Widget load
class blog_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Widget ID
'blog_widget', 
 
// Widget name in UI
__('Authors', 'widget-for-co-authors'), 
 
// Widget description
array( 'description' => __( 'Show avatars and names of the authors', 'widget-for-co-authors' ), ) 
);
}
 
// Widget front-end 
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
 
// before and after
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 
// output
blog_avatars();
echo $args['after_widget'];
}
         
// Widget backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Written by', 'widget-for-co-authors' );
}

// Widget form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
     
// Widget update
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
}
 
/* ------------------------------------------------------ */
?>
