<?php

use dflydev\markdown\MarkdownParser;

/**
 * This controller is used to display pages.
 */
class PageController extends BaseController {

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
	public function show($slug = '/')
	{
		$page = $this->page->whereSlug($slug)->first();

		if ($page === null)
		{
			App::abort(404, 'Sorry, but requested page doesn\'t exists.');
		}

		return $this->displayPage($page, $slug == '/' ? 'home.index' : 'home.page');
	}

	/**
	 * Get view of specified page.
	 *
	 * @param   Page    $page
	 *
	 * @return  \Illuminate\View\View
	 */
	protected function displayPage(Page $page, $view = 'home.page')
	{
		$page->body = $this->markdown->transformMarkdown($page->body);

		$content = View::make($view, compact('page'));

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
        	->withMenu($this->getMenu($page))
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
		$ancestors = $page->ancestors()->select('id', 'title', 'slug')->withoutRoot()->get();

		if ($active !== null) $ancestors->push($page);

		foreach ($ancestors as $item) 
		{
			$breadcrumbs[$item->title] = route($route, array($item->slug));
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
	protected function getMenu(Page $activePage)
	{
		Debugbar::startMeasure('menu');

		$items = $this->page
			->select('id', 'slug', 'title', '_lft', 'parent_id')
			->where('parent_id', '=', 1)
			->get();

		$items = make_contents($items->toTree(), $activePage);

		Debugbar::stopMeasure('menu');

		return $items;
	}
}