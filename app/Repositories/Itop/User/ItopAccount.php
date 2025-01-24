<?php
namespace App\Repositories\Itop\User;

use App\Repositories\ItopWebserviceRepository;
use Illuminate\Support\Facades\Log;

Class ItopAccount implements ItopUser {


	private static $_instance = null;
	protected $userAccount;


	public function __construct($userAccount){
		$this->userAccount = $userAccount;
//		error_log(htmlspecialchars_decode (Zend_Debug::dump($userAccount->itopId,' 4 - ItopAccount')),3,"/var/tmp/mes-erreurs.log");
        Log::debug('4 - ItopAccount (itopId='.$userAccount->itopId.')');
		if ($userAccount->itopAccount== 'KO') {
			if ($this->createItopAccount()) {$this->next($this->userAccount);}
			else {
				//Un soucis, on arrête là.
//				error_log(htmlspecialchars_decode (Zend_Debug::dump('Un soucis, on arrête là.',' 4! - ItopAccount')),3,"/var/tmp/mes-erreurs.log");
				//$this->prev($this->userAccount);
                Log::info('Un soucis, on arrête là - ItopAccount');
			}
		}
		else {$this->next($this->userAccount);}

	}

	public static function getInstance($userAccount) {
		if(is_null(self::$_instance)) {
			self::$_instance = new ItopAccount($userAccount);
		}
		return self::$_instance;
	}


	public function next($userAccount){
		$tab_result = array(
				'portalId' => $this->userAccount->portalId,
				'itopId' => $this->userAccount->itopId,
				'localAccount' => $this->userAccount->localAccount,
				'itopContact' => $this->userAccount->itopContact,
				'itopAccount' => $this->userAccount->itopAccount
		);
//		error_log(htmlspecialchars_decode (Zend_Debug::dump($tab_result,'OK - FIN')),3,"/var/tmp/mes-erreurs.log");
        Log::debug(print_r($tab_result, true).'OK - FIN');
	}

	public function prev($userAccount){
		$userAccount->setState(new ItopContact($userAccount));
	}

	public function cancel(){
		null;
	}

	public function getState(){
		return $this;
	}


	public function validState(){

	}

	public function doTheJob($options){
		return 'OK';
	}

	public function createItopAccount(){
		try {
			$ItopUser = $this->userAccount->ItopUser;
			$class = $this->getClass();
			$itopProfile = $this->getProfile();
			//error_log(htmlspecialchars_decode (Zend_Debug::dump($this->userAccount->itopId,'itopId')),3,"/var/tmp/mes-erreurs.log");
//			$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
            $webservice = new ItopWebserviceRepository();

			$objects = json_decode($webservice->CreateItopAccount($this->userAccount->itopId,
                                                                $this->userAccount->ItopUser->email, // L'identifiant pour se connecter à iTop
                                                                $this->userAccount->ItopUser->first_name,
                                                                $this->userAccount->ItopUser->last_name,
                                                                $this->userAccount->ItopUser->email,
                                                                $this->userAccount->ItopUser->org_id),
                                true);
//dd($objects);
			if (isset($objects['code']) && $objects['code'] > 0) {
				$result['code'] = $objects['code'];
				$result['message'] = $objects['message'];
				throw new \Exception(serialize($result));
			}

			foreach ($objects['objects'] as $res) {
				$result['code'] = $res['code'];
				$result['message'] = $res['message'];
				$result['class'] = $res['class'];

			}
//			error_log(htmlspecialchars_decode (Zend_Debug::dump($result,'create itop Account')),3,"/var/tmp/mes-erreurs.log");
			//On indique la progression dans le ItopUser

			$ItopUser->has_itop_account = 1;
			$ItopUser->save();
			$this->userAccount->itopAccount= 'OK';
			return true;


		} catch (Exception $e) {
			error_log(htmlspecialchars_decode ($e),3,"/var/tmp/mes-erreurs.log");
            Log::error($e);
			$ItopUser->error = 'Exception lors de l\'insertion du Contact iTop. '.$e;
			$ItopUser->save();
			return false;
		}

	}

	//Compte Client : forcément la Classe UserLocal (!=LDAPUser), les comptes clients ne sont pas gérer par un AD.
	private function getClass(){
		$class = 'UserLocal';
		return $class;
	}


	//Quoiqu'il arrive, le profil iTop sera ici Portal User : les clients n'ont pas accès à iTop.
	private function getProfile(){
		$profil = 'Portal User';
		return $profil;
	}
}
