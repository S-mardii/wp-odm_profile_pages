<div class="container">

  <!--  Filter-->
  <div class="row">
    <div class="sixteen columns">
      <div id="profiles_map" class="profiles_map"></div>
    </div>
  </div>
    <!--  Statistics-->
  <div class="row">
    <div class="sixteen columns">
      <?php if ($profiles) { ?>
      <div class="total_listed">
        <ul>
          <?php
          $count_project = array_count_values(array_map(function ($value) {return $value['map_id'];}, $profiles));?>
          <li>
            <p>
              <strong>
                <?php if (odm_language_manager()->get_current_language() == 'km') {
                        echo __('Total', 'odm').get_the_title().__('Listed', 'odm').__(':', 'odm');
                      } else {
                        echo __('Total', 'odm').' '.get_the_title().' '.__('Listed', 'odm').' '.__(':', 'odm');
                      } ?>
              </strong>
              <strong>
                <?php echo $count_project == '' ? convert_to_kh_number('0') : convert_to_kh_number(count($count_project));?>
              </strong>
            </p>
          </li>
          <?php
          $explode_total_number_by_attribute_name = explode("\r\n", $total_number_by_attribute_name);
          if (isset($total_number_by_attribute_name) && $total_number_by_attribute_name != '') {
            foreach ($explode_total_number_by_attribute_name as $key => $total_attribute_name) {
              if ($total_attribute_name != 'map_id') {
                $total_attributename = trim($total_attribute_name);
                if (strpos($total_attribute_name, '[') !== false) { //if march
                  $split_field_name_and_value = explode('[', $total_attributename);
                  $total_attributename = trim($split_field_name_and_value[0]); //eg. data_class
                  $total_by_specifit_value = str_replace(']', '', $split_field_name_and_value[1]);
                  $specifit_value = explode(',', $total_by_specifit_value);// explode to get: Government data complete
                }
                $GLOBALS['total_attribute_name'] = $total_attributename;
                $map_value = array_map(function ($value) { return $value[$GLOBALS['total_attribute_name']];}, $profiles);
                $count_number_by_attr = array_count_values($map_value); ?>

                <?php //count number by value: eg. Government data complete
                if (isset($specifit_value) && count($specifit_value) > 0) {
                  foreach ($specifit_value as $field_value) {
                    $field_value = trim(str_replace('"', '', $field_value)); ?>
                    <li>
                      <p>
                      <?php _e($field_value, 'odm'); ?>
                      <?php _e(':', 'odm');?>
                      <strong>
                        <?php echo $count_number_by_attr[$field_value] == '' ? convert_to_kh_number('0') : convert_to_kh_number($count_number_by_attr[$field_value]);?></strong>
                      </p>
                    </li>
                <?php
                  }
                } else {
                  if (isset($total_attributename) && $total_attributename != 'map_id') {?>
                   <li>
                     <p>
                      <?php
                      if (odm_language_manager()->get_current_language() == 'km') {
                        echo __('Total', 'odm').$DATASET_ATTRIBUTE[$total_attributename].__('Listed', 'odm').__(':', 'odm');
                      } else {
                        echo __('Total', 'odm').' '.$DATASET_ATTRIBUTE[$total_attributename].' '.__('Listed', 'odm').' '.__(':', 'odm');
                      } ?>

                      <strong><?php echo $total_attributename == '' ? convert_to_kh_number('0') : convert_to_kh_number(count($count_number_by_attr));?></strong>
                    </p>
                  </li>
               <?php
                  }
                }
              }
            }
          } ?>
        </ul>
      </div>
      <?php
    } ?>
    </div>
  </div>

  <div class="row panel">
    <div class="six columns">
      <p><?php _e('Textual search', 'odm');?></p>
      <input type="text" id="search_all" placeholder="<?php _e('Search data in profile page', 'odm'); ?>">
    </div>
    <div class="six columns">
      <?php if (isset($filtered_by_column_index) && $filtered_by_column_index != ''): ?>
        <div id="filter_by_classification">
          <p><?php _e('Filter by', 'odm');?></p>
        </div>
      <?php endif; ?>
    </div>
    <?php if (isset($related_profile_pages) && $related_profile_pages != '') {
      $temp_related_profile_pages = explode("\r\n", $related_profile_pages);  ?>
      <div class="four columns">
        <p><?php _e('Related profiles', 'odm');?></p>
        <ul>
        <?php foreach ($temp_related_profile_pages as $profile_pages_url) :
            $split_title_and_url = explode('|', $profile_pages_url);?>
            <li>
              <a href="<?php echo $split_title_and_url[1]; ?>"><?php echo $split_title_and_url[0]; ?></a>
            </li>
        <?php endforeach; ?>
        </ul>
      </div>
      <?php } ?>
  </div>

<!-- Table -->
<div class="row no-margin-buttom">
  <div class="fixed_top_bar"></div>
  <div class="sixteen columns table-column-container">

    <table id="profiles" class="data-table">
      <thead>
        <tr>
          <th><div class='th-value'><?php _e('Map ID', 'odm'); ?></div></th>
          <?php if ($DATASET_ATTRIBUTE) :
            foreach ($DATASET_ATTRIBUTE as $key => $value): ?>
              <th>
                <div class='th-value'>
                  <?php _e($DATASET_ATTRIBUTE[$key], 'odm');?>
                </div>
              </th>
            <?php endforeach;
          endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($profiles):
            foreach ($profiles as $profile):  ?>
            <tr>
              <td class="td-value">
                <?php echo $profile['map_id'];?>
              </td>
            <?php
              foreach ($DATASET_ATTRIBUTE as $key => $value): ?>
                <?php
                if (in_array($key, array('developer', 'name', 'block'))) :
                    ?>
                      <td class="entry_title">
                        <div class="td-value">
                          <a href="?map_id=<?php echo $profile['map_id'];?>"><?php echo $profile[$key];?></a>
                        </div>
                      </td>
                    <?php
                elseif (in_array($key, array('data_class', 'adjustment_classification', 'adjustment'))): ?>
                      <td>
                        <div class="td-value"><?php
                          if (odm_language_manager()->get_current_language() == 'en'):
                              echo ucwords(trim($profile[$key]));
                          else:
                              echo trim($profile[$key]);
                          endif;?>
                          <?php odm_data_classification_definition($profile[$key]);?>
                        </div>
                      </td>
                    <?php
                elseif ($key == 'reference'): ?>
                      <td>
                        <div class="td-value"><?php
                          $ref_docs_profile = explode(';', $profile['reference']);
                          $ref_docs = array_unique(array_merge($ref_docs_profile, $ref_docs_tracking));
                          odm_list_reference_documents($ref_docs, 1);?>
                        </div>
                      </td>
                    <?php
                elseif ($key == 'issuedate'): ?>
                    <td><div class="td-value"><?php
                        $issuedate = str_replace('T00:00:00', '', $profile[$key]);
                    echo $profile[$key] == '' ? __('Not found', 'odm') : str_replace(';', '<br/>', trim($issuedate));
                    ?></div>
                    </td>
                  <?php
                elseif (in_array($key, array('cdc_num', 'sub-decree', 'year'))):
                    if (odm_language_manager()->get_current_language() == 'km'):
                        $profile_value = convert_to_kh_number($profile[$key]);
                    else:
                        $profile_value = $profile[$key];
                    endif; ?>
                    <td>
                      <div class="td-value"><?php
                        echo $profile_value == '' ? __('Not found', 'odm') : str_replace(';', '<br/>', trim($profile_value));?>
                      </div>
                    </td>
                <?php
              else:
                    $profile_val = str_replace('T00:00:00', '', $profile[$key]);
                    if (odm_language_manager()->get_current_language() == 'km'):
                        if (is_numeric($profile_val)):
                            $profile_value = convert_to_kh_number(str_replace('.00', '', number_format($profile_val, 2, '.', ',')));
                        else:
                            $profile_value = str_replace('__', ' ', $profile_val);
                        endif;
                    else:
                        if (is_numeric($profile_val)):
                            $profile_value = str_replace('.00', '', number_format($profile_val, 2, '.', ','));
                        else:
                            $profile_value = str_replace('__', ', ', $profile_val);
                        endif;
                    endif;

                    $profile_value = str_replace(';', '<br/>', trim($profile_value));?>
                      <td>
                        <div class="td-value"><?php
                          echo $profile[$key] == '' ? __('Not found', 'odm') : str_replace(';', '<br/>', trim($profile_value));?>
                        </div>
                      </td>
                    <?php
              endif; ?>
              <?php endforeach; ?>
            </tr>
        <?php endforeach;
      endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="sixteen columns">
    <div class="disclaimer">
      <?php the_content(); ?>
    </div>
  </div>
</div>
</div>
