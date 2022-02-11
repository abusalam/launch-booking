<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Entities\Booking;

class BookingController extends BaseController
{
    public function index()
    {
        //
    }

    public function tryToBook()
	{
		$bookingModel = new BookingModel();
		$newBooking   = new Booking();
		$newBooking->fill($this->request->getPost());

		if (! $bookingModel->save($newBooking))
		{
			return redirect()->back()->withInput()->with('errors', $bookingModel->errors());
		}
		return redirect()->to(base_url(route_to('make-payment')));
	}
}
