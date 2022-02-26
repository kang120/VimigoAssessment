<?php

namespace App\Controllers;

class Blog extends BaseController
{
	public function home()
	{
		return view('post_list');
	}
}
