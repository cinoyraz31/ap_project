<?php
        $data = $this->request->data;
        $periodeFlag = !empty($periodeFlag)? TRUE : FALSE;
        $currency = Configure::read('__Site.config_currency_code');
        $modelName = !empty($modelName)? $modelName : 'BankSetting';

        $selectOptions = array(
            'frameClass' => 'col-sm-12',
            'labelClass' => 'col-xl-2 col-sm-5 control-label',
            'class' => 'relative col-sm-3',
        );

        echo $this->Html->tag('h2', __('Biaya Lainnya'), array(
            'class' => 'sub-heading'
        ));

        if(!empty($periodeFlag)){
?>
        <div class="row">
            <div class="col-sm-6">
                <?php 
                        echo $this->Rumahku->buildInputForm(sprintf('%s.periode_installment', $modelName), array_merge($options, array(
                            'type' => 'text',
                            'label' => __('Max Periode Cicilan *'),
                            'class' => 'relative col-sm-5',
                            'inputClass' => 'input_number',
                            'labelClass' => 'col-xl-2 col-sm-4 control-label',
                            'textGroup' => __('Tahun'),
                        )));
                ?>
            </div>
        </div>
<?php 
        }
?>
        
        <div class="row">
        	<div class="col-sm-6">
        		<?php 
			            echo $this->Rumahku->buildInputForm(sprintf('%s.dp', $modelName), array_merge($options, array(
			            	'type' => 'text',
			                'label' => __('Uang Muka *'),
		            		'class' => 'relative col-sm-5',
                            'inputClass' => 'input_number',
                            'labelClass' => 'col-xl-2 col-sm-4 control-label',
		                	'textGroup' => __('%'),
			            )));
        		?>
        	</div>
        	<div class="col-sm-6">
        		<?php 
			            echo $this->Rumahku->buildInputForm(sprintf('%s.provision', $modelName), array_merge($options, array(
			            	'type' => 'text',
			                'label' => __('Provisi'),
		            		'class' => 'relative col-sm-5',
                            'inputClass' => 'input_number',
                            'labelClass' => 'col-xl-2 col-sm-4 control-label',
		                	'textGroup' => __('%'),
			            )));
        		?>
        	</div>
        </div>
        <div class="row">
            <div class="col-sm-6">
            <?php
                    echo $this->Rumahku->buildInputForm(sprintf('%s.work_day', $modelName), array_merge($options, array(
                                'type' => 'text',
                                'label' => __('Lama kerja *'),
                                'class' => 'relative col-sm-5',
                                'inputClass' => 'input_number',
                                'labelClass' => 'col-xl-2 col-sm-4 control-label',
                                'textGroup' => __('Hari'),
                            )));
            ?>
            </div>
            <div class="col-sm-6">
                <?php 
                        echo $this->Rumahku->buildInputForm(sprintf('%s.provision_company', $modelName), array_merge($options, array(
                            'type' => 'text',
                            'label' => sprintf(__('Provisi %s'), Configure::read('__Site.site_name')),
                            'class' => 'relative col-sm-5',
                            'inputClass' => 'input_number',
                            'labelClass' => 'col-xl-2 col-sm-4 control-label',
                            'textGroup' => __('%'),
                        )));
                ?>
            </div>
        </div>
                <?php 
                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_insurance');
                $optionsGroup = $this->Rumahku->setGroupField('category_insurance', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
                echo $this->Rumahku->buildInputForm(sprintf('%s.insurance', $modelName), array_merge( $selectOptions, array(
                    'type' => 'text',
                    'label' => __('Asuransi'),
                    'inputClass' => $classPrice.' insurance',
                    'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_insurance', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-insurance',
                            'data-target' => '.insurance',
                            'data-hide' => '.param_insurance',
                            'data-group' => '.category_insurance',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_insurance '.$showParams,
                        'fieldName' => sprintf('%s.param_insurance', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
                )));
               
                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_appraisal');
                $optionsGroup = $this->Rumahku->setGroupField('category_appraisal', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
	            echo $this->Rumahku->buildInputForm(sprintf('%s.appraisal', $modelName), array_merge($selectOptions, array(
	            	'type' => 'text',
	                'label' => __('Appraisal'),
	                'inputClass' => $classPrice.' appraisal',
        			'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_appraisal', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-appraisal',
                            'data-target' => '.appraisal',
                            'data-hide' => '.param_appraisal',
                            'data-group' => '.category_appraisal',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_appraisal '.$showParams,
                        'fieldName' => sprintf('%s.param_appraisal', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
	            )));
                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_administration');
                $optionsGroup = $this->Rumahku->setGroupField('category_administration', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
	            echo $this->Rumahku->buildInputForm(sprintf('%s.administration', $modelName), array_merge($selectOptions, array(
	            	'type' => 'text',
	                'label' => __('Administrasi *'),
	                'inputClass' => $classPrice.' administration',
        			'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_administration', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-administration',
                            'data-target' => '.administration',
                            'data-hide' => '.param_administration',
                            'data-group' => '.category_administration',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_administration '.$showParams,
                        'fieldName' => sprintf('%s.param_administration', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
	            )));

	            echo $this->Html->tag('h2', __('Biaya Notaris'), array(
		            'class' => 'sub-heading'
		        ));

                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_credit_agreement');
                $optionsGroup = $this->Rumahku->setGroupField('category_credit_agreement', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
                echo $this->Rumahku->buildInputForm(sprintf('%s.credit_agreement', $modelName), array_merge($selectOptions, array(
                    'type' => 'text',
                    'label' => __('Akta Perjanjian Kredit '),
                    'inputClass' => $classPrice.' credit_agreement',
                    'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_credit_agreement', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-credit_agreement',
                            'data-target' => '.credit_agreement',
                            'data-hide' => '.param_credit_agreement',
                            'data-group' => '.category_credit_agreement',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_credit_agreement '.$showParams,
                        'fieldName' => sprintf('%s.param_credit_agreement', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
                )));
              //   echo $this->Rumahku->buildInputForm('note', array_merge($options, array(
              //       'label' => __('Catatan'),
                    // 'labelClass' => 'col-xl-2 col-sm-2 control-label',
              //   )));

                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_sale_purchase_certificate');
                $optionsGroup = $this->Rumahku->setGroupField('category_sale_purchase_certificate', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                // debug($optionsGroup);die();
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
                echo $this->Rumahku->buildInputForm(sprintf('%s.sale_purchase_certificate', $modelName), array_merge($selectOptions, array(
                    'type' => 'text',
                    'label' => __('Akte Jual Beli'),
                    'inputClass' => $classPrice.' sale_purchase_certificate',
                    'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_sale_purchase_certificate', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-sale_purchase_certificate',
                            'data-target' => '.sale_purchase_certificate',
                            'data-hide' => '.param_sale_purchase_certificate',
                            'data-group' => '.category_sale_purchase_certificate',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_sale_purchase_certificate '.$showParams,
                        'fieldName' => sprintf('%s.param_sale_purchase_certificate', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
                )));

                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_transfer_title_charge');
                $optionsGroup = $this->Rumahku->setGroupField('category_transfer_title_charge', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
                echo $this->Rumahku->buildInputForm(sprintf('%s.transfer_title_charge', $modelName), array_merge($selectOptions, array(
                    'type' => 'text',
                    'label' => __('Bea Balik Nama'),
                    'inputClass' => $classPrice.' transfer_title_charge',
                    'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_transfer_title_charge', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-transfer_title_charge',
                            'data-target' => '.transfer_title_charge',
                            'data-hide' => '.param_transfer_title_charge',
                            'data-group' => '.category_transfer_title_charge',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_transfer_title_charge '.$showParams,
                        'fieldName' => sprintf('%s.param_transfer_title_charge', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
                )));

                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_letter_mortgage');
                $optionsGroup = $this->Rumahku->setGroupField('category_letter_mortgage', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
                echo $this->Rumahku->buildInputForm(sprintf('%s.letter_mortgage', $modelName), array_merge($selectOptions, array(
                    'type' => 'text',
                    'label' => __('Akta SKMHT'),
                    'inputClass' => $classPrice.' letter_mortgage',
                    'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_letter_mortgage', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-letter_mortgage',
                            'data-target' => '.letter_mortgage',
                            'data-hide' => '.param_letter_mortgage',
                            'data-group' => '.category_letter_mortgage',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_letter_mortgage '.$showParams,
                        'fieldName' => sprintf('%s.param_letter_mortgage', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
                )));

                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_mortgage');
                $optionsGroup = $this->Rumahku->setGroupField('category_mortgage', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
                echo $this->Rumahku->buildInputForm(sprintf('%s.mortgage', $modelName), array_merge($selectOptions, array(
                    'type' => 'text',
                    'label' => __('Perjanjian HT'),
                    'inputClass' => $classPrice.' mortgage',
                    'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_mortgage', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-mortgage',
                            'data-target' => '.mortgage',
                            'data-hide' => '.param_mortgage',
                            'data-group' => '.category_mortgage',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_mortgage '.$showParams,
                        'fieldName' => sprintf('%s.param_mortgage', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
                )));

                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_imposition_act_mortgage');
                $optionsGroup = $this->Rumahku->setGroupField('category_imposition_act_mortgage', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
                echo $this->Rumahku->buildInputForm(sprintf('%s.imposition_act_mortgage', $modelName), array_merge($selectOptions, array(
                    'type' => 'text',
                    'label' => __('Akta APHT'),
                    'inputClass' => $classPrice.' imposition_act_mortgage',
                    'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_imposition_act_mortgage', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-imposition_act_mortgage',
                            'data-target' => '.imposition_act_mortgage',
                            'data-hide' => '.param_imposition_act_mortgage',
                            'data-group' => '.category_imposition_act_mortgage',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_imposition_act_mortgage '.$showParams,
                        'fieldName' => sprintf('%s.param_imposition_act_mortgage', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
                )));

                $category = $this->Rumahku->filterEmptyField($data, $modelName, 'category_other_certificate');
                $optionsGroup = $this->Rumahku->setGroupField('category_other_certificate', array(
                    'category' => $category,
                    'currency' => $currency,
                ));
                $showParams = $this->Rumahku->filterEmptyField($optionsGroup, 'showParams');
                $classPrice = $this->Rumahku->filterEmptyField($optionsGroup, 'classPrice');
                $selectOptions = array_merge($selectOptions, $optionsGroup);
                echo $this->Rumahku->buildInputForm(sprintf('%s.other_certificate', $modelName), array_merge($selectOptions, array(
                    'type' => 'text',
                    'label' => __('Cek Sertifikat, ZNT, PNBP HT'),
                    'inputClass' => $classPrice.' other_certificate',
                    'labelClass' => 'col-xl-2 col-sm-2 control-label',
                    'otherInput' => array(
                        'class' => 'col-sm-3',
                        'fieldName' => sprintf('%s.category_other_certificate', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'flag-other_certificate',
                            'data-target' => '.other_certificate',
                            'data-hide' => '.param_other_certificate',
                            'data-group' => '.category_other_certificate',
                            'currency' => $currency,
                            'options' => Configure::read('__Selectbox.price'),
                            'positionGroup' => 'left',
                        ),
                    ),
                    'paramsPrice' => array(
                        'class' => 'col-sm-3 param_other_certificate '.$showParams,
                        'fieldName' => sprintf('%s.param_other_certificate', $modelName),
                        'inputOptions' => array(
                            'type' => 'select',
                            'label' => false,
                            'options' => Configure::read('__Selectbox.paramsPrice'),
                        ),
                    ),
                )));

        ?>
	</div>
</div>