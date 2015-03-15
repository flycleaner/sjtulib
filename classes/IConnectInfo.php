<?php

interface IConnectInfo
{
	const HOST = "localhost";
	const UNAME = "root";
	const PW = "root";
	const DBNAME = "SJTULibrary";
	
	public function doConnect();
	}

?>