<?php

namespace Arneon\LaravelCategories\Application\Console\Commands;

use Illuminate\Console\Command;
use Arneon\LaravelCategories\Infrastructure\Models\Category as Model;
use Arneon\LaravelCategories\Application\UseCases\CreateUseCase;

class CategoryInit extends Command
{
    protected $signature = 'categories:init';
    protected $description = 'Initializes categories';

    public function __construct(
        private readonly CreateUseCase $createUseCase,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $categoies_seed = config('arneon-categories.categories_seed');
        if ($categoies_seed) {
            foreach ($categoies_seed as $category) {
                $row = Model::where('name', $category['name'])->first();
                if(!$row) {
                    $this->createUseCase->__invoke($category);
                }
            }
        }

    }
}
