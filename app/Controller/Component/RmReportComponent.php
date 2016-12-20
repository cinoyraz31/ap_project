<?php
class RmReportComponent extends Component {

	var $components = array(
		'RmCommon'
	);

	function initialize(Controller $controller, $settings = array()) {
		$this->controller = $controller;
	}

	function beforeViewFeePaid($values){

		if(!empty($values)){

			foreach($values AS $key => $value){

					$kpr_commission_payment = $this->RmCommon->filterEmptyField($value,'KprCommissionPayment');
					
					foreach($kpr_commission_payment As $key2 => $val){

							$tipe_komisi = $this->RmCommon->filterEmptyField($val,'KprCommissionPayment','type_komisi');
							$komisi = $this->RmCommon->filterEmptyField($val,'KprCommissionPayment','commission');
							$paid_fee = $this->RmCommon->filterEmptyField($val,'KprCommissionPayment','paid_fee');

							if($tipe_komisi && $komisi){
									$tipe_komisi = sprintf('%s_%s',__('komisi'),$tipe_komisi);
									$paid_komisi = sprintf('%s_%s',__('paid'),$tipe_komisi);

									$value['KprApplication'][$tipe_komisi] = $komisi;
									$value['KprApplication'][$paid_komisi] = $paid_fee;
							}
							

					}

				$values[$key] = $value;
			}
			return $values;
		}

	}
}

?>