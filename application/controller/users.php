<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class users extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');
		//~ sys::dump( $this->getPost());

		if ( $action == 'save/update') {
			$dao = new dao\users;
			$id = (int)$this->getPost('id');

			$a = [
				'updated' => db::dbTimeStamp(),
				'name' => $this->getPost('name'),
				'exchange_server' => $this->getPost('exchange_server'),
				'exchange_alias' => $this->getPost('exchange_alias'),
				'exchange_account' => $this->getPost('exchange_account'),
				'email' => $this->getPost('email')];

			if ( $xp = $this->getPost('exchange_password'))
				$a['exchange_password'] = $xp;

			if ( $pass = $this->getPost('pass'))
				$a['pass'] = password_hash( $pass, PASSWORD_DEFAULT);


			if ( $id) {
				$dao->UpdateByID( $a, $id);
				Response::redirect( 'users', 'updated user');

			}
			else {
				/** ensure username is unique */
				$a['created'] = db::dbTimeStamp();
				$a['username'] = strtolower( $this->getPost('username'));

				if ( $dto = $dao->getUserByUserName( $a['username'])) {
					Response::redirect( 'users', 'user already exists');

				}
				else {
					$dao->Insert( $a);
					Response::redirect( 'users', 'added user');

				}

			}

		}
		elseif ( $action == 'delete') {

			if ( $id = (int)$this->getPost('id')) {
				$dao = new dao\users;
				$dao->delete( $id);
				\Json::ack( 'deleted user');

			}
			else {
				\Json::nak( 'invalid id');

			}

		}
		elseif ( $action == 'check-exchange-password') {
			$user = $this->Request->getPost( 'user' );
			$pass = $this->Request->getPost( 'pass' );
			$server = $this->Request->getPost( 'server' );
			$creds = new ews\credentials( $user, $pass, $server);
			// sys::dump($creds);

			try {
				$client = ews\client::instance( $creds);
				\json::ack( $action);

			}
			catch ( \Exception $e) {
				\json::nak( $e->getMessage());

			}


		}

	}

	function __construct( $rootPath) {
		$this->RequireValidation = \sys::lockdown();
		parent::__construct( $rootPath);

	}

	function index() {
		if ( $this->isPost()) {
			$this->postHandler();

		}
		else {
			$dao = new dao\users;
			$this->data = $dao->getAll();
			//~ sys::dump( $this->data);

			$p = new page( $this->title = 'Users');
				$p
					->header()
					->title();

				$p->primary();
					$this->load('report');

				$p->secondary();
					$this->load('index');
					$this->load('main-index');

		}

	}

	function edit( $id = 0) {
		$this->data = (object)[
			'dto' => (object)[
				'id' => 0,
				'username' => '',
				'name' => '',
				'email' => '']];

		if ( $id) {
			$dao = new dao\users;
			if ( $dto = $dao->getByID( $id)) {
				$this->data = (object)['dto' => $dto];

			}
			else {
				throw new \Exception( 'user not found');

			}

		}

		$p = new page( $this->title = 'User');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('edit');

			$p->secondary();
				$this->load('index');

	}

}
