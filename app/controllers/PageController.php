<?php

/**
 * This controller is used to display pages.
 */
class PageController extends BaseController {

	protected $layout = 'layouts.front';

	/**
	 * The page storage.
	 *
	 * @var  Page
	 */
	protected $page;

	public function __construct(Page $page)
	{
		$this->page = $page;
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

        $view = $page->isRoot() ? 'home.index' : 'home.page';

        $this->layout->title = $page->title;
        $this->layout->content = View::make($view, compact('page'));
        $this->layout->menu = $this->getMenu($page);
        $this->layout->breadcrumbs = $this->getBreadcrumbs($page);
	}

	/**
	 * Get breadcrumbs to the current page.
	 *
	 * $active is the last crumb (the page title by default).
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

		$breadcrumbs['Index'] = url('/');
		$ancestors = $page
			->ancestors()
			->withoutRoot()
			->get(array('id', 'title', 'slug'));

		if ($active !== null) $ancestors->push($page);

		foreach ($ancestors as $item)
		{
			$breadcrumbs[$item->title] = route($route, array($item->slug));
		}

		$breadcrumbs[] = $active !== null ? $active : $page->title;

		return $breadcrumbs;
	}

    /**
     * Get main menu items.
     *
     * @param Page $activePage
     *
     * @return array
     */
	protected function getMenu(Page $activePage)
	{
		$itemTree = $this->page
			->where('parent_id', '=', 1)
			->get([ 'id', 'slug', 'title', '_lft', 'parent_id' ])
			->toTree();

		return make_nav($itemTree, $activePage->getKey());
	}
}