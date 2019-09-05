<?php
/**
Template Name: WP_Museum
 * 
 *
 * Museum Template cloned from Full Width
 *
 */

include_once( get_template_directory() . "/style-wp_museum.css");

get_header(); ?>

<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="page-<?php the_ID(); ?>">


  <!-- BEGIN .row -->
  <div class="row">

    <!-- BEGIN .content -->
    <div class="content">

      <!-- BEGIN .sixteen columns -->
      <div class="sixteen columns">

        <!-- BEGIN .post-area full-width -->
        <div class="post-area full-width">

          <?php get_template_part( 'content/loop', 'page' ); ?>

        <!-- END .post-area full-width -->
        </div>

      <!-- END .sixteen columns -->
      </div>

    <!-- END .content -->
    </div>

  <!-- END .row -->
  </div>

<!-- END .post class -->
</div>

<script>

jQuery(document).ready(function(){
  
});

jQuery("img.popo-category-image").click(function() {

  // Hide all left/right pairs
  jQuery(".popo-left").hide();
  jQuery(".popo-right").hide();

  // Show one left/right pair
  var shower = "#left" + this.id;
  jQuery(shower).show();
  shower = "#right" + this.id;
  jQuery(shower).show();
  // Long list, so go to top to see it
  jQuery(window).scrollTop(0);
});

// Click on right image thumb, url copied. Pass large picture id in alt
jQuery(".popo-right-image-thumb").click(function() {
  var pic = "#" + this.alt;
  jQuery(pic).attr('src', this.src);
});

// Submit Category select
jQuery("#category_form").click(function() {

});

</script>

<?php get_footer(); ?>
