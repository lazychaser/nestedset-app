<?php

class PageSeeder extends Seeder {

	public function run()
	{
		Page::truncate();

        $pages = require __DIR__.'/data/pages.php';

        foreach ($pages as $page)
        {
            Page::create($page);
        }
	}

}
