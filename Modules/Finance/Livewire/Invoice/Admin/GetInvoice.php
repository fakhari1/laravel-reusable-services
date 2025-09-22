<?php

namespace Modules\Finance\Livewire\Invoice\Admin;

use Illuminate\Filesystem\Filesystem;
use Livewire\Component;

class GetInvoice extends Component
{
    public function mount()
    {
        $this->authorize('application.factor');
    }
    public function render(Filesystem $filesystem)
    {
        return view('livewire.admin.pages.factor.show')->layoutData(['page_name' => 'show_factor']);
    }
}
