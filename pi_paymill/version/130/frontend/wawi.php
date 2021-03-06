<?php
require_once(dirname(__FILE__) . '/../paymentmethod/classes/lib/Services/Paymill/Refunds.php');

$orders = $GLOBALS['DB']->executeQuery(
    'SELECT cBestellNr FROM tbestellung WHERE cStatus = "' . BESTELLUNG_STATUS_STORNO . '"',
    2
);

foreach ($orders as $order) {
    $transaction = $GLOBALS['DB']->executeQuery(
        'SELECT * FROM xplugin_pi_paymill_transaction WHERE order_id = "' . $order->cBestellNr . '"',
        1
    );
    
    if ($transaction) {
        $params = array(
            'transactionId' => $transaction->transaction_id,
            'params' => array('amount' => $transaction->amount)
        );

        $refundsObject = new Services_Paymill_Refunds(
            trim($oPlugin->oPluginEinstellungAssoc_arr['pi_paymill_private_key']), 
            'https://api.paymill.com/v2/'
        );
        
        try {
            $refund = $refundsObject->create($params);
        } catch (Exception $ex) {
            
        }
    }
}