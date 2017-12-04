<?php
/*
Plugin Name: Co-Authors Widget
Description: Il plugin aggiunge un widget e uno shortcode per mostrare gli autori di un articolo. Compatibile con Co-Authors Plus.
Version: 0.3
Author: Gianluigi Filippelli
Author URI: http://dropseaofulaula.blogspot.it/
*/
/* ------------------------------------------------------ */
# ---------------------------------------------------------

# Shortcode per mostrare gli autori: compatibile con coauthors plus
add_shortcode('blog-post-coauthors', 'blog_post_coauthors');
function blog_post_coauthors() {
  return coauthors_posts_links(", ", " e ", null, null, false);
}

# Mostrare gli avatar degli autori: compatibile con coauthors plus
function blog_avatars() {
if ( function_exists( 'get_coauthors' ) ) {
  $coauthors = get_coauthors();
  $user_posts = get_author_posts_url( $coauthor->ID, $coauthor->user_nicename );
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
    <strong>+ Mostra profilo</strong>
  </label>
  <label for="<?php echo $i; ?>" class="read-more-trigger_opened">
    <strong>- Nascondi profilo</strong>
  </label>
</div>
    <?php
  }
//
} else {
  
  ?>
    <p><?php echo get_avatar( get_the_author_meta( 'user_email' ), 65 ); ?>
    <a href=<?php echo get_author_posts_url( $coauthor->ID, $coauthor->user_nicename ); ?>><?php echo $coauthor->display_name; ?></a></p>

  <?php
}
}

function blog_enqueue() {
    wp_register_style( 'blog-spoiler-style', plugins_url('/blog-spoiler.css', __FILE__) );
    wp_enqueue_style( 'blog-spoiler-style' );
}
add_action( 'wp_enqueue_scripts', 'blog_enqueue' );

# Shortcode avatar degli autori
add_shortcode('blog-coauthors-avatars', 'blog_coauthors_avatars');
function blog_coauthors_avatars() {
   return blog_avatars();
}

// Registrazione e caricamento del widget
function blog_load_widget() {
    register_widget( 'blog_widget' );
}
add_action( 'widgets_init', 'blog_load_widget' );
 
// Creazione del widget
class blog_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Base ID del widget
'blog_widget', 
 
// Il nome del widget comparirà nell'UI
__('Autori Blog', 'blog_widget_domain'), 
 
// descrizione del widget
array( 'description' => __( 'Widget per mostrare avatar e nomi degli autori', 'blog_widget_domain' ), ) 
);
}
 
// creazione del front-end del widget
 
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
 
// argomenti before e after
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 
// il codice mostra l'output
blog_avatars();
echo $args['after_widget'];
}
         
// backend del widget 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Articolo di', 'blog_widget_domain' );
}
// il form del widget
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
     
// aggiornamento del widget
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
}
 
/* ------------------------------------------------------ */
?>
