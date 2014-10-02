<?php

class SpendingsController extends BaseController {

	public function store()
	{
		Spending::create(Input::get('spending'));
		return Redirect::to('/');
	}

}