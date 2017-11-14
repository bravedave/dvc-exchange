<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class ews extends Controller {
	protected function postHandler() {
		$debug = TRUE;
		$creds = NULL;

		$date = $this->getPost('start');
		$end = $this->getPost('end');
		//~ if ( $debug) sys::logger( sprintf( 'ews\j :: %s - %s (raw)', $date, $end));

		if ( strtotime( $date) <= 0)
			$date = date( 'Y-m-dT00:00:00O');
		if ( strtotime( $end) <= 0)
			$end = date( 'Y-m-dT00:00:00O', strtotime('+7 days'));

		if ( $debug) sys::logger( sprintf( 'ews\j :: start : %s - %s', $date, $end));

		$x = ews\calendar::agenda( (object)[
			'start' => $date,
			'end' => $end ], $creds);

			//~ sys::dump( $x);

		$j = \Json::ack( 'ews')
			->add( 'start', date( 'Y-m-d', strtotime( $date)))
			->add( 'end', date( 'Y-m-d', strtotime( $end)))
			;


			//~ return;

		if ( $debug) sys::logger( sprintf( 'ews\j :: agenda done : %s - %s', $date, $end));
		foreach ( $x->items as $item) {
			$title = $item->subject;
			if ($item->location)
			$title .= ' ::' . $item->location;

			$j->append([
				'title' => $title,
				'location' => $item->location,
				'notes' => $item->notes,
				'start' => $item->startUTC,
				'end' => $item->endUTC,
				'id' => $item->item->ItemId->Id,
				'changekey' => $item->changekey

			]);

		}

	}

	protected function _index( ) {}

	public function fcj( $user = 0, $itemID = '') {
		$debug = $this->debug;
		//~ $debug = TRUE;

		$j = new Json();
		try {

			if ( $debug) sys::logger( 'calendar\fcj');

			$creds = NULL;
			if ( (int)$user > 0) {
				if ( $debug) sys::logger( '   for user :: ' . $user);
				//~ sys::logger( 'user is 3');
				$dao = new dao\users;
				if ( $dto = $dao->getUserByID( $user)) {
					if ( $dto instanceof dao\dto\users)
						$creds = new ExchangeCredentials( $dto->exchange_account, $dto->exchange_password);

				}

			}

			if ( $this->isPost()) {
				$itemID = $this->getPost('id');
				if ( $itemID) {
					$CalendarItems = Exchange::GetItemByID( $itemID, $creds);
					$item = $CalendarItems->Items->CalendarItem;
					$j->add( 'title', $item->Subject );
					if ( isset( $item->Location)) $j->add( 'location', $item->Location );
					$j->add( 'notes', $item->Body->_ );
					$j->add( 'start', $item->Start );
					$j->add( 'end', $item->End );
					$j->add( 'id', $item->ItemId->Id );
					$j->add( 'changekey', $item->ItemId->ChangeKey);

					$property_diary = new dao\property_diary;
					if ( $dtoSet = $property_diary->getByItemID( $itemID)) {
						if ( count( $dtoSet)) {
							if ( $debug) sys::logger( 'fcj : found property_diary record for ' . $itemID);

							$dto = $dtoSet[0];
							$j->add( 'property_id', $dto->property_id);
							$j->add( 'person_id', $dto->people_id);
							$j->add( 'invited', $dto->invitees);
							if ( $dto->people_id) {
								if ( $debug) sys::logger( 'fcj : property_diary record has oerson ' . $dto->people_id);
								$daoP = new dao\people;
								if ( $dtoP = $daoP->getByID( $dto->people_id)) {
									$j->add('person', $dtoP->name);

								}

							}

							if ( $dto->property_id) {
								if ( $debug) sys::logger( 'fcj : property_diary record has property ' . $dto->property_id);
								$daoP = new dao\properties;
								if ( $dtoP = $daoP->getByID( $dto->property_id)) {
									if ( $debug) sys::logger( 'fcj : property_diary record has property - found ' . $dto->property_id);
									$j->add('street', $dtoP->address_street);

								}

							}

						}

					}
					else {
						sys::logger( 'fcj : could not find property_diary record');

					}

				}

			}
			elseif ( $itemID != '') {
				$CalendarItems = Exchange::GetItemByID( $itemID, $creds);
				//~ sys::dump( $CalendarItems);
				$item = $CalendarItems->Items->CalendarItem;
				//~ sys::dump( $item);
				$j->append( array(
					'title' => $item->Subject,
					'location' => $item->Location,
					'notes' => $item->Body->_,
					'start' => $item->Start,
					'end' => $item->End,
					'id' => $item->ItemId->Id,
					'changekey' => $item->ItemId->ChangeKey));


			}
			else {


			}

		}
		catch ( \Exception $e) {
			$j
				->add( 'response', 'nak')
				->add( 'description', $e->getMessage());

		}

		//~ sys::dump( $x->items);
		//~ sys::dump( $a);
		//~ print '<pre>';

	}

	public function calendar( ) {
		$p = new page( $this->title = sys::name());
			$p->scripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', url::tostring('js/moment.min.js'));
			$p->scripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', url::tostring('ews/js'));
		$p
			->header()
			->title()
			->primary();

			$this->load( 'calendar');

		$p->secondary();

			$this->load('main-index');

	}

	public function index( $data = '' ) {
		if ( $this->isPost()) {
			$this->postHandler();

		}
		else {
			$this->calendar();

		}

	}

	public function js() {
		ews\js::lib();

	}

}
