<?php

	$status = $this->Rumahku->filterEmptyField($result,'status');
	$id = $this->Rumahku->filterEmptyField($result,'Log','document_id');
	$approval_status = $this->Rumahku->filterEmptyField($result,'approval_status',false,0);

	echo '<div id="status">'.$status.'</div>';
	echo '<div id="id">'.$id.'</div>';
	echo '<div id="approval_status">'.$approval_status.'</div>';
?>