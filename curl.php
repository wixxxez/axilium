<?php
require "connect.php";
$json = file_get_contents('php://input');
$obj = json_decode($json, TRUE);



if($obj['transactionStatus'] == 'Approved' ){
        $transact = R::findOne('transactions', 'order_reference = ?',[ $obj['orderReference'] ] );
        $transact=$transact->export();
        if($transact['verify'] == 0) {
                    R::exec('UPDATE `transactions` SET `status` = ? WHERE `transactions`.`order_reference` = ?;',[ $obj['transactionStatus'], $obj['orderReference'] ]);
                    R::exec('UPDATE `transactions` SET `amount` = ? WHERE `transactions`.`order_reference` = ?;',[ $obj['amount'], $obj['orderReference'] ]);
                    R::exec('UPDATE `transactions` SET `email` = ? WHERE `transactions`.`order_reference` = ?;',[ $obj['email'], $obj['orderReference'] ]);
                    R::exec('UPDATE `transactions` SET `verify` = ? WHERE `transactions`.`order_reference` = ?;',[ 1, $obj['orderReference'] ]);
                    $information = R::findOne('transactions','order_reference = ?', [ $obj['orderReference'] ]);
                    $information=$information->export();
                    $target_p = R::findOne('patients','id = ? ',[ $information['target'] ]);
                    $target_p = $target_p->export();
                    $new = $target_p['progress'] + $obj['amount'];
                    
                    R::exec('UPDATE `patients` SET `progress` = ? WHERE `patients`.`id` = ?;', [ $new, $information['target'] ]);
                    
        }

}
if($obj['transactionStatus'] == 'Decline' or $obj['transactionStatus'] == 'Expired' ){
    R::exec('DELETE FROM `transactions` WHERE `transactions`.`order_reference` = ?;' [ $obj['orderReference'] ]);
}

?>