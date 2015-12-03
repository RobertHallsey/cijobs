<?php $this->load->view('header'); ?>

  <div id="content">

    <h2>Saved Job Searches</h2>

<?php
	if (count($searches) == 0)
	{
		echo '<p>There are no saved searches.</p>';
	}
	else
	{
		$this->table->set_heading('Search Name', 'Job Site', 'Search URL', 'Edit', 'Delete', 'Execute');
		foreach ($searches as $search)
		{
			$this->table->add_row(
				$search['name'], $search['site_name'], $search['url'], 
				anchor('searches/edit/' . $search['id'], 'Edit'), 
				anchor('searches/delete/' . $search['id'], 'Delete'),
				anchor('searches/execute/' . $search['id'], 'Execute')
			);
		}
		echo $this->table->generate();
	}
?>

<?php $this->load->view($subview); ?>

	</div><!-- content -->

<?php $this->load->view('footer'); ?>
