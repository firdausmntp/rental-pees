<?php

namespace App\Livewire;

use App\Models\PlayStation;
use App\Models\Tarif;
use Livewire\Component;
use Livewire\WithPagination;

class PlayStationManagement extends Component
{
    use WithPagination;

    public $kode_ps, $tipe, $nama_konsol, $status, $keterangan;
    public $playstation_id;
    public $isEdit = false;
    public $search = '';
    public $filterStatus = '';

    // For tarif editing
    public $editTarifId, $editTarifHarga, $editTarifTipe;
    public $isEditTarif = false;

    protected $rules = [
        'kode_ps' => 'required|unique:play_stations,kode_ps',
        'tipe' => 'required|in:PS3,PS4,PS5',
        'nama_konsol' => 'required',
        'status' => 'required|in:tersedia,dipakai,rusak',
        'keterangan' => 'nullable',
    ];

    public function resetForm()
    {
        $this->reset(['kode_ps', 'tipe', 'nama_konsol', 'status', 'keterangan', 'playstation_id', 'isEdit']);
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        PlayStation::create([
            'kode_ps' => $this->kode_ps,
            'tipe' => $this->tipe,
            'nama_konsol' => $this->nama_konsol,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
        ]);

        $this->dispatch('toast', type: 'success', message: 'PlayStation berhasil ditambahkan!');
        $this->resetForm();
    }

    public function edit($id)
    {
        $ps = PlayStation::findOrFail($id);
        $this->playstation_id = $ps->id;
        $this->kode_ps = $ps->kode_ps;
        $this->tipe = $ps->tipe;
        $this->nama_konsol = $ps->nama_konsol;
        $this->status = $ps->status;
        $this->keterangan = $ps->keterangan;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'kode_ps' => 'required|unique:play_stations,kode_ps,' . $this->playstation_id,
            'tipe' => 'required|in:PS3,PS4,PS5',
            'nama_konsol' => 'required',
            'status' => 'required|in:tersedia,dipakai,rusak',
            'keterangan' => 'nullable',
        ]);

        $ps = PlayStation::find($this->playstation_id);
        $ps->update([
            'kode_ps' => $this->kode_ps,
            'tipe' => $this->tipe,
            'nama_konsol' => $this->nama_konsol,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
        ]);

        $this->dispatch('toast', type: 'success', message: 'PlayStation berhasil diupdate!');
        $this->resetForm();
    }

    public function delete($id)
    {
        PlayStation::find($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'PlayStation berhasil dihapus!');
    }

    public function editTarif($id)
    {
        $tarif = Tarif::findOrFail($id);
        $this->editTarifId = $tarif->id;
        $this->editTarifTipe = $tarif->tipe_ps;
        $this->editTarifHarga = $tarif->harga_per_jam;
        $this->isEditTarif = true;
    }

    public function updateTarif()
    {
        $this->validate([
            'editTarifHarga' => 'required|numeric|min:1000',
        ]);

        $tarif = Tarif::find($this->editTarifId);
        $tarif->update([
            'harga_per_jam' => $this->editTarifHarga,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Tarif berhasil diupdate!');
        $this->isEditTarif = false;
        $this->reset(['editTarifId', 'editTarifHarga', 'editTarifTipe']);
    }

    public function cancelEditTarif()
    {
        $this->isEditTarif = false;
        $this->reset(['editTarifId', 'editTarifHarga', 'editTarifTipe']);
    }

    public function render()
    {
        $query = PlayStation::query();

        if ($this->search) {
            $query->where('kode_ps', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_konsol', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.play-station-management', [
            'playstations' => $query->latest()->paginate(10),
            'tarifs' => Tarif::all(),
        ])->layout('layouts.app');
    }
}
