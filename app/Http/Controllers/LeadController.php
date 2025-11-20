<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\LeadRepositoryInterface;

class LeadController extends Controller
{
    public function index(Request $request, LeadRepositoryInterface $repository)
    {
        $perPage = $request->query('per_page', 5);

        $leads = $repository->list($request->all());
        return view('leads.index', compact('leads', 'perPage'));
    }
}
