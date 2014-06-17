<?php return array (
  0 => 
  array (
    'slug' => '/',
    'title' => 'Index',
    'body' => '<div class="jumbotron">
    <div class="container">
        <h1>Welcome, User!</h1>
        
        <p>What you see is made up entierly of pages that are stored in database hierarchically using <a href="http://github.com/lazychaser/laravel4-nestedset" target="_blank">Laravel 4 NestedSet package</a>. Every page can be edited. Go ahead and start creating your own pages!</p>

        <p><a href="/pages/create" class="btn btn-primary btn-lg">Create a page</a></p>
    </div>
</div>',
    'children' => 
    array (
      0 => 
      array (
        'slug' => 'docs',
        'title' => 'Documentation',
        'body' => 'This is a Laravel 4 package for working with trees in a database.

__Contents:__

- [Theory](what-are-nested-sets)
- [Manipulating nodes](manipulating-nodes)
- [Requirements](requirements)
- [Installation](installation)',
        'children' => 
        array (
          0 => 
          array (
            'slug' => 'what-are-nested-sets',
            'title' => 'What are nested sets?',
            'body' => 'Nested sets or [Nested Set Model](http://en.wikipedia.org/wiki/Nested_set_model) is
a way to effectively store hierarchical data in a relational table. From wikipedia:

> The nested set model is to number the nodes according to a tree traversal,
> which visits each node twice, assigning numbers in the order of visiting, and
> at both visits. This leaves two numbers for each node, which are stored as two
> attributes. Querying becomes inexpensive: hierarchy membership can be tested by
> comparing these numbers. Updating requires renumbering and is therefore expensive.

### Applications

NSM shows good performance when tree is updated rarely. It is tuned to be fast for
getting related nodes. It\'is ideally suited for building multi-depth menu or 
categories for shop.',
            'children' => 
            array (
            ),
          ),
          1 => 
          array (
            'slug' => 'manipulating-nodes',
            'title' => 'Manipulating nodes',
            'body' => 'Suppose that we have a model `Category`; a `$node` variable is an instance of that model
and the node that we are manipulating. It can be a fresh model or one from database.

__Contents:__

-   [Relationships](relationships)
-   [Inserting nodes](inserting-nodes)
-   [Getting related nodes](getting-related-nodes)
-   [Other methods](other-methods)
-   [Checking consistency](checking-consistency)',
            'children' => 
            array (
              0 => 
              array (
                'slug' => 'relationships',
                'title' => 'Relationships',
                'body' => 'Node has two predefined relationships: `parent` and `children`. They are fully
functional, except for Laravel\'s `has` due to limitations of the framework.
You can use `hasChildren` or `hasParent` to apply constraints:

```php
$items = Category::hasChildren()->get();
```',
                'children' => 
                array (
                ),
              ),
              1 => 
              array (
                'slug' => 'inserting-nodes',
                'title' => 'Inserting nodes',
                'body' => 'Moving and inserting nodes includes several database queries, so __transaction is
automatically started__ when node is saved. It is safe to use global transaction 
if you work with several models.

Another important note is that __structural manipulations are deferred__ until you
hit `save` on model (some methods implicitly call `save` and return boolean result
of the operation).

If model is successfully saved it doesn\'t mean that node has moved. To check whether
it has, use `hasMoved` function:

```php
if ($node->save())
{
    $moved = $node->hasMoved();
}
```

#### Creating a new node

When you just create a node, it will be appended to the end of the tree:

```php
Category::create($attributes);
```

In this case the node is considered a _root_ which means that it doesn\'t have a parent.

#### Making a root from existing node

```php
// #1 Implicit save
$node->saveAsRoot();

// #2 Explicit save
$node->makeRoot()->save();
```

The node will be appended to the end of the tree.

#### Appending and prepending to the specified parent

If you want to make node a child of other node, you can make it last or first child.

`$parent` variable is the node that exists in database, no matter how we get it.

There are few ways to append a node:

```php
// #1 Using deferred insert
$node->appendTo($parent)->save();

// #2 Using parent node
$parent->append($node);

// #3 Using parent\'s children relationship
$parent->children()->create($attributes);

// #5 Using node\'s parent relationship
$node->parent()->associate($parent)->save();

// #6 Using the parent attribute
$node->parent_id = $parent->id;
$node->save();

// #7 Using static method
Category::create($attributes, $parent);
```

And only a couple ways to prepend:

```php
// #1
$node->prependTo($parent)->save();

// #2
$parent->prepend($node);
```

#### Inserting before or after specified node

You can make a `$node` to be a neighbor of the `$neighbor` node using following methods:

_Neighbor is existing node, target node can be fresh. If target node is exists, 
it will be moved to the new position and parent will be changed if it\'s needed._

```php
# Explicit save
$node->after($neighbor)->save();
$node->before($neighbor)->save();

# Implicit save
$node->insertAfter($neighbor);
$node->insertBefore($neighbor);
```

#### Moving node up or down

```php
$bool = $node->down();
$bool = $node->up();

// Make node lower by 3 siblings
$bool = $node->down(3);
```

#### Building a tree from array

When using static method `create` on node, it checks whether attributes contains
`children` key. If it does, it creates more nodes recursively.

```php
$node = Category::create(
[
    \'name\' => \'Foo\',
    \'children\' =>
    [
        [
            \'name\' => \'Bar\',
            \'children\' =>
            [
                [ \'name\' => \'Baz\' ],
            ],
        ],
    ],
]);
```

`$node->children` now contains a list of created child nodes.',
                'children' => 
                array (
                ),
              ),
              2 => 
              array (
                'slug' => 'getting-related-nodes',
                'title' => 'Getting related nodes',
                'body' => 'In some cases we will use an `$id` variable which is an id of the target node.

#### Getting ancestors

```php
// #1 Using accessor
$result = $node->getAncestors();

// #2 Using a query 
$result = $node->ancestors()->get();

// #3 Getting ancestors by id of the node
$result = Category::ancestorsOf($id);

// #4 Applying constraints
$result = Category::whereAncestorOf($id)->get();
```

#### Getting descendants

```php
// #1 Using accessor
$result = $node->getDescendants();

// #2 Using a query 
$result = $node->descendants()->get();

// #3 Getting ancestors by id of the node
$result = Category::descendantsOf($id);

// #4 Applying constraints
$result = Category::whereDescendantOf($id)->get();
```

#### Getting siblings of the node

```php
$result = $node->getSiblings();

$result = $node->siblings()->get();
```

To get just next siblings ([default order](#default-order) is applied here):

```php
// Get a sibling that is immediately after the node
$result = $node->getNextSibling();

// Get all siblings that are after the node 
$result = $node->getNextSiblings();

// Get all siblings using a query
$result = $node->nextSiblings()->get();
```

To get previous siblings ([reversed order](#default-order) is applied):

```php
// Get a sibling that is immediately before the node
$result = $node->getPrevSibling();

// Get all siblings that are before the node 
$result = $node->getPrevSiblings();

// Get all siblings using a query
$result = $node->prevSiblings()->get();
```

#### Getting related models from other table

Imagine that each category `has many` goods. I.e. `HasMany` relationship is established.
How can you get all goods of all categories that are descendants of some node? Easy!

```php
// Get ids of descendants
$descendants = $node->descendants()->lists(\'id\');

// Get goods
$goods = Goods::whereIn(\'category_id\', $descendants)->get();
```

#### Manipulating a query

You have noticed that some methods return a query builder instance. It has some
features.

##### Including depth level into result

If you need to know at which level the node is:

```php
$result = Category::withDepth()->find($id);

$depth = $result->depth;
```

Root node will be at level 0. Children of root nodes will have a level of 1, etc.

To get nodes of specified level, you can apply `having` constraint:

```php
$result = Category::withDepth()->having(\'depth\', \'=\', 1)->get();
```

##### Default order

Each node has it\'s own unique value that determines its position in the tree. If
you want node to be ordered by this value, you can use `defaultOrder` method on
the query builder:

```php
// All nodes will now be ordered by lft value
$result = Category::defaultOrder()->get();
```

You can get nodes in reversed order:

```php
$result = Category::reversed()->get();
```

##### Constraints

-   __whereIsRoot()__ to get only root nodes;
-   __hasChildren()__ to get nodes that have children;
-   __hasParent()__ to get non-root nodes;
-   __whereIsAfter($id)__ to get every node (not just siblings) that are after a node
    with specified id;
-   __whereIsBefore($id)__ to get every node that is before a node with specified id.',
                'children' => 
                array (
                ),
              ),
              3 => 
              array (
                'slug' => 'other-methods',
                'title' => 'Other methods',
                'body' => 'To check if node is a descendant of other node:

```php
$bool = $node->isDescendantOf($parent);
```

To check whether the node is root:

```php
$bool = $node->isRoot();
```',
                'children' => 
                array (
                ),
              ),
              4 => 
              array (
                'slug' => 'checking-consistency',
                'title' => 'Checking consistency',
                'body' => 'You can check whether a tree is broken (i.e. has some structural errors):

```php
$bool = Category::isBroken();
```

It is possible to get error statistics:

```php
$data = Category::countErrors();
```

It will return an array with following keys:

-   `oddness` -- the number of nodes that have wrong set of `lft` and `rgt` values;
-   `duplicates` -- the number of nodes that have same `lft` or `rgt` values;
-   `wrong_parent` -- the number of nodes that have invalid `parent_id` value that
    doesn\'t correspond to `lft` and `rgt` values.',
                'children' => 
                array (
                ),
              ),
            ),
          ),
          2 => 
          array (
            'slug' => 'requirements',
            'title' => 'Requirements',
            'body' => '- PHP >= 5.4
- Laravel >= 4.1

Models are extended from new base class rather than `Eloquent`, so it\'s not possible
to use another framework that overrides `Eloquent`.

It is highly suggested to use database that supports transactions (like MySql\'s InnoDb) 
to secure a tree from possible corruption.',
            'children' => 
            array (
            ),
          ),
          3 => 
          array (
            'slug' => 'installation',
            'title' => 'Installation',
            'body' => 'To install the package, in terminal:

```
composer require kalnoy/nestedset:~2.0@beta
```

Add some aliases:

```php
\'aliases\' => array(
    ...
    \'NestedSet\' => \'Kalnoy\\Nestedset\\NestedSet\',
    \'Node\' => \'Kalnoy\\Nestedset\\Node\',
),
```

### Adding required columns

You can use a method to add needed columns with default names:

```php
Schema::create(\'table\', function (Blueprint $table)
{
    ...
    NestedSet::columns($table);
});
```

To drop columns:

```php
Schema::table(\'table\', function (Blueprint $table)
{
    NestedSet::dropColumns($table);
});
```

If, for some reasons, you want to init everything by yourself, this is preferred schema:

```php
$table->unsignedInteger(\'_lft\');
$table->unsignedInteger(\'_rgt\');
$table->unsignedInteger(\'parent_id\')->nullable();
```

You can change names of the columns, in this case you need to [alter constants](#changing-column-names)
on your model class.

### Transforming a model

Your model is now extended from `Node` class, not `Eloquent`:

```php
class Foo extends Node {
    
}
```

#### Changing column names

If you\'re using custom columns, you need to make shure that it\'s reflected on
the model:

```php
class Foo extends Node {
    
    const LFT = \'lft\';

    const RGT = \'rgt\';

    const PARENT_ID = \'parent\';

    protected $guarded = [ \'lft\', \'rgt\' ];

    // To allow mass asignment on parent attribute
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }
}
```

__Important!__ Make sure that `lft` and `rgt` columns are guarded!',
            'children' => 
            array (
            ),
          ),
        ),
      ),
      1 => 
      array (
        'slug' => 'resources',
        'title' => 'Resources',
        'body' => '* [Laravel 4 NestedSet](http://github.com/lazychaser/laravel4-nestedset)
* [This application\'s GitHub repository](http://github.com/lazychaser/nestedset-app)',
        'children' => 
        array (
        ),
      ),
      2 => 
      array (
        'slug' => 'license',
        'title' => 'License',
        'body' => 'Copyright (c) 2014 Alexander Kalnoy

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.',
        'children' => 
        array (
        ),
      ),
    ),
  ),
);