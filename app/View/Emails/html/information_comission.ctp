<?php 
		$id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
		$note = $this->Rumahku->filterEmptyField($params, 'FinanceConfirmation', 'note');  
		$domain = $this->Rumahku->filterEmptyField($params, 'BankDomain', 'sub_domain', FULL_BASE_URL);
?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
	<tbody>	
		<tr>
			<td>
				<?php 
						echo $this->element('blocks/kpr/commission_report');
				?>
			</td>
		</tr>
		<tr>
			<td style="padding: 0 20px;">
				<?php 
						$link = $domain.$this->Html->url(array(
							'controller' => 'kpr', 
							'action' => 'user_apply_detail',
							$id,
							'admin' => true,
						));
		      			echo $this->Html->link(__('Lihat Selengkapnya'), $link, array(
							'style' => 'padding:10px 15px;background:#204798;color:#fff;text-decoration:none;margin:20px auto 20px 200px;text-align:center;line-height: 68px;'
						));;
				?>
			</td>
		</tr>
	</tbody>
</table>