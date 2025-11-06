<div class="card w-96 bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title">Counter: {{ $count }}</h2>
        <p>Testing Livewire + DaisyUI</p>
        <div class="card-actions justify-end">
            <button wire:click="increment" class="btn btn-primary">
                Increment
            </button>
        </div>
    </div>
</div>
