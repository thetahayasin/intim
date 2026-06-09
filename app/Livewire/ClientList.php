<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Client;

class ClientList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';



    public function render()
    {
        $clients = Client::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('livewire.client-list', compact('clients'));
    }
}
