<?php

namespace App\Repositories\Itop\User;

use Illuminate\Support\Facades\Log;

Class ImportUser implements ItopUser{


	private static $_instance = null;
	//protected $ADLocation;
	//protected $AD;
	protected $userAccount;
	protected $currentState;

	public function __construct($userAccount){

		$this->userAccount = $userAccount;
		//dd($userAccount->itopId);
        Log::debug('1 - ImportUser');
	}

	public static function getInstance($userAccount) {

		if(is_null(self::$_instance)) {
			self::$_instance = new ImportUser($userAccount);
		}
		return self::$_instance;
	}



	public function next($userAccount){
		//Zend_Debug::dump($userAccount);
		$userAccount->setState(new LocalAccount($this->userAccount));

	}

	public function prev($userAccount){
		//$installation->setState(new InitState($this->installation));
	}

	public function cancel(){
		null;
	}

	public function getState(){
		return $this;
	}

	public function validState(){

	}

}

