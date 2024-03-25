<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Search extends Component
{
    public function render()
    {
        return view('livewire.search');
    }
   

    public function searchDoctor()
    {
        $this->validate([
            'search' => 'required|min:3',
        ]);

        $searchTerm = '%'.$this->search.'%';

        return view('livewire.search', [
            'doctors' => Doctor::where('name', 'like', $searchTerm)->get(),
        ]);


    }   


}
