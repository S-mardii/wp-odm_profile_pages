
  <section class="container">
      <div class="twelve columns post-title">
				<section class="content section-content">
          <?php /*
            if (jeo_has_marker_location()): ?>
            <section id="featured-media" class="row">
              <div style="height:350px;">
                <?php jeo_map(); ?>
              </div>
            </section>
          <?php endif;  */?>

  				<section id="post-content">
						<?php the_content(); ?>
  				</section>
        </section>
      </div>

      <div class="four columns">
        <aside id="sidebar">
          <ul class="widgets">
          	<?php dynamic_sidebar('profile-area-1'); ?>
          </ul>
        </aside>
      </div>
  </section>
  <?php
    if (odm_language_manager()->get_current_language() == 'km') {
      $data_visualitation = get_post_meta($post->ID, '_full_width_middle_content_localization', true);
    }else {
      $data_visualitation = get_post_meta($post->ID, '_full_width_middle_content', true);
    }

    if($data_visualitation):
      ?>
    	<section class="container">
    		<div class="row">
    			<div class="sixten columns">
            <?php echo "<div class='iframe-visualitation'>".$data_visualitation."</div>"; ?>
    			</div>
    		</div>
    	</section>
  <?php endif; ?>
  
	<section id="profile-area-bottom" class="page-section">
    <div class="container">
      <div class="row">
       <div class="eight columns">
         <?php dynamic_sidebar('profile-area-2'); ?>
       </div>
       <div class="eight columns">
         <?php dynamic_sidebar('profile-area-3'); ?>
       </div>
     </div>
    </div>
  </section>
