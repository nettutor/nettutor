<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	/**
	 * @return \App\Presenters\NavigationControl
	 */
	public function createComponentNavigation()
	{
		$control = new NavigationControl();
		return $control;
	}

}
