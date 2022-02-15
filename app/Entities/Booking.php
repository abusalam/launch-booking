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
		$slot = '';
		for($intStart = $start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
			$endPeriod = clone $intStart;
			$endPeriod->add($interval);
			if($endPeriod>$end){
				break;
			}
			$dateStr = \DateTime::createFromFormat("Y-m-d H:i:s", $date)->format("d/m/Y");
			$initStr = $intStart->format("H:iA");
			$endStr  = $endPeriod->format("H:iA");
			$slot = $dateStr .' ['. $initStr ." - ". $endStr . ']';
		}

		
		return $slot;
	}

	public function getSlot()
	{
		$start = new \DateTime($this->attributes['start_time']);
		$endTime = '12:00';
		$end = new \DateTime($endTime);

		if ($start > $end) {
			return 2;
		}
		return 1;
	}

	public function getBookedSlot()
	{
		$start = new \DateTime($this->attributes['start_time']);
		$endTime = '12:00';
		$end = new \DateTime($endTime);

		if ($start > $end) {
			$endTime = '20:00';
		}

		$slot = $this->getTimeSlot(
			$this->attributes['booking_date'],
			$this->attributes['hours'] * 60,
			0,
			$this->attributes['start_time'],
			$endTime
		);
		
		return $slot ?? '';
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

	public function isPaid()
	{
		return $this->attributes['status'] == 'SUCCESS';
	}

	public function isPaying()
	{
		return $this->attributes['status'] == 'pending';
	}

	public function isFailed()
	{
		return $this->attributes['status'] == 'FAILED';
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
		$this->attributes['booking_date'] = \DateTime::createFromFormat("d/m/Y", $date)->format("Y-m-d H:i:s");
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
