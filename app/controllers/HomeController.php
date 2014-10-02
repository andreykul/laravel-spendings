<?php

class HomeController extends BaseController {

	public function index()
	{
		$this->data['spendings'] = Spending::all();
		return View::make('spendings', $this->data);
	}

}
