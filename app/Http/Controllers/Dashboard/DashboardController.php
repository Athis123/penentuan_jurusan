<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MooraService;

class DashboardController extends Controller
{

    protected $mooraService;

    public function __construct(MooraService $mooraService)
    {
        $this->mooraService = $mooraService;
    }

    public function index()
    {
        $title = 'Dashboard';

        $data = $this->mooraService->hitungMoora();
        return view('dashboard.index', compact('title', 'data'));
    }
}
