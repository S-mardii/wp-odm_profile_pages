<script type="text/javascript">
var singleProfile = false;
var singleProfileMapId;
var mapViz;

<?php if(isset($map_visualization_url) && $map_visualization_url !='') { ?>
    var cartodb_user = "<?php echo $cartodb_layer_option['user_name']; ?>";
    var cartodb_layer_table = "<?php  echo $cartodb_layer_name; ?>";
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

    window.onload = function() {
       cartodb.createVis('profiles_map', '<?php echo $map_visualization_url; ?>', {
     		search: false,
     		shareable: true,
         zoom: 7,
         center_lat: 12.54384,
         center_long: 105.60059,
     		https: true
    }).done(function(vis, layers) {
         singleProfile = $('#profiles').length <= 0;
         //console.log("cartodb viz created. singleProfile: " + singleProfile);
     		mapViz = vis;
         if (singleProfile){
           singleProfileMapId  = $("#profile-map-id").text();
           filterEntriesMap([singleProfileMapId]);
         }
     	});

    }//window
<?php } ?>
 </script>
