<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php echo ($this->session->flashdata('message') == NULL) ? '' : '<div style="background-color: green; color: white; text-align: center; padding: 15px; margin-bottom: 15px;">'.$this->session->flashdata('message').'</div>'?>

<form class="form-horizontal well" action="<?php echo base_url(); ?>import/add" method="post" name="upload_excel" enctype="multipart/form-data">
<input type="file" name="file" id="file" class="input-large">
<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading">Upload</button>
</form>
</body>
</html>