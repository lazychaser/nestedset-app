<?php

class PagesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('pages')->truncate();

        $texts = include __DIR__.'/data/texts.php';

		$pages = array(
            array(
                'id' => 1, 
                'title' => 'Main',
                'slug' => '/',
                'body' => 'Welcome to the NestedSet test app!', 
                '_lft' => 1, 
                '_rgt' => 22,
                'parent_id' => null,
            ),

            array(
                'id' => 2,
                'title' => 'Documentation',
                'slug' => 'docs',
                'body' => $texts['documentation'],
                '_lft' => 2,
                '_rgt' => 19,
                'parent_id' => 1,
            ),

                array(
                    'id' => 3,
                    'title' => 'Installation',
                    'body' => $texts['installation'],
                    'slug' => 'docs/installation',
                    '_lft' => 3,
                    '_rgt' => 4,
                    'parent_id' => 2,
                ),

                array(
                    'id' => 4,
                    'title' => 'Basic usage',
                    'body' => $texts['basic_usage'],
                    'slug' => 'docs/basic-usage',
                    '_lft' => 5,
                    '_rgt' => 6,
                    'parent_id' => 2,
                ),

                array(
                    'id' => 5,
                    'title' => 'How-to\'s',
                    'body' => $texts['howtos'],
                    'slug' => 'docs/how-tos',
                    '_lft' => 7,
                    '_rgt' => 10,
                    'parent_id' => 2,
                ),        

                    array(
                        'id' => 6,
                        'title' => 'Moving nodes up and down',
                        'body' => $texts['updown'],
                        'slug' => 'docs/moving-nodes-up-and-down',
                        '_lft' => 8,
                        '_rgt' => 9,
                        'parent_id' => 5,
                    ),        

                array(
                    'id' => 7,
                    'slug' => 'docs/advanced',
                    'title' => 'Advanced usage',
                    'body' => $texts['advanced'],
                    '_lft' => 11,
                    '_rgt' => 18,
                    'parent_id' => 2,
                ),

                    array(
                        'id' => 8,
                        'slug' => 'docs/advanced/default-order',
                        'title' => 'Default order',
                        'body' => $texts['default_order'],
                        '_lft' => 12,
                        '_rgt' => 13,
                        'parent_id' => 7,
                    ),

                    array(
                        'id' => 9,
                        'title' => 'Custom collection',
                        'slug' => 'docs/advanced/custom-collection',
                        'body' => $texts['custom_collection'],
                        '_lft' => 14,
                        '_rgt' => 15,
                        'parent_id' => 7,
                    ),

                    array(
                        'id' => 10,
                        'slug' => 'docs/advanced/multiple-node-insertion',
                        'title' => 'Multiple node insertion',
                        'body' => $texts['multiple_insertion'],
                        '_lft' => 16,
                        '_rgt' => 17,
                        'parent_id' => 7,
                    ),
            array(
                'id' => 11, 
                'slug' => 'about',
                'title' => 'About', 
                'body' => 'This is general about page.', 
                '_lft' => 20, 
                '_rgt' => 21, 
                'parent_id' => 1,
            ),
		);

		// Uncomment the below to run the seeder
		DB::table('pages')->insert($pages);
	}

}
