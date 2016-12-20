<?php 
		$email = $this->Rumahku->filterEmptyField($params, 'User', 'email');
		$password = $this->Rumahku->filterEmptyField($params, 'User', 'new_password_ori');
		$username = $this->Rumahku->filterEmptyField($User, 'username');
		$name = !empty($username)?$username:sprintf('Admin %s', Configure::read('__Site.site_name'));
?>
<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
		      			echo $this->Html->tag('h1', sprintf(__('%s telah mereset password Anda.'), $name), array(
							'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 10px; color: #4a4a4a; text-align:center'
						));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td style="padding: 0 20px;">
      			<table cellpadding="0" cellspacing="0" border="0">
      				<tbody>
	        			<tr>
	          				<td>
					            <div>
					              	<table style="line-height: 1.5em;">
					              		<tbody>
						                	<tr>
					                  			<?php  
					                  					echo $this->Html->tag('td', __('Email'), array(
					                  						'width' => '200',
				                  						));
				                  						echo $this->Html->tag('td', sprintf(__('%s %s'), ':', $email));
					                  			?>
						                	</tr>
						                	<tr>
					                  			<?php  
					                  					echo $this->Html->tag('td', __('Password'), array(
					                  						'width' => '200',
				                  						));
				                  						echo $this->Html->tag('td', sprintf(__('%s %s'), ':', $password));
					                  			?>
						                	</tr>
						                </tbody>
					              	</table>
					            </div>
					        </td>
					    </tr>
					</tbody>
      			</table>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td style="padding: 0 20px;">
	    		<?php
		      			echo $this->Html->tag('h4', __('Jika karena suatu alasan, Anda tidak merasa melakukan permintaan reset password, mohon segera mencoba mereset password Anda kembali, lalu login ke akun Anda dan lakukan perubahan password sekali lagi untuk mencegah akses dari pihak yang tidak bertanggung-jawab.'), array(
		      				'style' => 'font-weight:normal;'
	      				));
	      		?>
	    	</td>
	  	</tr>
	</tbody>
</table>