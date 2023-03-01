<?php if($this->session->flashdata('success') != NULL){ ?>
	
	<script type="text/javascript">

	$(function(){

	new PNotify({
    title: "Notification",
    text: "Data has been saved successfully.",
    type: 'success'
	});

	});

	</script>

<?php } ?>

<?php if($this->session->flashdata('delete') != NULL){ ?>
	
	<script type="text/javascript">

	$(function(){

	new PNotify({
    title: "Notification",
    text: "Data has been deleted successfully.",
    type: 'success'
	});

	});

	</script>

<?php } ?>

<?php if($this->session->flashdata('update') != NULL){ ?>
	
	<script type="text/javascript">

	$(function(){

	new PNotify({
    title: "Notification",
    text: "Data has been updated successfully.",
    type: 'success'
	});

	});

	</script>

<?php } ?>

<?php if(validation_errors()){ ?>
	
	<script type="text/javascript">

	$(function(){

	var message = <?php echo $this->session->flashdata('validation');?>;
	$(document).find('body').append('<input type="hidden" id="message_notification" value="'+ message +'">');

	new PNotify({
    title: 'Notification',
    text: $(document).find('#message_notification').val(),
    type: 'notice'
	});

	});

	</script>

<?php } ?>