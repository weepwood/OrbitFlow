<?php
use Illuminate\Support\Facades\Artisan;
Artisan::command('orbit:about', function () { $this->info('OrbitFlow · Laravel + Livewire + Flux UI'); });
