<?php

class PagesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('pages')->truncate();

        $pages = require __DIR__.'/data/pages.php';

		// Uncomment the below to run the seeder
		DB::table('pages')->insert($pages);
	}

}
