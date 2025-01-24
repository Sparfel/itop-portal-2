<?php
namespace App\Repositories\Itop\User;

use App\Repositories\ItopWebserviceRepository;
use App\Models\User as User;
use Illuminate\Support\Facades\Log;

Class ItopContact implements ItopUser {

	private static $_instance = null;
	private $userAccount;


	public function __construct($userAccount){
		$this->userAccount = $userAccount;
//		error_log(htmlspecialchars_decode (Zend_Debug::dump($userAccount->itopId,' 3 - ItopContact')),3,"/var/tmp/mes-erreurs.log");
        Log::debug('3 - ItopContact (itopId='.$userAccount->itopId.')');
		if ($userAccount->itopContact == 'KO') {
			if ($this->createItopContact())
				{$this->next($this->userAccount);}
			else {$this->prev($this->userAccount);}
		}
		else {$this->next($this->userAccount);}
	}

	public static function getInstance($userAccount) {
		if(is_null(self::$_instance)) {
			self::$_instance = new ItopContact($userAccount);
		}
		return self::$_instance;
	}


	public function next($userAccount){
		if ($this->doTheJob(null) == 'OK'){
			$userAccount->setState(new ItopAccount($this->userAccount));
		}
	}

	public function prev($account){
		$account->setState(new LocalAccount($this->userAccount));
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


	public function createItopContact(){
		try {
			$ItopUser= $this->userAccount->ItopUser;

            $webservice = new ItopWebserviceRepository();

			$login = $ItopUser->email;
			$res_itop_id = null;
			$objects = json_decode($webservice->createUser(
                                    $ItopUser->first_name,
                                    $ItopUser->last_name,
                                    $ItopUser->email,
                                    $ItopUser->org_id,
                                    $ItopUser->location_id,
                                    $ItopUser->phone,
                                    $ItopUser->mobile_phone, //mobile phone
                                    $ItopUser->comment),
                                    true
					);
			//error_log(htmlspecialchars_decode (Zend_Debug::dump($objects,'create User itop')),3,"/var/tmp/mes-erreurs.log");
            Log::debug($objects);
			if (isset($objects['code']) && $objects['code'] > 0) {
				$result['code'] = $objects['code'];
				$result['message'] = $objects['message'];
				throw new \Exception(serialize($result));
			}

			// on récupère l'Id du user sous iTop pour le stocker dans la base du portail
			foreach ($objects['objects'] as $res) {
				//error_log(htmlspecialchars_decode (Zend_Debug::dump($obj,'$obj')),3,"/var/tmp/mes-erreurs.log");

				{
                    Log::debug($res);
					//error_log(htmlspecialchars_decode (Zend_Debug::dump($res['fields'],'$res[\'fields\']')),3,"/var/tmp/mes-erreurs.log");
					$res_itop_id = $res['fields']['id'];
					$org_name = $res['fields']['org_name'];
					$site_name = $res['fields']['site_name'];


				}
			}
            //Log::debug('portal id : '.$this->userAccount->portalId);
			//error_log(htmlspecialchars_decode (Zend_Debug::dump($result,'$result')),3,"/var/tmp/mes-erreurs.log");
			$this->userAccount->itopId = $res_itop_id;
			//MAj pour récupérer dans le portal l'ID du contact iTop créé pour Auth_User
			$this->setItopId($this->userAccount->portalId, $this->userAccount->itopId);
			//On indique la progression dans le iTopUser


			$ItopUser->itop_id = $res_itop_id;
			$ItopUser->org_name = $org_name;
			$ItopUser->location_name = $site_name;
			$ItopUser->is_in_itop = 1;
			//$ItopUser->error = serialize($result);
			$ItopUser->save();


			$this->userAccount->itopContact = 'OK';
			//error_log(htmlspecialchars_decode (Zend_Debug::dump($test,'completeItopUser')),3,"/var/tmp/mes-erreurs.log");
			if (is_null($this->userAccount->itopId)) { return false;}
			else {
				return true;
			}
		} catch (\Exception $e) {
			error_log(htmlspecialchars_decode ($e),3,"/var/tmp/mes-erreurs.log");
            Log::error($e);
			$ItopUser->error = 'Exception lors de l\'insertion du Contact iTop. '.$e;
			$ItopUser->save();
			return false;
		}
	}

	private function setItopId($portalId, $itopId){
        try {
            $UserRow = User::find($portalId);
            //$User = User::where('id',$portalId)->first();
            //$User = $UserRow->first();
            $UserRow->itop_id = $itopId;
            $UserRow->save();
            Log::debug("User->itop_id = ".$itopId." Portal id = ".$portalId);
        }
        catch (\Exception $e) {
            error_log(htmlspecialchars_decode ($e),3,"/var/tmp/mes-erreurs.log");
            Log::error($e);
            return false;
        }

	}
}
