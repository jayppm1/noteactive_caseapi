<?php
class Modelapicache extends Model {
	
	public function setcache($cacheid, $cadata) {
		$cresult = $this->cache->set($cacheid,$cadata);
		return $cresult;
	}
	public function getcache($cacheid) {
		$cresult = $this->cache->get($cacheid);
		return $cresult;
	}
	public function deletecache($cacheid) {
		$this->cache->delete($cacheid); 
		
	}
}
	
	