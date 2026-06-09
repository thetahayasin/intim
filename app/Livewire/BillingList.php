<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Billing;

class BillingList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $startDate;
    public $endDate;



    public function render()
    {
        $query = Billing::with(['client', 'items']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if (!empty($this->search)) {
            $query->whereHas('client', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.billing-list', compact('sales'));
    }
}
