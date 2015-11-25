		<form method="post">
		
			<fieldset>

				<legend>Add a New Search</legend>
					
				<p class="space">
					<label for="name">Search Name</label>
					<input type="text" id="name" name="search[name]" value="<?php echo set_value('search[name]', '') ?>" size="32" maxlength="32">
					<?php echo form_error('search[name]', '<br>', '') ?>
				</p>

				<p>
					<label for="sites">Job Site</label>
					<select id="sites" name="search[site_id]">
						<option selected>Select one</option>
<?php foreach ($sites as $row):?>
						<option value="<?=$row['id']?>" <?php echo set_select('search[site_id]', $row['id']) ?>><?=$row['name']?></option>
<?php endforeach;?>
					</select>
					<?php echo form_error('search[site_id]', '<br>', '') ?>
				</p>
				
				<p>
					<label for="url">Search URL</label>
					<input type="text" id="url" name="search[url]" value="<?php echo set_value('search[url]', '') ?>" size="105" maxlength="254">
					<?php echo form_error('search[url]', '<br>', '') ?>
				</p>

				<p><input type="submit" name="submit" value="Ok"></p>
				
			</fieldset>

		</form>

	    <p>&nbsp;</p>
