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


	// $nowDate = date("Y-m-d h:i:sa");

	// $start = '21:39:35';
	// $end   = '25:39:35';
	// $time = date("H:i:s", strtotime($nowDate));

	// $this->isWithInTime($start, $end, $time);


	private function isWithInTime($start, $end, $time)
	{

		if (($time >= $start) && ($time <= $end)) {
			// echo 'OK';
				return TRUE;
		} else {
				//echo 'Not OK';
				return FALSE;
		}

	}


	private function getTimeSlot($date, $duration, $cleanup, $start, $end){
		$start = new \DateTime($start);
		$end = new \DateTime($end);
		$interval = new \DateInterval("PT".$duration."M");
		$cleanupInterval = new \DateInterval("PT".$cleanup."M");
		$slots = array();
		
		for($intStart = $start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
			$endPeriod = clone $intStart;
			$endPeriod->add($interval);
			if($endPeriod>$end){
				break;
			}
			
			$slots[] = \DateTime::createFromFormat("Y-m-d H:i:s", $date)->format("d/m/Y") .' ['. $intStart->format("H:iA")." - ". $endPeriod->format("H:iA") . ']';
			
		}
		
		return $slots;
	}

	public function getTimeSlots()
	{
		$slots = [];
		array_push($slots,$this->getTimeSlot(date('Y-m-d H:i:s'),120,0,'08:00','12:00'));
		array_push($slots,$this->getTimeSlot(date('Y-m-d H:i:s'),120,0,'16:00','20:00'));
		$this->timeSlot = $slots;
		return $this->timeSlot;
	}

	public function getBookedSlot()
	{
		$slot = $this->getTimeSlot(
			$this->attributes['booking_date'],
			$this->attributes['hours'] * 60,
			0,
			$this->attributes['start_time'],
			'12:00'
		);
		return $slot[0] ?? '';
	}

	public function getPassenger()
	{
		return $this->attributes['passenger'] ?? '';
	}

	public function getMobile()
	{
		return $this->attributes['mobile'] ?? '';
	}

	public function getAddress()
	{
		return $this->attributes['address'] ?? '';
	}

	public function getPurpose()
	{
		return $this->attributes['purpose'] ?? '';
	}

	public function setStatus($status)
	{
		$this->attributes['status']=$status;
		return $this;
	}

	public function getStatus()
	{
		return $this->attributes['status'] ?? '';
	}

	public function getAmount()
	{
		return $this->attributes['amount'] ?? '';
	}

	public function setAmount($hours=2)
	{
		if ($hours >= env('BOAT_MIN_HOURS') )
		{
			$this->setHours($hours);
			$this->attributes['amount'] = $hours * env('BOAT_RATE');
		}
		return $this;
	}

	public function setDate($date)
	{
		$this->attributes['booking_date'] = \DateTime::createFromFormat("d/m/Y", $date)->format("Y-m-d");
		return $this;
	}

	public function setStartTime($startTime)
	{
		$this->attributes['start_time'] = $startTime;
		return $this;
	}

	public function setHours($hours)
	{
		$this->attributes['hours'] = $hours;
		return $this;
	}

	public function setPgResp($pg_resp)
	{
		$this->attributes['pg_resp'] = json_encode($pg_resp);
		return $this;
	}

}
