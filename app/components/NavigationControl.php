<?php

namespace App\Presenters;

use Nette;

class NavigationControl extends Nette\Application\UI\Control
{

	public function render()
	{
		$this->template->setFile(__DIR__ . '/NavigationControl.latte');
		$this->template->render();
	}

}
