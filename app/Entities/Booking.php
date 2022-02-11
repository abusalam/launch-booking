<?php namespace App\Entities;

use CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

/**
 * Handles CRUD operation of Assignments
 *
 * @package ePathshala
 */
class Booking extends Entity
{
	/**
	 * This is to provide access to the time slot of this booking
	 *
	 * @var object
	 */
	protected $timeSlot;

	private function timeSlots($date, $duration, $cleanup, $start, $end){
		$start = new DateTime($start);
		$end = new DateTime($end);
		$interval = new DateInterval("PT".$duration."M");
		$cleanupInterval = new DateInterval("PT".$cleanup."M");
		$slots = array();
		
		for($intStart = $start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
			$endPeriod = clone $intStart;
			$endPeriod->add($interval);
			if($endPeriod>$end){
				break;
			}
			
			$slots[] = $date .' => '. $intStart->format("H:iA")." - ". $endPeriod->format("H:iA");
			
		}
		
		return $slots;
	}

	public function getTimeSlots()
	{
		$slots = [];
		array_push($slots,$this->timeSlots(date('d/m/Y'),120,0,'08:00','12:00'));
		array_push($slots,$this->timeSlots(date('d/m/Y'),120,0,'16:00','20:00'));
		$this->timeSlot = $slots;
		return $this->timeSlot;
	}

	public function getStatus()
	{
		return $this->attributes['status'] ?? '';
	}

}
