<?php
namespace App\Livewire;

use App\Support\WorkspaceContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class BillingPage extends Component
{
    public function render()
    {
        $workspace = WorkspaceContext::current();
        return view('livewire.billing-page', [
            'workspace' => $workspace,
            'seatCount' => $workspace->users()->count(),
            'projectCount' => $workspace->projects()->count(),
        ]);
    }
}
