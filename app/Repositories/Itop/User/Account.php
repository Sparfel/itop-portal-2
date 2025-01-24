<?php

namespace App\Repositories\Itop\User;

use App\Repositories\Itop\User\ImportUser;

class Account {


	protected $currentState;

	public $ItopUser;
	public $localAccount = 'KO';
	public $itopContact = 'KO';
	public $itopAccount = 'KO';
	public $portalId;
	public $itopId = 0;


	public function __construct($ItopUserRow)
	{
		$this->ItopUser= $ItopUserRow;
		if ($ItopUserRow->is_local == 1)
			{ $this->localAccount = 'OK';
			$this->portalId = $ItopUserRow->portal_id;
		}
		if ($ItopUserRow->is_in_itop == 1)
				{ $this->itopContact = 'OK';
				$this->itopId= $ItopUserRow->itop_id;
		}
		if ($ItopUserRow->has_itop_account == 1)
			{ $this->itopAccount= 'OK';
		}
		//if
		$this->setState(new ImportUser($this));
	}


	public function setState($state){
		$this->currentState = $state;
	}

	public function getState() {
		return $this->currentState->getState();
	}

	public function next(){
		$this->currentState->next($this);
	}



}
