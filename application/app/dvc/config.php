<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
NameSpace dvc;

abstract class config extends _config {
	static $WEBNAME = 'Exchange Workbench';
	static $DB_TYPE = 'sqlite';

	static $exchange_server;
	static $exchange_timezone = 'E. Australia Standard Time';
	static $exchange_verifySSL = FALSE;

	const use_inline_logon = TRUE;

}

pages\bootstrap::$BootStrap_Version = '4';
