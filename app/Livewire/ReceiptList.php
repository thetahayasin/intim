<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Receipt;

class ReceiptList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $startDate;
    public $endDate;



    public function render()
    {
        $query = Receipt::with('client');

        if (!empty($this->search)) {
            $query->whereHas('client', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->startDate) {
            $query->whereDate('date', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('date', '<=', $this->endDate);
        }

        $res = $query->orderBy('date', 'desc')->paginate(20);

        return view('livewire.receipt-list', compact('res'));
    }
}
