<?php

/**
 * Class File
 * fil hanteringen hittade jag på:
 * http://davidwalsh.name/basic-php-file-handling-create-open-read-write-append-close-delete
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
	public function write($data){
		$handle = fopen($this->file, 'a');
		if($data != '') {
			fwrite($handle, $data . '.');
		}
	}

	private function getData(){
		$data = @file($this->file);
		if($data != false) {
			$data = explode('.', $data);
			unset($data[count($data) - 1]);
		}
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
			$this->write($SaveData);
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