<script type='text/javascript'>
jQuery(document).ready(function($){
	/* The product image has ID 'image' in OpenCart 1.5.5 */
	var large = $('#image').parent().attr('href')
	$('#image').addimagezoom({
		<?php
		if (isset($range_low) && isset($range_high)) {
			echo "zoomrange: [$range_low, $range_high],\n";
		}
		if (isset($size_low) && isset($size_high)) {
			echo "magnifiersize: [$size_low, $size_high],\n";
		}
		if (isset($curshade)) {
			echo "cursorshade: ".($curshade ? 'true' : 'false').",\n";
		}
		if (isset($curshadecolor)) {
			echo "cursorshadecolor: '$curshadecolor',\n";
		}
		if (isset($curshadeopacity)) {
			echo "cursorshadeopacity: $curshadeopacity,\n";
		} ?>
		largeimage: large
	})
})
</script>