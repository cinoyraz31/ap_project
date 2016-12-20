	<?php 
		$fromDate = $this->Rumahku->filterEmptyField($chartApplications, 'fromDate', false, date('01 M Y'));
		$toDate = $this->Rumahku->filterEmptyField($chartApplications, 'toDate', false, date('d M Y'));
		$customDate = $this->Rumahku->getCombineDate($fromDate, $toDate);

		if( isset($action_type) && $action_type == 'leads' ) {
			$label = __('Pengunjung Kalkulator KPR');

			$total = !empty($chartApplications['total'])?$chartApplications['total']:0;
		} else {
			$label = __('Pengajuan Aplikasi KPR');
			$total_pending 	= !empty($chartApplications['total_pending'])?$chartApplications['total_pending']:0;
			$total_approved_admin = !empty($chartApplications['total_approved_admin'])?$chartApplications['total_approved_admin']:0;
			$total_rejected  = !empty($chartApplications['total_rejected'])?$chartApplications['total_rejected']:0;
			$total_referral 	= !empty($chartApplications['total_referral'])?$chartApplications['total_referral']:0;
			$total_approved_bank 	= !empty($chartApplications['total_approved_bank'])?$chartApplications['total_approved_bank']:0;
			$total_credit_process 	= !empty($chartApplications['total_credit_process'])?$chartApplications['total_credit_process']:0;
			$total_completed 	= !empty($chartApplications['total_completed'])?$chartApplications['total_completed']:0;
			$action_type 	= !empty($action_type)?$action_type:'applications';
			$total = $total_pending + $total_approved_admin + $total_rejected + $total_referral + $total_approved_bank + $total_credit_process + $total_completed;
		}
?>
<div id="wrapper-chart-kpr">
	<div class="dashbox">
		<div id="kprAppliedStat" class="chart">
			<div class="chart-head">
				<div class="row">
					<div class="col-sm-6">
						<ul class="tabs">
							<?php 
									$classActive = ( $action_type == 'applications' )?'active':false;
									echo $this->Html->tag('li', $this->Html->link(__('Pengajuan'), '#', array(
	                                	'escape' => false,
		                                'class' => 'kpr-chart '.$classActive,
		                                'url' => $this->Html->url(array(
		                                    'controller' => 'ajax',
		                                    'action' => 'get_kpr',
		                                    'applications',
		                                )),
		                            )), array(
		                                'role' => 'presentation',
		                            ));

									if( $this->Rumahku->_isAdmin() ) {
										$classActive = ( $action_type == 'leads' )?'active':false;
										echo $this->Html->tag('li', $this->Html->link(__('Visitor'), '#', array(
		                                	'escape' => false,
			                                'class' => 'kpr-chart '.$classActive,
			                                'url' => $this->Html->url(array(
			                                    'controller' => 'ajax',
			                                    'action' => 'get_kpr',
			                                    'leads',
			                                )),
			                            )), array(
			                                'role' => 'presentation',
			                            ));
									}
							?>
						</ul>
					</div>
					<div class="col-sm-4 col-sm-offset-2">
						<div class="form-group taright">
							<?php 
									echo $this->Html->link($this->Rumahku->icon('rv4-calendar'), 'javascript:', array(
	                                	'escape' => false,
		                                'class' => 'daterange-dasboard',
		                                'title' => __('Tanggal'),
		                                'url' => $this->Html->url(array(
		                                    'controller' => 'ajax',
		                                    'action' => 'get_kpr',
		                                    $action_type,
		                                )),
		                            ));
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-body">
					<div id="chart_div"></div>
				</div>
				<div class="chart-foot">
					<div class="summary-applied">
						<?php 
								echo $this->Html->tag('h3', $label);
								echo $this->Html->tag('span', sprintf(__('(tanggal %s)'), $customDate));
								echo $this->Html->tag('h4', $total);
						?>
					</div>
					<?php 
							if( $action_type != 'leads' ) {
					?>
					<div class="summary">
						<div class="row">
							<?php 
									$pending = $this->Html->tag('h5', $total_pending);
									$pending .= $this->Html->tag('span', __('Pending'));
									echo $this->Html->tag('div', $pending, array(
										'class' => 'col-sm-4',
									));

									$rejected = $this->Html->tag('h5', $total_rejected);
									$rejected .= $this->Html->tag('span', __('Ditolak/Cancel'));
									echo $this->Html->tag('div', $rejected, array(
										'class' => 'col-sm-4',
									));

									if( $this->Rumahku->_isAdmin() ) {
											$sent = $this->Html->tag('h5', $total_approved_admin);
											$sent .= $this->Html->tag('span', __('Terkirim'));
											echo $this->Html->tag('div', $sent, array(
												'class' => 'col-sm-4',
											));
										
									}

									$proposal = $this->Html->tag('h5', $total_referral);
									$proposal .= $this->Html->tag('span', __('Referral disetujui'));
									echo $this->Html->tag('div', $proposal, array(
										'class' => 'col-sm-4',
									));

									$approval = $this->Html->tag('h5', $total_approved_bank);
									$approval .= $this->Html->tag('span', __('Disetujui bank'));
									echo $this->Html->tag('div', $approval, array(
										'class' => 'col-sm-4',
									));

									$agent = $this->Html->tag('h5', $total_credit_process);
									$agent .= $this->Html->tag('span', __('Agen setujui'));
									echo $this->Html->tag('div', $agent, array(
										'class' => 'col-sm-4',
									));

									$akad = $this->Html->tag('h5', $total_completed);
									$akad .= $this->Html->tag('span', __('Completed'));
									echo $this->Html->tag('div', $akad, array(
										'class' => 'col-sm-4',
									));
							?>
						</div>
					</div>
					<?php 
							}
					?>
				</div>
		</div>
	</div>
	<?php 
			echo $this->Form->hidden('chart_value', array(
				// 'value' => $values,
				'id' => 'chartValue',
			));
	?>
</div>