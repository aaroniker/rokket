<?php

class host {
	
	protected $ssh;
	
	public function __construct($host, $user, $pass) {
		
		$this->ssh = new ssh($host, $user, $pass);
		
	}

	public function cpu() {
		
		$loads = sys_getloadavg();

		$cores = trim($this->ssh->exec("grep -P '^processor' /proc/cpuinfo|wc -l"));
		
		$load = round($loads[0] / ($cores + 1) * 100, 2);
	
		return [
			'num' => $cores,
			'load' => $load
		];
	}
	
	public function heat() {
	
		$heat_file = '/sys/class/thermal/thermal_zone0/temp';
	
		$label = "warning";
		
		$heat = 0;
		
		if(file_exists($heat_file)){
			$heat = round(file_get_contents($heat_file) / 1000,1);
	
			//OK
			if ($heat < 55)
				$label = "success";
	
			//WARNING
			if (($heat >= 55) && ($heat < 70))
				$label = "warning";
	
			//DANGER
			if ($heat >= 70)
				$label = "important";
		}
		
		return [
			'degrees' => $heat,
			'label' => $label
		];
	}
	
	public function disks() {
		
		$result = [];
	
		exec('lsblk --pairs', $disksArray);
	
		for ($i = 0; $i < count($disksArray); $i++) { 
	
			parse_str(str_replace(array('"',' '), array("","&"), $disksArray[$i]), $output);		
			$result[$i]['name'] = $output["NAME"];     
			$result[$i]['maj:min'] = $output["MAJ:MIN"];     
			$result[$i]['rm'] = $output["RM"];     
			$result[$i]['size'] = $output["SIZE"];     
			$result[$i]['ro'] = $output["RO"];     
			$result[$i]['type'] = $output["TYPE"];     
			$result[$i]['mountpoint'] = $output["MOUNTPOINT"];     
		
		}
	
		return $result;
	} 
	
	public function ram() {
		
		//$result['percentage'] >= '80'  = 'danger'
		
		exec('free -mo', $out);
		
		preg_match_all('/\s+([0-9]+)/', $out[1], $matches);
		
		list($total, $used, $free, $shared, $buffers, $cached) = $matches[1];
		
		return array(
			'free' => $free + $buffers + $cached,
			'percentage' => $total == 0 ? 0:round(($used - $buffers - $cached) / $total * 100),
			'total'  => $total,
			'used' => $used - $buffers - $cached,
			'detail' => $this->ssh->exec('ps -e -o pmem,user,args --sort=-pmem | sed "/^ 0.0 /d" | head -5')
		);
	}
	
	public function swap() {
	
		//$result['percentage'] >= '80' = danger
	
		exec('free -mo', $out);
		
		preg_match_all('/\s+([0-9]+)/', $out[2], $matches);
	
		list($total, $used, $free) = $matches[1]; 
	
		return [
			'percentage' => round($used / $total * 100),
			'free' => $free,
			'used' => $used,
			'total' => $total
		];
	}
	
	public function gpio() {
	
		$gpios = array();
		
		for($i=0;$i<25;$i++){
			$gpios[$i] = $this->ssh->exec("/usr/local/bin/gpio read ".$i);
		}
	
		return $gpios;
	}
	
	public function connections() {
	
		//$connections >= 50 = 'warning'
		
		$connections = $this->ssh->exec("netstat -nta --inet | wc -l");
	
		$connections--;
		
		return substr($connections, 0, -1);
	}
	
	public function ethernet() {
	
		$data = str_ireplace(array("TX bytes:","RX bytes:"), "", $this->ssh->exec("/sbin/ifconfig eth0 | grep RX\ bytes"));
		$data =  explode(" ", trim($data));
	
		return array(
			'up' => round($data[4] / 1024 / 1024,2),
			'down' => round($data[0] / 1024 / 1024,2)
		);
	}
	
	public function distribution() {
		
		$distroTypeRaw = $this->ssh->exec("cat /etc/*-release | grep PRETTY_NAME=");
	
		return str_ireplace(array('PRETTY_NAME="','"'), '', $distroTypeRaw);
	}
	
	public function kernel() {
		return $this->ssh->exec("uname -mrs");
	}
	
	public function firmware() {
		return $this->ssh->exec("uname -v");
	}
	
	public function hostname($full = false) {
		return $full ? $this->ssh->exec("hostname -f") : gethostname();
	}
	
	public function hdd() {
		
		//$result[$i]['percentage'] > '80' = danger
	
		$result = array();
	
		exec('df -T | grep -vE "tmpfs|rootfs|Filesystem"', $drivesarray);
	
		for ($i=0; $i<count($drivesarray); $i++) {
	
			$drivesarray[$i] = preg_replace('!\s+!', ' ', $drivesarray[$i]);
		
			preg_match_all('/\S+/', $drivesarray[$i], $drivedetails);
			
			list($fs, $type, $size, $used, $available, $percentage, $mounted) = $drivedetails[0];
			
			$result[$i] = array(
				'name' => $mounted,
				'total' => self::convertSize($size),
				'free' => self::convertSize($available),
				'used' => self::convertSize($size - $available),
				'format' => $type,
				'percentage' => rtrim($percentage, '%')
			); 
			
		}
	
		return $result;
	}
	
	public function services() {
	
		$result = [];
		
		exec('/usr/sbin/service --status-all', $servicesArray);
		
		for ($i = 0; $i < count($servicesArray); $i++) {
			
			$servicesArray[$i] = preg_replace('!\s+!', ' ', $servicesArray[$i]);
			
			preg_match_all('/\S+/', $servicesArray[$i], $serviceDetails);
			
			list($bracket1, $result[$i]['status'], $bracket2, $result[$i]['name']) = $serviceDetails[0];
			
			$result[$i]['status'] = ($result[$i]['status']=='+'?true:false);
		}
		
		return $result;
	}
	
	public function uptime() {
	
		$uptime = $this->ssh->exec("cat /proc/uptime");
		$uptime = explode(" ", $uptime); 
	
		return self::convertTime($uptime[0]);
	}
	
	protected function convertTime($seconds) {
	
		$y = floor($seconds / 60/60/24/365);
		$d = floor($seconds / 60/60/24) % 365;
		$h = floor(($seconds / 3600) % 24);
		$m = floor(($seconds / 60) % 60);
		$s = $seconds % 60;
	
		$return = [];
	
		if ($y > 0) {
			$yw = $y > 1 ? lang::get('years') : lang::get('year');
			$return[] = [$y, $yw];
		}
	
		if ($d > 0) {
			$dw = $d > 1 ? lang::get('days') : lang::get('day');
			$return[] = [$d, $dw];
		}
	
		if ($h > 0) {
			$hw = $h > 1 ? lang::get('hours') : lang::get('hour');
			$return[] = [$h, $hw];
		}
	
		if ($m > 0) {
			$mw = $m > 1 ? lang::get('minutes') : lang::get('minute');
			$return[] = [$m, $mw];
		}
	
		if ($s > 0) {
			$sw = $s > 1 ? lang::get('seconds') : lang::get('second');
			$return[] = [$s, $sw];
		}
	
		return $return;
	}
	
	public static function convertSize($kSize){
	
		$unit = array('KB', 'MB', 'GB', 'TB');
		$i = 0;
		
		$size = $kSize;
	
		while($i < 3 && $size > 1024){
			$i++;
			$size = $size / 1024;
		}
		
		return round($size, 2).$unit[$i];
	}
	
	protected function loadUrl($url){
		
		if(function_exists('curl_init')){
		
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($curl);
			curl_close($curl);
			
			return trim($content);
		
		} elseif(function_exists('file_get_contents'))
			return trim(file_get_contents($url));
		else
			return false;
				
	}

}

?>