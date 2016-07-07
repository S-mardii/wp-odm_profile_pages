<?php

$metadata_dataset = null;

if (isset($_GET['metadata'])) {
    $metadata_dataset = htmlspecialchars($_GET['metadata']);
}  ?>

<div class="container">
    <div class="row no-margin-top">
      <div class="sixteen columns profiles_page profile-metadata">
        <h2 class="align-left h2_name"><?php _e('Metadata:', 'opendev'); the_title() ?></h2>
        <div class="clear"></div>
        <?php
          $showing_fields = "";
          /*$showing_fields = array(
                          "title_translated" => "Title",
                          "notes_translated" => "Description",
                          "odm_source" => "Source(s)",
                          "odm_date_created" => "Date of data",
                          "odm_completeness" => "Completeness",
                          "license_id" => "License"
                      );*/
          wpckan_get_metadata_info_of_dataset_by_id(wpckan_get_ckan_domain(), $metadata_dataset, 0, $showing_fields); ?>
      </div>
    </div>
</div>
