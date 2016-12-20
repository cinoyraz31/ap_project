<?php 
		$is_admin = $this->Rumahku->_isAdmin();
		$cnt_data = !empty($cnt_data)?$cnt_data:array();
		$cnt_pending = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_pending', false, 0);
		$cnt_approved_admin = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_approved_admin', false, 0);
		$cnt_rejected_admin = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_rejected_admin', false, 0);
		$cnt_cancel = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_cancel', false, 0);
		$cnt_approved_proposal = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_approved_proposal', false, 0);
		$cnt_rejected_proposal = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_rejected_proposal', false, 0);
		$cnt_proposal_without_comiission = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_proposal_without_comiission', false, 0);
		$cnt_approved_bank = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_approved_bank', false, 0);
		$cnt_rejected_bank = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_rejected_bank', false, 0);
		$cnt_credit_process = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_credit_process', false, 0);
		$cnt_rejected_credit = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_rejected_credit', false, 0);
		$cnt_approved_credit = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_approved_credit', false, 0);
		$cnt_completed = $this->Rumahku->filterEmptyField($cnt_data, 'cnt_completed', false, 0);
		$cnt_proposal_costom = $cnt_approved_proposal + $cnt_proposal_without_comiission;
		$cnt_rejected_custom = $cnt_rejected_credit + $cnt_rejected_bank + $cnt_rejected_admin + $cnt_cancel + $cnt_rejected_proposal;
		$cnt_akad_credit_custom = $cnt_approved_credit + $cnt_completed;
?>
<div class="wrapper-selector mb30">
	<div class="dashbox">
		<div id="kprAppliedStat" class="chart">
			<div class="chart-head">
				<?php
						echo $this->Html->tag('div', $this->Html->tag('strong',__('LAPORAN APLIKASI KPR')), array(
							'class' => 'mb15',
						));
				?>
				<div class="row">
					<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
						<?php
								$report_view = $this->Html->tag('div', $cnt_akad_credit_custom, array(
									'class' => 'label',
								));
								$report_view .= $this->Html->tag( 'h3', __('Completed'), array(
									'class' => 'color-green'
								));
								echo $this->Html->link( $report_view, array(
									'controller' => 'kpr',
									'action' => 'list_user_apply',
									'document_status' => 'approved_credit_custom',
									'admin' => true,
								), array(
									'escape' => false,
								));
						?>
					</div>
					<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
						<?php
								$report_view = $this->Html->tag('div', $cnt_credit_process, array(
									'class' => 'label',
								));
								$report_view .= $this->Html->tag( 'h3', __('Agen Setujui KPR'), array(
									'class' => 'color-yellow'
								));
								echo $this->Html->link( $report_view, array(
									'controller' => 'kpr',
									'action' => 'list_user_apply',
									'document_status' => 'credit_process',
									'admin' => true,
								), array(
									'escape' => false,
								));
						?>
					</div>
				</div>
					<?php 
							if( !empty($is_admin) ){
					?>
				<div class="row">
					<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
						<?php
								$report_view = $this->Html->tag('div', $cnt_approved_bank, array(
									'class' => 'label',
								));
								$report_view .= $this->Html->tag( 'h3', __('KPR Disetujui Bank'), array(
									'class' => 'color-green-light'
								));
								echo $this->Html->link( $report_view, array(
									'controller' => 'kpr',
									'action' => 'list_user_apply',
									'document_status' => 'approved_bank',
									'admin' => true,
								), array(
									'escape' => false,
								));
						?>
					</div>
					<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
						<?php
								$report_view = $this->Html->tag('div', $cnt_rejected_bank, array(
									'class' => 'label',
								));
								$report_view .= $this->Html->tag( 'h3', __('KPR Ditolak Bank'), array(
									'class' => 'color-red'
								));
								echo $this->Html->link( $report_view, array(
									'controller' => 'kpr',
									'action' => 'list_user_apply',
									'document_status' => 'rejected_bank',
									'admin' => true,
								), array(
									'escape' => false,
								));
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
						<?php
								$report_view = $this->Html->tag('div', $cnt_approved_admin, array(
									'class' => 'label',
								));
								$report_view .= $this->Html->tag( 'h3', __('KPR Terkirim'), array(
									'class' => 'color-blue-light'
								));
								echo $this->Html->link( $report_view, array(
									'controller' => 'kpr',
									'action' => 'list_user_apply',
									'document_status' => 'approved_admin',
									'admin' => true,
								), array(
									'escape' => false,
								));
						?>
					</div>
					<?php
							} else {
					?>
				<div class="row">
					<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
						<?php
								$report_view = $this->Html->tag('div', $cnt_approved_bank, array(
									'class' => 'label',
								));
								$report_view .= $this->Html->tag( 'h3', __('Aplikasi Disetujui'), array(
									'class' => 'color-blue'
								));
								echo $this->Html->link( $report_view, array(
									'controller' => 'kpr',
									'action' => 'list_user_apply',
									'document_status' => 'approved_bank',
									'admin' => true,
								), array(
									'escape' => false,
								));
						?>
					</div>
					<?php 
							}
					?>
					<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
						<?php
								$report_view = $this->Html->tag('div', $cnt_proposal_costom, array(
									'class' => 'label',
								));
								$report_view .= $this->Html->tag( 'h3', __('Referral Disetujui'), array(
									'class' => 'color-orange'
								));
								echo $this->Html->link( $report_view, array(
									'controller' => 'kpr',
									'action' => 'list_user_apply',
									'document_status' => 'approved_proposal_custom',
									'admin' => true,
								), array(
									'escape' => false,
								));
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
						<?php
								$report_view = $this->Html->tag('div', $cnt_rejected_custom, array(
									'class' => 'label',
								));
								$report_view .= $this->Html->tag( 'h3', __('Ditolak/Cancel'), array(
									'class' => 'color-red'
								));
								echo $this->Html->link( $report_view, array(
									'controller' => 'kpr',
									'action' => 'list_user_apply',
									'document_status' => 'rejected_custom',
									'admin' => true,
								), array(
									'escape' => false,
								));
						?>
					</div>
						<?php
							if( !empty($is_admin) ){
						?>
								<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
									<?php
											$report_view = $this->Html->tag('div', $cnt_pending, array(
												'class' => 'label',
											));
											$report_view .= $this->Html->tag( 'h3', __('Pending'), array(
												'class' => 'color-black'
											));
											echo $this->Html->link( $report_view, array(
												'controller' => 'kpr',
												'action' => 'list_user_apply',
												'document_status' => 'pending',
												'admin' => true,
											), array(
												'escape' => false,
											));
									?>
								</div>
						<?php

							}else{

						?>
								<div class="col-sm-6 tacenter report-dashboard-kpr quick-data">
									<?php
											$report_view = $this->Html->tag('div', $cnt_pending, array(
												'class' => 'label',
											));
											$report_view .= $this->Html->tag( 'h3', __('Pending'), array(
												'class' => 'color-black'
											));
											echo $this->Html->link( $report_view, array(
												'controller' => 'kpr',
												'action' => 'list_user_apply',
												'document_status' => 'approved_admin',
												'admin' => true,
											), array(
												'escape' => false,
											));
									?>
								</div>
						<?php

							}
						?>
					
				</div>
			</div>
		</div>
	</div>
</div>