<?php

namespace App\Http\Controllers;

use App\Actions\ListLeadsAction;
use App\Actions\UpdateLeadAction;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LeadController extends Controller
{
    /**
     * Display a listing of the Leads.
     */
    public function index(Request $request, ListLeadsAction $action): View
    {
        $perPage = $request->query('per_page', 5);

        $leads = $action->execute($request->all());

        return view('leads.index', compact('perPage', 'leads'));
    }

    /**
     * Show the form for editing the specified Lead.
     */
    public function edit(Lead $lead): View
    {
        return view('leads.edit', compact('lead'));
    }

    /**
     * Update the specified Lead.
     */
    public function update(UpdateLeadRequest $request, Lead $lead, UpdateLeadAction $action)
    {
        $lead = $action->execute($request->toDto());

        Log::info('Lead updated successfully.', ['lead' => $request->toDto()->toArray()]);

        return redirect()->route('leads.edit', ['lead' => $lead])->with('success', 'Lead updated successfully.');
    }
}
