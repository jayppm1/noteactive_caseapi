<?php
class Modelsettinghoursminutes extends Model {
	public function hoursFunction() {
		$hours = range(0, 23);
		$hourarray = array();
		$hourarray[0] = 'HH';
		foreach( $hours as $hour ) {
			$hourarray[$hour+1] = $hour < 10 ? "0{$hour}" : "{$hour}";
		}
		
		return $hourarray;
	}
	
	public function minutesFunction() {
		$minutes = range(0, 59);
		$minutesarray = array();
		$minutesarray[0] = 'MM';
		foreach( $minutes as $minute ) {
			$minutesarray[$minute+1] = $minute < 10 ? "0{$minute}" : "{$minute}";
		}
		
		return $minutesarray;
	}
}
?>