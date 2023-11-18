<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait ResetsDatabase {
    use RefreshDatabase;

    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {

            // dd($this->shouldDropViews());

            // $this->artisan('migrate:refresh', [
            //     '--drop-views' => true
            // ]);

            $this->artisan('migrate:fresh', ['--drop-views' => true]);
            //$this->artisan('migrate:refresh');
            $this->artisan('db:seed');
            //$this->artisan('db:seed', []);
            // $this->artisan('db:seed', [
            //     '--class' => 'DatabaseSeeder',
            // ]);

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
