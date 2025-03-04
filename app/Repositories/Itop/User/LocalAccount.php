<?php
namespace App\Repositories\Itop\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User as User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

Class LocalAccount implements ItopUser {

	private static $_instance = null;
	protected $userAccount;

	protected $Default_group = 3;// 3 pour USer Client : le compte pour nos clients.


	public function __construct($userAccount){
		Log::debug('2 - LocalAccount (itopId='.$userAccount->itopId.'), LocalAccount?'.$userAccount->localAccount);
		$this->userAccount = $userAccount;

        try {
            if ($userAccount->localAccount == 'KO') {
                if ($this->createAccount()) {
                    $this->next($this->userAccount);
                } else {
                    $this->prev($this->userAccount);
                }
            } else {
                $this->next($this->userAccount);
            }
//        } catch (UserCreationException $e) {
        } catch (\Exception $e) {
            Log::error("Erreur dans LocalAccount : " . $e->getMessage());
            throw $e; // Relancer pour que le contrôleur AJAX puisse capturer l'erreur
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
		//Le Username sera désormais l'adresse mail
		$username = $ItopUser->login;
        $this->userAccount->password =  $this->generateSecurePassword(8);
//        \Log::debug($this->userAccount->password);
		$data = array ('username' => $username,
				'name' => $ItopUser->first_name.' '.$ItopUser->last_name,
				'first_name' => $ItopUser->first_name,
				'last_name' => $ItopUser->last_name,
				'email' => $ItopUser->email,
				'is_active' => 1,
				'is_staff' => 0, // is_staff à OFF car on créé ici des comptes client
				'itop_cfg'=> 0, //iTop de production par défaut
				'itop_id' => $ItopUser->itop_id,
				'password' => Hash::make($this->userAccount->password),
				'guid' => $this->userAccount->password, // usage détourné du GUID de l'AD, ici compte local uniquement, on y stocke le mot de passe temporaire (initial)
				'domain' => null, // il s'agit d'un compte local, on ne précise rien ici
//                'role_id' => 2, // à déterminer de façon plus sioux peut-être ?
                'org_id' => $ItopUser->org_id,
                'loc_id' => $ItopUser->location_id
		);

		//Insertion dans la DB
		try {
            Log::debug('Creation User Local '.print_r($data, true));
            //User::insert($data);
            $User = new User($data);
            $User->save();
            //Log::debug('$User: '.$User);
            // Gestion des rôles, si pas spécifié, alors on lui assigne le rôle 2 (User)
            // Définir le rôle de l'utilisateur (utiliser role_id s'il est défini, sinon 2)
            $roleId = $ItopUser->role_id ?? 2;
            // Récupérer le rôle Spatie correspondant
            $role = Role::find($roleId);
            if ($role) {
                // Assigner le rôle à l'utilisateur avec l'ID $pk
                $User->assignRole($role);
            }
            $ItopUser->is_local = 1;

            $ItopUser->portal_id = $User->id;
			$ItopUser->save();
			$this->userAccount->localAccount = 'OK';
			$this->userAccount->portalId = $User->id;
			return true;
		} catch (\Exception $e) {
            Log::error('Erreur lors de la création de l’utilisateur : ' . $e->getMessage());
            // Lever une exception spécifique pour être captée par les classes appelantes
//            throw new UserCreationException("Erreur lors de la création du compte : " . $e->getMessage());
            throw new \Exception("Erreur lors de la création du compte : " . $e->getMessage());
		}
	}
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
