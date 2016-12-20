<?php
		$w = 300;
		$h = 300;
		$ratio = '1:1';
		$pathPhoto = Configure::read('__Site.thumbnail_view_path');
		$size = Configure::read('__Site.fullsize');

		$user = !empty($user)?$user:false;
		$save_path = !empty($save_path)?$save_path:false;
		$photo = $this->Rumahku->filterEmptyField($user, 'User', 'photo');
		$photoUrl = $this->Rumahku->photo_thumbnail(array(
			'save_path' => $save_path, 
			'src' => $photo, 
			'thumb' => false,
			'url' => true,
		));
?>
<div id="crop-photo" class="relative">
	<?php 
			echo $this->Form->create('User');

			$imagePath = $this->Rumahku->getPathPhoto($pathPhoto, $size, $save_path, $photo);
			$photoSize = @getimagesize($imagePath);

			echo $this->CropImage->createForm($photo, $w, $h, $photoSize);
	?>
	<div class="row">
		<?php
				echo $this->Html->tag('div', $this->Html->tag('div', $this->CropImage->createSourceBlock($photoUrl), array(
					'id' => 'wrapper-crop-preview',
				)), array(
					'class' => 'col-sm-8',
				));
		?>
		<div class="col-sm-4">
			<?php
					echo $this->Html->tag('p', __('Silahkan klik dan geser kursor Anda untuk membentuk kotak yang mencerminkan tampilan foto Anda inginkan. Anda juga dapat menggeser kotak yang telah Anda buat untuk mengatur posisi tampilan foto.'));
			?>
			<div>
				<?php
						echo $this->Html->tag('h4', __('Tampilan Thumbnail'));
						echo $this->Html->tag('div', $this->CropImage->createThumbnailBlock($photoUrl), array(
							'id' => 'crop_thumbnail',
						));

						echo $this->Html->tag('p', sprintf(__('Foto yang ada pada tampilan akan digunakan untuk menampilkan foto Anda pada seluruh aktifitas Anda di %s.'), Configure::read('__Site.site_name')));
				?>
				<div class="row">
				    <div class="col-sm-12">
				        <div class="action-group bottom">
				            <div class="btn-group floleft">
				                <?php
				                        echo $this->Form->button(__('Simpan'), array(
				                            'type' => 'submit', 
				                            'class'=> 'btn blue',
				                        ));

				                        echo $this->Html->link(__('Kembali'), array(
				                            'action' => $refer,
				                            'admin' => true
				                        ), array(
				                            'class'=> 'btn default',
				                        ));
				                ?>
				            </div>
				        </div>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<?php
			echo $this->Form->end();
	?>
</div>