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
		<?php
		if (isset($banner_id)) {
			echo "<input type='hidden' name='banner_id' value='$banner_id' />";
		}
		?>
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" size="100" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>

		<div with='100%' style='overflow: hidden'>
		<table id='allproducts' class='list'>
			<thead>
				<tr>
					<td class='left'>Image</td>
					<td><?php echo $entry_name; ?></td>
					<td>Model</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($allproducts as $p) {
				$id = $p['product_id']; ?>
				<tr>
				<td><img src='<?php echo $p['image']?>' /></td>
				<td><?php echo $p['name']?></td>
				<td><?php echo $p['model']?></td>
				<td class='center'>
				<img class='addproduct' src='view/image/package_go.png'
					 alt='<?php echo $id ?>'/></td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<table id='ownproducts' class='list'>
			<thead>
				<tr>
					<td class='left'>Image</td>
					<td><?php echo $entry_name; ?></td>
					<td>Model</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($ownproducts as $p) {
				$id = $p['product_id']; ?>
				<tr>
				<td><img src='<?php echo $p['image']?>' /></td>
				<td><?php echo $p['name']?></td>
				<td><?php echo $p['model']?></td>
				<td class='center'>
				<img class='remproduct' src='view/image/delete.png'
					 alt='<?php echo $id ?>'/>
				<input type='hidden' name='selected[]' value='<?php echo $id ?>' />
				</td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		</div>
		</form>
		</div>
	</div>
</div>
<script type='text/javascript'>
	var remove_empty = true;
	var table_allproducts = null;
	var table_ownproducts = null;
	/* Remove the row saying that there is no data */
	function remove_empty_row() {
		var tbody = $('#ownproducts > tbody:last');
		var td = tbody.find('td.dataTables_empty');
		if (td.length) {
			tbody.empty();
		}
		remove_empty = false;
	}

	function init_tables() {
		$('#allproducts').removeAttr('style');
		table_allproducts = $('#allproducts').dataTable();
		$('#ownproducts').removeAttr('style');
		table_ownproducts = $('#ownproducts').dataTable({"bLengthChange": false, "bInfo": false})
	}

	function restore_tables() {
		table_allproducts.fnDestroy();
		table_ownproducts.fnDestroy();
	}

	$(document).ready(function() {
		init_tables();
	});

	function select_product(event) {
		//if (remove_empty) remove_empty_row();
		restore_tables();
		var row = $(this).closest('tr');
		this.src = 'view/image/delete.png';
		var select_indicator = document.createElement('input')
		select_indicator.type = 'hidden'
		select_indicator.name = 'selected[]'
		select_indicator.value = this.alt
		$(this).closest('td').append(select_indicator)
		$(this).one('click', unselect_product);
		$('#ownproducts > tbody:last').append(row);
		init_tables();
	}

	function unselect_product(event) {
		restore_tables();
		$(this).next('input[type=hidden]').remove()
		this.src = 'view/image/package_go.png';
		$(this).one('click', select_product);
		var row = $(this).closest('tr');
		$('#allproducts > tbody:last').append(row);
		init_tables();
	}

	$('img.addproduct').one('click', select_product);
	$('img.remproduct').one('click', unselect_product);
</script>
<?php echo $footer ?>