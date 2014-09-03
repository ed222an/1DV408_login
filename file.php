<?php

/**
 * Class File
 */
class File{
	/**
	 * @var string
	 */
	private $file = 'file.txt';

	/**
	 * @param $data
	 * @param bool $new
	 */
	public function write($data, $new = false){
		if($new){
			$handle = fopen($this->file, 'w');
		}else {
			$handle = fopen($this->file, 'a');
		}
		if($data != '') {
			fwrite($handle, $data . '.');
		}
		fclose($handle);
	}

	/**
	 *
	 */
	public function createFile(){
		if(!file_exists($this->file)) {
			$handle = fopen($this->file, 'w');
			fclose($handle);
		}else{
			$this->checkrows();
		}
	}

	/**
	 * @return array|string
	 */
	private function getData(){
		$handle = fopen($this->file, 'r');
		$data = array();
		if((filesize($this->file))) {
			$data = fread($handle, filesize($this->file));
			$data = explode('.', $data);
			unset($data[count($data) - 1]);
		}
		fclose($handle);
		return $data;
	}

	/**
	 *
	 */
	public function checkrows(){
		$data = $this->getData();
		$SaveData = array();
		for($i = 0; $i < count($data); $i++){
			$dataColumns = explode('/', $data[$i]);
			if(count($dataColumns) == 2) {
				if($dataColumns[1] > strtotime(date('Y-m-d H:i:s')) ) {
					$SaveData[] = $data[$i];
				}
			}
		}
		$SaveData = implode('.', $SaveData);
		if($SaveData != '.') {
			$this->write($SaveData, true);
		}
	}

	/**
	 * @param $cookieValue
	 * @return bool
	 */
	public function cookieIsOk($cookieValue){
		$data = $this->getData();
		for($i = 0; $i < count($data); $i++){
			if($cookieValue == $data[$i]){
				return true;
			}
		}
		return false;
	}




}