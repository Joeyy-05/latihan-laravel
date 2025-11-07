<form class="card card-md" wire:submit="save"> {{-- <-- PERBAIKAN 1: 'login' diubah menjadi 'save' --}}
    <div class="card-body">
        <h2 class="h2 text-center mb-4">Login</h2>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="Masukkan email" wire:model="email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">
                Password
            </label>
            <div class="input-group input-group-flat">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan password" wire:model="password">
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">
                {{-- PERBAIKAN 2: 'login' diubah menjadi 'save' --}}
                <span wire:loading.remove wire:target="save"> 
                    Login
                </span>
                <span wire:loading wire:target="save">
                    Loading...
                </span>
            </button>
        </div>
    </div>
</form>