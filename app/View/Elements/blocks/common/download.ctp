<?php
        $ext = $this->Rumahku->_callGetExt($filepath);
        $content_type = $this->Rumahku->_getContentType($ext);

        header('Pragma: public');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Content-Type: '.$content_type);
        header("Content-disposition: attachment; filename=\"" . String::uuid().".".$ext . "\""); 
	header('Content-Transfer-Encoding: binary');
        readfile($filepath);
        exit();
?>
