<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php
		foreach ($breadcrumbs as $breadcrumb) {
			echo $breadcrumb['separator']; ?>
			<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php
		}
		?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
				<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td class="left"><?php echo $entry_zoomrange; ?></td>
							<td class="left"><?php echo $entry_magnifiersize; ?></td>
							<td class="left"><?php echo $entry_curshade; ?></td>
							<td class="left"><?php echo $entry_cursorshadecolor; ?></td>
							<td class="left"><?php echo $entry_cursorshadeopacity; ?></td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<input type='number' maxlength='2' size='2' min='1' max='10'
											 name='fiz[range_low]'
											<?php if (isset($range_low)) echo "value='$range_low'"?> /> -
								<input type='number' maxlength='2' size='2' min='1' max='10'
											 name='fiz[range_high]'
											<?php if (isset($range_high)) echo "value='$range_high'"?> />
								<?php if ($error_zoomrange)
								echo "<div class='error'>$error_zoomrange</div>"; ?>
							</td>
							<td>
								<input type='number' maxlength='3' size='3' min='50' max='900'
											name='fiz[size_low]' value='<?php
											if (isset($size_low)) echo $size_low;
											else echo '200'?>' /> -
								<input type='number' maxlength='3' size='3' min='50' max='900'
											name='fiz[size_high]' value='<?php
											if (isset($size_high)) echo $size_high;
											else echo '200'?>' />
								<?php if ($error_magnifiersize)
								echo "<div class='error'>$error_magnifiersize</div>"; ?>
							</td>
							<td>
								<select name='fiz[curshade]'>
									<?php $sel = isset($curshade) ? $curshade : 0 ?>
									<option value='0' <?php if (!$sel) echo "selected='true'"?>>False</option>
									<option value='1' <?php if ($sel) echo "selected='true'"?>>True</option>
								</select>
							</td>
							<td>
								<input type='text' name='fiz[curshadecolor]'
									   maxlength='7' size='7'
									value='<?php echo isset($curshadecolor) ? $curshadecolor : '#fff' ?>'/>
								<?php if ($error_curshadecolor)
								echo "<div class='error'>$error_curshadecolor</div>"; ?>
							</td>
							<td>
								<input type='number' id='opacity' size='2'
											 style='border: 0; color: #f6931f; font-weight: bold; float: left'
											 name='fiz[curshadeopacity]'
									value='<?php echo isset($curshadeopacity) ? $curshadeopacity : '0.3' ?>'/>
								<div id="slider" style='width: 100px; float: left'></div>
								<?php if ($error_curshadeopacity)
								echo "<div class='error'>$error_curshadeopacity</div>"; ?>
							</td>
							<td class='right'><a class='button' id='reset'>Reset to default</a></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>
<script type='text/javascript'>
	// Onload
	$(function() {
		var initval = $("#opacity").val();
		if (!initval) {
			initval = 0.3;
			$("#opacity").val(initval);
		}
		$("#slider").slider({
				value: initval,
				min: 0.1,
				max: 1,
				step: 0.1,
				slide: function(event, ui) {
						$("#opacity").val(ui.value);
				}
		});
	});

	$('#reset').click(function() {
		$('input[name="fiz[range_low]"]').val('')
		$('input[name="fiz[range_high]"]').val('')
		$('input[name="fiz[size_low]"]').val(200)
		$('input[name="fiz[size_high]"]').val(200)
		$('select[name="fiz[curshade]"]').val(0)
		$('input[name="fiz[curshadecolor]"]').val('#fff')
		$("#slider").slider('value', 0.3)
		$('input[name="fiz[curshadeopacity]"]').val(0.3)
	});
</script>
<?php echo $footer; ?>
