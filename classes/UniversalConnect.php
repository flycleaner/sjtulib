<?php
include_once('IConnectInfo.php');

class UniversalConnect implements IConnectInfo
{
	private  $server=IConnectInfo::HOST;
	private $currentDB=IConnectInfo::DBNAME;
	private $user = IConnectInfo::UNAME;
	private $pass = IConnectInfo::PW;
	static private $hookup;
	
	function __construct($server='',$currentDB='',$user='',$pass=''){
		if($server!='')
			$this->server = $server;
		if($currentDB!='')
			$this->currentDB = $currentDB;
		if($user!='')
			$this->user=$user;
		if($pass!='')
			$this->pass = $pass;
		$this->doConnect();
	}
	public function doConnect()
	{
		self::$hookup=@mysql_connect($this->server, $this->user, $this->pass);
		@mysql_select_db($this->currentDB,self::$hookup);
		@mysql_query('set names utf8');
	}
	public function destroyConnection() {
		self::$hookup->kill(self::$hookup->tread_id);
		self::$hookup->close();
	}
	/*锟斤拷锟斤拷锟�sql为select锟斤拷锟�,锟斤拷锟截讹拷维锟斤拷锟介， 
	 *$theres[0][property1], $theres[0][property2]...
	 *$theres[1][property1], $theres[1][property2]...
	 *...
	 */
	static function justquery($sql){
		if(self::$hookup == ''){
			$this->doConnect();
		}
		$result = mysql_query($sql,self::$hookup);
		return $result;
	}
	
	static function doSql($sql)
	{
		if(self::$hookup == ''){
			$this->doConnect();
		}
		$SQLN = explode(' ', $sql);
		$res = mysql_query($sql,self::$hookup);
		if(empty($res)) return false;
		if($SQLN[0] == 'select'){
			$theres = array();
			$i=0;
			while($row = mysql_fetch_array($res,MYSQL_ASSOC)){
				$theres[$i] = $row;
				$i++;
			}
			if(count($theres) != 0){
				return $theres;
			} else {
				return false;
			}
		} else {
			return $res;
		}
	}
}

?>