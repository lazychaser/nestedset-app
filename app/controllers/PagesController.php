<?php

use dflydev\markdown\MarkdownParser;

class PagesController extends BaseController {

	protected $layout = 'layouts.backend';

	/**
	 * The page storage.
	 *
	 * @var  Page
	 */	
	protected $page;

	/**
	 * Markdown parser.
	 *
	 * @var  MarkdownParser
	 */
	protected $markdown;

	public function __construct(Page $page, MarkdownParser $markdown)
	{
		$this->page = $page;
		$this->markdown = $markdown;

		$me = $this;

		$this->beforeFilter(function () use($me) {
			$me->setupLayout();
		}, 
		array('except' => array('store', 'update', 'destroy')));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pages = $this->page->withDepth()->get();

        return $this->layout
        	->withTitle('Manage Pages')
        	->nest('content', 'pages.index', compact('pages'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$parents = $this->getParents();

        return $this->layout
        	->withTitle('Create a page')
        	->nest('content', 'pages.create', compact('parents'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = $this->getData();

		$page = new Page;
		$page->fill($data);

		if (($messages = $page->validate()) === true)
		{
			if ($page->save())
			{
				return Redirect::route('pages.index')->withSuccess('The page has been created!');
			}

			return Redirect::route('pages.create')
				->withError('Something went wrong trying to save page.')
				->withInput($data);
		}

		return Redirect::route('pages.create')->withInput($data)->withErrors($messages);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$page = $this->page->findOrFail($id);
		$parents = $this->getParents();

        return $this->layout
        	->withTitle('Update page')
        	->nest('content', 'pages.edit', compact('page', 'parents'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$page = $this->page->findOrFail($id);

		$data = $this->getData();

		$page->fill($data);

		if (($messages = $page->validate()) === true)
		{
			if ($page->save())
			{
				$response = Input::has('save') 
					? Redirect::route('pages.index')
					: Redirect::route('pages.edit', array($id));

				return $response->withSuccess('The page has been updated!');
			}

			return Redirect::route('pages.edit', array($id))
				->withError('Could not save the page.');
		}

		return Redirect::route('pages.edit', array($id))
			->withInput($data)
			->withErrors($messages);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$page = $this->page->findOrFail($id);

		$response = Redirect::route('pages.index');

		if ($page->delete()) 
		{
			Cache::flush();

			$response->withSuccess('The page has been removed!');
		}
		else
		{
			$response->withWarning('The page was not removed.');
		}

		return $response;
	}

	protected function getParents()
	{
		$all = $this->page->select('id', 'title')->withDepth()->get();
		$result = array();

		foreach ($all as $item) 
		{
			$title = $item->title;

			if ($item->depth > 0) $title = str_repeat('—', $item->depth).' '.$title;

			$result[$item->id] = $title;
		}

		return $result;
	}

	protected function getData()
	{
		$data = Input::all();

		if (isset($data['slug'])) $data['slug'] = strtolower($data['slug']);

		return $data;
	}
}
