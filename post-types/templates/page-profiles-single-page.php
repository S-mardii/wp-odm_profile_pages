<?php require_once PLUGIN_DIR.'/utils/profile-spreadsheet-post-meta.php'; ?>
<div class="container">
    <div class="sixteen columns">
      <header>
        <?php  if ($profile["developer"]!="")
            echo '<h3 class="profile-name">'.$profile["developer"].'</h3>';
         else if ($profile["name"]!="")
            echo '<h3 class="profile-name">'.$profile["name"].'</h3>';
         else if ($profile["block"]!="")
            echo '<h3 class="profile-name">'.$profile["block"].'</h3>';
       ?>
      </header>
    </div>
    <div class="sixteen columns">
      <div id="profiles_map" class="profiles_map"></div>
    </div>
    <div class="row">
      <div class="sixteen columns">
        <div id="profile-map-id" class="hidden"><?php echo $filter_map_id; ?></div>
        <div class="profile-metadata">

          <table id="profile" class="data-table">
            <tbody>
              <?php
              foreach ($DATASET_ATTRIBUTE as $key => $value):
                if($key !="reference"){ ?>
              <tr>
              <td class="row-key"><?php _e( $DATASET_ATTRIBUTE[$key], 'odm' ); ?></td>
                <td><?php
                    $profile_val = str_replace("T00:00:00", "", $profile[$key]);
                    if(odm_language_manager()->get_current_language() =="km"){
                      if (is_numeric($profile_val)) {
                        $profile_value = convert_to_kh_number(str_replace(".00", "", number_format($profile_val, 2, '.', ',')));
                      }else {
                        $profile_value = $profile_val;
                      }
                    }else {
                      if (is_numeric($profile_val)) {
                        $profile_value = str_replace(".00", "", number_format($profile_val, 2, '.', ','));
                      }else {
                        $profile_value = $profile_val;
                      }
                    }

                    echo $profile_value == ""? __("Not found", 'odm'): str_replace(";", "<br/>", $profile_value);

                    if(in_array($key, array("data_class", "adjustment_classification", "adjustment")))
                      odm_data_classification_definition( $profile[$key]);
                ?>
                </td>
              </tr>
              <?php }
              endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="sixteen columns">
        <?php if (count($ammendements) > 0): ?>
          <div class="profile-metadata">
            <h2><?php _e("Amendments", 'odm'); ?></h2>
            <table id="tracking" class="data-table">
              <tbody>
                <!--<thead>
                  <tr>
                    <?php /* foreach ($DATASET_ATTRIBUTE_TRACKING as $key => $value): ?>
                      <td class="row-key"><?php _e( $DATASET_ATTRIBUTE_TRACKING[$key], 'odm'); ?></td>
                    <?php endforeach; */ ?>
                  </tr>
                </thead>-->

                <?php
                //Sort by amendment_date
          			foreach ($ammendements as $key => $sort_by) {
          				$tmp_arr[$key] = $sort_by['amendment_date'];
          			}
          			array_multisort($tmp_arr, SORT_ASC, $ammendements);

                $concession_or_developer = '';
                foreach ($ammendements as $key => $ammendement):
                  if (!empty($ammendement["reference"])) {
                    $ammendement_references = $ref_docs_tracking = explode(";", $ammendement["reference"]);
                    $ref_docs_tracking = array_merge($ref_docs_tracking,$ammendement_references);
                  }
                  $first_attr_key = array_shift(array_keys($DATASET_ATTRIBUTE_TRACKING));
                  $ammendement_title[] = $ammendement[$first_attr_key];
                  $concession_or_developer = $ammendement[$first_attr_key];
                  $ammendement_infomation = "";

                  foreach ($DATASET_ATTRIBUTE_TRACKING as $key => $value) {
                    if (isset($ammendement[$key])):
                      if($key != $first_attr_key):
                        $ammendement_infomation .= "<td>".$ammendement[$key]."</td>";
                      endif;
                    endif;
                  }
                  $ammendement_info[$concession_or_developer][] = '<tr>'.$ammendement_infomation.'</tr>';
                endforeach;

                if (!empty($ammendement_title)):
                  foreach (array_unique($ammendement_title) as $group_value) {
                    echo "<tr><td colspan='".count($DATASET_ATTRIBUTE_TRACKING)."'><strong>".__($group_value, 'odm')."</strong></td></tr>";
                    foreach ($ammendement_info[$group_value] as $info_value) {
                      echo $info_value;
                    }
                  }
                endif;
                ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p><php _e('No records found','odm') ?></p>
        <?php endif; ?>

        <?php
          $ref_docs_profile = explode(";", $profile["reference"]);
          $ref_docs = array_merge($ref_docs_profile,$ref_docs_tracking);
          if (count($ref_docs)> 0): ?>
          <div class="profile-metadata">
            <h2><?php _e("Reference documents", 'odm'); ?></h2>
                <?php odm_list_reference_documents($ref_docs)?>
          </div>
          <?php else: ?>
            <p><php _e('No records found','odm') ?></p>
          <?php endif; ?>
        </div>
    </div>
  </div>

<?php require_once PLUGIN_DIR.'/utils/profile-mapping-script.php'; ?>
