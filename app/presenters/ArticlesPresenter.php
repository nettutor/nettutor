<?php

namespace App\Presenters;

use App\Article\ArticleFacade;
use Nette;

class ArticlesPresenter extends BasePresenter
{

	/**
	 * @var string|null
	 */
	private $search;

	/**
	 * @var int|null
	 */
	private $author;

	/**
	 * @var \App\Article\ArticleFacade
	 */
	private $articleFacade;

	public function __construct(ArticleFacade $articleFacade)
	{
		$this->articleFacade = $articleFacade;
	}

	public function renderDefault()
	{
		$this->template->articles = $this->articleFacade->getArticlesByUserIdContains(
			$this->author !== null ? $this->author : null,
			$this->search
		);
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentFilterForm()
	{
		$authors = $this->getAuthors();

		$form = new Nette\Application\UI\Form();
		$form->getElementPrototype()->novalidate = true;
		$form->addText('search', 'Search')
			->addRule(Nette\Application\UI\Form::FILLED, 'VyplŇ to');
		$form->addSelect('author', 'Author', $authors)
			->setPrompt('-------');
		$form->addSubmit('_submit', 'Filter');
		$form->onError[] = function () {
			$this->flashMessage('Vyplnil jsi to totálně blbě');
		};
		$form->onSuccess[] = function (Nette\Application\UI\Form $form, Nette\Utils\ArrayHash $values) {
			$this->search = $values->search;
			$this->author = $values->author;

			$this->redirect('this');
		};
		$form->setDefaults(array(
			'search' => $this->search,
			'author' => $this->author,
		));

		return $form;
	}

	/**
	 * @return string[]
	 */
	private function getAuthors()
	{
		$authors = array();
		foreach ($this->articleFacade->getAuthors() as $author) {
			$authors[$author->getId()] = $author->getName();
		}

		return $authors;
	}

	public function loadState(array $params)
	{
		parent::loadState($params);

		$this->search = isset($params['search'])
			? $params['search']
			: null;
		$this->author = isset($params['author'])
			? (int) $params['author']
			: null;

		if ($this->search === '') {
			$this->search = null;
		}

		if ($this->author === '') {
			$this->author = null;
		}

		if ($this->author !== null && !array_key_exists($this->author, $this->getAuthors())) {
			$this->error();
		}
	}

	public function saveState(array & $params, $reflection = null)
	{
		parent::saveState($params, $reflection);

		if ($this->search !== null) {
			$params['search'] = $this->search;
		}

		if ($this->author !== null) {
			$params['author'] = $this->author;
		}
	}

}
