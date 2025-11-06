<?php

namespace App\Livewire;

use App\Models\Pelanggan;
use Livewire\Component;
use Livewire\WithPagination;

class PelangganManagement extends Component
{
    use WithPagination;

    public $nama, $nomor_hp, $alamat;
    public $pelanggan_id;
    public $isEdit = false;
    public $search = '';

    protected $rules = [
        'nama' => 'required|min:3',
        'nomor_hp' => 'required|min:10',
        'alamat' => 'required',
    ];

    public function resetForm()
    {
        $this->reset(['nama', 'nomor_hp', 'alamat', 'pelanggan_id', 'isEdit']);
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        Pelanggan::create([
            'nama' => $this->nama,
            'nomor_hp' => $this->nomor_hp,
            'alamat' => $this->alamat,
        ]);

        session()->flash('message', 'Pelanggan berhasil ditambahkan!');
        $this->resetForm();
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $this->pelanggan_id = $pelanggan->id;
        $this->nama = $pelanggan->nama;
        $this->nomor_hp = $pelanggan->nomor_hp;
        $this->alamat = $pelanggan->alamat;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        $pelanggan = Pelanggan::find($this->pelanggan_id);
        $pelanggan->update([
            'nama' => $this->nama,
            'nomor_hp' => $this->nomor_hp,
            'alamat' => $this->alamat,
        ]);

        session()->flash('message', 'Pelanggan berhasil diupdate!');
        $this->resetForm();
    }

    public function delete($id)
    {
        Pelanggan::find($id)->delete();
        session()->flash('message', 'Pelanggan berhasil dihapus!');
    }

    public function render()
    {
        $query = Pelanggan::with('member');

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_hp', 'like', '%' . $this->search . '%');
        }

        return view('livewire.pelanggan-management', [
            'pelanggans' => $query->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}
