<?php

namespace App;

use App\DBConnection;
use App\EmailSender;
use Carbon\Carbon;
use Illuminate\Http\Response;

class BankAccount {

    private $emailSender;
    private $dbConnection;
    
    public function __construct(int $amount, User $owner) {
        $this->amount = $amount;
        $this->owner = $owner;
        
        $this->dbConnection = new DBConnection();
        $this->emailSender = new EmailSender();
    }

    public function credit(int $credit){
        
        if($this->owner->isValid() && $this->creditValid()){
            
            $current_amount = $this->amount;

            $amountBalanceCredit = $this->defineNewBalance($credit);

            if($amountBalanceCredit['balanceAfterCredit'] - $current_amount != 0){
                $this->emailSender->sendEmail($this->receiver->getEmail(), 'Account Credited');
            }
            
            return (object) array(
                'amountCredited' => $amountBalanceCredit['amountCredited'],
                'balanceAfterCredit' => $amountBalanceCredit['balanceAfterCredit']
            );
        }else{
            return response('Problème lors du crédit', Response::HTTP_BAD_REQUEST);
        }
    }

    public function creditValid($credit){
        return $credit > 0;
    }

    public function defineNewBalance($credit){
        
        if($this->amount >= 1000){
            return array('amountCredited' => 0, 'balanceAfterCredit' => 1000);
        }
        
        $this->amount = $this->amount + $credit;

        if($this->amount >= 1000){
            $surplus = $this->amount - $credit;
            $amountCredited = $credit - $surplus;
            return array('amountCredited' => $amountCredited, 'balanceAfterCredit' => 1000);
        }
        
        return array('amountCredited' => $credit, 'balanceAfterCredit' => $this->amount);
    }
}

?>