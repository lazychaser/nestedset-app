<?php return array (
  0 => 
  array (
    'id' => '1',
    'slug' => '/',
    'title' => 'Index',
    'body' => '<div class="jumbotron">
    <div class="container">
        <h1>Welcome, User!</h1>
        
        <p>What you see is made up entierly of pages that are stored in database hierarchically using <a href="http://github.com/lazychaser/laravel4-nestedset" target="_blank">Laravel 4 NestedSet package</a>. Every page can be edited. Go ahead and start creating your own pages!</p>

        <p><a href="/pages/create" class="btn btn-primary btn-lg">Create a page</a></p>
    </div>
</div>',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-18 10:05:54',
    '_lft' => '1',
    '_rgt' => '26',
    'parent_id' => NULL,
  ),
  1 => 
  array (
    'id' => '2',
    'slug' => 'docs',
    'title' => 'Documentation',
    'body' => 'Say hi to Laravel 4 extension that will allow to create and manage hierarchies in
your database out-of-box. You can:

*   Create multi-level menus and select items of specific level;
*   Create categories for the store with no limit of nesting level, query for
    descendants and ancestors;
*   Forget about performance issues!',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-30 20:03:09',
    '_lft' => '2',
    '_rgt' => '21',
    'parent_id' => '1',
  ),
  2 => 
  array (
    'id' => '3',
    'slug' => 'docs/installation',
    'title' => 'Installation',
    'body' => 'The package can be installed using Composer, just include it into `required` 
section of your `composer.json` file:

    "required": {
        "kalnoy/nestedset": "1.0-beta"
    }
    
Hit `composer update` in the terminal, and you are ready to go next!',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-18 12:26:34',
    '_lft' => '3',
    '_rgt' => '4',
    'parent_id' => '2',
  ),
  3 => 
  array (
    'id' => '4',
    'slug' => 'docs/basic-usage',
    'title' => 'Basic usage',
    'body' => '### Schema

Storing hierarchies in a database requires additional columns for the table, so these
fields need to be included in the migration. Also, the root node is required.
So, basic migration looks like this:

    <?php

    use Illuminate\\Database\\Migrations\\Migration;
    use Illuminate\\Database\\Schema\\Blueprint;
    use Kalnoy\\Nestedset\\NestedSet;

    class CreateCategoriesTable extends Migration {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create(\'categories\', function(Blueprint $table) {
                $table->increments(\'id\');
                $table->string(\'title\');
                $table->timestamps();

                NestedSet::columns($table);
            });

            // The root node is required
            NestedSet::createRoot(\'categories\', array(
                \'title\' => \'Store\',
            ));
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::drop(\'categories\');
        }
    }

### The model

The next step is to create `Eloquent` model. I prefer [Jeffrey Way\'s generators][1],
but you can stick to whatever you prefer, just make shure that model is extended 
from `\\Kalnoy\\Nestedset\\Node`, like here:

[1]: https://github.com/JeffreyWay/Laravel-4-Generators

    <?php

    class Category extends \\Kalnoy\\Nestedset\\Node {}

### Queries

You can insert nodes using several methods:

    $node = new Category(array(\'title\' => \'TV\\\'s\'));
    $target = Category::root();

    $node->appendTo($target)->save();
    $node->prependTo($target)->save();

The parent can be changed via mass asignment:

    // The equivalent of $node->appendTo(Category::find($parent_id))
    $node->parent_id = $parent_id;
    $node->save();

You can insert the node right next to or before the other node:

    $srcNode = Category::find($src_id);
    $targetNode = Category::find($target_id);

    $srcNode->after($targetNode)->save();
    $srcNode->before($targetNode)->save();

_Ancestors_ can be obtained in two ways:

    // Target node will not be included into result since it is already available
    $path = $node->ancestors()->get();

or using the scope:

    // Target node will be included into result
    $path = Category::ancestorsOf($nodeId)->get();

_Descendants_ can easily be retrieved in this way:

    $descendants = $node->descendants()->get();

This method returns query builder, so you can apply any constraints or eager load
some relations. 

There are few more methods:

*   `siblings()` for querying siblings of the node;
*   `nextSiblings()` and `prevSiblings()` to query nodes after and before the node
    respectively.

Nodes can be provided with _nesting level_ if scope `withDepth` is applied:

    // Each node instance will recieve \'depth\' attribute with depth level starting at
    // zero for the root node.
    $nodes = Category::withDepth()->get();

Using `depth` attribute it is possible to get nodes with maximum level of nesting:

    $menu = Menu::withDepth()->having(\'depth\', \'<=\', 2)->get();

The root node can be filtered out using scope `withoutRoot`:

    $nodes = Category::withoutRoot()->get();

Nothing changes when you need to remove the node:

    $node->delete();

### Relations

There are two relations provided by `Node`: _children_ and _parent_.

### Insertion, re-insertion and deletion of nodes

Operations such as insertion and deletion of nodes imply extra queries
before node is actually saved. That is why if something goes wrong, the whole tree
might be broken. To avoid such situations, each call of `save()` has to be enclosed 
in the transaction.',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-18 12:26:36',
    '_lft' => '5',
    '_rgt' => '6',
    'parent_id' => '2',
  ),
  4 => 
  array (
    'id' => '7',
    'slug' => 'docs/advanced',
    'title' => 'Advanced usage',
    'body' => 'In this section some features described in detail.',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-19 07:20:12',
    '_lft' => '7',
    '_rgt' => '14',
    'parent_id' => '2',
  ),
  5 => 
  array (
    'id' => '8',
    'slug' => 'docs/advanced/default-order',
    'title' => 'Default order',
    'body' => 'Nodes are ordered by lft column unless there is `limit` or `offset` is provided,
or when user uses `orderBy`.',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-18 12:26:40',
    '_lft' => '8',
    '_rgt' => '9',
    'parent_id' => '7',
  ),
  6 => 
  array (
    'id' => '9',
    'slug' => 'docs/advanced/custom-collection',
    'title' => 'Custom collection',
    'body' => 'This package also provides custom collection, which has two additional functions:
`toDictionary` and `toTree`. The latter builds a tree from the list of nodes just like
if you would query only root node with all of the children, and children of that
children, etc. This function restores parent-child relations, so the resulting collection
will contain only top-level nodes and each of this node will have `children` relation
filled. The interesting thing is that when some node is rejected by a query constraint,
whole subtree will be rejected during building the tree.

Consider the tree of categories:

    Catalog
    - Mobile
    -- Apple
    -- Samsung
    - Notebooks
    -- Netbooks
    --- Apple
    --- Samsung
    -- Ultrabooks

Let\'s see what we have in PHP:

    $tree = Category::where(\'title\', \'<>\', \'Netbooks\')->withoutRoot()->get()->toTree();
    echo $tree;

This is what we are going to get:

    [{
        "title": "Mobile",
        "children": [{ "title": "Apple", "children": [] }, { "title": "Samsung", "children": [] }]
    },

    {
        "title": "Notebooks",
        "children": [{ "title": "Ultrabooks", "children": [] }]
    }];

Even though the query returned all nodes but _Netbooks_, the resulting tree does 
not contain any child from that node. This is very helpful when nodes are soft deleted. 
Active children of soft deleted nodes will inevitably show up in query results, 
which is not desired in most situations.',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-18 12:26:42',
    '_lft' => '10',
    '_rgt' => '11',
    'parent_id' => '7',
  ),
  7 => 
  array (
    'id' => '10',
    'slug' => 'docs/advanced/multiple-node-insertion',
    'title' => 'Multiple node insertion',
    'body' => '_DO NOT MAKE MULTIPLE INSERTIONS DURING SINGLE HTTP REQUEST_

Since when node is inserted or re-inserted tree is changed in database, nodes
that are already loaded might also have changed and need to be refreshed. This
doesn\'t happen automatically with exception of one scenario.

Consider this example:

    $nodes = Category::whereIn(\'id\', Input::get(\'selected_ids\'))->get();
    $target = Category::find(Input::get(\'target_id\'));

    foreach ($nodes as $node) {
        $node->appendTo($target)->save();
    }

This is the example of situation when user picks up several nodes and moves them
into new parent. When we call `appendTo` nothing is really changed but internal
variables. Actual transformations are performed when `save` is called. When that
happens, values of internal variables are definately changed for `$target` and
might change for some nodes in `$nodes` list. But this changes happen in database
and do not reflect into memory for loaded nodes. Calling `appendTo` with outdated 
values brakes the tree.

In this case only values of `$target` are crucial. The system always updates crucial
attributes of parent of node being saved. Since `$target` becomes new parent for
every node, the data of that node will always be up to date and this example will
work just fine.

_THIS IS THE ONLY CASE WHEN MULTIPLE NODES CAN BE INSERTED AND/OR RE-INSERTED 
DURING SINGLE HTTP REQUEST WITHOUT REFRESHING DATA_',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-18 12:26:44',
    '_lft' => '12',
    '_rgt' => '13',
    'parent_id' => '7',
  ),
  8 => 
  array (
    'id' => '5',
    'slug' => 'docs/how-tos',
    'title' => 'How-to\'s',
    'body' => 'This section contains basic recipes for different applications.',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-19 07:22:20',
    '_lft' => '15',
    '_rgt' => '20',
    'parent_id' => '2',
  ),
  9 => 
  array (
    'id' => '6',
    'slug' => 'docs/how-tos/moving-nodes-up-and-down',
    'title' => 'Moving nodes up and down',
    'body' => 'Sometimes there is need to move nodes around while remaining in boundaries of 
the parent.

To move node down, this snippet can be used:

    if ($sibling = $node->nextSiblings()->first())
    {
        $node->after($sibling)->save();
    }

Moving up is a little bit trickier:

    if ($sibling = $node->prevSiblings()->reversed()->first())
    {
        $node->before($sibling)->save();
    }

To move node up we need to insert it before node that is right at the top of it.
If we use `$node->prevSiblings()->first()` we\'ll get the first child of the parent 
since all nodes are ordered by fixed values. We apply `reversed()` scope to reverse
default order.',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '2013-10-15 20:47:51',
    '_lft' => '16',
    '_rgt' => '17',
    'parent_id' => '5',
  ),
  10 => 
  array (
    'id' => '15',
    'slug' => 'docs/how-tos/documentation-navigation',
    'title' => 'Documentation navigation: next & prev',
    'body' => 'Navigating through such resources as documentation, tutorials or articles is simplified when in the end of each section there is a link to the next. This is especially helpful for navigating documentation since it is usually consists of many sections that have other sub sections. While you are inside one section you want to have link to the next article in that section but once you are at the end of the section you would probably want to get link to the introduction of the next section.

This is extremely easy when using nested sets. The next article is simply the node that follows immediately after current. The same is for the previous article. In database it\'s the first node lft column value of which is larger than current node\'s lft value:

    $nextNode = $node->where($node->getLftName(), \'>\', $node->getLft())->first();
    $prevNode = $node->where($node->getLftName(), \'<\', $node->getLft())->reversed()->first();

This can be rewritten to use default implementation:

    $nextNode = $node->getNext(); // == $node->next()->first();
    $prevNode = $node->getPrev(); // == $node->prev()->first();',
    'created_at' => '2013-10-19 07:39:02',
    'updated_at' => '2013-10-30 19:55:18',
    '_lft' => '18',
    '_rgt' => '19',
    'parent_id' => '5',
  ),
  11 => 
  array (
    'id' => '12',
    'slug' => 'resources',
    'title' => 'Resources',
    'body' => '* [Laravel 4 NestedSet](http://github.com/lazychaser/laravel4-nestedset)
* [This application\'s GitHub repository](http://github.com/lazychaser/nestedset-app)',
    'created_at' => '2013-10-15 16:44:00',
    'updated_at' => '2013-10-30 20:04:58',
    '_lft' => '22',
    '_rgt' => '23',
    'parent_id' => '1',
  ),
  12 => 
  array (
    'id' => '11',
    'slug' => 'about',
    'title' => 'About',
    'body' => 'This is general about page.',
    'created_at' => '0000-00-00 00:00:00',
    'updated_at' => '0000-00-00 00:00:00',
    '_lft' => '24',
    '_rgt' => '25',
    'parent_id' => '1',
  ),
);