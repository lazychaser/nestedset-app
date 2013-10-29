<?php

class Page extends \Kalnoy\Nestedset\Node {

	protected $fillable = array('slug', 'title', 'body', 'parent_id');

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
        $v = Validator::make($this->attributes, $this->getRules());

        return $v->fails() ? $v->messages() : true;
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getRules()
    {
        $rules = array(
            'title' => 'required',
            
            'slug'  => array(
                'required',
                'regex:/^[a-z0-9\-\/]+$/',
                'unique:pages'.($this->exists ? ',slug,'.$this->id : ''),
            ),

            'body'  => 'required',
        );

        if ($this->exists && $this->isRoot())
        {
            $rules['parent_id'] = 'required|exists:pages,id';
        }

        return $rules;
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

    /**
     * Get url for navigation.
     *
     * @return  string
     */
    public function getNavUrl()
    {
        return URL::route('page', array($this->attributes['slug']));
    }

    /**
     * Get navigation item label.
     *
     * @return  string
     */
    public function getNavLabel()
    {
        return $this->title;
    }
}
