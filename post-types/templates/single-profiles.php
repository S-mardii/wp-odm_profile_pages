<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <?php

  // End of hack
  $ammendements = null;
  $profile = null;
  $profiles = null;
  $filter_map_id = null;
  $metadata_dataset = null;
  $dataset = null;
  $DATASET_ATTRIBUTE = null;
  if (isset($_GET['map_id'])) {
      $filter_map_id = htmlspecialchars($_GET['map_id']);
  }
  if (isset($_GET['metadata'])) {
      $metadata_dataset = htmlspecialchars($_GET['metadata']);
  }

  $map_visualization_url = get_post_meta($post->ID, '_map_visualization_url', true);
  $map_visualization_url_localization = get_post_meta($post->ID, '_map_visualization_url_localization', true);
  $csv_resource_url = get_post_meta($post->ID, '_csv_resource_url', true);
  $csv_resource_url_localization = get_post_meta($post->ID, '_csv_resource_url_localization', true);
  $tracking_csv_resource_url = get_post_meta($post->ID, '_tracking_csv_resource_url', true);
  $tracking_csv_resource_url_localization = get_post_meta($post->ID, '_tracking_csv_resource_url_localization', true);

  $filtered_by_column_index = get_post_meta($post->ID, '_filtered_by_column_index', true);
  $filtered_by_column_index_localization = get_post_meta($post->ID, '_filtered_by_column_index_localization', true);

if (isset($map_visualization_url) && $map_visualization_url !== '') {
    if ((odm_language_manager()->get_current_language() != 'en')) {
        $map_visualization_url = str_replace('?type=dataset', '', get_post_meta($post->ID, '_map_visualization_url_localization', true));
        $ckan_dataset = str_replace('?type=dataset', '', get_post_meta($post->ID, '_csv_resource_url_localization', true));
        $ckan_dataset_tracking = str_replace('?type=dataset', '', get_post_meta($post->ID, '_tracking_csv_resource_url_localization', true));
        $filtered_by_column_index = str_replace('?type=dataset', '', get_post_meta($post->ID, '_filtered_by_column_index_localization', true));  // index start from zero, so "-1" is needed, however, due to adding "map_id" to first column of table, so -1 don't need it
          $group_data_by_column_index = str_replace('?type=dataset', '', get_post_meta($post->ID, '_group_data_by_column_index_localization', true));
        $total_number_by_attribute_name = str_replace('?type=dataset', '', get_post_meta($post->ID, '_total_number_by_attribute_name_localization', true));
        $related_profile_pages = str_replace('?type=dataset', '', get_post_meta($post->ID, '_related_profile_pages_localization', true));
    } else {
        $map_visualization_url = str_replace('?type=dataset', '', get_post_meta($post->ID, '_map_visualization_url', true));
        $ckan_dataset = str_replace('?type=dataset', '', get_post_meta($post->ID, '_csv_resource_url', true));
        $ckan_dataset_tracking = str_replace('?type=dataset', '', get_post_meta($post->ID, '_tracking_csv_resource_url', true));
        $filtered_by_column_index = str_replace('?type=dataset', '', get_post_meta($post->ID, '_filtered_by_column_index', true));  // index start from zero, so "-1" is needed, however, due to adding "map_id" to first column of table, so -1 don't need it
       $group_data_by_column_index = str_replace('?type=dataset', '', get_post_meta($post->ID, '_group_data_by_column_index', true));
        $total_number_by_attribute_name = str_replace('?type=dataset', '', get_post_meta($post->ID, '_total_number_by_attribute_name', true));
        $related_profile_pages = str_replace('?type=dataset', '', get_post_meta($post->ID, '_related_profile_pages', true));
    }
}
if (isset($map_visualization_url)) {
    $cartodb_url = $map_visualization_url;
    $cartodb_json = file_get_contents($cartodb_url);
    $cartodb_json_data = json_decode($cartodb_json, true);
    $cartodb_layer_option = $cartodb_json_data['layers'][1]['options'];
    $cartodb_layer_name = $cartodb_layer_option['layer_definition']['layers'][0]['options']['layer_name'];
}
if (isset($ckan_dataset) && $ckan_dataset != '') {
    $ckan_dataset_exploded_by_dataset = explode('/dataset/', $ckan_dataset);
    $ckan_dataset_exploded_by_resource = explode('/resource/', $ckan_dataset_exploded_by_dataset[1]);
    $ckan_dataset_id = $ckan_dataset_exploded_by_resource[0];
    $ckan_dataset_csv_id = $ckan_dataset_exploded_by_resource[1];

    $dataset = wpckan_api_package_show(wpckan_get_ckan_domain(),$ckan_dataset_id);
    if (!empty($filter_map_id)) {
        $profile = wpckan_get_datastore_resources_filter(wpckan_get_ckan_domain(), $ckan_dataset_csv_id, 'map_id', $filter_map_id)[0];
    } else {
        $profiles = wpckan_get_datastore_resource(wpckan_get_ckan_domain(), $ckan_dataset_csv_id);
    }
}

if (isset($ckan_dataset_tracking) && $ckan_dataset_tracking != '') {
    $ckan_dataset_tracking_exploded_by_dataset = explode('/dataset/', $ckan_dataset_tracking);
    $ckan_dataset_tracking_exploded_by_resource = explode('/resource/', $ckan_dataset_tracking_exploded_by_dataset[1]);
    $ckan_dataset_tracking_id = $ckan_dataset_tracking_exploded_by_resource[0];
    $ckan_dataset_tracking_csv_id = $ckan_dataset_tracking_exploded_by_resource[1];
    if (!empty($filter_map_id)) {
        $ammendements = wpckan_get_datastore_resources_filter(wpckan_get_ckan_domain(), $ckan_dataset_tracking_csv_id, 'map_id', $filter_map_id);
    }
}

if ((isset($ckan_dataset) && $ckan_dataset != '') || (isset($ckan_dataset_tracking) &&  $ckan_dataset_tracking != '')) {
    if ((odm_language_manager()->get_current_language() != 'en')) {
        $ckan_attribute = get_post_meta($post->ID, '_attributes_csv_resource_localization', true);
        $ckan_attribute_tracking = get_post_meta($post->ID, '_attributes_csv_resource_tracking_localization', true);
    } else {
        $ckan_attribute = trim(get_post_meta($post->ID, '_attributes_csv_resource', true));
        $ckan_attribute_tracking = get_post_meta($post->ID, '_attributes_csv_resource_tracking', true);
    }
}

if (isset($ckan_attribute) && $ckan_attribute != '') {
    $temp_ckan_attribute = explode("\r\n", $ckan_attribute);
    $array_attribute = array();
    foreach ($temp_ckan_attribute as $value) {
        $array_value = explode('=>', trim($value));
        $array_attribute[trim($array_value[0])] = trim($array_value[1]);
    }
    $DATASET_ATTRIBUTE = $array_attribute;
}

if (isset($ckan_attribute_tracking) && $ckan_attribute_tracking != '') {
    $temp_ckan_attribute_tracking = explode("\r\n", $ckan_attribute_tracking);
    $array_attribute = array();
    foreach ($temp_ckan_attribute_tracking as $value) {
        $array_value_tracking = explode('=>', trim($value));
        $array_attribute_tracking[trim($array_value_tracking[0])] = trim($array_value_tracking[1]);
    }
    $DATASET_ATTRIBUTE_TRACKING = $array_attribute_tracking;
}

$ref_docs_profile = array();
$ref_docs_tracking = array();
?>

  <section class="container section-title main-title">
    <header class="row">
      <div class="eight columns">
        <h1><?php the_title(); ?></h1>
      </div>
      <div class="eight columns align-right">
        <?php echo_metadata_button($dataset) ?>
        <?php echo_download_buttons($dataset); ?>
      </div>
    </header>
  </section>

  <section id="content" class="single-post">
    <?php if (!empty($filter_map_id)):
              include 'page-profiles-single-page.php';
          elseif (!empty($metadata_dataset)):
              include 'page-profiles-metadata-page.php';
          else:
            $template = get_post_meta($post->ID, '_attributes_template_layout', true);
            if ($template == 'with-widget'):
              include 'page-profiles-list-page-with-widget.php';
            else:
              include 'page-profiles-list-page.php';
            endif;

          endif; ?>
	</section>
<?php endif; ?>

<?php get_footer(); ?>

<script type="text/javascript">
var singleProfile = false;
var singleProfileMapId;
var mapViz;
var oTable;
var mapIdColNumber = 0;

<?php if (isset($map_visualization_url) && $map_visualization_url != '') {
    ?>
    var cartodb_user = "<?php echo $cartodb_layer_option['user_name'];?>";
    var cartodb_layer_table = "<?php  echo $cartodb_layer_name;?>";
    var cartodbSql = new cartodb.SQL({ user: cartodb_user });


    var filterEntriesMap = function(mapIds){
      var layers = mapViz.getLayers();
    	var mapIdsString = "('" + mapIds.join('\',\'') + "')";
    	var sql = "SELECT * FROM " + cartodb_layer_table + " WHERE map_id in " + mapIdsString;
    	var bounds = cartodbSql.getBounds(sql).done(function(bounds) {
    		mapViz.getNativeMap().fitBounds(bounds);
    	});
      if (mapIds.length==1){
        mapViz.map.set({
          maxZoom: 10
        });
      }
    	layers[1].getSubLayer(0).setSQL(sql);
    }

<?php
} ?>

jQuery(document).ready(function($) {
  //click file format show the list item for downloading
  $('.format_button').click(function(e){
      e.stopPropagation();
      $('.show_list_format').hide();
      $(this).children('.show_list_format').show();
  });
  //hide show download item if click anywhere
  $(document).click(function(){
    $('.show_list_format').hide(); //hide the button
  });

  if ($('.profile-metadata h2').hasClass('profile-name')) {
    var addto_breadcrumbs = $('.profile-metadata h1.profile-name').text();
    var add_li = $('<li class="separator_by"> / </li><li class="item_map_id"><strong class="bread-current">'+addto_breadcrumbs+'</strong></li>');
    add_li.appendTo( $('#breadcrumbs'));
    $('.item-current a').text($('.item-current a strong').text());
  }

  $.fn.dataTableExt.oApi.fnFilterAll = function (oSettings, sInput, iColumn, bRegex, bSmart) {
   var settings = $.fn.dataTableSettings;
   for (var i = 0; i < settings.length; i++) {
     settings[i].oInstance.fnFilter(sInput, iColumn, bRegex, bSmart);
   }
  };

<?php if ($filter_map_id == '' && $metadata_dataset == '') { ?>
  	var get_datatable = $('#profiles').position().top;
  	    get_datatable = get_datatable +230;

  	$(".content_wrapper").scroll(function(){
  			if ($(".content_wrapper").scrollTop()   >= get_datatable) {
  				$('.dataTables_scrollHead').css('position','fixed').css('top','50px');
  				$('.dataTables_scrollHead').css('z-index',9999);
  				$('.dataTables_scrollHead').width($('.dataTables_scrollBody').width());
  				$('.fixed_top_bar').width($('.dataTables_scrollBody').width());
  				$('.dataTables_scrollBody').css('top','60px');
          $('.fixed_top_bar').show();
  		   }
  		   else {
  				$('.dataTables_scrollHead').css('position','static');
          $('.fixed_top_bar').hide();
  				$('.dataTables_scrollBody').css('top','0');
  		   }
       });
     oTable = $("#profiles").dataTable({
       scrollX: true,
       responsive: false,
       "sDom": 'T<"H"lf>t<"F"ip>',
       processing: true,
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       displayLength: -1
       , columnDefs: [
         {
           "targets": [ 0 ],
           "visible": false
         }
       ]
       <?php if (odm_language_manager()->get_current_language() == 'km') { ?>
       , "oLanguage": {
           "sLengthMenu": 'បង្ហាញទិន្នន័យចំនួន <select>'+
               '<option value="10">10</option>'+
               '<option value="25">20</option>'+
               '<option value="50">50</option>'+
               '<option value="-1">ទាំងអស់</option>'+
             '</select> ក្នុងមួយទំព័រ',
           "sZeroRecords": "ព័ត៌មានពុំអាចរកបាន",
           "sInfo": "បង្ហាញពីទី _START_ ដល់ _END_ នៃទិន្នន័យចំនួន _TOTAL_",
           "sInfoEmpty": "បង្ហាញពីទី 0 ដល់ 0 នៃទិន្នន័យចំនួន 0",
           "sInfoFiltered": "(ទាញចេញពីទិន្នន័យសរុបចំនួន _MAX_)",
           "sSearch":"ស្វែងរក",
           "oPaginate": {
             "sFirst": "ទំព័រដំបូង",
             "sLast": "ចុងក្រោយ",
             "sPrevious": "មុន",
             "sNext": "បន្ទាប់"
           }
       }
       <?php
} ?>
       <?php
        if (isset($group_data_by_column_index) && $group_data_by_column_index != '') { ?>
         , "aaSortingFixed": [[<?php echo $group_data_by_column_index; ?>, 'asc' ]] //sort data in Data Classifications first before grouping
      <?php
} ?>
         , "drawCallback": function ( settings ) {  //Group colums
                 var api = this.api();
                 var rows = api.rows( {page:'current'} ).nodes();
                 var last=null;
                <?php
                if (isset($group_data_by_column_index) && $group_data_by_column_index != '') { ?>
                   api.column(<?php echo $group_data_by_column_index; ?>, {page:'current'} ).data().each( function ( group, i ) {
                       if ( last !== group ) {
                           $(rows).eq( i ).before(
                               '<tr class="group" id="cambodia-bgcolor"><td colspan="<?php echo  count($DATASET_ATTRIBUTE)?>">'+group+'</td></tr>'
                           );
                           last = group;
                       }
                   } );
                <?php
} ?>
               align_width_td_and_th();
           }
    });

     <?php if (isset($filtered_by_column_index) &&  $filtered_by_column_index != '') {
    $num_filtered_column_index = explode(',', $filtered_by_column_index);
    $number_selector = 1;
    foreach ($num_filtered_column_index as $column_index) {
        $column_index = trim($column_index);
        if ($number_selector <= 3) { ?>
          create_filter_by_column_index(<?php echo $column_index;?>);
    <?php
        }
        ++$number_selector;
    }
}
    ?>

     //Set width of table header and body equally
     function align_width_td_and_th(){
         var widths = [];
         var $tableBodyCell = $('.dataTables_scrollBody #profiles tbody tr:nth-child(2) td');
         var $headerCell = $('.dataTables_scrollHead thead tr th');
         var $max_width;
         $tableBodyCell.each(
           function(){
             widths.push($(this).width());
         });
         $tableBodyCell.each(
               function(i, val){
                 if ( $(this).width() >= $headerCell.eq(i).width() ){
                      $max_width =   widths[i];
                        $headerCell.eq(i).children('.th-value').css('width', $max_width);
                        if(!$(this).hasClass('group'))
                         $tableBodyCell.eq(i).children('.td-value').css('width', $max_width);
                 }else if ( $(this).width() < $headerCell.eq(i).width() ){
                      $max_width =   $headerCell.eq(i).width();
                      $tableBodyCell.eq(i).children('.td-value').css('width', $max_width);
                      $headerCell.eq(i).children('.th-value').css('width', $max_width);
                 }
             });
     }

     function create_filter_by_column_index(col_index){

       var columnIndex = col_index;
       var column_filter_oTable = oTable.api().columns( columnIndex );
       var column_headercolumnIndex = columnIndex -1;
       var column_header = $("#profiles").find("th:eq( "+column_headercolumnIndex+" )" ).text();
        <?php if (odm_language_manager()->get_current_language() == 'km') {
    ?>
                 var div_filter = $('<div class="filter_by filter_by_column_index_'+columnIndex+'"></div>');
                 div_filter.appendTo( $('#filter_by_classification'));
                 var select = $('<select><option value="">'+column_header+'<?php _e('all', 'odm');
    ?></option></select>');
        <?php

} else {
    ?>
                 var div_filter = $('<div class="filter_by filter_by_column_index_'+columnIndex+'"></div>');
                 div_filter.appendTo( $('#filter_by_classification'));
                 var select = $('<select><option value=""><?php _e('All ', 'odm');
    ?>'+column_header+'</option></select>');
        <?php
}
    ?>
           select.appendTo( $('.filter_by_column_index_'+columnIndex) )
           .on( 'change', function () {
               var val = $.fn.dataTable.util.escapeRegex(
                   $(this).val()
               );
               column_filter_oTable
                   .search( val ? '^'+val : '', true, false )
                   .draw();

                    var filtered = oTable._('tr', {"filter":"applied"});
                    <?php if (isset($map_visualization_url) &&  $map_visualization_url != '') {
    ?>
                    filterEntriesMap(_.pluck(filtered,mapIdColNumber));
                    <?php
}
    ?>
           } );
           var i = 1;
           column_filter_oTable.data().eq( 0 ).unique().sort().each( function ( d, j ) {
               d = d.replace(/[<]br[^>]*[>]/gi,"");
               var value = d.split('<');
               var first_value = value[1].split('>');
               var only_value = first_value[1].split('<');
               val = first_value[1].trim();
              select.append( '<option value="'+val+'">'+val+'</option>' )
           } );
     }

    var $filter_data = $("#filter_by_classification").clone(true);
    var $fg_search_filter_bar = $(".dataTables_filter").clone(true);
    var $fg_show_entry_bar = $(".dataTables_length").clone(true);

    $(".fixed_top_bar").prepend($filter_data);
    $(".fixed_top_bar").append($fg_show_entry_bar);
    $(".fixed_top_bar").append($fg_search_filter_bar);

    $('.fixed_top_bar .dataTables_length select').val($('.table-column-container .dataTables_length select').val());
    $('.fixed_top_bar .dataTables_length select').on( 'change', function () {
       $('.table-column-container .dataTables_length select').val($(this).val());
    });
    $('.table-column-container .dataTables_length select').on( 'change', function () {
       $('.fixed_top_bar .dataTables_length select').val($(this).val());
    });

    $('.table-column-container #filter_by_classification select').each(function(index){
        $(this).change(function() {
            $('.fixed_top_bar #filter_by_classification select').eq(index).val($(this).val());
        });
    })
    $('.fixed_top_bar #filter_by_classification select').each(function(index){
          $(this).change(function() {
            $('.table-column-container #filter_by_classification select').eq(index).val($(this).val());
          });
    })

    $('.dataTables_scrollHead').scroll(function(e){
           $('.dataTables_scrollBody').scrollLeft(e.target.scrollLeft);
    });

   $("#search_all").keyup(function () {
     oTable.fnFilterAll(this.value);
     var filtered = oTable._('tr', {"filter":"applied"});
     <?php if (isset($map_visualization_url) && $map_visualization_url != '') {
    ?>
     filterEntriesMap(_.pluck(filtered,mapIdColNumber));
     <?php
}
    ?>
   });
<?php
}
?>
 });
<?php if (isset($map_visualization_url) && $map_visualization_url != '') {
    ?>
     window.onload = function() {
       cartodb.createVis('profiles_map', '<?php echo $map_visualization_url;?>', {
     		search: false,
     		shareable: true,
         zoom: 7,
         center_lat: 12.54384,
         center_long: 105.60059,
     		https: true
     	}).done(function(vis, layers) {
         singleProfile = $('#profiles').length <= 0;
     		mapViz = vis;
         if (singleProfile){
           singleProfileMapId  = $("#profile-map-id").text();
           filterEntriesMap([singleProfileMapId]);
         }
     	});

    }
<?php
} ?>
 </script>
