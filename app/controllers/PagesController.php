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

	public function __construct(Page $page)
	{
		$this->page = $page;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pages = $this->page->withDepth()->defaultOrder()->get();

        $this->layout
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

        $this->layout
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
		$data = $this->page->preprocessData(Input::all());

		$page = new Page($data);

		if (($messages = $page->validate()) === true)
		{
			if ($page->save())
			{
				return Redirect::route('pages.index')->withSuccess('The page has been created!');
			}

			return Redirect::route('pages.create')
				->withError('Something went wrong while saving the page.')
				->withInput($data);
		}

		return Redirect::route('pages.create')->withInput($data)->withErrors($messages);
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

        $this->layout
        	->withTitle('Update '.$page->title)
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

		$data = $this->page->preprocessData(Input::all());

		try
		{
			$page->fill($data);
		}
		catch (Exception $e)
		{
			return Redirect::route('pages.edit', array($id))
				->withInput()
				->withError($e->getMessage());
		}

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
				->withInput()
				->withError('Could not save the page.');
		}

		return Redirect::route('pages.edit', array($id))
			->withInput($data)
			->withErrors($messages);
	}

	/**
	 * Display destroy confirmation.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function confirm($id)
	{
		$page = $this->page->findOrFail($id);

		$message = "Are you shure to destroy {$page->title}?";

		if ($page->getDescendantCount())
		{
			$message .= " All descendants will also be destroyed!";
		}

		$this->layout
			->withTitle('Confirm destroy')
			->nest('content', 'pages.confirm', compact('message', 'page'));
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
			$response->withSuccess('The page has been destroyed!');
		}
		else
		{
			$response->withWarning('The page was not destroyed.');
		}

		return $response;
	}

	/**
	 * Move the specified page up.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function up($id)
	{
		return $this->move($id, 'before');
	}

	/**
	 * Move the specified page down.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function down($id)
	{
		return $this->move($id, 'after');
	}

	/**
	 * Move the page.
	 *
	 * @param  int $id
	 * @param  'before'|'after' $dir
	 *
	 * @return Response
	 */
	protected function move($id, $dir)
	{
		$page = $this->page->findOrFail($id);
		$response = Redirect::route('pages.index');

		$sibling = $dir === 'before' ? $page->getPrevSibling() : $page->getNextSibling();

		if ($sibling)
		{
			$page->$dir($sibling)->save();

			if ($page->hasMoved())
			{
				return $response->withSuccess('The page has been successfully moved!');
			}
		}

		return $response->withWarning('The page did not move.');
	}

	/**
	 * Export pages.
	 *
	 * @return Response
	 */
	public function export()
	{
		$exporter = App::make('PagesExporter');
		$path = storage_path('tmp/pages.tmp');

		if ($exporter->export($path))
		{
			$headers = array('Content-Type' => $exporter->getMimeType());
			$fileName = 'pages.'.$exporter->getExtension();

			return Response::download($path, $fileName, $headers);
		}

		return Redirect::route('pages.index')->withError('Failed to export pages.');
	}

	/**
	 * Get all available nodes as a list for HTML::select.
	 *
	 * @return array
	 */
	protected function getParents()
	{
		$all = $this->page->select('id', 'title')->withDepth()->defaultOrder()->get();
		$result = array();

		foreach ($all as $item)
		{
			$title = $item->title;

			if ($item->depth > 0) $title = str_repeat('—', $item->depth).' '.$title;

			$result[$item->id] = $title;
		}

		return $result;
	}
}
