<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
NameSpace dvc;

class user extends _user {
	var $id = 0;
	protected $dto = FALSE;

	public function __construct() {
		if ( ( $id = (int)session::get('uid')) > 0 ) {
			$dao = new \dao\users;
			if ( $this->dto = $dao->getByID( $id)) {
				$this->id = $this->dto->id;
				$this->name = $this->dto->name;
				$this->exchange_alias = $this->dto->exchange_alias;
				$this->exchange_account = $this->dto->exchange_account;
				$this->exchange_password = $this->dto->exchange_password;
				$this->exchange_server = $this->dto->exchange_server;

			}

		}

	}

	public function valid() {
		/**
		 * if this function returns true you are logged in
		 */

		return ( $this->id > 0);

	}

}
