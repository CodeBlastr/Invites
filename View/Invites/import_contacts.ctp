<div id="importedContacts">
	<h1><?php echo __('Imported Contacts'); ?></h1>
	<?php 
		if(!empty($contacts['users'])) { 
			echo $this->Form->create('Referral', array('url' => 'invitation'));
	?>
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th><input type = "checkbox" id = "selectAll" /></th>
						<th>Email</th>
						<th>First Name</th>
						<th>Last Name</th>
					</tr>
				</thead>
			<?php
				foreach($contacts['users'] as $ck => $contact):
			?>
				<?php $name = explode(' ', $contact['first_name']) ;?>
				<tbody>
					<tr>
						<td> 
						<?php 
							echo $this->Form->checkbox("Referral.{$ck}.email", array('div'=>'false', 'label'=>'false', 'class' => 'select', 'value' => $contact['email']));
							echo $this->Form->hidden("Referral.{$ck}.first_name", array('value' => $name[0]));
							echo $this->Form->hidden("Referral.{$ck}.last_name", array('value' => !empty($name[1]) ? $name[1] : '' ));
						?>
						</td>
						<td><?php echo $contact['email']; ?>&nbsp;</td>
						<td><?php echo $name[0]; ?>&nbsp;</td>
						<td><?php echo !empty($name[1]) ? $name[1] : '' ; ?>&nbsp;</td>
					</tr>
				</tbody>	
			<?php 
				endforeach; 
			?>
			</table>	
	<?php 	
			echo $this->Form->end(__('Invite', true));
		} else {
			if(!empty($contacts['error'])) {
				echo "<h3>{$contacts['error']}</h3>";
			} else {
				echo "<h3>No Data Found</h3>";
				echo $this->Html->link('Try Again', 'invite/invites/invitation');
			}
		} 
	?>
</div>

<script>
$("#selectAll").live('change',function(e){
	if ($("#selectAll").is(':checked')) {
		$(".select").attr("checked", true) ;
	} else {
		$(".select").attr("checked", false) ;
	}
});
</script>
	