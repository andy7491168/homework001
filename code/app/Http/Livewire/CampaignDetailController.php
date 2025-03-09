<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\LineItemService;
use App\DTOs\LineFilter;

class CampaignDetailController extends Component
{
    use WithPagination;

    public $search = '';
    protected $lineItemService;
    public $filterField = 'booked_amount'; 
    public $filterOperator = '>=';
    public $filterValue = null; 
    public $sortField = 'campaigns.name';
    public $campaignID;

    public $sortDirection = 'asc';
    protected $paginationTheme = 'bootstrap';


    public function mount(LineItemService $lineItemService,$campaignID)
    {
        $this->lineItemService = $lineItemService;
        $this->campaignID = $campaignID;
    }    
    public function hydrate()
    {
        $this->lineItemService = app(LineItemService::class);
    }

    public function render()
    {
        return view('livewire.campaign-detail', [
            'lineItems' => $this->lineItemService->getLineItems(new LineFilter(
                $this->search, 
                $this->filterField, 
                $this->filterOperator, 
                $this->filterValue, 
                $this->sortField, 
                $this->sortDirection
            ),$this->campaignID
            )
        ])->layout('layouts.app');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
}
