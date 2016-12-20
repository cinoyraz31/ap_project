<?php 
class RmImageComponent extends Component {
	public $components = array(
		'RmCommon'
	);

	function _setValue ( $options ) {
		$allow_only_favicon = array('ico');
		$allowed_ext = Configure::read('__Site.allowed_ext');
		$allow_only_mimefavicon = array(
			'image/vnd.microsoft.icon', 
			'image/ico', 'image/icon', 
			'text/ico', 'application/ico',
			'image/x-icon'
		);
		$default_mime = array(
			'image/gif', 'image/jpeg', 
			'image/png', 'image/pjpeg', 'image/x-png',
			'application/pdf',
		);
		$allowed_mime = $default_mime;
		$baseuploadpath = Configure::read('__Site.upload_path');

		if(!empty($options) && !empty($options['favicon'])) {
			$allowed_ext = $allow_only_favicon;
			$allowed_mime = $allow_only_mimefavicon;
		}

    	return array(
			'max_size' => Configure::read('__Site.max_image_size'),
			'max_width' => Configure::read('__Site.max_image_width'),
			'max_height' => Configure::read('__Site.max_image_height'),
			'allowed_ext' => $allowed_ext,
			'allowed_mime' => $allowed_mime,
			'baseuploadpath' => $baseuploadpath,
		);
	}

	private function _setNameConvertJpg( $prefix, $convertExtension, $upload_sub_path, $uploadPhotoPath ) {
		$filename = sprintf('%s.%s', $prefix, $convertExtension);
		$uploadFilename = $upload_sub_path.$filename;
		return array(
			'filename' => $filename,
			'filenameConverter' => $uploadFilename,
			'pathNameConverter' => str_replace('/', DS, $uploadPhotoPath.$uploadFilename),
		);
	}

	private function _setDimensionList ( $save_path, $extension = false, $options ) {
		$dimensionList = $this->_rulesDimensionImage($save_path, false, $extension, $options);

		return $dimensionList;
	}

	function _createFolderThumb ( $thumbnailPath, $filename ) {
        $srcName = explode('/', $filename);

        if( count($srcName) == 5 ) {
	        $year = !empty($srcName[1])?$srcName[1]:date('Y');
			$month = !empty($srcName[2])?$srcName[2]:date('m');
			$char = isset($srcName[3])?DS.$srcName[3]:false;
			$name = !empty($srcName[4])?$srcName[4]:false;
        } else {
        	$year = !empty($srcName[1])?$srcName[1]:date('Y');
			$month = !empty($srcName[2])?$srcName[2]:date('m');
			$name = !empty($srcName[3])?$srcName[3]:false;
			$char = false;
        }
		$thumbnailPath = $thumbnailPath.DS.$year.DS.$month.$char;

		if(!file_exists($thumbnailPath)) {
			mkdir($thumbnailPath, 0755, true);
		}

		return $thumbnailPath.DS.$name;
	}

	function _allowGeneratePhoto () {
		return array(
			'AllowFullsize' => array(
				Configure::read('__Site.profile_photo_folder'), 
				Configure::read('__Site.logo_photo_folder'), 
				Configure::read('__Site.general_folder'), 
				Configure::read('__Site.file_folder'),
				Configure::read('__Site.document_folder'),
			),
		);
	}

	function _generateThumbnail ( $filename, $field, $save_path, $options = false, $extension = false ) {
		if( !empty($filename[$field])) {

			$photoPath = Configure::read('__Site.upload_path');
			$thumbnailPath = Configure::read('__Site.thumbnail_view_path');
			$allowAction = $this->_allowGeneratePhoto();
			$allowFullsize = $allowAction['AllowFullsize'];

			$save_path = str_replace(array( '/', DS ), array( '', '' ), $save_path);
			$dimensionList = $this->_setDimensionList( $save_path, $extension, $options );
			$filename[$field] = $this->replaceSlash($filename[$field], 'reverse');

			$filePhotoPath = $this->replaceSlash($photoPath.DS.$save_path.$filename[$field]);

			if( file_exists($filePhotoPath) ) {
				if( in_array($save_path, $allowFullsize) ) {
					$dimensionList['fullsize'] = 'fullsize';
				}

				if( !empty($dimensionList) ) {
					foreach ($dimensionList as $key => $dimension) {
						$thumbnail_size_path = str_replace('/', DS, $thumbnailPath.DS.$save_path.DS.$key);
						$thumbnail_filename = $this->_createFolderThumb( $thumbnail_size_path, $filename[$field] );
						
						if( $dimension != 'fullsize' ) {
							list($thumbnail_width, $thumbnail_height) = explode('x', $dimension);

							$this->_createThumbnail($filePhotoPath, $thumbnail_filename, $thumbnail_width, $thumbnail_height);
						} else if( !file_exists($thumbnail_filename) ) {
							copy($filePhotoPath, $thumbnail_filename);
						}
					}
				}
			}
		} else {
			return false;
		}
	}

	function _updatePhotoThumbnail ( $data, $field, $save_path, $modelName, $options = false ) {
		if( !empty($data) && empty($data['is_generate_photo']) && !empty($data['id']) ) {
			if( is_array($field) && !empty($field) ) {
				foreach ($field as $key => $value) {
					$this->_generateThumbnail( $data, $value, $save_path, $options );
				}
			} else {
				$this->_generateThumbnail( $data, $field, $save_path, $options );
			}

			$this->unGeneratePhoto( $data['id'], $modelName );
		}
	}

	function _getDataArr ( $data, $indexName ) {
		if( !empty($indexName) && !empty($data[$indexName]) ) {
			$dataArr = $data[$indexName];
		} else {
			$dataArr = $data;
		}

		return $dataArr;
	}

	function _callGenerateThumbnail ( $data, $modelName = false, $field, $save_path, $is_loop = false, $options = false ) {
		if( is_array($modelName) ) {
			$tmpModelName = $modelName;
			$indexName = !empty($tmpModelName[0])?$tmpModelName[0]:false;
			$modelName = !empty($tmpModelName[1])?$tmpModelName[1]:false;
		} else {
			$indexName = $modelName;
		}
		$dataArr = $this->_getDataArr( $data, $indexName );

		if( !empty($is_loop) && !empty($data) ) {
			foreach ($data as $key => $media) {
				$dataArr = $this->_getDataArr( $media, $indexName );

				if( !empty($dataArr) && empty($dataArr['is_generate_photo']) ) {
					$this->_updatePhotoThumbnail( $dataArr, $field, $save_path, $modelName, $options );
				}
			}
		} else if( !empty($dataArr) && empty($dataArr['is_generate_photo']) ) {
			$this->_updatePhotoThumbnail( $dataArr, $field, $save_path, $modelName, $options );
		}
	}

	function upload($uploadedInfo, $uploadTo, $prefix, $options = array()){
		$this->options = $this->_setValue( $options );
		$uploadTo = sprintf('/%s/', $uploadTo);

		if( !empty($options) ) {
			foreach($options as $key=>$value) {
				$this->options[$key] = $value;
			}
		}

		$result = $this->validateFile($uploadedInfo);

		if( empty($result['error']) ) {
			$upload_sub_path = '';
			$file_info = pathinfo($uploadedInfo['name']);
			$basename = $this->RmCommon->filterEmptyField($file_info, 'basename');

			$upload_dir = str_replace('/', DS, $uploadTo);
			$save_path = str_replace('/', '', $uploadTo);
			$upload_path = $this->options['baseuploadpath'].$upload_dir;
			$photo_extension = strtolower($file_info['extension']);
			$filename = sprintf('%s.%s', $prefix, $photo_extension);
			$thumbnailPath = Configure::read('__Site.thumbnail_view_path').$uploadTo;

			// if( !in_array($photo_extension, array( 'pdf', 'xls', 'xlsx' )) ) {
			// 	$convertExtension = 'jpg';
			// } else {
				$convertExtension = $photo_extension;
			// }

			$photo_subpath = $this->generateSubPathFolder($filename);
			$upload_sub_path = $this->makeDir( $upload_path, $photo_subpath );

			if( empty($upload_sub_path) ) {
				$result = false;
			} else {
				$uploadPhotoPath = str_replace('/', DS, $upload_path);
				$uploadFilename =  str_replace('/', DS, $upload_sub_path.$filename);
				$uploadSource = $uploadPhotoPath.$uploadFilename;
				if( move_uploaded_file( $uploadedInfo["tmp_name"], $uploadSource ) ){

					$name_for_db = str_replace(DS, '/', '/'.$uploadFilename);

					$sizes = getimagesize($uploadSource);
					$width = $sizes[0];
					$height = $sizes[1];
					if( !in_array($photo_extension, array( 'pdf', 'xls', 'xlsx' )) ) {
						$dimensionThumb = $this->_rulesDimensionImage($save_path, 'thumb', $photo_extension, $options);
						$imagePath = Configure::read('__Site.cache_view_path').str_replace(DS, '/', $upload_dir.$dimensionThumb.DS.$uploadFilename);

						$scale = $this->getScale( $width, $height, $this->options['max_width'], $this->options['max_height'] );
						$uploaded = $this->_resizeImage($uploadSource, $width, $height, $scale, $options, $photo_extension);
					} else {
						$scale = 1;

						if( $photo_extension == 'pdf' ) {
							$imagePath = '/img/pdf.png';
						} else {
							$imagePath = '/img/excel.png';
						}
					}

					$allowAction = $this->_allowGeneratePhoto();
					$allowFullsize = $allowAction['AllowFullsize'];
					$data = array(
						'src' => DS.$uploadFilename,
						'is_generate_photo' => false,
					);
					$this->_generateThumbnail( $data, 'src', $save_path, $options, $photo_extension );
					$result = array(
						'error' => 0,
						'baseName' => $basename, 
						'imagePath' => $imagePath, 
						'imageName' => $name_for_db, 
						'imageWidth' => ceil($width * $scale), 
						'imageHeight' => ceil($height * $scale),
					);
				} else {
					$result = false;
				}
			}
		}
        return $result;
    }
	
    private function _createThumbnail($source, $destination, $width, $height, $options = false ) {
    	$info = pathinfo($source);
        $info['extension'] = strtolower($info['extension']);

        if (class_exists('imagick')) {
			$image = new Imagick($source);

			if( in_array($info['extension'], array( 'png', 'gif' )) ){
				$image->setImageBackgroundColor(new ImagickPixel('transparent')); 
				$image->thumbnailImage ($width, $height, true, false);
			} else {
				$image->thumbnailImage ($width, $height, true, true);
			}
			
			$image_width = $image->getImageWidth();
			$image_height = $image->getImageHeight();
			
			$image->writeImage($destination);			
		} else {
			$this->_oldCreateThumbs( $source, $destination, $width, $height );
		}

		return $source;
	}

	function _oldCreateThumbs( $source, $destination, $width, $height )  {
		App::import('Vendor', 'thumb', array('file' => 'thumb'.DS.'ThumbLib.inc.php'));
        // parse path for the extension
        $info = pathinfo($source);
        $info['extension'] = strtolower($info['extension']);

		copy($source, $destination);

		if( !in_array($info['extension'], array( 'ico', 'pdf', 'xls' )) ){
			$this->thumb = PhpThumbFactory::create($destination);
			$imgCrop = $this->thumb->adaptiveResize($width, $height);
		}

		if($info['extension'] == "png"){
            @imagepng($imgCrop->workingImageCopy, $destination, 9);
        } elseif($info['extension'] == "jpg" || $info['extension'] == "jpeg") {
            @imagejpeg($imgCrop->workingImageCopy, $destination, 90);
        } elseif($info['extension'] == "gif") {
            @imagegif($imgCrop->workingImageCopy, $destination);
        }
    }

	/**
	* Scaling Foto
	*
	* @param number $w - Lebar Foto
	* @param number $h - Panjang Foto
	* @param number max_w - Maksimal Lebar Foto
	* @param number max_h - Maksimal Panjang Foto
	* @param number wscale - Lebar Skala
	* @param number hscale - Panjang Skala
	* @param number scale - Skala Foto
	* @return number - Skala Foto
	*/
    function getScale ( $w, $h, $max_w, $max_h ) {
    	if (($w > $max_w) && ($h > $max_h)){
			$wscale = $max_w/$w;
			$hscale = $max_h/$h;
			if($wscale <= $hscale) {
				$scale = $wscale;	
			} else {
				$scale = $hscale;	
			}
		} elseif ($w > $max_w){
			$scale = $max_w/$w;
		} elseif ($h > $max_h){
			$scale = $max_h/$h;
		} else {
			$scale = 1;
		}
		return $scale;
    }

	/**
	* Craete Direktori
	*
	* @param string $upload_path - Direktori folder yang akan diupload
	* @param array $thumbnailPath - Direktori upload file Thumbnail
	* @param string $year - Tahun Upload
	* @param string $month - Bulan Upload
	* @param string $yearDir - Direktori Folder Tahun
	* @param string $monthDir - Direktori Folder Bulan
	* @param string $yearFullsizeDir - Direktori Folder Tahun untuk file ukuran sebenarnya
	* @param string $monthFullsizeDir - Direktori Folder Bulan untuk file ukuran sebenarnya
	* @return string - Direktori Folder File
	*/
    function makeDir( $upload_path = false, $photo_subpath = '', $year = false, $month = false ) {
    	$year = !empty($year)?$year:date('Y');
    	$month = !empty($month)?$month:date('m');

    	if( !empty($upload_path) ) {
	    	$yearDir = $upload_path.$year.DS;
	    	$monthDir = $yearDir.$month.DS;

	    	if( !file_exists($yearDir) ) {
	    		mkdir($yearDir, 0775, true);
	    	}

	    	if( !file_exists($monthDir) ) {
	    		mkdir($monthDir, 0775, true);
	    	}

	    	if($photo_subpath != '') {
		    	$subDir = $monthDir.$photo_subpath.DS;

		    	if( !file_exists($subDir) ) {
		    		mkdir($subDir, 0775, true);
		    	}
		    }
	    	
    	}

		if($photo_subpath != '') {
			return sprintf('%s/%s/%s/', $year, $month, $photo_subpath);
		} else {
			return sprintf('%s/%s/', $year, $month);
		}
    }

	/**
	* Validasi File
	*
	* @param array $file - data file berupa name, type, tmp_name, error, size
	*		array name - Nama file
	*		string type - Tipe file
	*		string tmp_name - Direktori penyimpanan dilocal
	*		boolean error - status upload file, True terjadi error, False tidak terjadi Error
	*		number size - Besar ukuran file
	* @param array $error - hasil validasi foto berupa error, message
	*		boolean error - status upload file, True terjadi error, False tidak terjadi Error
	*		string message - Notifikasi pesan alert
	* @param array $file_info - Informasi File yang diupload
	*		string dirname - Direktori file
	*		string basename - Nama file beserta extension
	*		string extension - Extension file
	*		string filename - Nama file tanpa extension
	* @return array - hasil validasi foto berupa error, message
	*/
	function validateFile($file) {
		$error = array(
			'error' => 0,
			'message' => ''
		);

		if($file['error'] != 0) {
			$error = array(
				'error' => 1,
				'message' => __('File tidak valid')
			);
		} else {
			$file_info = pathinfo($file["name"]);
			$file_info['extension'] = strtolower($file_info['extension']);
			if(!in_array($file_info['extension'], $this->options['allowed_ext'])) {
				$error = array(
					'error' => 1,
					'message' => sprintf(__('Mohon hanya mengunggah file berekstensi %s'), implode(', ', $this->options['allowed_ext']))
				);
			} else if(!in_array($file['type'], $this->options['allowed_mime'])) {
				$error = array(
					'error' => 1,
					'message' => sprintf(__('Mohon hanya mengunggah file berekstensi %s'), implode(', ', $this->options['allowed_ext']))
				);
			} else if(!empty($file['size']) && $file['size'] > $this->options['max_size']) {
				$error = array(
					'error' => 1,
					'message' => sprintf(__('Besar file maksimum adalah %s'), $this->format_size($this->options['max_size'], 'MB'))
				);
			}
		}
		return $error;
	}

	function rotateImage($image, $direction) {
		$direction = strtolower($direction);
		$degrees = $direction == 'cw' ? 270 : ($direction == 'ccw' ? 90 : NULL); 

		if(!$degrees) {
			return $image;
		}

		$width = imagesx($image);
		$height = imagesy($image);
		$side = $width > $height ? $width : $height;
		$imageSquare = imagecreatetruecolor($side, $side);

		imagecopy($imageSquare, $image, 0, 0, 0, 0, $width, $height);
		imagedestroy($image);

		$imageSquare = imagerotate($imageSquare, $degrees, 0, -1);
		$image = imagecreatetruecolor($height, $width);
		$x = $degrees == 90 ? 0 : ($height > $width ? 0 : ($side - $height));
		$y = $degrees == 270 ? 0 : ($height < $width ? 0 : ($side - $width));

		imagecopy($image, $imageSquare, 0, 0, $x, $y, $height, $width);
		imagedestroy($imageSquare);

		return $image;
	}

	function _callGenerateTransparent ( $photo_extension, $image_source, $new_image ) {
		if( in_array($photo_extension, array( 'gif', 'png' )) ){
		    $transparencyIndex = @imagecolortransparent($image_source); 
	        $transparencyColor = array('red' => 255, 'green' => 254, 'blue' => 254); 
	         
	        if ($transparencyIndex >= 0) { 
	            $transparencyColor = @imagecolorsforindex($image_source, $transparencyIndex);    
	        } 
	        
	        $transparencyIndex = @imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']); 
	        @imagefill($new_image, 0, 0, $transparencyIndex); 
	     	@imagecolortransparent($new_image, $transparencyIndex);
     	}

     	return $new_image;
	}

	/**
	* Resize Foto
	*
	* @param string $image - Direktori folder yang akan diupload
	* @param number $width - Ukuran lebar file yang diupload
	* @param number $height - Ukuran panjang file yang diupload
	* @param number scale - Skala Foto
	* @param string $options - Opsi tambahan parameter
	*		boolean favicon - True file berupa favicon, allow extension ico
	*		number max_size - Maksimal ukuran foto yang diupload
	*		number max_width - Maksimal lebar foto yang diupload
	*		number max_height - Maksimal panjang foto yang diupload
	*		array allowed_ext - Extension foto yang diperbolehkan
	*		boolean prefix_as_name - True menggunakan Prefix sebagai Nama file
	*		boolean rar - Allow extension rar
	* @param number $newImageWidth - Ukuran lebar yang akan diresize
	* @param number $newImageHeight - Ukuran panjang yang akan diresize
	* @param string $newImage - Generate image sesuai dengan panjang dan lebar yang telah diresize
	* @param array $file_info - Informasi File yang diupload
	*		string dirname - Direktori file
	*		string basename - Nama file beserta extension
	*		string extension - Extension file
	*		string filename - Nama file tanpa extension
	* @param string $white - Alokasi warna Putih
	* @param string $source - hasil resize foto
	* @return string - hasil resize foto
	*/
    function _resizeImage( $image, $width, $height, $scale, $options=array(), $photo_extension ) {
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		
		$file_info = pathinfo($image);
		
		if((isset($this->options['career']) && $this->options['career'] == 1) || (isset($this->options['excess']) && $this->options['excess'] == 1) || (isset($this->options['transparent']) && $this->options['transparent'] == 1)) {
			$white = imagecolorallocate($newImage, 238, 238, 238);
		} else if( !in_array($photo_extension, array( 'gif', 'png' )) ){
			$white = imagecolorallocate($newImage, 255, 255, 255);
		}

        $source = "";

        if( in_array($photo_extension, array('jpg', 'jpeg')) && $width > 300 ){
        	$exif = @exif_read_data($image);
        }else{
        	$exif = false;
        }

        if($photo_extension == "png"){
			$source = @imagecreatefrompng($image);
        } elseif ($photo_extension == "jpg" || $photo_extension == "jpeg"){
            $source = @imagecreatefromjpeg($image);
        } elseif ($photo_extension == "gif"){
            $source = @imagecreatefromgif($image);
        }

        $newImage = $this->_callGenerateTransparent($photo_extension, $source, $newImage);

        if($exif && isset($exif['Orientation'])) {
        	$orientation = $exif['Orientation'];
        	if($orientation != 1){
        		$deg = 0;
        		switch ($orientation) {
        			case 3:
						$newImage = imagerotate($source, 180, 0);
	        			break;
    				case 6:
						$newImage = $this->rotateImage($source, 'cw');
            			break;
        			case 8:
						$newImage = $this->rotateImage($source, 'ccw');
        				break;
    			}
			}
		}

        if( $source ) {
		  	if($photo_extension != 'ico'){
	        	@imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
		  	}

		  	if($photo_extension == "png"){
	            @imagepng($newImage,$image, 9);
	        }elseif($photo_extension == "jpg" || $photo_extension == "jpeg"){
        		@imagejpeg($newImage, $image, 90);
	        }elseif($photo_extension == "gif"){
	            @imagegif($newImage,$image);
	        }

	    	@imagedestroy($image);
	    }

    	return $image;
    }
    
	private function _convertToJPG($imagefile, $new_imagefile) {
        if (class_exists('imagick')) {
			$image_info = pathinfo($imagefile);
			$im = new Imagick($imagefile);

			$im->setImageBackgroundColor('white');
			$im = $im->flattenImages();
			$im->setImageFormat('jpg');

			if($im->writeImage($new_imagefile)) {
				return $new_imagefile;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	* Resize Thumbnail Foto
	*
	* @param string $thumb_image_name - Direktori folder Thumbnail yang akan diupload
	* @param string $image - Direktori folder yang akan diupload
	* @param number $width - Ukuran lebar file yang diupload
	* @param number $height - Ukuran panjang file yang diupload
	* @param number $start_width - Posisi Lebar Crop Foto
	* @param number $start_height - Posisi Panjang Crop Foto
	* @param number scale - Skala Foto
	* @param string $options - Opsi tambahan parameter
	*		boolean favicon - True file berupa favicon, allow extension ico
	*		number max_size - Maksimal ukuran foto yang diupload
	*		number max_width - Maksimal lebar foto yang diupload
	*		number max_height - Maksimal panjang foto yang diupload
	*		array allowed_ext - Extension foto yang diperbolehkan
	*		boolean prefix_as_name - True menggunakan Prefix sebagai Nama file
	*		boolean rar - Allow extension rar
	* @param number $newImageWidth - Ukuran lebar yang akan diresize
	* @param number $newImageHeight - Ukuran panjang yang akan diresize
	* @param string $newImage - Generate image sesuai dengan panjang dan lebar yang telah diresize
	* @param array $file_info - Informasi File yang diupload
	*		string dirname - Direktori file
	*		string basename - Nama file beserta extension
	*		string extension - Extension file
	*		string filename - Nama file tanpa extension
	* @param string $white - Alokasi warna Putih
	* @param string $source - hasil resize foto
	* @return string - hasil resize foto
	*/
    function resizeThumbnailImage($image, $width, $height, $start_width, $start_height, $scale){
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
			
		$file_info = pathinfo($image);
		$photo_extension = strtolower($file_info['extension']);
		
		$white = imagecolorallocate($newImage, 255, 255, 255);
        $source = "";
        
        if( in_array($photo_extension, array('jpg', 'jpeg')) && $width > 300 ){
        	$exif = @exif_read_data($image);
        }else{
        	$exif = false;
        }
        
        if($photo_extension == "png"){
			imagefilledrectangle($newImage, 0, 0, $newImageWidth, $newImageHeight, $white);
            $source = @imagecreatefrompng($image);
        } elseif($photo_extension == "jpg" || $photo_extension == "jpeg"){
            $source = @imagecreatefromjpeg($image);
        } elseif($photo_extension == "gif"){
            $source = @imagecreatefromgif($image);
        }

        $newImage = $this->_callGenerateTransparent($photo_extension, $source, $newImage);

        if($exif && isset($exif['Orientation'])) {
        	$orientation = $exif['Orientation'];
        	if($orientation != 1){
        		$deg = 0;
        		switch ($orientation) {
        			case 3:
	        			$deg = 180;
	        			break;
    				case 6:
    					$deg = 270;
            			break;
        			case 8:
        				$deg = 90;
        				break;
    			}

				if ($deg) {
					$newImage = imagerotate($source, $deg, 0);
				}

			}
		}

        @imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);

        if($photo_extension == "png"){
            imagepng($newImage,$image, 9);
        }elseif($photo_extension == "jpg" || $photo_extension == "jpeg"){
            imagejpeg($newImage,$image, 90);
        }elseif($photo_extension == "gif"){
            imagegif($newImage,$image);
        }
        return $image;
    }

    function cropPhoto( $data, $uploadTo, $thumb_width = 300 ){
    	 $x1 = $this->RmCommon->filterEmptyField($data, 'x1');
    	 $y1 = $this->RmCommon->filterEmptyField($data, 'y1');
    	 $x2 = $this->RmCommon->filterEmptyField($data, 'x2');
    	 $y2 = $this->RmCommon->filterEmptyField($data, 'y2');
    	 $w = $this->RmCommon->filterEmptyField($data, 'w');
    	 $h = $this->RmCommon->filterEmptyField($data, 'h');
    	 $w_img = $this->RmCommon->filterEmptyField($data, 'w_img');
    	 $h_img = $this->RmCommon->filterEmptyField($data, 'h_img');
    	 $imagePath = $this->RmCommon->filterEmptyField($data, 'imagePath');

    	if( !empty($w) ) {
	    	App::import('Vendor', 'thumb', array('file' => 'thumb'.DS.'ThumbLib.inc.php'));
			
	        $scale = $thumb_width/$w;
			$pathPhoto = Configure::read('__Site.upload_path');
			$sourceImage = $this->getPathPhoto($pathPhoto, false, $uploadTo, $imagePath);
			$sizeImg = @getimagesize($sourceImage);

			$image_w = !empty($sizeImg[0])?$sizeImg[0]:false;
			$image_h = !empty($sizeImg[1])?$sizeImg[1]:false;

			if( !empty($sizeImg) ) {
				$w_scale = $image_w/$w_img;
				$h_scale = $image_h/$h_img;

				$x1 = ceil($x1 * $w_scale);
				$y1 = ceil($y1 * $h_scale);
				$w = ceil($w * $w_scale);
				$h = ceil($h * $h_scale);
			}

	        $this->resizeThumbnailImage($sourceImage,$w,$h,$x1,$y1,$scale);
			$data = array(
				'src' => $imagePath,
				'is_generate_photo' => false,
			);

			$this->_generateThumbnail( $data, 'src', $uploadTo );

	        return $imagePath;
	    } else {
	    	return false;
	    }
    }

	/**
	* Check Format Ukuran File
	*
	* @param number file_size - Maksimal ukuran foto yang diupload
	* @param string $sizetype - Tipe Ukuran Foto
	* @param number $filesize - Convert berdasarkan Tipe Ukuran File
	* @return number - Convert Ukuran File
	*/
	function format_size($file_size, $sizetype) {
		switch(strtolower($sizetype)){
			case "kb":
				$filesize = $file_size * .0009765625; // bytes to KB
			break;
			case "mb":
				$filesize = $file_size * .0009765625 * .0009765625; // bytes to MB
			break;
			case "gb":
				$filesize = $file_size * .0009765625 * .0009765625 * .0009765625; // bytes to GB
			break;
		}
		if($filesize <= 0){
			$filesize = 0;
		} else {
			$filesize = round($filesize, 2).' '.$sizetype;
		}
		return $filesize;
	}
	
	/**
	* Check Format Ukuran File
	*
	* @param string image - Nama File
	* @param string $path - Nama Path Folder
	*/
	function delete($image, $path) {
		if($path) {
			$path = $path.DS;
		}
        @unlink(Configure::read('__Site.upload_path').DS.$path.$image);
	}

    /**
	*
	*	aturan dalam mengupload gambar
	*	@param string $directory_name : nama directory gambar
	*	@return array
	*/
    function _rulesDimensionImage($directory_name, $data_type = false, $photo_extension = false, $options = array()){
    	$type_image = $this->RmCommon->filterEmptyField($options, 'type_image', false, 'landscape');
    	$result = array();
    	
    	if( in_array($directory_name, array( 'logos' )) ) {
    		if( $data_type == 'thumb' ) {
    			$result = 'xsm';
    		} else if( $data_type == 'large' ) {
    			$result = 'xxsm';
    		} else {
	    		$result = array(
					'xsm' => '100x40',
					'xm' => '165x165',
					'xxsm' => '240x96'
				);
	    	}
    	} else if( in_array($directory_name, array( 'users' )) ) {
    		if( $data_type == 'thumb' ) {
    			$result = 'pm';
    		} else if( $data_type == 'large' ) {
    			$result = 'pxl';
    		} else {
	    		$result = array(
					'ps' => '50x50',
					'pm' => '100x100',
					'pl' => '150x150',
					'pxl' => '300x300',
				);
	    	}
    	} else if( in_array($directory_name, array( 'ebrosur' )) ) {
            if( $data_type == 'thumb' ) {
    			$result = 'm';
    		} else if( $data_type == 'large' ) {
    			$result = 'xl';
    		} else {
    			if($type_image == 'potrait'){
    				$result = array(
						's' => '296x420',
						'm' => '453x640',
						'xl' => '724x1024'
					);
    			}else{
    				$result = array(
						's' => '420x296',
						'm' => '640x453',
						'xl' => '1024x724'
					);
    			}
	    	}
        } else if( in_array($directory_name, array( 'files', 'crms', 'document', 'documents')) && in_array($photo_extension, array( 'pdf', 'xls', 'xlsx' )) ) {
			$result = false;
        } else {
    		if( $data_type == 'thumb' ) {
    			$result = 'm';
    		} else if( $data_type == 'large' ) {
    			$result = 'l';
    		} else {
	    		$result = array(
					's' => '150x84',
					'm' => '300x169',
					'l' => '855x481',
					'company' => '855x481'
				);
	    	}
    	}

    	return $result;
    }

	/**
	*
	*	generate subfolder
	* 	ketentuan xxxxx-[x]xxxx-xxxxxx satu huruf di tali ke 2
	*	@param string $filename : nama file
	*	@return string
	*/
    function generateSubPathFolder($filename) {
    	$photo_subpath = '';
    	$sub_part = explode('-',$filename);
    	
    	if(!empty($sub_part[1])) {
			$photo_subpath = substr($sub_part[1], 0, 1);
		}
    	
    	return (string)$photo_subpath;
    }

    function replaceSlash ( $file, $action = false ) {
    	if( $action == 'reverse' ) {
    		return str_replace(DS, '/', $file);
    	} else if( $action == 'remove' ) {
    		return str_replace(array( '/', DS ), array( '', '' ), $file);
    	} else {
    		return str_replace('/', DS, $file);
    	}
    }

	function unGeneratePhoto ( $id, $modelName ) {
		$this->{$modelName} = ClassRegistry::init($modelName); 
		$this->{$modelName}->id = $id;
		$this->{$modelName}->set('is_generate_photo', 1);
		$this->{$modelName}->save();
	}

	function _callDataPosition ( $data, $modelName ) {
		return array(
			'x1' => $this->RmCommon->filterEmptyField($data, $modelName, 'x1'),
			'y1' => $this->RmCommon->filterEmptyField($data, $modelName, 'y1'),
			'x2' => $this->RmCommon->filterEmptyField($data, $modelName, 'x2'),
			'w' => $this->RmCommon->filterEmptyField($data, $modelName, 'w'),
			'h' => $this->RmCommon->filterEmptyField($data, $modelName, 'h'),
			'w_img' => $this->RmCommon->filterEmptyField($data, $modelName, 'w_img'),
			'h_img' => $this->RmCommon->filterEmptyField($data, $modelName, 'h_img'),
			'imagePath' => $this->RmCommon->filterEmptyField($data, $modelName, 'imagePath'),
		);
	}

    function getPathPhoto ( $path, $size, $save_path, $filename ){
        $file = $path.DS.$save_path;

        if( !empty($size) ) {
        	$file .= DS.$size;
        }

    	$file .= $filename;
        $file = str_replace('/', DS, $file);

        return $file;
    }

    function _uploadPhoto ( $data, $modelName, $fieldName, $save_path, $unset = false, $options = array() ) {
    	if( !empty($data) ) {
    		$hideField = $fieldName.'_hide';
    		$photoHide = $this->RmCommon->filterEmptyField($data, $modelName, $hideField);

    		if( !empty($data[$modelName][$fieldName]['name']) ) {
	    		$tmpName = $data[$modelName][$fieldName];
				$data[$modelName][$fieldName] = $tmpName['name'];

				$uploaded = $this->upload($tmpName, '/'.$save_path.'/', String::uuid(), $options);
        
	            if( isset($uploaded['error']) && $uploaded['error'] != 1 ) {
	            	$data[$modelName][$hideField] = $data[$modelName][$fieldName] = $uploaded['imageName'];
	            } else {
	            	$data[$modelName][$fieldName] = false;
	            }
			} else if( !empty($photoHide) ) {
    			$data[$modelName][$fieldName] = $photoHide;
    		} else {
				$data[$modelName][$fieldName] = false;
			}

			if( !empty($unset) && empty($data[$modelName][$fieldName]) ) {
				unset($data[$modelName][$fieldName]);
			}
		}

		return $data;
    }

	function fileExist($folder, $size, $file_name){
		$full_path = Configure::read('__Site.thumbnail_view_path').DS.$folder.DS.$size.str_replace('/', DS, $file_name);
		if(file_exists($full_path)){
			return $full_path;
		}else{
			return false;
		}
	}
}
?>