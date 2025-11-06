<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\Pelanggan;
use Livewire\Component;
use Livewire\WithPagination;

class MemberManagement extends Component
{
    use WithPagination;

    public $pelanggan_id, $tanggal_daftar, $tanggal_berakhir, $diskon_persen = 10;
    public $member_id;
    public $isEdit = false;
    public $search = '';

    protected $rules = [
        'pelanggan_id' => 'required|exists:pelanggans,id',
        'tanggal_daftar' => 'required|date',
        'tanggal_berakhir' => 'required|date|after:tanggal_daftar',
        'diskon_persen' => 'required|numeric|min:0|max:100',
    ];

    public function mount()
    {
        $this->tanggal_daftar = now()->format('Y-m-d');
        $this->tanggal_berakhir = now()->addYear()->format('Y-m-d');
    }

    public function resetForm()
    {
        $this->reset(['pelanggan_id', 'member_id', 'isEdit']);
        $this->tanggal_daftar = now()->format('Y-m-d');
        $this->tanggal_berakhir = now()->addYear()->format('Y-m-d');
        $this->diskon_persen = 10;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $kodeMember = Member::generateKodeMember();

        Member::create([
            'pelanggan_id' => $this->pelanggan_id,
            'kode_member' => $kodeMember,
            'tanggal_daftar' => $this->tanggal_daftar,
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'diskon_persen' => $this->diskon_persen,
        ]);

        // Update pelanggan jadi member
        Pelanggan::find($this->pelanggan_id)->update(['is_member' => true]);

        session()->flash('message', 'Member berhasil ditambahkan dengan kode: ' . $kodeMember);
        $this->resetForm();
    }

    public function toggleStatus($id)
    {
        $member = Member::find($id);
        $member->update(['is_active' => !$member->is_active]);
        session()->flash('message', 'Status member berhasil diubah!');
    }

    public function delete($id)
    {
        $member = Member::find($id);
        $member->pelanggan->update(['is_member' => false]);
        $member->delete();
        session()->flash('message', 'Member berhasil dihapus!');
    }

    public function render()
    {
        $query = Member::with('pelanggan');

        if ($this->search) {
            $query->where('kode_member', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pelanggan', function($q) {
                      $q->where('nama', 'like', '%' . $this->search . '%');
                  });
        }

        $pelangganNonMember = Pelanggan::where('is_member', false)->get();

        return view('livewire.member-management', [
            'members' => $query->latest()->paginate(10),
            'pelangganNonMember' => $pelangganNonMember,
        ])->layout('layouts.app');
    }
}
