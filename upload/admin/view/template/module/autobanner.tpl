<?php echo $header ?>
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
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a>
				<a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class='list'>
				<thead>
					<tr>
					<td><input type='checkbox' /></td>
					<td class='left'><?php echo $entry_name ?></td>
					<td></td>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($own_banners as $b) { ?>
					<tr>
						<td><input type='checkbox' name='delete[]'
						           value='<?php echo $b['banner_id']?>'/></td>
						<td class='left'><?php echo $b['name']?></td>
						<td>
							<a href='<?php echo $b['edit_link']?>'>Edit</a>
						</td>
					</tr>
				<?php
				} ?>
				</tbody>
			</table>
			</form>
		</div>
	</div>
</div>
<?php echo $footer ?>