    <form method="post">
    
      <fieldset>
      
        <legend>Execute this Search</legend>

        <p class="space">
          <label for="name">Search Name</label>
          <input type="text" id="name" name="search[name]" value="<?php echo set_value('search[name]', $search['name']); ?>" size="32" disabled>
        </p>

        <p>
          <label for="sites">Site</label>
          <input type="text" id="name" name="search[site_name]" value="<?php echo set_value('search[site_name]', $search['site_name']); ?>" size="11" disabled>
        </p>
        
        <p>
          <label for="url">Search URL</label>
          <input type="text" id="url" name="search[url]" value="<?php echo set_value('search[url]', $search['url']); ?>" size="105" maxlength="254" autofocus>
          <?php echo form_error('search[url]', '<br>', ''); ?>
        </p>

        <p><input type="submit" name="submit" value="Ok"></p>
        
      </fieldset>

    </form>

      <p><?php echo anchor('searches', 'Add a Search'); ?></p>
