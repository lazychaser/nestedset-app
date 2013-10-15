<?php

use dflydev\markdown\MarkdownParser;

class HomeController extends BaseController {

	public $layout = 'layouts.front';

	/**
	 * The page storage.
	 *
	 * @var  Page
	 */	
	protected $page;

	/**
	 * Markdown parser.
	 *
	 * @var dflydev\markdown\MarkdownParser;
	 */
	protected $markdown;

	public function __construct(Page $page, MarkdownParser $markdown)
	{
		$this->page = $page;
		$this->markdown = $markdown;

		$this->setupLayout();
	}

	/**
	 * Display a page with given slug.
	 *
	 * @param   string  $slug
	 *
	 * @return  mixed
	 */
	public function showPage($slug = '/')
	{
		$page = $this->page->whereSlug($slug)->first();

		if ($page === null)
		{
			App::abort(404, 'Sorry, but requested page doesn\'t exists.');
		}

		return $this->displayPage($page);
	}

	/**
	 * Get view of specified page.
	 *
	 * @param   Page    $page
	 *
	 * @return  \Illuminate\View\View
	 */
	protected function displayPage(Page $page)
	{
		$page->body = $this->markdown->transformMarkdown($page->body);

		$content = View::make('home.page', compact('page'));

		if (!$page->isRoot())
		{
			$content->with(array(
				'contents' => make_contents($page->getContents(), $page),
				'next' => $page->getNext(),
				'prev' => $page->getPrev(),
			));
		}

        return $this->layout
        	->withTitle($page->title)
        	->withBreadcrumbs($this->getBreadcrumbs($page))
        	->withContent($content);
	}

	/**
	 * Get breadcrumbs to the current page.
	 *
	 * $active is last crumb (the page title by default).	
	 *
	 * @param   Page    $page
	 * @param   string  $active
	 * @param 	string  $route
	 *
	 * @return  array
	 */
	protected function getBreadcrumbs(Page $page, $active = null, $route = 'page')
	{
		if ($page->isRoot()) return array();

		Debugbar::startMeasure('breadcrumbs');

		$breadcrumbs['Index'] = url('/');
		$ancestors = $page->ancestors()->select('id', 'title')->withoutRoot()->get();

		if ($active !== null) $ancestors->push($page);

		foreach ($ancestors as $item) 
		{
			$breadcrumbs[$item->title] = route($route, array('slug' => $item->slug));
		}

		$breadcrumbs[] = $active !== null ? $active : $page->title;

		Debugbar::stopMeasure('breadcrumbs');

		return $breadcrumbs;
	}

	/**
	 * Get main menu items.
	 *
	 * @return  array
	 */
	protected function getMenu()
	{
		Debugbar::startMeasure('menu');

		$items = $this->page
			->select('slug', 'title', '_lft', 'parent_id')
			->withoutRoot()
			->withDepth()
			->having('depth', '<=', 2)
			->get()
			->toTree()
			->toArray();

		Debugbar::stopMeasure('menu');

		return $items;
	}

	/**
	 * Setup layout.
	 *
	 * @return  void
	 */
	protected function setupLayout()
	{
		parent::setupLayout();

		if (is_object($this->layout))
		{
        	$this->layout->with('menu', $this->getMenu());	
		}
	}
}