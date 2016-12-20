<?php
class PropertyHelper extends AppHelper {
    var $helpers = array(
        'Rumahku', 'Html', 'Number',
        'Session'
    );

    // function getStatus ( $data ) {
    //     $active = $this->Rumahku->filterEmptyField($data, 'Property', 'active', 0);
    //     $status = $this->Rumahku->filterEmptyField($data, 'Property', 'status', 0);
    //     $sold = $this->Rumahku->filterEmptyField($data, 'Property', 'sold', 0);
    //     $published = $this->Rumahku->filterEmptyField($data, 'Property', 'published', 0);
    //     $deleted = $this->Rumahku->filterEmptyField($data, 'Property', 'deleted', 0);
    //     $inactive = $this->Rumahku->filterEmptyField($data, 'Property', 'inactive', 0);
    //     $updated = $this->Rumahku->filterEmptyField($data, 'Property', 'updated', 0);
    //     $property_action_id = $this->Rumahku->filterEmptyField($data, 'Property', 'property_action_id', 0);

    //     if( $active && $status && !$sold && $published && !$deleted && !$inactive ) {
    //         return __('Aktif');
    //     } else if( !$active && $status && !$sold && $published && !$deleted ) {
    //         return __('Proses');
    //     } else if( $updated && $active && $status && !$sold && $published && !$deleted && !$inactive ) {
    //         return __('Update');
    //     } else if( $sold && $active && $status && $published && !$deleted && !$inactive ) {
    //         if( $property_action_id == 2 ) {
    //             return __('Tersewa');
    //         } else {
    //             return __('Terjual');
    //         }
    //     } else if( !$status && $published && !$deleted ) {
    //         return __('Non-Aktif');
    //     } else if( !$published && !$deleted ) {
    //         return __('Unpublish');
    //     } else {
    //         return false;
    //     }
    // }

    function getLotUnit ( $size = 'm2', $action_type = false, $position = false ) {
        $lblUnit = array(
            1 => __('m2'),
            2 => __('m2'),
            3 => __('hektar'),
            4 => __('are'),
        );
        $size = !empty($lblUnit[$size])?$lblUnit[$size]:$size;

        if( !empty($size) ) {
            switch ($action_type) {
                case 'format':
                    $lotUnit = str_split($size);
                    $unit_name = end($lotUnit);

                    if( !is_numeric($unit_name) ) {
                        $unit_name = false;
                    } else {
                        array_pop($lotUnit);

                        $size = implode('', $lotUnit);
                    }

                    switch ($position) {
                        case 'top':

                            return sprintf('%s%s', $size, $this->Html->tag('sup', $unit_name));
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        } else {
            return false;
        }
    }

    public function getTypeLot($data) {
        $is_lot = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_lot');
        $is_building = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_building');
        $is_space = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_space');
        $action_id = $this->Rumahku->filterEmptyField($data, 'Property', 'property_action_id');

        if( $is_lot && !$is_building && $action_id == 1 ) {
            return true;
        } else if( $is_space ) {
            return true;
        } else {
            return false;
        }
    }

    function getPeriode ( $data ) {
        $action_id = $this->Rumahku->filterEmptyField($data, 'Property', 'property_action_id');
        $period = $this->Rumahku->filterEmptyField($data, 'Property', 'period');
        $result = false;

        $periodOptions = array(
            'day' => __('hari'),
            'week' => __('minggu'),
            'month' => __('bulan'),
            'year' => __('tahun'),
        );

        if( $action_id == 2) {
            $result = $this->Rumahku->filterEmptyField($periodOptions, $period);
        }

        return $result;
    }

    function getPrice( $data ){
        $price = $this->Rumahku->filterEmptyField($data, 'Property', 'price');
        $sold = $this->Rumahku->filterEmptyField($data, 'Property', 'sold');
        $period = $this->Rumahku->filterEmptyField($data, 'Property', 'period');

        $currency = $this->Rumahku->filterEmptyField($data, 'Currency', 'symbol');
        $lot_unit = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'lot_unit');

        $lot_unit = $this->getLotUnit($lot_unit, 'format', 'top');
        $lot_type = $this->getTypeLot($data);
        $period_name = $this->getPeriode($data);
        $period_separator = '/';

        $lot_unit_name = '';  

        if( !empty($sold) ) {
            $price_sold = $this->Rumahku->filterEmptyField($data, 'PropertySold', 'price_sold');
        } else if( $lot_type ) {
            $lot_unit_name = ( $lot_unit )?sprintf(__(' / %s'), $lot_unit):'';
        }

        $price = $this->Number->currency($price, $currency.' ', array('places' => 0));

        if( !empty($lot_unit_name) ) {
            $price = sprintf('%s / %s', $price, $lot_unit_name);
            $period_separator = 'per';
        }
        if( !empty($period_name) ) {
            $price = sprintf('%s %s %s', $price, $period_separator, $period_name);
        }

        return $price;
    }

    function getCertificate ( $data ) {
        $certificate_id = $this->Rumahku->filterEmptyField($data, 'Property', 'certificate_id');
        $others_certificate = $this->Rumahku->filterEmptyField($data, 'Property', 'others_certificate');
        $certificate = $this->Rumahku->filterEmptyField($data, 'Certificate', 'name');
        $certificate_name = false;

        if( $certificate_id == -1 && !empty($others_certificate) ) {
            $certificate_name = $others_certificate;
        } else if( !empty($certificate) ) {
            $certificate_name = $certificate;
        }

        return $certificate_name;
    }

    // function getSpec ( $data ) {
    //     $result = false;
    //     $spec = array();
    //     $type_id = $this->Rumahku->filterEmptyField($data, 'Property', 'property_type_id');

    //     $is_lot = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_lot');
    //     $is_building = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_building');
    //     $is_residence = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_residence');

    //     $lot_unit = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'lot_unit');
    //     $level = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'level');
    //     $building_size = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'building_size');
    //     $lot_dimension = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'lot_dimension');
    //     $lot_size = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'lot_size');

    //     $lot_unit = $this->getLotUnit($lot_unit, 'format', 'top');

    //     if( ( in_array($type_id, array( 6, 7 )) || ( $is_building && !$is_residence ) ) ) {
    //         if( !empty($level) ) {
    //             $spec[] = array(
    //                 'name' => __('Lantai'),
    //                 'alias' => __('Lantai'),
    //                 'value' => $level,
    //             );
    //         }
    //         if( !empty($building_size) ) {
    //             $spec[] = array(
    //                 'name' => __('L. Bangunan'),
    //                 'alias' => __('LB'),
    //                 'value' => sprintf('%s %s', $building_size, $lot_unit),
    //             );
    //         }
    //         if( count($spec) < 2 && !empty($lot_dimension) ) {
    //             $spec[] = array(
    //                 'name' => __('Dimensi'),
    //                 'alias' => __('Dimensi'),
    //                 'value' => $lot_dimension,
    //             );
    //         }
    //     } else if( ( in_array($type_id, array( 2 )) || ( $is_lot && !$is_building ) ) ) {
    //         if( !empty($lot_size) ) {
    //             $spec[] = array(
    //                 'name' => __('L. Tanah'),
    //                 'alias' => __('LT'),
    //                 'value' => sprintf('%s%s', $lot_size, $lot_unit),
    //             );
    //         }

    //         $certificate_name = $this->getCertificate($data);
            
    //         if( !empty($certificate_name) ) {
    //             $spec[] = array(
    //                 'name' => __('Sertifikat'),
    //                 'alias' => __('Sertifikat'),
    //                 'value' => $certificate_name,
    //             );
    //         }
    //     } else if( ( in_array($type_id, array( 1 )) || !empty($is_residence) ) ) {
    //         $dir_type_id = $this->Rumahku->filterEmptyField($data, 'DirectoryTypes', 'id');
    //         $dir_lot_size = $this->Rumahku->filterEmptyField($data, 'DirectoryTypes', 'lot_size');
    //         $dir_beds = $this->Rumahku->filterEmptyField($data, 'DirectoryTypes', 'beds');

    //         if( !empty($dir_type_id) ) {
    //             if( !empty($dir_lot_size) ) {
    //                 $spec[] = array(
    //                     'name' => __('L. Unit'),
    //                     'alias' => __('LB'),
    //                     'value' => sprintf('%s%s', $dir_lot_size, $lot_unit),
    //                 );
    //             }
    //             if( !empty($dir_beds) ) {
    //                 $valueKamar = $dir_beds;
    //                 $spec[] = array(
    //                     'name' => __('K. Tidur'),
    //                     'alias' => __('KT'),
    //                     'value' => $valueKamar,
    //                 );
    //             }
    //         } else {
    //             $beds = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'beds');
    //             $beds_maid = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'beds_maid');

    //             if( !empty($building_size) ) {
    //                 $spec[] = array(
    //                     'name' => __('L. Bangunan'),
    //                     'alias' => __('LB'),
    //                     'value' => sprintf('%s%s', $building_size, $lot_unit),
    //                 );
    //             }
    //             if( !empty($beds) ) {
    //                 if( !empty($beds_maid) ) {
    //                     $beds = sprintf('%s + %s', $beds, $beds_maid);
    //                 }

    //                 $spec[] = array(
    //                     'name' => __('K. Tidur'),
    //                     'alias' => __('KT'),
    //                     'value' => $beds,
    //                 );
    //             }
    //         }
    //     }

    //     if( !empty($spec) ) {
    //         $contentLi = '';

    //         foreach ($spec as $key => $value) {
    //             $lblSpec = $this->Html->tag('span', $value['name']);
    //             $lblSpec .= $this->Html->tag('strong', $value['value']);

    //             $contentLi .= $this->Html->tag('li', $lblSpec);
    //         }

    //         $result = $this->Html->tag('ul', $contentLi);
    //     }

    //     return $result;
    // }

    function getStatus ( $data, $tag = false ) {
        $id = $this->Rumahku->filterEmptyField($data, 'Property', 'id');
        $active = $this->Rumahku->filterEmptyField($data, 'Property', 'active', 0);
        $status = $this->Rumahku->filterEmptyField($data, 'Property', 'status', 0);
        $sold = $this->Rumahku->filterEmptyField($data, 'Property', 'sold', 0);
        $published = $this->Rumahku->filterEmptyField($data, 'Property', 'published', 0);
        $deleted = $this->Rumahku->filterEmptyField($data, 'Property', 'deleted', 0);
        $in_update = $this->Rumahku->filterEmptyField($data, 'Property', 'in_update', 0);
        $property_action_id = $this->Rumahku->filterEmptyField($data, 'Property', 'property_action_id', 0);
        $action_name = $this->Rumahku->filterEmptyField($data, 'PropertyAction', 'inactive_name', 0);
        $addClass = false;

        if( $in_update && $status && !$sold && $published && !$deleted ) {
            $labelStatus = __('Update');
            $addClass = 'update';
        } else if( $active && $status && !$sold && $published && !$deleted ) {
            $labelStatus = __('Aktif');
            $addClass = 'active';
        } else if( !$active && $status && !$sold && $published && !$deleted ) {
            $labelStatus = __('Pratinjau');
            $addClass = 'process';
        } else if( $sold ) {
            if( $property_action_id == 2 ) {
                $labelStatus = __('Tersewa');
            } else {
                $labelStatus = __('Terjual');
            }
            $addClass = 'sold';
            $prefix = Configure::read('App.prefix');

            if( $prefix == 'admin' && !empty($tag) ) {
                $labelStatus .= $this->Html->link(__('Lihat detil..'), array(
                    'controller' => 'properties',
                    'action' => 'sold_preview',
                    $id,
                    'admin' => true,
                ), array(
                    'class' => 'ajaxModal',
                    'title' => sprintf(__('Keterangan %s'), $action_name),
                ));
            }
        } else if( !$status && $published && !$deleted ) {
            $labelStatus = __('Non-Aktif/Rejected');
            $addClass = 'non-active';
        } else if( !$published && !$deleted ) {
            $labelStatus = __('Unpublish');
            $addClass = 'unpublish';
        } else {
            $labelStatus = false;
        }

        if( !empty($tag) && !empty($labelStatus) ) {
            return $this->Html->tag($tag, $labelStatus, array(
                'class' => $addClass.' fbold',
            ));
        } else {
            return $labelStatus;
        }
    }

    function _callUnset( $fieldArr, $data ) {
        if( !empty($fieldArr) ) {
            foreach ($fieldArr as $key => $value) {
                if( is_array($value) ) {
                    foreach ($value as $idx => $fieldName) {
                        if( !empty($data[$key][$fieldName]) ) {
                            unset($data[$key][$fieldName]);
                        }
                    }
                } else {
                    unset($data[$value]);
                }
            }
        }

        return $data;
    }

    function getSpec ( $data, $showParams = array(), $options = false, $wrapper = false ) {
        $result = false;
        $spec = array();
        $display = $this->Rumahku->filterEmptyField($options, 'display');

        $options = $this->_callUnset(array(
            'display',
        ), $options);

        $is_lot = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_lot');
        $is_building = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_building');
        $is_residence = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_residence');
        $is_space = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_space');

        $dataAsset = $this->Rumahku->filterEmptyField($data, 'PropertyAsset');
        $level = $this->Rumahku->filterEmptyField($dataAsset, 'level');
        $building_size = $this->Rumahku->filterEmptyField($dataAsset, 'building_size');
        $lot_width = $this->Rumahku->filterEmptyField($dataAsset, 'lot_width');
        $lot_length = $this->Rumahku->filterEmptyField($dataAsset, 'lot_length');
        $lot_size = $this->Rumahku->filterEmptyField($dataAsset, 'lot_size');
        $lot_unit = $this->Rumahku->filterEmptyField($dataAsset, 'LotUnit', 'slug');

        $lot_unit = ucwords($lot_unit);

        if( ( $is_space && $is_building ) || ( $is_building && !$is_residence ) ) {
            if( !empty($level) ) {
                $spec[] = array(
                    'name' => __('Lantai'),
                    'value' => $level,
                );
            }
            if( !empty($building_size) ) {
                $spec[] = array(
                    'name' => __('L. Bangunan'),
                    'alias' => __('LB'),
                    'value' => sprintf('%s %s', $building_size, $lot_unit),
                );
            }
            if( count($spec) < 2 && !empty($lot_width) ) {
                $lot_dimension = $this->_callGetLotDimension($lot_width, $lot_length);

                $spec[] = array(
                    'name' => __('Dimensi'),
                    'value' => $lot_dimension,
                );
            }
        } else if( ( $is_space && $is_lot ) || ( $is_lot && !$is_building ) ) {
            if( !empty($lot_size) ) {
                $spec[] = array(
                    'name' => __('L. Tanah'),
                    'alias' => __('LT'),
                    'value' => sprintf('%s %s', $lot_size, $lot_unit),
                );
            }

            $certificate_name = $this->getCertificate($data);
            
            if( !empty($certificate_name) ) {
                $spec[] = array(
                    'name' => __('Sertifikat'),
                    'value' => $certificate_name,
                );
            }
        } else if( ( $is_building && $is_residence && $is_lot ) || !empty($is_residence) ) {
            $beds = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'beds');
            $beds_maid = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'beds_maid');

            if( !empty($building_size) ) {
                $spec[] = array(
                    'name' => __('L. Bangunan'),
                    'alias' => __('LB'),
                    'value' => sprintf('%s %s', $building_size, $lot_unit),
                );
            }
            if( $display != 'frontend' ) {
                if( !empty($lot_size) ) {
                    $spec[] = array(
                        'name' => __('L. Tanah'),
                        'alias' => __('LT'),
                        'value' => sprintf('%s %s', $lot_size, $lot_unit),
                    );
                }
            }
            if( !empty($beds) ) {
                if( !empty($beds_maid) ) {
                    $beds = sprintf('%s + %s', $beds, $beds_maid);
                }

                $spec[] = array(
                    'name' => __('K. Tidur'),
                    'alias' => __('KT'),
                    'value' => $beds,
                );
            }
        }

        if( !empty($showParams) ) {
            $addText = false;
            
            foreach ($showParams as $modelName => $params) {
                foreach ($params as $key => $fieldName) {
                    if( is_array($fieldName) ) {
                        $field = $this->Rumahku->filterEmptyField($fieldName, 'name');
                        $label = $this->Rumahku->filterEmptyField($fieldName, 'label');
                        $alias = $this->Rumahku->filterEmptyField($fieldName, 'alias');
                        $addText = $this->Rumahku->filterEmptyField($fieldName, 'addText');
                        $newline = $this->Rumahku->filterEmptyField($fieldName, 'newline');
                        $format = $this->Rumahku->filterEmptyField($fieldName, 'format');
                        $display = isset($fieldName['display'])?$fieldName['display']:true;

                        if( !empty($display) ) {                            
                            $value = $this->Rumahku->filterEmptyField($data, $modelName, $field);
                        }

                        if( $format == 'date' ) {
                            $value = $this->Rumahku->formatDate($value, 'd/m/Y');
                        }
                    } else {
                        $value = $this->Rumahku->filterEmptyField($data, $modelName, $fieldName);
                        $label= $alias = ucwords($fieldName);
                    }

                    if( !empty($value) ) {
                        $spec[] = array(
                            'name' => $label,
                            'alias' => $alias,
                            'value' => $value.$addText,
                            'newline' => $newline,
                        );
                    }
                }
            }
        }

        if( !empty($spec) ) {
            $contentLi = '';

            if( empty($wrapper) ) {
                $wrapperLabel = 'span';
                $wrapperValue = 'strong';
            } else {
                $wrapperLabel = $wrapper['wrapperLabel'];
                $wrapperValue = $wrapper['wrapperValue'];
            }

            foreach ($spec as $key => $value) {
                $newline = $this->Rumahku->filterEmptyField($value, 'newline');
                $alias = $this->Rumahku->filterEmptyField($value, 'alias');

                $lblSpec = $this->Html->tag($wrapperLabel, $value['name']);
                $lblSpec .= $this->Html->tag($wrapperValue, $value['value']);

                if( !empty($newline) ) {
                    $contentLi .= '<br>';
                }
                $contentLi .= $this->Html->tag('li', $lblSpec, array(
                    'title' => $alias,
                ));
            }

            $result = $this->Html->tag('ul', $contentLi, $options);
        }

        return $result;
    }

    function getActiveStep ( $step, $current ) {
        $sessionData = $this->Session->read('Session.Property.'.$current);
        $addClass = '';

        if( !empty($sessionData) ) {
            $addClass = 'done';
        }

        if( $step == $current ) {
            $addClass = ' active';
        }

        return $addClass;
    }

    function getUrlStep ( $url, $current ) {
        $sessionData = $this->Session->read('Session.Property.'.$current);

        if( !empty($sessionData) ) {
            return $url;
        } else {
            return '#';
        }
    }

    function _callGetLotDimension ( $lot_width, $lot_length ) {
        $lot_dimension = $lot_width;

        if( !empty($lot_length) ) {
            $lot_dimension = sprintf('%s X %s', $lot_dimension, $lot_length);
        }

        return $lot_dimension;
    }

    function _callGetSpecification ( $data, $options = false, $wrapper = true, $data_revision = array() ) {
        $result = '';
        $is_lot = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_lot');
        $is_building = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_building');
        $is_residence = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_residence');
        $is_space = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'is_space');
        $_type = $this->Rumahku->filterEmptyField($data, 'PropertyType', 'name');

        $dataAsset = $this->Rumahku->filterEmptyField($data, 'PropertyAsset');
        $lot_unit_id = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'lot_unit_id');

        $lot_name = $this->Rumahku->filterEmptyField($dataAsset, 'LotUnit', 'slug');
        $lot_name = $this->Rumahku->filterEmptyField($data, 'LotUnit', 'slug', $lot_name);

        $level = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'level');
        $building_size = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'building_size');
        $lot_width = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'lot_width');
        $lot_length = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'lot_length');
        $lot_size = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'lot_size');
        $beds = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'beds');
        $beds_maid = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'beds_maid');
        $baths = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'baths');
        $baths_maid = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'baths_maid');
        $cars = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'cars');
        $carports = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'carports');
        $phoneline = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'phoneline');
        $electricity = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'electricity');
        $furnished = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'furnished', 'none');
        $year_built = $this->Rumahku->filterEmptyField($data, 'PropertyAsset', 'year_built', '-', true, 'year');
        $direction = $this->Rumahku->filterEmptyField($dataAsset, 'PropertyDirection', 'name');
        $condition = $this->Rumahku->filterEmptyField($dataAsset, 'PropertyCondition', 'name');
        $view = $this->Rumahku->filterEmptyField($dataAsset, 'ViewSite', 'name');
        $spec = $this->Rumahku->filterEmptyField($options, 'specs', false, array());

        if( !empty($spec) ) {
            unset($options['specs']);
        }

        $certificate_name = $this->getCertificate($data);
        $furnishedOptions = Configure::read('Global.Data');

        // if( !empty($certificate_name) ) {
            $spec[] = array(
                'alias' => __('Srtfkt'),
                'name' => __('Sertifikat'),
                'value' => $certificate_name,
                'model' => 'Property',
                'field' => 'certificate_id,others_certificate'
            );
        // }

        if( !empty($is_residence) ) {
            // if( !empty($beds) ) {
                if( !empty($beds_maid) ) {
                    $beds = sprintf('%s + %s', $beds, $beds_maid);
                }

                $spec[] = array(
                    'alias' => __('KT'),
                    'name' => __('Kamar Tidur'),
                    'value' => $beds,
                    'model' => 'PropertyAsset',
                    'field' => 'beds,beds_maid'
                );
            // }
            // if( !empty($baths) ) {
                if( !empty($baths_maid) ) {
                    $baths = sprintf('%s + %s', $baths, $baths_maid);
                }

                $spec[] = array(
                    'alias' => __('KM'),
                    'name' => __('Kamar Mandi'),
                    'value' => $baths,
                    'model' => 'PropertyAsset',
                    'field' => 'baths,baths_maid'
                );
            // }
        }

        if( !empty($data_revision) ) {
            // if( !empty($lot_unit_id) && !empty($lot_name) ){
            if( !empty($lot_unit_id) ){
                if( !empty($is_space) ) {
                    $lblLotName = __('Harga Satuan');
                    $lot_name = sprintf(__('Per %s'), $lot_name);
                } else {
                    $lblLotName = __('Satuan Luas');
                }

                $spec[] = array(
                    'name' => $lblLotName,
                    'value' => $lot_name,
                    'model' => 'PropertyAsset',
                    'field' => 'lot_unit_id'
                );
            }
        }

        // if( !empty($lot_width) ) {
            $lot_dimension = $this->_callGetLotDimension($lot_width, $lot_length);
            $spec[] = array(
                'alias' => __('Dimensi'),
                'name' => __('Dimensi'),
                'value' => $lot_dimension,
                'model' => 'PropertyAsset',
                'field' => 'lot_width,lot_length'
            );
        // }

        if( !empty($is_lot) ) {
            // if( !empty($lot_size) ) {
                $spec[] = array(
                    'alias' => __('LT'),
                    'name' => __('Luas Tanah'),
                    'value' => trim(sprintf('%s %s', $lot_size, $lot_name)),
                    'model' => 'PropertyAsset',
                    'field' => 'lot_size,lot_unit_id'
                );
            // }
        }

        if( !empty($is_building) ) {
            // if( !empty($building_size) ) {
                $spec[] = array(
                    'alias' => __('LB'),
                    'name' => __('L. Bangunan'),
                    'value' => trim(sprintf('%s %s', $building_size, $lot_name)),
                    'model' => 'PropertyAsset',
                    'field' => 'building_size,lot_unit_id'
                );
            // }
            // if( !empty($level) ) {
                $spec[] = array(
                    'alias' => __('Lantai'),
                    'name' => __('Lantai'),
                    'value' => $level,
                    'model' => 'PropertyAsset',
                    'field' => 'level'
                );
            // }
            // if( !empty($cars) ) {
                $spec[] = array(
                    'alias' => __('Garasi'),
                    'name' => __('Garasi'),
                    'value' => $cars,
                    'model' => 'PropertyAsset',
                    'field' => 'cars'
                );
            // }
            // if( !empty($carports) ) {
                $spec[] = array(
                    'alias' => __('Carport'),
                    'name' => __('Carport'),
                    'value' => $carports,
                    'model' => 'PropertyAsset',
                    'field' => 'carports'
                );
            // }
            // if( !empty($phoneline) ) {
                $spec[] = array(
                    'alias' => __('Line Tlp'),
                    'name' => __('Jml Line Telepon'),
                    'value' => $phoneline,
                    'model' => 'PropertyAsset',
                    'field' => 'phoneline'
                );
            // }
            // if( !empty($electricity) ) {
                $spec[] = array(
                    'alias' => __('Listrik'),
                    'name' => __('Daya Listrik'),
                    'value' => $electricity,
                    'model' => 'PropertyAsset',
                    'field' => 'electricity'
                );
            // }
            // if( !empty($furnished) ) {
                $customFurnished = $this->Rumahku->filterEmptyField($furnishedOptions, 'furnished', $furnished);
                $spec[] = array(
                    'name' => __('Interior'),
                    'value' => $customFurnished,
                    'model' => 'PropertyAsset',
                    'field' => 'furnished'
                );
            // }
            // if( !empty($direction) ) {
                $spec[] = array(
                    'alias' => __('Arah'),
                    'name' => __('Arah Bangunan'),
                    'value' => $direction,
                    'model' => 'PropertyAsset',
                    'field' => 'property_direction_id'
                );
            // }
            // if( !empty($year_built) && $year_built != '0000' ) {
                $spec[] = array(
                    'alias' => __('Tahun'),
                    'name' => __('Tahun dibangun'),
                    'value' => $year_built,
                    'model' => 'PropertyAsset',
                    'field' => 'year_built'
                );
            // }
            // if( !empty($condition) ) {
                $spec[] = array(
                    'alias' => __('Kondisi'),
                    'name' => __('Kondisi Bangunan'),
                    'value' => $condition,
                    'model' => 'PropertyAsset',
                    'field' => 'property_condition_id'
                );
            // }
            // if( !empty($view) ) {
                $spec[] = array(
                    'alias' => __('View'),
                    'name' => sprintf(__('View %s'), $_type),
                    'value' => $view,
                    'model' => 'PropertyAsset',
                    'field' => 'view_site_id'
                );
            // }
        }

        if( !empty($spec) ) {
            if( !empty($wrapper) ) {
                $contentLi = '';

                if( is_array($wrapper) ) {
                    $wrapperLabel = $wrapper['wrapperLabel'];
                    $wrapperValue = $wrapper['wrapperValue'];
                } else {
                    $wrapperLabel = 'span';
                    $wrapperValue = 'strong';
                }

                foreach ($spec as $key => $value) {
                    if( empty($data_revision) ) {
                        if( !empty($value['value']) ) {
                            $flag = true;
                        } else {
                            $flag = false;
                        }
                    } else {
                        $flag = true;
                    }

                    if( !empty($flag) ) {

                        $lblSpec = $this->Html->tag($wrapperLabel, sprintf('%s: ', $value['name']));
                        $lblSpec .= $this->Html->tag($wrapperValue, $value['value']);
                        
                        $lblSpec = $this->Html->tag('span', sprintf('%s: ', $value['name']));
                        $lblSpec .= $this->Html->tag('strong', $value['value']);

                        $contentLi .= $this->Html->tag('li', $lblSpec, array(
                            'class' => 'clearafter',
                        ));
                    }


                }

                $result = $this->Html->tag('ul', $contentLi, $options);
            } else {
                $result = $spec;
            }
        }

        return $result;
    }

    function _callBackApplication ( $title, $options = array() ) {
        $application = $this->Rumahku->filterEmptyField($this->params, 'named', 'application');
        $logkpr = $this->Rumahku->filterEmptyField($this->params, 'named', 'logkpr');
        $helpkpr = $this->Rumahku->filterEmptyField($this->params, 'named', 'helpkpr');

        $options['escape'] = false;
        $url = array(
            'controller' => 'kpr',
            'admin' => true
        );

        if( !empty($logkpr) ) {
            $url['controller'] = 'logs';
            $url['action'] = 'history_detail';
            $url[] = $logkpr;
        } else if( !empty($helpkpr) ) {
            $url['action'] = 'help_user_apply_detail';
            $url[] = $helpkpr;
        } else if( !empty($application) ) {
            $url['action'] = 'user_apply_detail';
            $url[] = $application;
        } else {
            return false;
        }

        return $this->Html->link($title, $url, $options);
    }

    function getNameCustom( $data, $only_location = false ) {
        $dataAddress = !empty($data['PropertyAddress'])?$data['PropertyAddress']:false;

        $subarea = $this->Rumahku->filterEmptyField($dataAddress, 'Subarea', 'name');
        $city = $this->Rumahku->filterEmptyField($dataAddress, 'City', 'name');
        $zip = $this->Rumahku->filterEmptyField($dataAddress, 'zip');

        if( !empty($subarea) && !empty($city) ) {
            $location = sprintf('%s, %s %s', $subarea, $city, $zip);
        } else {
            $location = '';
        }

        if( !empty($only_location) ) {
            $result = $location;
        } else {
            $type = strtolower($this->Rumahku->filterEmptyField($data, 'PropertyType', 'name'));
            $action = strtolower($this->Rumahku->filterEmptyField($data, 'PropertyAction', 'name'));
            
            $result = trim(sprintf(__('%s %s %s'), $type, $action, $location));
        }

        $result = ucwords($result);

        return $this->Rumahku->safeTagPrint($result);
    }

    function _callGetCustom ( $data, $tag = 'div', $data_revision = false, $contract = true ) {
        $_config = Configure::read('Config.Company.data');
        $is_bt = $this->Rumahku->filterEmptyField($_config, 'UserCompanyConfig', 'is_bt_commission');
        $is_kolisting_koselling = $this->Rumahku->filterEmptyField($_config, 'UserCompanyConfig', 'is_kolisting_koselling');

        $resultArr = array(
            'ClientProfile' => array(
                array(
                    'name' => 'full_name',
                    'label' => __('Klien'),
                    'newline' => true,
                ),
                array(
                    'name' => 'no_hp',
                    'label' => __('No. HP Klien'),
                ),
            ),
        );
        $result = '';

        if( !empty($resultArr) ) {
            if( !empty($tag) ) {
                foreach ($resultArr as $modelName => $values) {
                    if( !empty($values) ) {
                        foreach ($values as $key => $value) {
                            $fieldName = $this->Rumahku->filterEmptyField($value, 'name');
                            $label = $this->Rumahku->filterEmptyField($value, 'label');
                            $display = $this->Rumahku->filterEmptyField($value, 'display');
                            $addText = $this->Rumahku->filterEmptyField($value, 'addText');
                            $newline = $this->Rumahku->filterEmptyField($value, 'newline');
                            $title = $this->Rumahku->filterEmptyField($value, 'title');
                            $format = $this->Rumahku->filterEmptyField($value, 'format');

                            $val = $this->Rumahku->filterEmptyField($data, $modelName, $fieldName);

                            if( empty($data_revision) ) {
                                if( !empty($val) ) {
                                    $flag = true;
                                } else {
                                    $flag = false;
                                }
                            } else {
                                $flag = true;
                            }

                            if( !empty($display) && $flag ) {
                                if( !empty($newline) ) {
                                    $result .= '<br>';
                                }

                                if( $format == 'date' ) {
                                    $val = $this->Rumahku->formatDate($val, 'd/m/Y');
                                }

                                $result .= $this->Html->tag($tag, $this->Rumahku->getCheckRevision($modelName, $fieldName, $data_revision, sprintf('%s : %s', $label, $this->Html->tag('strong', $val.$addText, array(
                                    'title' => $title,
                                )))));
                            }
                        }
                    }
                }
            } else {
                $result = $resultArr;
            }
        }

        return $result;
    }

}