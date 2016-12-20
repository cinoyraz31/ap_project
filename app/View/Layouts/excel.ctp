<?php
		$officer_name = $this->Rumahku->filterEmptyField($User, 'full_name');
		$kpr_application = $this->Rumahku->filterEmptyField($value, 'KprApplication', 'code');

		header('Content-type: application/ms-excel');
	    header('Content-Disposition: attachment; filename=commission-kpr-'.$kpr_application.'.xls');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<meta name="viewport" content="width=device-width"/>
  	</head>

  	<body style="font-family: Helvetica, Arial, sans-serif;">
  		<table align="center" cellpadding="0" cellspacing="0" border="0" style="width: 500px;">
  			<tbody>
  				<tr>
			    	<td style="text-align: center;font-weight: bold;padding: 10px;">
			    		<?php 
			    				echo $this->element('headers/commission_report');
			    		?>
			    	</td>
			  	</tr>
				<tr>
					<td style="padding: 0 20px;">
						&nbsp;
					</td>
				</tr>
			  	<tr>
					<td>
						<?php 
								echo $this->fetch('content');
				        ?>
					</td>
		  		</tr>
				<tr>
					<td style="padding: 0 20px;">
						<?php
								echo $this->Html->tag('p', __('Demikian disampaikan untuk menjadi perhatian, terima kasih.'),array(
				  					'style' => 'font-size:14px;',
				  				));
						?>
					</td>
				</tr>
				<tr>
					<td style="padding: 0 20px;">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td style="padding: 0 20px;text-align:right;">
						<?php
								echo $this->Html->tag('p', __('Petugas'), array(
				  					'style' => 'font-size:14px;text-align:right',
				  				));
			  			?>
			  			<br><br><br><br>
			  			<?php
				  				echo $this->Html->tag('p', $officer_name, array(
				  					'style' => 'font-size:14px;text-align:right',
				  				));
						?>
						<br>
					</td>
				</tr>
				<tr>
					<td style="padding: 0 20px;">
						&nbsp;
					</td>
				</tr>
		  		<tr>
					<td>
						<table align="center" cellpadding="0" cellspacing="0" border="0">
						  	<tbody>
							  	<tr>
							    	<td align="center" style="padding: 15px 0;margin: 15px 0 0;font-size: 16px;">
							      		<?php
								    			printf(__('Power By: %s'), $this->Html->tag('span', __('Rumahku.com'), array(
								    				'style' => 'font-weight: bold;'
							    				)));
							    		?>
							    	</td>
							  	</tr>
							</tbody>
						</table>
					</td>
		  		</tr>
			</tbody>
		</table>
  	</body>
</html>