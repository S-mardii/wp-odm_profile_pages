<?php
function get_metadata_info_of_dataset_by_id($ckan_domain, $ckan_dataset_id, $individual_layer = '', $atlernative_links = 0, $showing_fields = '')
{
    $lang = CURRENT_LANGUAGE;

    $attribute_metadata = array(
                        //  "title_translated" => "Title",
                          'notes_translated' => 'Description',
                          'odm_source' => 'Source(s)',
                          'odm_completeness' => 'Completeness',
                          'odm_metadata_reference_information' => 'Metadata Reference Information',
                          'odm_process' => 'Process(es)',
                          'odm_attributes' => 'Attributes',
                          'odm_logical_consistency' => 'Logical Consistency',
                          'odm_copyright' => 'Copyright',
                          'version' => 'Version',
                          'odm_date_created' => 'Date created',
                          'odm_date_uploaded' => 'Date uploaded',
                          'odm_temporal_range' => 'Temporal range',
                          'odm_accuracy-en' => 'Accuracy',
                          'odm_logical_consistency' => 'Logical Consistency',
                          'odm_contact' => 'Contact',
                          'odm_access_and_use_constraints' => 'Access and use constraints',
                          'license_id' => 'License',
                      );

    $get_info_from_ckan = get_dataset_by_id($ckan_domain, $ckan_dataset_id);
    ?>
    <div class="layer-toggle-info toggle-info toggle-info-<?php echo $individual_layer['ID'];
    ?>">
        <table border="0" class="toggle-talbe">
          <tr><td colspan="2"><h5><?php echo $get_info_from_ckan['title_translated'][$lang] ?></h5></td></tr>
          <?php
          if ($showing_fields == '') {
              if ($get_info_from_ckan) {
                  foreach ($get_info_from_ckan as $key => $info) {
                      if ($key == 'license_id') {
                          ?>
                  <tr>
                      <td><?php echo $attribute_metadata['license_id'];
                          ?></td>
                      <td><?php echo $info == 'unspecified' ? ucwords($get_info_from_ckan['license_id']) : $get_info_from_ckan['license_id'];
                          ?></td>
                  </tr>
              <?php

                      } else {
                          if (array_key_exists($key, $attribute_metadata)) {
                              ?>    <tr>
                        <td><?php echo $attribute_metadata[$key];
                              ?></td><td><?php echo is_array($info) ? $info[$lang] : $info;
                              ?></td>
                    </tr>
          <?php

                          }
                      }
                  }
              }
          } else {
              foreach ($showing_fields as $key => $info) {
                  if ($key == 'license_id') {
                      ?>
                <tr>
                    <td><?php echo $showing_fields['license_id'];
                      ?></td>
                    <td><?php echo $info == 'unspecified' ? ucwords($get_info_from_ckan['license_id']) : $get_info_from_ckan['license_id'];
                      ?></td>
                </tr>
            <?php

                  } else {
                      if ($get_info_from_ckan) {
                          ?>    <tr>
                        <td><?php echo $showing_fields[$key];
                          ?></td>
                        <td><?php echo is_array($get_info_from_ckan[$key]) ? $get_info_from_ckan[$key][$lang] : $get_info_from_ckan[$key];
                          ?></td>
                    </tr>
        <?php

                      }
                  }
              } //end foreach
          }
    ?>
        </table>
      <?php if ($atlernative_links == 1) {
    ?>
        <div class="atlernative_links">
        <?php if ($lang != 'en') {
    ?>
                <div class="div-button"><a href="<?php echo $individual_layer['download_url_localization'];
    ?>" target="_blank"><i class="fa fa-arrow-down"></i> <?php _e('Download data', 'opendev');
    ?></a></div>

                <?php if ($individual_layer['profilepage_url_localization']) {
    ?>
                  <div class="div-button"><a href="<?php echo $individual_layer['profilepage_url_localization'];
    ?>" target="_blank"><i class="fa fa-table"></i> <?php _e('View dataset table', 'opendev');
    ?></a></div>
                <?php

}
    ?>
        <?php

} else {
    ?>
                <div class="div-button"><a href="<?php echo $individual_layer['download_url'];
    ?>" target="_blank"><i class="fa fa-arrow-down"></i> <?php _e('Download data', 'opendev');
    ?></a></div>

                <?php if ($individual_layer['profilepage_url']) {
    ?>
                  <div class="div-button"><a href="<?php echo $individual_layer['profilepage_url'];
    ?>" target="_blank"><i class="fa fa-table"></i> <?php _e('View dataset table', 'opendev');
    ?></a></div>
                <?php

}
    ?>
        <?php

}
    ?>
        </div>
      <?php

}
    ?>
    </div>
<?php

}//end function

/// Data Classification definition using in Profile pages
function data_classification_definition($info)
{
    $info = trim($info);
    if ($info == 'កាត់បន្ថយ') {
        $info = 'Downsized';
    } elseif ($info == 'កាត់បន្ថយបន្ទាប់ពីដកហូត') {
        $info = 'Downsized after revocation';
    } elseif ($info == 'គ្មានភស្តុតាងនៃការផ្លាស់ប្តូរ') {
        $info = 'No evidence of adjustment';
    } elseif ($info == 'ដកហូត') {
        $info = 'Revoked';
    } elseif ($info == 'ទិន្នន័យរដ្ឋាភិបាលពេញលេញ') {
        $info = 'Government data complete';
    } elseif ($info == 'ទិន្នន័យរដ្ឋាភិបាលមិនពេញលេញ') {
        $info = 'Government data partial';
    } elseif ($info == 'ទិន្នន័យដទៃទៀត') {
        $info = 'Secondary source data';
    } elseif ($info == 'ទិន្នន័យបន្ទាប់បន្សំ') {
        $info = 'Other data';
    }

    $info = strtolower(str_replace(' ', '_', $info));
    echo '&nbsp; <div class="tooltip tooltip_definition ">';
    if ($info != '' && $info != __('Not found', 'opendev')) {
        echo '<i class="fa fa-question-circle info-data-classification" title=""></i>';
    }
    if ($info == 'no_evidence_of_adjustment') {
        echo '<div class="tooltip-info tooltip-no_evidence_of_adjustment">';
        echo '<p>'.__('ODC is not aware of any adjustments to the concession since it was first granted.', 'opendev');
        echo '</p>';
        echo '</div>';
    } elseif ($info == 'downsized') {
        echo '<div class="tooltip-info tooltip-downsized">';
        echo '<p>'.__('The concession has been subjected to additional reductions in size and has not been cancelled previously. Publicly available information on land area cut from ELCs does not include maps or spatial data of excisions. Thus, ODC cannot present land area cut in shapes. As a result, ELC projects that are visualized on the interactive map represent the original contract size.', 'opendev');
        echo '</p>';
        echo '</div>';
    } elseif ($info == 'revoked') {
        echo '<div class="tooltip-info tooltip-revoked">';//<!--data_revoked_layer-->
            echo '<p>'.__('The concession has been cancelled with or without a history of reductions in size.', 'opendev');
        echo '</p>';
        echo '</div>';
    } elseif ($info == 'downsized_after_revocation') {
        echo '<div class="tooltip-info tooltip-downsized_after_revocation">';//<!--data_downsized_after_revocation_layer-->
            echo '<p>'.__('The concession has been subjected to reduction(s) in size although it had been cancelled previously. Publicly available information on land area cut from ELCs does not include maps or spatial data of excisions. Thus, ODC cannot present land area cut in shapes. As a result, ELC projects that are visualized on the interactive map represent the original contract size.', 'opendev');
        echo '</p>';
        echo '</div>';
    } elseif ($info == 'government_data_complete') {
        echo '<div class="tooltip-info tooltip-government_data_complete">';//<!--Complete Data-->
            echo '<p>'.__('Information obtained from official Government sources, with official legal documentation, in the four identification fields: <br>a. Company name; <br>b. Location; <br>c. GPS coordinates and/or analog map; and <br>  d. Purpose (crop, ore, etc.)', 'opendev').'</p>';
        echo '</div>';
    } elseif ($info == 'government_data_partial') {
        echo '<div class="tooltip-info tooltip-government_data_partial">';//<!--Partial Data-->
            echo '<p>'.__('Information obtained from official Government sources, with legal documentation, but missing one or more of the following identification fields: <br>a. Company name; <br>b. Location; <br>c. GPS coordinates and/or analog map; and <br>d. Purpose (crop, ore, etc.)', 'opendev').'</p>';
        echo '</div> ';
    } elseif ($info == 'other_data') {
        echo '<div class="tooltip-info tooltip-other_data">';//<!--Other-->
            echo '<p>'.__('Information obtained from any other source in public domain (including documentation from photographs, etc.)', 'opendev').'</p>';
        echo '</div>';
    } elseif ($info == 'secondary_source_data') {
        echo '<div class="tooltip-info tooltip-secondary_source_data">';//<!--Secondary Data-->
            echo '<p>'.__('Information obtained from the concessionaire (company/entity) or from government source(s) without legal documentation.', 'opendev').'</p>';
        echo '</div>';
    } elseif ($info == 'canceled_data') {
        echo '<div class="tooltip-info tooltip-canceled_data">';//<!--Canceled Concessions:-->
            echo '<p>'.__('These concessions have been cancelled by the Royal Government of Cambodia.', 'opendev').'</p>';
        echo '</div>';
    }
    echo '</div>';
}

function convert_date_to_kh_date($date_string, $splitted_by = '.')
{ //$date_string = Day.Month.Year
  if ((CURRENT_LANGUAGE == 'kh') || (CURRENT_LANGUAGE == 'km')) {
      $splitted_date = explode($splitted_by, $date_string); // split the date by "."
        $joined_date = '';
      if (count($splitted_date) > 1) {
          if (strlen($date_string) == 7) { //month and year //Month.Year  02.2014
                $month_year = $splitted_date; //get Month.Year  02.2014
                    if ($month_year[0] != '00') {
                        $joined_date .= ' ខែ'.convert_to_kh_month($month_year[0]);
                    }
              if ($month_year[1] != '0000') {
                  $joined_date .= ' ឆ្នាំ'.convert_to_kh_number($month_year[1]);
              }
          } else {
              $day_month_year = $splitted_date; //get Day.Month.Year  20.02.2014
                    if ($day_month_year[0] != '00') {
                        $joined_date .= 'ថ្ងៃទី '.convert_to_kh_number($day_month_year[0]);
                    }
              if ($day_month_year[1] != '00') {
                  $joined_date .= ' ខែ'.convert_to_kh_month($day_month_year[1]);
              }
              if ($day_month_year[2] != '0000') {
                  $joined_date .= ' ឆ្នាំ'.convert_to_kh_number($day_month_year[2]);
              }
          }
      } else {
          if (strlen($date_string) == 4) {
              $joined_date = ' ឆ្នាំ'.convert_to_kh_number($date_string);
          }
      }

      return $joined_date;
  } else {
      $return_date = date('d F Y', strtotime($date_string));

      return  $my_date;
  }
}
function convert_to_kh_month($month = '')
{
    if ((CURRENT_LANGUAGE == 'kh') || (CURRENT_LANGUAGE == 'km')) {
        if ($month == 'Jan') {
            $kh_month = 'មករា';
        } elseif ($month == 'Feb') {
            $kh_month = 'កុម្ភៈ';
        } elseif ($month == 'Mar') {
            $kh_month = 'មីនា';
        } elseif ($month == 'Apr') {
            $kh_month = 'មេសា';
        } elseif ($month == 'May') {
            $kh_month = 'ឧសភា';
        } elseif ($month == 'Jun') {
            $kh_month = 'មិថុនា';
        } elseif ($month == 'Jul') {
            $kh_month = 'កក្កដា';
        } elseif ($month == 'Aug') {
            $kh_month = 'សីហា';
        } elseif ($month == 'Sep') {
            $kh_month = 'កញ្ញា';
        } elseif ($month == 'Oct') {
            $kh_month = 'តុលា';
        } elseif ($month == 'Nov') {
            $kh_month = 'វិច្ឆិកា';
        } elseif ($month == 'Dec') {
            $kh_month = 'ធ្នូ';
        } elseif ($month == '01') {
            $kh_month = 'មករា';
        } elseif ($month == '02') {
            $kh_month = 'កុម្ភៈ';
        } elseif ($month == '03') {
            $kh_month = 'មីនា';
        } elseif ($month == '04') {
            $kh_month = 'មេសា';
        } elseif ($month == '05') {
            $kh_month = 'ឧសភា';
        } elseif ($month == '06') {
            $kh_month = 'មិថុនា';
        } elseif ($month == '07') {
            $kh_month = 'កក្កដា';
        } elseif ($month == '08') {
            $kh_month = 'សីហា';
        } elseif ($month == '09') {
            $kh_month = 'កញ្ញា';
        } elseif ($month == '10') {
            $kh_month = 'តុលា';
        } elseif ($month == '11') {
            $kh_month = 'វិច្ឆិកា';
        } elseif ($month == '12') {
            $kh_month = 'ធ្នូ';
        } elseif ($month == '០១') {
            $kh_month = 'មករា';
        } elseif ($month == '០២') {
            $kh_month = 'កុម្ភៈ';
        } elseif ($month == '០៣') {
            $kh_month = 'មីនា';
        } elseif ($month == '០៤') {
            $kh_month = 'មេសា';
        } elseif ($month == '០៥') {
            $kh_month = 'ឧសភា';
        } elseif ($month == '០៦') {
            $kh_month = 'មិថុនា';
        } elseif ($month == '០៧') {
            $kh_month = 'កក្កដា';
        } elseif ($month == '០៨') {
            $kh_month = 'សីហា';
        } elseif ($month == '០៩') {
            $kh_month = 'កញ្ញា';
        } elseif ($month == '១០') {
            $kh_month = 'តុលា';
        } elseif ($month == '១១') {
            $kh_month = 'វិច្ឆិកា';
        } elseif ($month == '១២') {
            $kh_month = 'ធ្នូ';
        }

        return $kh_month;
    } else {
        return $month;
    }
}
function convert_to_kh_number($number)
{
    if ((CURRENT_LANGUAGE == 'kh') || (CURRENT_LANGUAGE == 'km')) {
        $conbine_num = '';
        $split_num = str_split($number);
        foreach ($split_num as $num) {
            if ($num == '0') {
                $kh_num = '០';
            } elseif ($num == '1') {
                $kh_num = '១';
            } elseif ($num == '2') {
                $kh_num = '២';
            } elseif ($num == '3') {
                $kh_num = '៣';
            } elseif ($num == '4') {
                $kh_num = '៤';
            } elseif ($num == '5') {
                $kh_num = '៥';
            } elseif ($num == '6') {
                $kh_num = '៦';
            } elseif ($num == '7') {
                $kh_num = '៧';
            } elseif ($num == '8') {
                $kh_num = '៨';
            } elseif ($num == '9') {
                $kh_num = '៩';
            } else {
                $kh_num = $num;
            }

            $conbine_num .= $kh_num;
        }

        return $conbine_num;
    } else {
        return $number;
    }
}

 ?>
