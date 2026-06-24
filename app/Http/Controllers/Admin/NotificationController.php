<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\NotificationService;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Notification::query();

        // Scope by user/brand if not Super Admin
        if ($user->user_type != 'Admin') {
            $query->where(function ($q) use ($user) {
                if ($user->brand_id) {
                    $q->where('brand_id', $user->brand_id);
                }
                $q->orWhere('user_id', $user->id);
            });
        }

        // Filters
        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        if ($request->has('status') && $request->status != '') {
            $isRead = $request->status == 'read' ? true : false;
            $query->where('is_read', $isRead);
        }
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $notifications = $query->with(['brand', 'createdByUser'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(20);

        return view('admin.pages.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $this->notificationService->markRead($id, Auth::id());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        
        $user = Auth::user();
        // Permission check
        if ($user->user_type != 'Admin') {
            if ($notification->brand_id != $user->brand_id && $notification->user_id != $user->id) {
                return back()->with('error', 'Unauthorized action.');
            }
        }

        $notification->delete();
        return back()->with('success', 'Notification deleted successfully.');
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllRead(Auth::user());
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
