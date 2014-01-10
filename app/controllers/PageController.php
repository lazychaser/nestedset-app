<?php

use dflydev\markdown\MarkdownParser;

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

        $page->body = $this->markdown->transformMarkdown($page->body);

        $content = View::make($slug == '/' ? 'home.index' : 'home.page', compact('page'));

        if (!$page->isRoot())
        {
            $content->with(array(
                'contents' => make_nav($page->getContents(), $page->getKey()),
                'next' => $page->getNext(),
                'prev' => $page->getPrev(),
            ));
        }

        $this->layout->with(array(
            'title' => $page->title,
            'breadcrumbs' => $this->getBreadcrumbs($page),
            'menu' => $this->getMenu($page),
            'content' => $content,
        ));
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
			->select('id', 'slug', 'title', '_lft', 'parent_id')
			->where('parent_id', '=', 1)
			->get()
			->toTree();

		return make_nav($itemTree, $activePage->getKey());
	}
}