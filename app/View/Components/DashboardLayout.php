<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DashboardLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // Ini menghubungkan <x-dashboard-layout> ke file resources/views/layouts/dashboard.blade.php
        return view('layouts.dashboard');
    }
}