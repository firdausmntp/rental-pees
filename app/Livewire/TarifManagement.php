<?php

namespace App\Livewire;

use App\Models\Tarif;
use Livewire\Component;

class TarifManagement extends Component
{
    public $tarifs;
    public $tipe_ps, $harga_per_jam, $denda_per_jam;
    public $tarif_id;

    public function mount()
    {
        $this->loadTarifs();
    }

    public function loadTarifs()
    {
        $this->tarifs = Tarif::all();
    }

    public function edit($id)
    {
        $tarif = Tarif::find($id);
        $this->tarif_id = $tarif->id;
        $this->tipe_ps = $tarif->tipe_ps;
        $this->harga_per_jam = $tarif->harga_per_jam;
        $this->denda_per_jam = $tarif->denda_per_jam;
    }

    public function update()
    {
        $this->validate([
            'harga_per_jam' => 'required|numeric|min:0',
            'denda_per_jam' => 'required|numeric|min:0',
        ]);

        Tarif::find($this->tarif_id)->update([
            'harga_per_jam' => $this->harga_per_jam,
            'denda_per_jam' => $this->denda_per_jam,
        ]);

        session()->flash('message', 'Tarif berhasil diupdate!');
        $this->reset(['tarif_id', 'tipe_ps', 'harga_per_jam', 'denda_per_jam']);
        $this->loadTarifs();
    }

    public function render()
    {
    return view('livewire.tarif-management')->layout('layouts.app');
    }
}
