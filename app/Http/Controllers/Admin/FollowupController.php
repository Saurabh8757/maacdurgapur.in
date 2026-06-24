<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\Brand;
use App\Models\User;
use App\Services\LeadFollowupService;
use Illuminate\Http\Request;

class FollowupController extends Controller
{
    protected $followupService;

    public function __construct(LeadFollowupService $followupService)
    {
        $this->followupService = $followupService;
    }

    public function index(Request $request)
    {
        $query = LeadFollowup::with(['lead', 'lead.brand', 'assignedUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('followup_date', $request->date);
        }
        if ($request->filled('assigned_user_id')) {
            $query->where('assigned_user_id', $request->assigned_user_id);
        }
        if ($request->filled('brand_id')) {
            $query->whereHas('lead', function($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }

        $followups = $query->orderBy('followup_date', 'asc')->orderBy('followup_time', 'asc')->paginate(20);
        $users = User::where('user_type', 'Admin')->get();
        $brands = Brand::all();

        return view('admin.pages.followups.index', compact('followups', 'users', 'brands'));
    }

    public function store(Request $request, $leadId)
    {
        $request->validate([
            'followup_date' => 'required|date',
            'followup_time' => 'nullable|date_format:H:i',
            'remarks' => 'required|string',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $lead = Lead::findOrFail($leadId);

        $this->followupService->createFollowup(
            $lead,
            $request->followup_date,
            $request->followup_time,
            $request->remarks,
            $request->assigned_user_id
        );

        return redirect()->back()->with('success', 'Followup created successfully.');
    }

    public function complete(Request $request, $id)
    {
        $followup = LeadFollowup::findOrFail($id);
        
        $request->validate([
            'remarks' => 'nullable|string'
        ]);

        $this->followupService->completeFollowup($followup, $request->remarks);

        return redirect()->back()->with('success', 'Followup marked as completed.');
    }

    public function cancel(Request $request, $id)
    {
        $followup = LeadFollowup::findOrFail($id);
        
        $request->validate([
            'reason' => 'nullable|string'
        ]);

        $this->followupService->cancelFollowup($followup, $request->reason);

        return redirect()->back()->with('success', 'Followup cancelled.');
    }
}
