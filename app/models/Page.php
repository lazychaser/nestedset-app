<?php

class Page extends \Kalnoy\Nestedset\Node {
	protected $fillable = array('slug', 'title', 'body', 'parent_id');
    
	public static $rules = array(
        'title'       => 'required',
        'slug'        => 'required|regex:/^[a-z0-9\-\/]+$/',
        'body'        => 'required',
        'parent_id'   => 'required|exists:pages',
    );

    public function validate()
    {
        $rules = self::$rules;

        if ($this->isRoot()) unset($rules['parent_id']);

        $validator = Validator::make($this->attributes, $rules);

        return $validator->fails() ? $validator->messages() : true;
    }

    public function getContents()
    {
        Debugbar::startMeasure(__FUNCTION__);

        $source = $this->parent_id == 1 
            ? $this 
            : $this->ancestors()->withoutRoot()->first();

        $contents = $source
            ->descendants()
            ->select('id', 'slug', 'title', static::LFT, 'parent_id')
            ->get()
            ->toTree();

        Debugbar::stopMeasure(__FUNCTION__);

        return $contents;
    }

    public function getNext(array $columns = array('slug', 'title'))
    {
        Debugbar::startMeasure(__FUNCTION__);

        $result = $this->newQuery()
            ->select($columns)
            ->where(static::LFT, '>', $this->_lft)
            ->where('parent_id', '<>', 1)
            ->first();

        Debugbar::stopMeasure(__FUNCTION__);

        return $result;
    }

    public function getPrev(array $columns = array('slug', 'title'))           
    {
        if ($this->isRoot() || $this->parent_id == 1) return null;

        Debugbar::startMeasure(__FUNCTION__);

        $result = $this->newQuery()
            ->select($columns)
            ->where(static::LFT, '<', $this->_lft)
            ->reversed()
            ->first();

        Debugbar::stopMeasure(__FUNCTION__);

        return $result;
    }
}
