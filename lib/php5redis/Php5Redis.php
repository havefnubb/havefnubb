<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
class Php5RedisException extends Exception{}
class Php5Redis{
	private $port;
	private $host;
	private $_sock;
	public $debug=false;
	function __construct($host='localhost',$port=6379){
		$this->host=$host;
		$this->port=$port;
	}
	function __destruct(){
		$this->disconnect();
	}
	private function connect(){
		if($this->_sock)
			return;
		if($sock=fsockopen($this->host,$this->port,$errno,$errstr)){
			$this->_sock=$sock;
			$this->debug('Connected');
			return;
		}
		$msg="Cannot open socket to {$this->host}:{$this->port}";
		if($errno||$errmsg)
			$msg.="," .($errno ? " error $errno" : "").($errmsg ? " $errmsg" : "");
		throw new Php5RedisException("$msg.");
	}
	private function debug($msg){
		if($this->debug)echo sprintf("[Php5Redis] %s\n",$msg);
	}
	private function read($len=1024){
		if($s=fgets($this->_sock)){
			$this->debug('Read: '.$s.' ('.strlen($s).' bytes)');
			return $s;
		}
		$this->disconnect();
		throw new Php5RedisException("Cannot read from socket.");
	}
	private function cmdResponse(){
		$s=trim($this->read());
		switch($s [0]){
			case '-' :
				throw new Php5RedisException(substr($s,1));
				break;
			case '+' :
				return substr($s,1);
			case ':' :
				return substr($s,1)+ 0;
			case '$' :
				$i=( int )(substr($s,1));
				if($i==- 1)
					return null;
				$buffer='';
				if($i==0){
					$s=$this->read();
				}
				while($i > 0){
					$s=$this->read();
					$l=strlen($s);
					$i-=$l;
					if($i < 0)
						$s=substr($s,0,$i);
					$buffer.=$s;
				}
				return $buffer;
				break;
			case '*' :
				$i=( int )(substr($s,1));
				if($i==- 1)
					return null;
				$res=array();
				for($c=0;$c < $i;$c ++){
					$res []=$this->cmdResponse();
				}
				return $res;
				break;
			default :
				throw new Php5RedisException('Unknown responce line: ' . $s);
				break;
		}
	}
	private function cmd($command,$readResp=true){
		$this->debug('Command: '.$command);
		$this->connect();
		$s=$command . "\r\n";
		while($s){
			$i=fwrite($this->_sock,$s);
			if($i==0)
				break;
			$s=substr($s,$i);
		}
		if($readResp)
			return $this->cmdResponse();
		else
			return '';
	}
	function disconnect(){
		if($this->_sock)
			@fclose($this->_sock);
		$this->_sock=null;
	}
	/**
	 * close the connection
	 * 
	 * Ask the server to silently close the connection. 
	 * 
	 * @return void The connection is closed as soon as the QUIT command is received. 
	 */
	function quit(){
		$this->cmd('QUIT',false);
		$this->_sock=null;
	}
	function auth($password){
		return $this->cmd('AUTH '.$password);
	}
	function set($key,$value,$preserve=false){
		return $this->cmd(($preserve ? 'SETNX' : 'SET'). " $key " . strlen($value). "\r\n$value");
	}
	function get($key){
		$args=func_get_args();
		if(count($args)> 1){
			$key=$args;
		}
		if(is_array($key)){
			return $this->cmd("MGET ".join(' ',$key));
		}
		else{
			return $this->cmd("GET $key");
		}
	}
	function __get($key){
		return $this->get($key);
	}
	function __set($key,$value){
		return $this->set($key,$value);
	}
	function getset($key,$value){
		return $this->cmd("GETSET $key ".strlen($value)."\r\n".$value);
	}
	function incr($key,$amount=1){
		if($amount==1)
			return $this->cmd("INCR $key");
		else
			return $this->cmd("INCRBY $key $amount");
	}
	function decr($key,$amount=1){
		if($amount==1)
			return $this->cmd("DECR $key");
		else
			return $this->cmd("DECRBY $key $amount");
	}
	function exists($key){
		return $this->cmd("EXISTS $key");
	}
	function delete($key){
		return $this->cmd("DEL $key");
	}
	function type($key){
		return $this->cms("TYPE $key");
	}
	function keys($pattern){
		return $this->cmd("KEYS $pattern");
	}
	function randomkey(){
		return $this->cmd("RANDOMKEY");
	}
	function rename($src,$dst,$preserve=False){
		if($preserve){
			return $this->cmd("RENAMENX $src $dst");
		}
		return $this->cmd("RENAME $src $dst");
	}
	function dbsize(){
		return $this->cmd("DBSIZE");
	}
	function expire($key,$ttl){
		return $this->cmd("EXPIRE $key $ttl");
	}
	function ttl($key){
		return $this->cmd("TTL $key");
	}
	function push($key,$value,$tail=true){
		return $this->cmd(($tail ? 'RPUSH' : 'LPUSH'). " $key " . strlen($value). "\r\n$value");
	}
	function llen($key){
		return $this->cmd("LLEN $key");
	}
	function lrange($key,$start,$end){
		return $this->cmd("LRANGE $key $start $end");
	}
	function ltrim($key,$start,$end){
		return $this->cmd("LTRIM $key $start $end");
	}
	function lindex($key,$index){
		return $this->cmd("LINDEX $key $index");
		return $this->_get_value();
	}
	function lset($key,$value,$index){
		return $this->cmd("LSET $key $index " . strlen($value). "\r\n$value");
	}
	function lrem($key,$value,$count=1){
		return $this->cmd("LREM $key $count " . strlen($value). "\r\n$value");
	}
	function pop($key,$tail=true){
		return $this->cmd(($tail ? 'RPOP' : 'LPOP'). " $key");
	}
	function sadd($key,$value){
		return $this->cmd("SADD $key " . strlen($value). "\r\n$value");
	}
	function srem($key,$value){
		return $this->cmd("SREM $key " . strlen($value). "\r\n$value");
	}
	function spop($key){
		return $this->cmd("SPOP $key");
	}
	function smove($srckey,$dstkey,$member){
		$this->cmd("SMOVE $srckey $dstkey " . strlen($member). "\r\n$member");
	}
	function scard($key){
		return $this->cmd("SCARD $key");
	}
	function sismember($key,$value){
		return $this->cmd("SISMEMBER $key " . strlen($value). "\r\n$value");
	}
	function sinter($key1){
		if(is_array($key1)){
			$sets=$key1;
		}
		else{
			$sets=func_get_args();
		}
		return $this->cmd('SINTER ' . implode(' ',$sets));
	}
	function sinterstore($dstkey,$key1){
		if(is_array($key1)){
			$sets=$key1;
		}
		else{
			$sets=func_get_args();
			array_shift($sets);
		}
		return $this->cmd('SINTERSTORE ' . $dstkey . ' ' . implode(' ',$sets). "");
	}
	function sunion($key1){
		if(is_array($key1)){
			$sets=$key1;
		}
		else{
			$sets=func_get_args();
		}
		return $this->cmd('SUNION ' . implode(' ',$sets). "");
	}
	function sunionstore($dstkey,$key1){
		if(is_array($key1)){
			$sets=$key1;
		}
		else{
			$sets=func_get_args();
			array_shift($sets);
		}
		return $this->cmd('SUNIONSTORE ' . $dstkey . ' ' . implode(' ',$sets). "");
	}
	function sdiff($key1){
		if(is_array($key1)){
			$sets=$key1;
		}
		else{
			$sets=func_get_args();
		}
		return $this->cmd('SDIFF ' . implode(' ',$sets). "");
	}
	function sdiffstore($dstkey,$key1){
		if(is_array($key1)){
			$sets=$key1;
		}
		else{
			$sets=func_get_args();
			array_shift($sets);
		}
		return $this->cmd('SDIFFSTORE ' . $dstkey . ' ' . implode(' ',$sets). "");
	}
	function smembers($key){
		return $this->cmd("SMEMBERS $key");
	}
	function select_db($key){
		return $this->cmd("SELECT $key");
	}
	function move($key,$db){
		return $this->cmd("MOVE $key $db");
	}
	function flushdb(){
		return $this->cmd("FLUSHDB");
	}
	function flushall(){
		return $this->cmd("FLUSHALL");
	}
	function sort($key,$query=false){
		if($query===false){
			return $this->cmd("SORT $key");
		}else{
			return $this->cmd("SORT $key $query");
		}
	}
	function save($background=false){
		return $this->cmd(($background ? "BGSAVE" : "SAVE"));
	}
	function lastsave(){
		return $this->cmd("LASTSAVE");
	}
	function shutdown(){
		return $this->cmd("SHUTDOWN");
	}
	function info(){
		return $this->cmd("INFO");
	}
	function slaveof($host=null,$port=6379){
		return $this->cmd('SLAVEOF '.($host?"$host $port":'no one'));
	}
	function ping(){
		return $this->cmd("PING");
	}
	function do_echo($s){
		return $this->cmd("ECHO " . strlen($s). "\r\n$s");
	}
}
