<?php
        $document_dates = $this->Rumahku->filterEmptyField( $value, 'KprBank','KprBankDate');
        $full_name = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'client_name');
        $created = $this->Rumahku->filterEmptyField( $value, 'KprBank', 'created', false, false, array(
            'date'=> 'd M Y',
        ));
        $statusLabel = $this->Kpr->_callStatus( $value, true );
?>
<div class="app-status">
    <div class="row">
        <?php
                echo $this->Html->tag('div', $this->Html->tag('h4', $full_name), array(
                    'class' => 'col-sm-12 print-split-col'
                ));

                if( empty($help_apply) && empty($log_kpr) ) {
                    if( !empty($note) ) {
                        $note = str_replace(PHP_EOL, '<br>', $note);
                        $statusLabel .= $this->Html->tag('p', $note);
                    }
                }

                echo $this->html->tag('div', $this->Html->tag('div', $statusLabel, array(
                    'class' => 'mt15 '
                )), array(
                    'class' => 'col-sm-12 print-split-col'
                ));
        ?>
    </div>
        <?php
                if( !empty($document_dates) ) {
        ?>
    <div class="row hidden-print">
        <div class="mt30 ms15">
            <?php
                    $pending = Set::extract($document_dates, '/KprBankDate[slug=pending]');

                    foreach ($document_dates as $key => $date) {
                        $slug = $this->Rumahku->filterEmptyField( $date, 'KprBankDate', 'slug');
                        $note = $this->Rumahku->filterEmptyField( $date, 'KprBankDate', 'note');
                        $action_date = $this->Rumahku->filterEmptyField( $date, 'KprBankDate', 'action_date', false, false, array(
                            'date'=> 'd M Y H:i:s',
                        ));
                        $calDate = $this->Kpr->_callDates( $slug, $action_date, false , $pending);

                        if(!empty($calDate)){
                            echo $this->Html->tag('div', $calDate, array(
                                'class' => 'col-sm-4 print-split-col col-right'
                            ));
                        }
                    }
            ?>
        </div>
    </div>
        <?php
                }
        ?>
</div>