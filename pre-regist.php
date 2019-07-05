<?php

error_reporting(0);

class ghost{
	
	public $source_api = "https://www.ubeejoy.com/activity/yuyue/reserve.json?";
	
	public $fileListEmail = null;
	
	public $limit = 1;

	protected function Curl($link = null){

			$ch = curl_init(); 

			// set url 
			curl_setopt($ch, CURLOPT_URL, $this->source_api.$link); 

			//return the transfer as a string 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

			// $output contains the output string 
			$output = curl_exec($ch); 

			// close curl resource to free up system resources 
			curl_close($ch);

		return $output;
	}

	protected function getEmailList(){

		// $get_content = implode(null,file($this->fileListEmail));
		// $split = explode("\n",$get_content);
		// return $split;
		$handle = fopen($this->fileListEmail, "r");
		while (!feof($handle)) {

			$r = trim(fgets($handle));

			yield $r;
		}
	}

	protected function generateVerification(){

		foreach($this->getEmailList() as $key => $val){

			$generate = $this->Curl("email=".$val."&actId=3&lang=en");	
			$fetch_json = json_decode($generate);
			
			if(isset($fetch_json->data)){

				if($fetch_json->data->detail == "success"){

					$result = "email : ".$val." => Pre-Regist sukses\n";

				}else{

					$result = "email : ".$val." => Gagal, coba lagi\n";

				}
				
			}else{
				
				$result = "email : ".$val." => Udah kepake gblk\n";
			
			}
			
			echo $result;
			
			usleep(300);
			
			if(($key+1) == $this->limit) break;
			
		}

	}
	
	function start(){
		
		return $this->generateVerification();
		
	}

}

echo "Nama File List Emailnya : ";
$path_file = trim(fgets(STDIN));
echo "Jumlah Proses           : ";
$counter = trim(fgets(STDIN));
$call = new ghost();
$call->fileListEmail = $path_file;
$call->limit = $counter;
echo $call->start();
