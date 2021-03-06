<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
NameSpace ews;

class calendaritem {
	var $start = '',
		$end = '',
		$location = '',
		$subject = '',
		$notes = '',
		$startUTC = '',
		$endUTC = '',
		$timelabel = '',
		$invitees = '';

	public function tostring() {
		return ( sprintf( '%s - %s : Subject: %s, Location: %s :: utc: %s - %s',
			$this->start,
			$this->end,
			$this->subject,
			$this->location,
			$this->startUTC,
			$this->endUTC));

	}

}
