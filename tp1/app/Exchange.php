<?php

namespace App;

use App\DBConnection;
use App\EmailSender;
use Carbon\Carbon;
use Illuminate\Http\Response;

class Exchange {

    private $emailSender;
    private $dbConnection;
    
    public function __construct(User $receiver, Product $product, Carbon $begin_date, Carbon $end_date) {
        $this->receiver = $receiver;
        $this->product = $product;
        $this->begin_date = $begin_date;
        $this->end_date = $end_date;

        $this->dbConnection = new DBConnection();
        $this->emailSender = new EmailSender();
    }

    public function save(){
        
        if($this->receiver->isValid() && $this->product->isValid() && $this->verifyDateStart() && $this->verifyDate()){
            
            $this->dbConnection->saveExchange($this->receiver, $this->product, $this->begin_date, $this->end_date);
            
            if( !$this->receiver->isMajor() ){
                $this->emailSender->sendEmail($this->receiver->getEmail(), 'Exchange Done');
            }

            return response('Échange enregistré', Response::HTTP_CREATED);
            
        }else{
            return response('Problème lors de l\'échange', Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyDateStart(){
        return $this->begin_date > Carbon::now();
    }
    
    public function verifyDate(){
        return $this->end_date > $this->begin_date;
    }
    
    public function setDateStart($y, $m, $d){
        return $this->begin_date = Carbon::createFromDate($y, $m , $d);
    }

    public function setDateEnd($y, $m, $d){
        return $this->end_date = Carbon::createFromDate($y, $m , $d);
    }

}

?>