<?php

class Page extends \Kalnoy\Nestedset\Node {
	protected $fillable = array('slug', 'title', 'body', 'parent_id');
    
    /**
     * The validation rules.
     *
     * @var array
     */
	public static $rules = array(
        'title'       => 'required',
        'slug'        => 'required|regex:/^[a-z0-9\-\/]+$/',
        'body'        => 'required',
        'parent_id'   => 'required|exists:pages',
    );

    /**
     * Apply some processing for an input.
     *
     * @param  array  $data
     *
     * @return array
     */
    public function preprocessData(array $data)
    {
        if (isset($data['slug'])) $data['slug'] = strtolower($data['slug']);

        return $data;
    }

    /**
     * Perform validation.
     *
     * @return \Illuminate\Support\MessageBag|true
     */
    public function validate()
    {
        $rules = self::$rules;

        if ($this->exists && $this->isRoot()) unset($rules['parent_id']);

        $validator = Validator::make($this->attributes, $rules);

        return $validator->fails() ? $validator->messages() : true;
    }

    /**
     * Get the contents.
     *
     * @return \Kalnoy\Nestedset\Collection
     */
    public function getContents()
    {
        // The source of contents is the top page not including the root.
        $source = $this->parent_id == 1 
            ? $this 
            : $this->ancestors()->withoutRoot()->first();

        $contents = $source
            ->descendants()
            ->select('id', 'slug', 'title', static::LFT, 'parent_id')
            ->get()
            ->toTree();

        return $contents;
    }

    /**
     * Get the page that is immediately after current page.
     * 
     * @param array $columns 
     * 
     * @return Page|null
     */
    public function getNext(array $columns = array('slug', 'title'))
    {
        $result = $this->next()
            ->select($columns)
            ->where('parent_id', '<>', 1)
            ->first();

        return $result;
    }

    /**
     * Get the page that is immediately before current page.
     * 
     * @param array $columns 
     * 
     * @return Page|null
     */
    public function getPrev(array $columns = array('slug', 'title'))           
    {
        if ($this->isRoot() || $this->parent_id == 1) return null;

        $result = $this->prev()
            ->select($columns)
            ->where(static::LFT, '<', $this->_lft)
            ->first();

        return $result;
    }
}
