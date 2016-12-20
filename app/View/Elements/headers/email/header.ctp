<?php 
		$logo = $this->Html->image('/img/angkasa_pura.png', array(
			'fullBase' => true,
		));
?>
<tr>
	<td align="left" valign="top">
		<!-- pembungkus header | start -->
		<table class="header" border="0" cellspacing="0" cellpadding="10" width="100%">
			<tbody>
				<tr>
					<td align="center" valign="top">
						<!-- isi header | start -->
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tbody>
								<tr>
									<td align="left" valign="top">
										<div style="vertical-align: middle; padding-top: 10px;">
											<?php 
													echo $logo;
											?>
										</div>
									</td>
									<td align="right" valign="bottom">
										<div style="color: #888888; font-size: 12px; padding-top: 15px;">
											<?php 
													printf('Dikirim pada tanggal: %s', $this->Html->tag('strong', date('d M Y')));
											?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<!-- isi header | end -->
					</td>
				</tr>
			</tbody>
		</table>
		<!-- pembungkus header | end -->
	</td>
</tr>