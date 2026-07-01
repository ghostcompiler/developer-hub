<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('github:sync')->hourly();
Schedule::command('project:cache-trees')->hourly();