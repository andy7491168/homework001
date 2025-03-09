<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CampaignService;
use App\DTOs\CampaignFilter;

class CampaignController extends Component
{
    use WithPagination;

    public $search = '';
    protected $campaignService;
    public $filterField = 'booked_amount'; 
    public $filterOperator = '>=';
    public $filterValue = null; 
    public $sortField = 'campaigns.name';
    public $sortDirection = 'asc';
    protected $paginationTheme = 'bootstrap';


    public function mount(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }    
    public function hydrate()
    {
        $this->campaignService = app(CampaignService::class);
    }

    public function render()
    {
        return view('livewire.campaign', [
            'campaigns' => $this->campaignService->getCampaigns(new CampaignFilter(
                $this->search, 
                $this->filterField, 
                $this->filterOperator, 
                $this->filterValue, 
                $this->sortField, 
                $this->sortDirection
                )
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
