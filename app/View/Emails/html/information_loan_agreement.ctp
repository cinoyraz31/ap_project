<?php
		$date_now = date('d M Y');
		// debug($params);die();

		## DATA CLIENT
		$code = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'code');
		$full_name = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'full_name');
		$email = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'email');
		$no_hp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_hp');
		$no_hp_2 = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'no_hp_2');
		$address = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'address');
		$address_2 = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'address_2');
		$same_as_address_ktp = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'same_as_address_ktp');
		$company = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'company');

		# DATA AGENT
		$agent = $this->Rumahku->filterEmptyField($params, 'User');
		$agent_profile = $this->Rumahku->filterEmptyField($params, 'UserProfile');
		$agent_name = $this->Rumahku->filterEmptyField($agent, 'full_name');
		$agent_no_hp = $this->Rumahku->filterEmptyField($agent_profile, 'no_hp');
		$agent_no_hp_2 = $this->Rumahku->filterEmptyField($agent_profile, 'no_hp_2');
		$agent_address = $this->Rumahku->filterEmptyField($agent_profile, 'address');
		$agent_address2 = $this->Rumahku->filterEmptyField($agent_profile, 'address2');
		$agent_email = $this->Rumahku->filterEmptyField($agent, 'email');

		# DATA KPR
		$property_price = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'property_price');
		$loan_plafond = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'loan_plafond');
		$down_payment = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'down_payment');
		$periode_fix = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'periode_fix');
		$persen_loan = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'persen_loan');
		$interest_rate = $this->Rumahku->filterEmptyField($params, 'KprApplicationConfirm', 'interest_rate');
		$creditFix = $this->Rumahku->creditFix($loan_plafond, $interest_rate, $periode_fix);
		$customCreditFix = $this->Rumahku->getCurrencyPrice($creditFix, '-');
		$customPropertyPrice = $this->Rumahku->getCurrencyPrice($property_price, '-');
		$customDownPayment = $this->Rumahku->getCurrencyPrice($down_payment, '-');
		$customLoanPlafond = $this->Rumahku->getCurrencyPrice($loan_plafond, '-');

		// AKad Kredit
		$credit_date = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'process_akad_date');
		$creditNote = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'description_akad', false, false, 'EOL');
		$creditContactName = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'contact_name');
		$creditContactEmail = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'contact_email');
		$creditContactBank = $this->Rumahku->filterEmptyField($params, 'KprApplication', 'contact_bank');

		$creditDate = $this->Rumahku->formatDate($credit_date, 'd M Y');
		$creditTime = $this->Rumahku->formatDate($credit_date, 'H:i');

?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
	<tbody>	
		<tr>
	  		<td style="padding: 20px;width: 50%;">
	  			<?php
		  				$code = $this->Html->tag('span', $code,array(
		  					'style' => 'font-size:14px;font-weight:bold;',
		  				));

		  				$label_kpr =  $this->Html->tag('label',__('Kode KPR'),  array(
		  					'style' => 'font-size:14px;',
		  				));

		  				printf('%s : %s', $label_kpr, $code);

	  			?>
	  		</td>
	  		<td style="padding: 20px;text-align:right;width: 50%;">
	  			<?php
	  				echo $this->Html->tag('span', $date_now, array(
	  					'style' => 'margin-top:-10px; font-weight:bold; padding-bottom: 10px;font-size: 14px;line-height: 24px;'
	  				));
	  			?>
	  		</td>
	  	</tr>
  		<tr>
  			<td colspan="2">
  				<table cellpadding="0" cellspacing="0" border="0" width="100%">
			  		<tr>
				  		<td style="padding: 0 20px;" valign="TOP">
					  		<?php
						  			echo $this->Html->tag('h3', __('INFORMASI KLIEN'), array(
						  				'style' => 'font-size:14px;font-weight:bold;display:block;margin: 0 0 10px;padding: 0;',
						  			));
					  		?>
					  		<table style="background:#fff;margin-bottom: 20px" cellpadding="0" cellspacing="0" border="0" width="100%">
					  			<?php
					  					if(!empty($full_name)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Nama'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $full_name, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
					  					}

					  					if(!empty($no_hp)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('No. HP'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $no_hp, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php	
						  				}

						  				if(!empty($no_hp_2)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('No. HP 2'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $no_hp_2, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
					  					}

					  					if(!empty($email)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Email'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $email, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($address)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Alamat'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $address, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($address2) && !$same_as_address_ktp){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Alamat 2'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $address2, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($company)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Perusahaan'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $company, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
					  					}
					  			?>
					  		</table>

					  	</td>
					</tr>
					<tr>
					  	<td style="padding: 0 20px;" valign="TOP">
					  		<?php
						  			echo $this->Html->tag('h3', __('INFORMASI AGEN'), array(
						  				'style' => 'font-size:14px;font-weight:bold;display:block;margin: 0 0 10px;padding: 0;',
						  			));
					  		?>
					  		<table style="background:#fff;margin-bottom: 20px" cellpadding="0" cellspacing="0" border="0" width="100%">
					  			<?php
					  					if(!empty($agent_name)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Nama'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $agent_name, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($agent_no_hp)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('No. HP'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $agent_no_hp, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php	
						  				}

						  				if(!empty($agent_no_hp_2)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('No. HP 2'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $agent_no_hp_2, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($agent_email)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Email'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $agent_email, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($agent_address)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Alamat'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $agent_address, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($agent_address2)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Alamat 2'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $agent_address2, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 100px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
					  					}
					  			?>
					  		</table>
					  	</td>
					</tr>
					<tr>
						<td style="padding: 0 20px;" valign="TOP">
							<?php
						  			echo $this->Html->tag('h3', __('INFORMASI KPR'), array(
						  				'style' => 'font-size:14px;font-weight:bold;display:block;margin: 0 0 10px;padding: 0;',
						  			));
							?>
							<table  style="background:#fff;margin-bottom: 20px" cellpadding="0" cellspacing="0" border="0" width="100%">
								<?php
					  					if(!empty($property_price)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Harga Properti'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $customPropertyPrice, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($loan_plafond)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Jumlah Pinjaman'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $customLoanPlafond, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($down_payment)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Uang Muka'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $customDownPayment, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($periode_fix)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Installment'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', sprintf('%s Tahun',$periode_fix), array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($interest_rate)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Bunga tetap'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', sprintf('%s %%', $interest_rate), array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($customCreditFix)){
					  			?>
				  				<tr>	
					  				<?php
						  				$label =  $this->Html->tag('label',__('Cicilan pertama'),  array(
					  						'style' => 'font-size:14px;',
						  				));
							  			$value = $this->Html->tag('span', $customCreditFix, array(
											'style' => 'font-size:14px;font-weight:bold;'
										));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
					  					}
					  			?>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 0 20px;" valign="TOP">
							<?php
						  			echo $this->Html->tag('h3', __('INFORMASI AKAD KREDIT'), array(
						  				'style' => 'font-size:14px;font-weight:bold;display:block;margin: 0 0 10px;padding: 0;',
						  			));
							?>
							<table  style="background:#fff;margin-bottom: 20px" cellpadding="0" cellspacing="0" border="0" width="100%">
								<?php
					  					if(!empty($credit_date)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Tanggal'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $creditDate, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Pukul'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $creditTime, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($creditNote)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Lokasi'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $creditNote, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($creditContactName)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Bertemu dengan'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $creditContactName, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($creditContactBank)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Telp.'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $creditContactBank, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}

						  				if(!empty($creditContactEmail)){
					  			?>
				  				<tr>	
					  				<?php
							  				$label =  $this->Html->tag('label',__('Email'),  array(
						  						'style' => 'font-size:14px;',
							  				));
								  			$value = $this->Html->tag('span', $creditContactEmail, array(
												'style' => 'font-size:14px;font-weight:bold;'
											));
					  				?>
					  				<td style="padding: 5px 10px 0 0;width: 150px;"><?php echo $label;?></td>
					  				<td style="padding: 5px 0 0 0;">:</td>
					  				<td style="padding: 5px 10px 0 0;"><?php echo $value;?></td>
					  			</tr>
					  			<?php
						  				}
					  			?>
							</table>
						</td>
					</tr>
			  	</table>
  			</td>
  		</tr>
	</tbody>
</table>