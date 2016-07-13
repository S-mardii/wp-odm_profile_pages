<?php

$metadata_dataset = null;

if (isset($_GET['metadata'])) {
    $metadata_dataset = htmlspecialchars($_GET['metadata']);
}  ?>

<div class="container">
    <div class="row no-margin-top">
      <div class="sixteen columns profiles_page profile-metadata">
        <h2 class="align-left profile-name"><?php _e('Metadata:', 'odm'); the_title() ?></h2>
        <div class="clear"></div>
        <?php wpckan_get_metadata_info_of_dataset_by_id(wpckan_get_ckan_domain(), $metadata_dataset, 0, null); ?>
      </div>
    </div>
</div>
