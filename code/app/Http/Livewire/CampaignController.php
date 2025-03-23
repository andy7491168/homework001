<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CampaignService;
use App\DTOs\CampaignFilter;
use App\Models\Campaign;
use Illuminate\Http\Request;
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

    public function showComments($id)
    { 
        $campain = Campaign::find($id);
        // $campain->comments = '新的名稱';
        // $campain->save();
        return view('comment', ['data' => $campain->comments,'id'=>$id]);
       
    }
    public function editComments($id, Request $request)
    {
        try {
            $comments = $request->input('comments');
            $campaign = Campaign::find($id);
            
            if (!$campaign) {
                return response("<script>alert('fail: Campaign not found'); window.location.href = '/campaign/comment/".$id."';</script>", 404)
                    ->header('Content-Type', 'text/html');
            }
            
            $campaign->comments = $comments;
            $campaign->save();
            
            return response("<script>alert('success'); window.location.href = '/campaign/comment/".$id."';</script>", 200)
                ->header('Content-Type', 'text/html');
        } catch (\Exception $e) {
            return response("<script>alert('fail: " . addslashes($e->getMessage()) . "'); window.location.href = '/campaign/comment/".$id."';</script>", 500)
                ->header('Content-Type', 'text/html');
        }
    }
}
