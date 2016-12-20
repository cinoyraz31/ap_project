<table align="center" width="600" style="background:#fff" cellpadding="0" cellspacing="0" border="0">
  	<tbody>
	  	<tr>
	  		<td style="padding: 0 20px;">
	      		<?php
	      			echo $this->Html->tag('h1', __('Permohonan KPR Anda ditolak'), array(
						'style' => 'border-bottom: 1px solid #ccc; font-size: 18px; font-weight: 700; padding-bottom: 10px; color: #4a4a4a; text-align:center'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td style="padding: 0 20px;">
      			<?php
	      			echo $this->Html->tag('h4', sprintf(__('Halo, %s,'), 'Thomas Winnar'), array(
						'style' => 'font-weight:400; text-align:center;'
					));
	      		?>
	      		<?php
	      			echo $this->Html->tag('h4', __('Permohonan KPR Anda ditolak karena:'), array(
						'style' => 'margin-top:-10px; font-weight:400; text-align:center;'
					));
	      		?>
	      		<?php
	      			echo $this->Html->tag('h4', sprintf(__('"%s"'), 'Data tidak lengkap, scan KTP tidak jelas.'), array(
						'style' => 'font-weight:400; text-align:center;'
					));
	      		?>
	      		<?php
	      			echo $this->Html->tag('h4', __('Anda dapat mencoba kembali dan memperbaiki permohonan aplikasi KPR Anda dengan mengajukan permohonan aplikasi KPR kembali.'), array(
						'style' => 'font-weight:400; text-align:center;'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td>
	      		<?php
	      			$link = FULL_BASE_URL.$this->Html->url(array(
						'controller' => 'properties', 
						'action' => 'find',
						'admin' => false
					));
	      			echo $this->Html->link(__('Ajukan Permohonan KPR'), $link, array(
						'style' => 'display: block; width: 200px; padding:10px 15px; background:#204798; color: #fff; text-decoration: none; border-radius: 3px; margin: 0 0 30px 20px; text-align: center'
					));
	      		?>
	    	</td>
	  	</tr>
	  	<tr>
	    	<td style="padding: 0 20px;">
	    		<?php
	      			echo $this->Html->tag('h4', __('Terima kasih telah menggunakan KPR Bank BTN bersama Rumahku.com. Kami telah lampirkan detil permohonan KPR Anda pada email ini.'), array(
						'style' => 'border-top: 1px solid #ccc; font-weight:400; padding-top:20px; text-align:center;'
					));
	      		?>
	    	</td>
	  	</tr>
	</tbody>
</table>
