<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;

class LeadManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['brand', 'assignedUser']);

        // Filters
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source_page')) {
            $query->where('source_page', $request->source_page);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);
        }

        if ($request->filled('course_name')) {
            $query->where('course_name', 'like', '%' . $request->course_name . '%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        $query->orderBy('created_at', 'desc');
        $leads = $query->paginate(20)->appends($request->all());

        $brands = Brand::all();
        $sourcePages = Lead::select('source_page')->distinct()->whereNotNull('source_page')->pluck('source_page');

        return view('admin.pages.lead_management.index', compact('leads', 'brands', 'sourcePages'));
    }

    public function show($id)
    {
        $lead = Lead::with(['brand', 'assignedUser'])->findOrFail($id);
        $users = User::where('user_type', 'Admin')->get();
        return view('admin.pages.lead_management.show', compact('lead', 'users'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,follow_up,converted,closed'
        ]);

        $lead = Lead::findOrFail($id);
        $lead->status = $request->status;
        $lead->save();

        return redirect()->back()->with('success', 'Lead status updated successfully.');
    }

    public function assignUser(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $lead = Lead::findOrFail($id);
        $lead->assigned_to = $request->assigned_to;
        $lead->save();

        return redirect()->back()->with('success', 'Lead assigned successfully.');
    }

    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->route('admin::leads.index')->with('success', 'Lead deleted successfully.');
    }

    public function exportCsv(Request $request)
    {
        return $this->export($request, 'csv');
    }

    public function exportExcel(Request $request)
    {
        // For simplicity without packages, we return a CSV with a .csv extension 
        // Excel opens .csv files perfectly.
        return $this->export($request, 'excel');
    }

    private function export(Request $request, $type)
    {
        $query = Lead::with(['brand', 'assignedUser']);

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('source_page')) {
            $query->where('source_page', $request->source_page);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);
        }
        if ($request->filled('course_name')) {
            $query->where('course_name', 'like', '%' . $request->course_name . '%');
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        $leads = $query->orderBy('created_at', 'desc')->get();

        $filename = "leads_export_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['ID', 'Brand', 'Name', 'Phone', 'Email', 'Course', 'Location', 'Message', 'Custom Data', 'Source Page', 'Status', 'Assigned To', 'Created At'];

        $callback = function() use($leads, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($leads as $lead) {
                $customString = '';
                if (!empty($lead->custom_data) && is_array($lead->custom_data)) {
                    $pairs = [];
                    foreach ($lead->custom_data as $k => $v) {
                        $pairs[] = "$k: $v";
                    }
                    $customString = implode(' | ', $pairs);
                }

                $row['ID']  = $lead->id;
                $row['Brand']    = $lead->brand ? $lead->brand->name : 'N/A';
                $row['Name']    = $lead->name;
                $row['Phone']  = $lead->phone;
                $row['Email']  = $lead->email;
                $row['Course']  = $lead->course_name;
                $row['Location']  = $lead->location;
                $row['Message']  = $lead->message;
                $row['Custom Data'] = $customString;
                $row['Source Page']  = $lead->source_page;
                $row['Status']  = ucfirst($lead->status);
                $row['Assigned To']  = $lead->assignedUser ? $lead->assignedUser->name : 'Unassigned';
                $row['Created At']  = $lead->created_at->format('Y-m-d H:i:s');

                fputcsv($file, array($row['ID'], $row['Brand'], $row['Name'], $row['Phone'], $row['Email'], $row['Course'], $row['Location'], $row['Message'], $row['Custom Data'], $row['Source Page'], $row['Status'], $row['Assigned To'], $row['Created At']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
