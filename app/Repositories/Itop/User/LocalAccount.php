<?php
namespace App\Repositories\Itop\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User as User;
use Illuminate\Support\Str;

Class LocalAccount implements ItopUser {

	private static $_instance = null;
	protected $userAccount;

	protected $Default_group = 3;// 3 pour USer Client : le compte pour nos clients.


	public function __construct($userAccount){
		//echo $userAccount->LdapUser->email.' - '.$userAccount->itopContact;
		//error_log(htmlspecialchars_decode (Zend_Debug::dump($userAccount->itopId,' 2 - LocalAccount')),3,"/var/tmp/mes-erreurs.log");
        Log::debug('2 - LocalAccount (itopId='.$userAccount->itopId.'), LocalAccount?'.$userAccount->localAccount);
		$this->userAccount = $userAccount;
		//if ($userAccount->LdapUser->email == 'luke.skywalker@fivesgroup.com') // A virer, pour test seulement
		{
			if ($userAccount->localAccount == 'KO') { //Si pas de compte local, on le créé

				if ($this->createAccount())
				{$this->next($this->userAccount);}
				else {$this->prev($this->userAccount);}

			}

			else {$this->next($this->userAccount);}
		}
	}


	public static function getInstance($userAccount) {
		if(is_null(self::$_instance)) {
			self::$_instance = new LocalAccount($userAccount);
		}
		return self::$_instance;
	}

	public function next($userAccount){
		//Zend_Debug::dump($userAccount);
		if ($this->doTheJob(null) == 'OK'){
			$userAccount->setState(new ItopContact($this->userAccount));
            //Log::debug('LocalAccount: Next()');
		}
	}

	public function prev($userAccount){
		$userAccount->setState(new ImportUser($this));
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

	private function createAccount(){
		$ItopUser = $this->userAccount->ItopUser;
		//On va tenter de créer le compte local

		$etape = 'Account creation';
        Log::info($etape);
		// On crée ensuite l'enregistrement le user local (table auth_user)
		//$user = new Auth_Model_DbTable_User();
        //$user =new User();
		//Détermination du password par défaut
		//Le Username sera désormais l'adresse mail, résoud le soucis d'unicité entre les 3 AD
		$username = $ItopUser->login;
		$salt = $this->getSalt($username);
        //Log::debug('$ItopUser '.print_r($ItopUser, true));
//		$itopUser = new UserLocal();
		$password =  Str::random(3).''.rand( 1000, 9999 );
		$data = array ('username' => $username,
				'name' => $ItopUser->first_name.' '.$ItopUser->last_name,
				'first_name' => $ItopUser->first_name,
				'last_name' => $ItopUser->last_name,
				'email' => $ItopUser->email,
				'is_active' => 1,
				'is_staff' => 0, // is_staff à OFF car on créé ici des comptes client
				'itop_cfg'=> 1, //iTop de production par défaut
				'itop_id' => $ItopUser->itop_id,
				'password' => Hash::make($password),
				'guid' => $password, // usage détourné du GUID de l'AD, ici compte local uniquement, on y stocke le mot de passe temporaire (initial)
				'domain' => null, // il s'agit d'un compte local, on ne précise rien ici
                'role_id' => 2, // à déterminer de façon plus sioux peut-être ?
                'org_id' => $ItopUser->org_id,
                'loc_id' => $ItopUser->location_id
		);

		//error_log(htmlspecialchars_decode (Zend_Debug::dump($data,'Data')),3,"/var/tmp/mes-erreurs.log");
		//Insertion dans la DB
		try {
            Log::debug('Creation User Local '.print_r($data, true));
            //User::insert($data);
            $User = new User($data);
            $User->save();
            //$User->insert($data);
            //Log::debug('$User: '.$User);
            $pk = $User->getId();
            //Log::debug('PK: '.$pk);
//			$pk = $user->insert($data);
//			$ItopUser->setPortalUserId($pk);
//			//On gère ensuite les autorisations
//			//Zend_Debug::dump($pk);
//			$belong = new Auth_Model_DbTable_Belong();
//			$data = array('user_id' => $pk,
//					'group_id' => $this->Default_group);
//			$belong->insert($data);
//			//Puis le profile
//			$profile = new User_Model_DbTable_Profile();
//			$data = array('user_id' => $pk,
//					'nickname' => $username,
//					'department' => $ItopUser->org_name,
//					'service' =>  $ItopUser->location_name
//			);
//			$profile->insert($data);
//			//Maj des données origines dans la table des imports => le compte a été créé
//			$ItopUser->group_id = $this->Default_group; // Groupe utilisateur par défaut
			$ItopUser->is_local = 1;
            $ItopUser->portal_id = $pk;
			$ItopUser->save();
			$this->userAccount->localAccount = 'OK';
			//$pk = 999999;
			$this->userAccount->portalId = $pk;
			return true;
		} catch (\Exception $e) {
			//error_log(htmlspecialchars_decode ($e),3,"/var/tmp/mes-erreurs.log");
            Log::error($e);
			$ItopUser->error = 'Exception lors de l\'insertion du User Local. '.htmlspecialchars_decode ($e);
			$ItopUser->save();
			return false;
		}

	}

	private function getSalt($username){
		return  md5(rand(100000, 999999). $username);
	}
}
