<?php  
App::uses('AppHelper', 'View/Helper');

/**
* Helper to load the upload form
*
* NOTE: If you want to use it out of this plugin you NEED to include the CSS files in your Application.
* The files are loaded in `app/Plugins/FileUpload/View/Layouts/default.ctp` starting at line 16
*
*/
class UploadFormHelper extends AppHelper {
	var $helpers = array(
		'Rumahku', 'Html', 'Form'
	);

	/**
	*	Load the form
	* 	@access public
	*	@param String $url url for the data handler
	*   @param Boolean $loadExternal load external JS files needed
	* 	@return void
	*/
	public function load( $url = '/file_upload/handler', $data = false, $save_path = false )
	{
		// Remove the first `/` if it exists.
	    if( $url[0] == '/' )
	    {
	        $url = substr($url, 1);
	    }

		$this->_loadScripts($data);

		$this->_loadTemplate( $url, $data, $save_path );

		// if( $loadExternal )
		// {
		// 	$this->_loadExternalJsFiles();	
		// }
		
	}


	public function loadUser( $url, $data = false, $save_path = false ) {
		$this->_loadUserScripts($data);
		$this->_loadUserTemplate( $url, $data, $save_path );
	}

	private function _loadUserScripts($data) {
		echo '<script id="template-upload" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		    <div class="template-upload fade">
		        {% if (file.error) { %}
		            <div class="error alert"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
		        {% } else if (o.files.valid && !i) { %}
		            <div>
		                <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
		            </div>
		            <div class="start">{% if (!o.options.autoUpload) { %}
		                <button class="btn btn-primary">
		                    <i class="icon-upload icon-white"></i>
		                    <span>{%=locale.fileupload.start%}</span>
		                </button>
		            {% } %}</div>
		        {% } %}
		    </div>
		{% } %}
		</script>
		<script id="template-download" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		    <li class="template-download col-sm-4 ajax-parent" rel="{%=file.id%}">
				<div class="item">
			        {% if (file.error) { %}
			            <div class="error alert"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
			        {% } else { %}
			            {%=file.actions%}
			        {% } %}
				</div>
		    </li>
		{% } %}
		</script>';

	}

	private function _loadUserTemplate( $url = null, $data = false, $save_path = false )
	{
		echo '<form id="single-fileupload" action="'.$url.'" method="POST" enctype="multipart/form-data">
	        <div class="fileupload-buttonbar hide">
	            <div class="span7">
	                <span class="btn btn-success fileinput-button">
	                    <i class="icon-plus icon-white"></i>
	                    <span>Add files...</span>
	                    <input type="file" name="data[files][]">
	                </span>
	                <button type="button" class="btn btn-danger delete">
	                    <i class="icon-trash icon-white"></i>
	                    <span>Delete</span>
	                </button>
	            </div>
	        </div>
	        <div class="fileupload-loading"></div>
	        <div class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></ul>
	    </form>
		<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd">
		    <div class="modal-header">
		        <a class="close" data-dismiss="modal">&times;</a>
		        <h3 class="modal-title"></h3>
		    </div>
		    <div class="modal-body"><div class="modal-image"></div></div>
		    <div class="modal-footer">
		        <a class="btn modal-download" target="_blank">
		            <i class="icon-download"></i>
		            <span>Download</span>
		        </a>
		        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
		            <i class="icon-play icon-white"></i>
		            <span>Slideshow</span>
		        </a>
		        <a class="btn btn-info modal-prev">
		            <i class="icon-arrow-left icon-white"></i>
		            <span>Previous</span>
		        </a>
		        <a class="btn btn-primary modal-next">
		            <span>Next</span>
		            <i class="icon-arrow-right icon-white"></i>
		        </a>
		    </div>
		</div>
		';
	}

	/**
	*	Load the scripts needed.
	* 	@access private
	* 	@return void
	*/
	private function _loadScripts($data)
	{
		$msgDelete = __('Anda yakin ingin menghapus foto ini ?');
		$photoAction = $this->Form->input('PropertyMedias.category_media_id.', array(
			'label' => false,
			'div' => array(
				'class' => 'form-group',
			),
			'class' => 'form-control disable-drag',
			'required' => false,
			'empty' => __('Pilih Label'),
			'options' => !empty($data['categories'])?$data['categories']:false,
		));
		$photoAction .= $this->Html->tag('div', $this->Form->input('PropertyMedias.options_id.', array(
			'type' => 'checkbox',
			'label' => array(
				'text' => __('Pilih Foto'),
				'data-show' => '.fly-button-media',
			),
			'div' => false,
			'required' => false,
            'hiddenField' => false,
			'value' => '{%=file.id%}',
		)), array(
			'class' => 'bottom cb-checkmark disable-drag',
		));
		$orderUrl = $this->Html->url(array(
			'controller' => 'ajax',
			'action' => 'property_photo_order',
			!empty($data['session_id'])?$data['session_id']:false,
			'admin' => false,
		));

		echo '<script id="template-upload" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		    <div class="template-upload fade col-sm-3">
		        <div class="preview"><span class="fade"></span></div>
		        {% if (file.error) { %}
		            <div class="error alert"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
		        {% } else if (o.files.valid && !i) { %}
		            <div>
		                <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
		            </div>
		            <div class="start">{% if (!o.options.autoUpload) { %}
		                <button class="btn btn-primary">
		                    <i class="icon-upload icon-white"></i>
		                    <span>{%=locale.fileupload.start%}</span>
		                </button>
		            {% } %}</div>
		        {% } %}
		    </div>
		{% } %}
		</script>
		<script id="template-download" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		    <li class="template-download col-sm-4 ajax-parent" rel="{%=file.id%}">
				<div class="item">
			        {% if (file.error) { %}
			            <div class="error alert"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
			        {% } else { %}
			            {%=file.actions%}
						<div class="action cb-custom">
							'.$photoAction.'
						</div>
						<div class="primary-file">
							<a href="{%=file.primary_url%}" class="btn default ajax-link" data-alert="Anda yakin ingin menjadikan foto utama ?">Foto Utama</a>
						</div>
			        {% } %}
				</div>
		    </li>
		{% } %}
		</script>';

	}
	
	private function _loadTemplate( $url = null, $data = false, $save_path = false )
	{
		$content = '';
		$orderUrl = false;

		if( !empty($data) ) {
			$idx = 0;

			foreach ($data as $key => $value) {
				$photoId = false;
				$photoSessionId = false;
				$photoPropertyId = false;
				$photoName = false;
				$photoTitle = false;
				$primary = false;
				$categories = false;

				switch ($save_path) {
					case 'properties':
						$photoId = $this->Rumahku->filterEmptyField($value, 'PropertyMedias', 'id');
						$photoSessionId = $this->Rumahku->filterEmptyField($value, 'PropertyMedias', 'session_id');
						$photoPropertyId = $this->Rumahku->filterEmptyField($value, 'PropertyMedias', 'property_id');
						$photoName = $this->Rumahku->filterEmptyField($value, 'PropertyMedias', 'name');
						$photoTitle = $this->Rumahku->filterEmptyField($value, 'PropertyMedias', 'title');
						$primary = $this->Rumahku->filterEmptyField($value, 'PropertyMedias', 'primary');

						$categories = $this->Rumahku->filterEmptyField($value, 'CategoryMedia');
						$orderUrl = $this->Html->url(array(
							'controller' => 'ajax',
							'action' => 'property_photo_order',
							$photoSessionId,
							$photoPropertyId,
							'admin' => false,
						));
						break;
				}

				if( !empty($primary) ) {
					$contentPrimary = $this->Html->link(__('Foto Utama'), 'javascript:', array(
						'class' => 'btn green primary',
					));
				} else {
					$contentPrimary = $this->Html->link(__('Jadikan Foto Utama'), array(
						'controller' => 'ajax', 
						'action' => 'property_photo_primary', 
						$photoId,
						$photoSessionId,
						'admin' => false,
					), array(
						'class' => 'btn default ajax-link',
						'data-alert' => __('Anda yakin ingin menjadikan foto utama ?'),
						'data-type' => 'content',
						'escape' => false,
					));
				}

				$photo = $this->Rumahku->photo_thumbnail(array(
					'save_path' => $save_path, 
					'src' => $photoName, 
					'size' => 'm',
				), array(
					'title'=> $photoTitle, 
					'alt'=> $photoTitle, 
					'class' => 'default-thumbnail',
				));
				// $photoDeleteLink = $this->Html->link($this->Rumahku->icon('times'), array(
				// 	'controller' => 'ajax', 
				// 	'action' => 'property_photo_delete', 
				// 	$photoId,
				// 	$photoSessionId,
				// 	'admin' => false,
				// ), array(
				// 	'class' => 'btn btn-danger btn-xs ajax-link',
				// 	'data-alert' => __('Anda yakin ingin menghapus foto ini ?'),
				// 	'data-type' => 'media-delete',
				// 	'escape' => false,
				// ));

				$photoAction = $this->Form->input('PropertyMedias.category_media_id.'.$idx, array(
					'label' => false,
					'div' => array(
						'class' => 'form-group',
					),
					'class' => 'form-control disable-drag',
					'required' => false,
					'empty' => __('Pilih Label'),
					'options' => $categories,
				));
				$photoAction .= $this->Html->tag('div', $this->Form->input('PropertyMedias.options_id.'.$idx, array(
					'type' => 'checkbox',
					'label' => array(
						'text' => __('Pilih Foto'),
						'data-show' => '.fly-button-media',
					),
					'div' => false,
					'required' => false,
                    'hiddenField' => false,
					'value' => $photoId,
				)), array(
					'class' => 'bottom cb-checkmark disable-drag',
				));

				$content .= '<li class="template-download fade col-sm-4 ajax-parent in" rel="'.$photoId.'">
					<div class="item">
						<div class="preview">
							'.$photo.'
						</div>
						<div class="action cb-custom">
							'.$photoAction.'
						</div>
						<div class="primary-file">
							'.$contentPrimary.'
						</div>
					</div>
	    		</li>';

				$idx++;
			}
		}

		echo '<form id="fileupload" action="'.Router::url('/', true).$url.'" method="POST" enctype="multipart/form-data">
	        <div class="fileupload-buttonbar hide">
	            <div class="span7">
	                <span class="btn btn-success fileinput-button">
	                    <i class="icon-plus icon-white"></i>
	                    <span>Add files...</span>
	                    <input type="file" name="data[files][]" multiple>
	                </span>
	                <button type="button" class="btn btn-danger delete">
	                    <i class="icon-trash icon-white"></i>
	                    <span>Delete</span>
	                </button>
	            </div>
	        </div>
	        <div class="fileupload-loading"></div>
	        <br>
	        <ul class="files row drag" data-toggle="modal-gallery" data-target="#modal-gallery" data-url="'.$orderUrl.'">'.$content.'</ul>
	    </form>
		<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd">
		    <div class="modal-header">
		        <a class="close" data-dismiss="modal">&times;</a>
		        <h3 class="modal-title"></h3>
		    </div>
		    <div class="modal-body"><div class="modal-image"></div></div>
		    <div class="modal-footer">
		        <a class="btn modal-download" target="_blank">
		            <i class="icon-download"></i>
		            <span>Download</span>
		        </a>
		        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
		            <i class="icon-play icon-white"></i>
		            <span>Slideshow</span>
		        </a>
		        <a class="btn btn-info modal-prev">
		            <i class="icon-arrow-left icon-white"></i>
		            <span>Previous</span>
		        </a>
		        <a class="btn btn-primary modal-next">
		            <span>Next</span>
		            <i class="icon-arrow-right icon-white"></i>
		        </a>
		    </div>
		</div>
		';
	}

	/**
	*	Load external JS files needed.
	* 	@access private
	* 	@return void
	*/
	private function _loadExternalJsFiles()
	{
		echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="'.Router::url('/', true).'file_upload/js/vendor/jquery.ui.widget.js"></script>
		<script src="http://blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
		<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
		<script src="http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
		<script src="http://blueimp.github.com/cdn/js/bootstrap.min.js"></script>
		<script src="http://blueimp.github.com/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js"></script>
		<script type="text/javascript" src="'.Router::url('/', true).'file_upload/js/jquery.iframe-transport.js"></script>
		<script type="text/javascript" src="'.Router::url('/', true).'file_upload/js/jquery.fileupload.js"></script>
		<script type="text/javascript" src="'.Router::url('/', true).'file_upload/js/jquery.fileupload-fp.js"></script>
		<script type="text/javascript" src="'.Router::url('/', true).'file_upload/js/jquery.fileupload-ui.js"></script>
		<script type="text/javascript" src="'.Router::url('/', true).'file_upload/js/locale.js"></script>
		<script type="text/javascript" src="'.Router::url('/', true).'file_upload/js/main.js"></script>';	
	}

}
?>