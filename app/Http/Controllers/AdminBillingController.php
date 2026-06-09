<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Client;
use App\Models\Sale;
use Carbon\Carbon;

class AdminBillingController extends Controller
{
    /**
     * Display a paginated list of billings.
     */
    public function index()
    {
        return view('admin.bill.billings');
    }

    /**
     * Filter billings by service type.
     */
    public function filter($name)
    {
        $query = Billing::with(['client', 'items']);

        if ($name === 'Audit') {
            $query->where('remarks', 'Audit');
        } elseif ($name === 'Tax') {
            $query->where('remarks', 'Tax');
        } else {
            $query->whereNotIn('remarks', ['Tax', 'Audit']);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.bill.billings', compact('sales'));
    }

    /**
     * Show the create billing form (Livewire component).
     */
    public function create()
    {
        return view('admin.bill.addbill');
    }

    /**
     * Delete a billing record and its items (cascade).
     */
    public function delete($id)
    {
        $billing = Billing::findOrFail($id);
        $billing->delete(); // cascade deletes invoice_items via FK

        return redirect()->back()->with('error', 'Billing Record Deleted');
    }

    /**
     * Show the edit billing form.
     */
    public function editForm($id)
    {
        $billing = Billing::findOrFail($id);
        return view('admin.bill.editbill', ['billingId' => $billing->id]);
    }

    /**
     * Print/view an invoice for a billing.
     */
    public function print($id)
    {
        $bill = Billing::with(['client', 'items'])->findOrFail($id);

        return view('admin.bill.invoice', compact('bill'));
    }

    /**
     * Search billings by client name.
     */
    public function search(Request $request)
    {
        $search = trim($request->input('search'));

        $sales = Billing::query()
            ->when($search, function ($query) use ($search) {
                $query->whereHas('client', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->with(['client', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.bill.billings', compact('sales', 'search'));
    }
}
