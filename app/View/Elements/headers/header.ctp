<?php  
		$site_name = Configure::read('__Site.site_name');
		$User = !empty($User)?$User:false;
		$User = $this->Rumahku->mergeUser($User);
		$_site_name = !empty($_site_name)?$_site_name:false;
		$lblDay = $this->Rumahku->_callGreetingDate();

		$cntNotif = $this->Rumahku->filterEmptyField($kpr_notifications, 'cnt');
		// $dataNotif = $this->Rumahku->filterEmptyField($kpr_notifications, 'data');
		$urlNotif = array(
			'controller' => 'users',
			'action' => 'notifications',
			'admin' => true,
		);
		$urlNotifHeader = array_merge($urlNotif, array(
			'sort' => 'KprApplication.read',
			'direction' => 'asc',
		));

		$userPhoto = $this->Rumahku->filterEmptyField($User, 'photo');
		$userFullName = $this->Rumahku->filterEmptyField($User, 'full_name');
		$userFirstName = $this->Rumahku->filterEmptyField($User, 'first_name');
?>
<header id="main" role="banner" class="hidden-print">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#on-mobile-nav" aria-expanded="false">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<?php 						
						
						$contentH1 = $this->Html->image('/img/angkasa_pura.png');
						echo $this->Html->tag('h1', $contentH1);
				?>
			</div>

			<div class="desktop-only floright quick-response">
				<ul>
					<li id="message">
						<div class="btn-group">
							<?php
									$contentNotif = $this->Rumahku->icon('rv4-ring');

									if( !empty($cntNotif) ){
										$contentNotif .= $this->Html->tag('span', $cntNotif, array(
											'class' => 'label total',
										));
									}

									echo $this->Html->link($contentNotif, '#', array(
										'escape' => false,
										'class' => 'dropdown-toggle',
										'data-toggle' => 'dropdown',
										'aria-hashpopup' => 'true',
										'aria-expanded' => 'false',
									));
							?>
							
							<div class="dropdown-menu wow fadeIn">
								<ul>
									<?php
											$labelLi = sprintf(__('Anda memiliki %s notifikasi'), $this->Html->tag('strong', $cntNotif));
											echo $this->Html->tag('li', $labelLi, array(
												'class' => 'first',
											));

                    						if( !empty($dataNotif) ) {
												echo $this->element('blocks/common/notification/items', array(
													'values' => $dataNotif,
                        							'data_style' => 'notif',
                        							'data_unread_class' => 'new',
												));
											}

											echo $this->Html->tag('li', $this->Html->link(__('Lihat Semua'), $urlNotif, array(
												'escape' => false,
												'class' => 'see-all',
											)), array(
												'class' => 'last',
											));
									?>
								</ul>
							</div>
						</div>
					</li>
					<li id="user-action">
						<div class="btn-group">
							<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-hashpopup="true" aria-expanded="false">
								<?php 
										echo $this->Html->tag('span', $this->Rumahku->photo_thumbnail(array(
							                'save_path' => Configure::read('__Site.profile_photo_folder'), 
							                'src'=> $userPhoto, 
							                'size' => 'ps',
							            ), array(
							            	'title' => $userFullName,
							            	'alt' => $userFullName,
							            )), array(
							            	'class' => 'user-photo',
							            ));
							            echo $this->Html->tag('span', sprintf(__('Selamat %s, '), $lblDay), array(
							            	'class' => 'greetings',
							            ));
							            echo $this->Html->tag('span', $userFirstName, array(
							            	'class' => 'user-name',
							            ));
							            echo $this->Rumahku->icon('rv4-angle-down fs06');
								?>
							</a>
							<div class="dropdown-menu wow fadeIn">
								<div class="acc-managed">
									<?php 
								            echo $this->Html->tag('p', sprintf(__('Akun ini dikelola oleh %s'), $this->Html->tag('strong', $_site_name)));
									?>
								</div>
								<?php 
										echo $this->element('blocks/users/profile');
								?>
								<div class="user-action">
									<?php 
											echo $this->Html->tag('div', $this->Html->tag('div', $this->Html->link(__('Edit Profil'), array(
												'controller' => 'users',
												'action' => 'edit',
												'admin' => true,
											), array(
												'escape' => false,
												'class' => 'btn default fs085',
											)), array(
												'class' => 'floleft',
											)));
											echo $this->Html->tag('div', $this->Html->tag('div', $this->Html->link(__('Log out'), array(
												'controller' => 'users',
												'action' => 'logout',
												'admin' => true,
											), array(
												'escape' => false,
												'class' => 'btn default fs085',
											)), array(
												'class' => 'floright',
											)));
									?>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>