<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Client;

class AdminReceiptController extends Controller
{
    /**
     * Display a paginated list of receipts.
     */
    public function index()
    {
        return view('admin.receipt.receipts');
    }

    /**
     * Delete a receipt record.
     */
    public function delete($id)
    {
        $receipt = Receipt::findOrFail($id);
        $receipt->delete();

        return redirect()->back()->with('error', 'Receipt Deleted');
    }

    /**
     * Show the create receipt form (Livewire component).
     */
    public function create()
    {
        return view('admin.receipt.create');
    }
}
