<?php
namespace App\Repositories\Itop\User;

use App\Repositories\Itop\ItopWebserviceRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

Class ItopAccount implements ItopUser {
	private static $_instance = null;
	protected $userAccount;


	public function __construct($userAccount){
		$this->userAccount = $userAccount;
//		error_log(htmlspecialchars_decode (Zend_Debug::dump($userAccount->itopId,' 4 - ItopAccount')),3,"/var/tmp/mes-erreurs.log");
        Log::debug('4 - ItopAccount (itopId='.$userAccount->itopId.')');
        try {
            if ($userAccount->itopAccount == 'KO') {
                if ($this->createItopAccount()) {
                    $this->next($this->userAccount);
                } else {
                    //Un soucis, on arrête là.
                    //$this->prev($this->userAccount);
                    Log::info('Un soucis, on arrête là - ItopAccount');
                }
            } else {
                $this->next($this->userAccount);
            }
        } catch (\Exception $e) {
            Log::error("Erreur dans ItopAccount : " . $e->getMessage());
            throw $e; // Relancer pour que le contrôleur AJAX puisse capturer l'erreur
        }

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
            $webservice = new ItopWebserviceRepository();
            //Si password est null, on lui assigne generateSecurePassword(). Sinon, il garde sa valeur existante.
            $this->userAccount->ItopUser->password ??= $this->generateSecurePassword(8);
			$objects = json_decode($webservice->CreateItopAccount($this->userAccount->itopId,
                                                                $this->userAccount->ItopUser->email, // L'identifiant pour se connecter à iTop
                                                                $this->userAccount->ItopUser->password,
                                                                $this->userAccount->ItopUser->first_name,
                                                                $this->userAccount->ItopUser->last_name,
                                                                $this->userAccount->ItopUser->email,
                                                                $this->userAccount->ItopUser->org_id),
                                true);
            unset($this->userAccount->ItopUser->password); // on supprime la variable car on ne la stocke pas.
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
			//On indique la progression dans le ItopUser
          	$ItopUser->has_itop_account = 1;
			$ItopUser->save();
			$this->userAccount->itopAccount= 'OK';
			return true;


		} catch (Exception $e) {
            Log::error('Erreur lors de la création du compte iTop : ' . $e->getMessage());
            $ItopUser->error = 'Erreur lors de la création du compte iTop. '.$e->getMessage();
            $ItopUser->save();
            // Lever une exception spécifique pour être captée par les classes appelantes
//            throw new UserCreationException("Erreur lors de la création du compte : " . $e->getMessage());
            throw new \Exception("Erreur lors de la création du compte iTop: " . $e->getMessage());
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

    /*
     * On génère ici un nouveau mot de passe pour le compte iTop, différent de celui du portail
     * et sécurisé. On ne le mémorise nulle part donc il reste inconnu.
     */
    private function generateSecurePassword($length = 12) {
        $uppercase = Str::random(1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'); // 1 majuscule
        $lowercase = Str::random(1, 'abcdefghijklmnopqrstuvwxyz'); // 1 minuscule
        $number = Str::random(1, '0123456789'); // 1 chiffre
        // Liste des caractères spéciaux
        $specialChars = '!@#$%^&*()-_=+';
        $special = $specialChars[random_int(0, strlen($specialChars) - 1)]; // 1 caractère spécial
        $remaining = Str::random($length - 4); // Reste des caractères aléatoires

        // Mélanger les caractères pour éviter une prévisibilité
        $password = str_shuffle($uppercase . $lowercase . $number . $special . $remaining);

        return $password;
    }
}
