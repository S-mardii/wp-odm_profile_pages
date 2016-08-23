
  <section class="container">
      <div class="eleven columns post-title">
	      <header class="row">
        <h1><?php the_title(); ?></h1>
        <?php echo_post_meta(get_post()); ?>

				</header>
				<section class="content section-content">
          <?php
            if (jeo_has_marker_location()): ?>
            <section id="featured-media" class="row">
              <div style="height:350px;">
                <?php jeo_map(); ?>
              </div>
            </section>
          <?php endif; ?>
					<section id="post-content" class="row">
						<div class="item-content">
            	<?php the_content(); ?>
	            <?php echo_downloaded_documents(); ?>
	            <?php odm_echo_extras(); ?>
						</div>
					</section>
        </section>
      </div>

      <div class="four columns">
        <div class="widget share-widget">
          <?php odm_get_template('social-share',array(),true); ?>
        </div>

				<div>
          <aside id="sidebar">
            <ul class="widgets">
              <?php dynamic_sidebar('profile-area-1'); ?>
            </ul>
          </aside>
        </div>
      </div>
  </section>
	<section id="profile-area-4-5" class="page-section">
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
