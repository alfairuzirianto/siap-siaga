<div>
    <x-page-header 
        title="Kelola Pengguna"
        subtitle="Manajemen kendali user"
        :breadcrumbs="[['label' => 'Kelola Pengguna']]">
        <a href="{{ route('users.create') }}" wire:navigate 
           class="btn-primary flex items-center gap-2 py-2 px-4 rounded-xl shadow-sm text-sm font-medium">
            <i class="ti ti-user-plus text-base"></i>
            <span>Tambah User</span>
        </a>
    </x-page-header>

    <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row items-center gap-3 justify-between">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="ti ti-search text-base"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       class="form-input rounded-xl border-slate-200 pl-9 text-sm py-2" placeholder="Cari nama, nip, username, email">
            </div>

            <div class="sm:w-48">
                <select wire:model.live="filterRole" class="form-input rounded-xl border-slate-200 text-sm py-2">
                    <option value="">-- Semua Role --</option>
                    @foreach(App\Models\User::ROLES as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($users->isEmpty())
            <div class="p-12">
                <x-empty-state icon="ti-users" title="User tidak ditemukan" message="Tidak ada data pegawai yang terdaftar berdasarkan parameter filter." />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-bold uppercase text-slate-400 bg-slate-50/30 tracking-wider">
                            <th class="py-3.5 px-6">Nama / NIP</th>
                            <th class="py-3.5 px-4">Kredensial</th>
                            <th class="py-3.5 px-4">Unit / Jabatan</th>
                            <th class="py-3.5 px-4">Hak Akses</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/40 transition-colors" wire:key="user-row-{{ $user->id }}">
                                <td class="py-3.5 px-6 whitespace-nowrap">
                                    <span class="text-slate-800 font-semibold block">{{ $user->nama_lengkap }}</span>
                                    <span class="text-xs text-slate-400 font-normal">NIP: {{ $user->nip ?: '—' }}</span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="text-slate-700 text-xs block">{{ $user->username }}</span>
                                    <span class="text-xs text-slate-400 font-normal">{{ $user->email }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-slate-700 text-xs block truncate max-w-[180px]" title="{{ $user->unit }}">{{ $user->unit ?: '—' }}</span>
                                    <span class="text-[11px] text-slate-400 font-normal block truncate max-w-[180px] mt-0.5">{{ $user->jabatan ?: '—' }}</span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset 
                                        {{ match($user->role) {
                                            'Admin' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                            'Supervisor' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                            default => 'bg-slate-50 text-slate-600 ring-slate-500/10'
                                        } }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                    <button wire:click="toggleStatus({{ $user->id }})" 
                                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold transition-all outline-none focus:outline-none focus:ring-0
                                            {{ $user->is_active ? 'bg-green-50 text-green-700 hover:bg-green-100 ring-1 ring-green-600/20' : 'bg-red-50 text-red-600 hover:bg-red-100 ring-1 ring-red-600/20' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="py-3.5 px-6 whitespace-nowrap text-center">
                                    <a href="{{ route('users.edit', $user) }}" wire:navigate 
                                       class="btn-ghost p-1.5 text-slate-500 rounded-lg outline-none focus:outline-none focus-visible:outline-none">
                                        <i class="ti ti-edit text-base"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
