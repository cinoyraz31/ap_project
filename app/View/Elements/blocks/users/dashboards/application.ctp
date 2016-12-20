<?php
	
	if( !empty($applications) ) {
		$account_elements = !empty($account_elements) ? $account_elements : array();
?>
<div class="dashbox mt30 mb30">
	<div class="applications-list user-list">
		<?php 
				echo $this->Html->tag('h4', __('Aplikasi KPR Terbaru'));
		?>
		<div class="table-responsive">
			<table class="table">
				<?php 
						$dataColumns = array(
				            'name' => array(
				                'name' => __('Klien'),
				                'field_model' => false,
				                'style' => 'width: 35%;',
				                'display' => true,
				            ),
				            'price' => array(
				                'name' => __('Harga Properti'),
				                'style' => 'width: 30%;',
				                'field_model' => false,
				                'display' => true,
				            ),
				            'location' => array(
				                'name' => __('Lokasi Properti'),
				                'field_model' => false,
				                'display' => true,
				            ),
				            'action' => array(
				                'name' => __('Action'),
				            ),
				        );
				        $fieldColumn = $this->Rumahku->_generateShowHideColumn( $dataColumns, 'field-table' );

		                if( !empty($fieldColumn) ) {
		                    echo $this->Html->tag('thead', $this->Html->tag('tr', $fieldColumn));
		                }
		        ?>
		        <tbody>
		        	<?php 
  							foreach( $applications as $key => $value ) {
  								$id = $this->Rumahku->filterEmptyField($value, 'KprBank', 'id');
  								$user = $this->Rumahku->filterEmptyField($value, 'KprBank', 'client_name');
  								$property_price = $this->Rumahku->filterEmptyField($value, 'KprBankInstallment', 'property_price');
  								$propertyAddress = $this->Rumahku->filterEmptyField($value, 'PropertyAddress');
  								$property_cities = $this->Rumahku->filterEmptyField($propertyAddress, 'City', 'name');
  								$customPrice = $this->Rumahku->getCurrencyPrice($property_price, '-');
  								$action = $this->Html->link($this->Rumahku->icon('rv4-angle-right'), array(
  									'controller' => 'kpr',
  									'action' => 'user_apply_detail',
  									$id,
  									'admin' => true,
									), array(
										'escape' => false,
  									'class' => 'more',
								));

  								echo $this->Html->tableCells(array(
						            $user,
						            $customPrice,
							        array(
						         		$property_cities,
							            array(
							            	'class' => 'clear',
						            	),
							        ),
							        $action,
						        ));
		                	}
					?>
		        </tbody>
			</table>
		</div>
			<?php 
					echo $this->Html->link(__('Lihat semua &raquo;'), array_merge(array(
						'controller' => 'kpr',
						'action' => 'list_user_apply',
						'admin' => true,
					), $account_elements), array(
						'escape' => false,
						'class' => 'see-all',
					));
			?>
	</div>
</div>
<?php
	}

?>