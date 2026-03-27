<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::latest()->get();
        return view('admin.complaints.index', compact('complaints'));
    }

    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        return view('admin.complaints.show', compact('complaint'));
    }

    // public function reply(Request $request)
    // {
    //     $request->validate([
    //         'complaint_id' => 'required',
    //         'reply' => 'required'
    //     ]);

    //     $complaint = Complaint::find($request->complaint_id);
    //     $complaint->reply = $request->reply;
    //     $complaint->status = "replied";
    //     $complaint->save();

    //     return back()->with('success', 'Reply sent to user.');
    // }
    public function reply(Request $request)
{
    $request->validate([
        'complaint_id' => 'required',
        'reply' => 'required'
    ]);

    $complaint = Complaint::findOrFail($request->complaint_id);

    $complaint->reply = $request->reply;
    $complaint->status = 'replied';
    $complaint->is_read = 0; // 🔔 mark as unread for user
    $complaint->save();

    return redirect()->route('admin.complaints.index')->with('success', 'Reply sent to user.');
}

    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return back()->with('success', 'Complaint deleted successfully.');
    }
    public function enquiryMessages($orderId)
{
    $complaint = Complaint::where('order_id', $orderId)
        ->where('user_id', auth()->id())
        ->first();

    if ($complaint) {
        $complaint->is_read = 1; // ✅ read
        $complaint->save();
    }

    return view('frontend.user.enquiry.chat', compact('complaint'));
}

}
