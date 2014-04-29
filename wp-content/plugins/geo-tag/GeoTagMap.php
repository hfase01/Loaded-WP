<?php

class GeoTagMap {
	
	function generateMap($attributes, $posts, $showPostCollectionMap, $count) {

		ob_start();
		
	?>

	<script type="text/javascript"> 
		<?php if($count == 0):?> 
			var geoTagPosts = new Array(); 
			var geoTagAttributes = new Array(); 
		<?php endif;?> 

		geoTagPosts[<?php echo $count; ?>] = <?php echo json_encode($posts); ?>; 
		geoTagAttributes[<?php echo $count; ?>] = <?php echo json_encode($attributes); ?>; 

	</script>
		
	<?php if($showPostCollectionMap): ?>	
		<div id="geoTagMap_<?php echo $count; ?>" class="geoTagMap" style="height:<?php echo $attributes['height']; ?>px; width:<?php echo $attributes['width']; ?>px;"></div>
	<?php endif; ?>	
	<?php

		return ob_get_clean();
		
	}
	
	
}

?>