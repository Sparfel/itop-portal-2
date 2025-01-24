<?php
namespace App\Repositories\Itop\User;

interface ItopUser {

	public function __construct($account);
	public function next($account);
	public function prev($account);
	public function cancel();
	public function getState();
	//public function checkParam($check,$account);
	public function validState();


}
