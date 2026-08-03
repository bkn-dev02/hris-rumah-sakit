@php $isEdit = isset($employee); @endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-sky-900">Akun User</label>
        <select name="user_id" class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">-- Pilih Akun User --</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" @selected(old('user_id', $employee->user_id ?? null) == $user->id)>
                {{ $user->username }} ({{ $user->email }})
            </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-400">Cuma akun yang belum terhubung ke Employee lain yang muncul di sini.</p>
        @error('user_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Nomor Induk Pegawai</label>
        <input type="text" name="employee_number" value="{{ old('employee_number', $employee->employee_number ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('employee_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $employee->name ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Jenis Kelamin</label>
        <select name="gender" class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">-- Pilih --</option>
            <option value="male" @selected(old('gender', $employee->gender ?? '') === 'male')>Laki-laki</option>
            <option value="female" @selected(old('gender', $employee->gender ?? '') === 'female')>Perempuan</option>
        </select>
        @error('gender') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Status Kepegawaian</label>
        <select name="employment_status_id" class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">-- Pilih Status --</option>
            @foreach($employmentStatuses as $status)
            <option value="{{ $status->id }}" @selected(old('employment_status_id', $employee->employment_status_id ?? null) == $status->id)>
                {{ $status->name }}
            </option>
            @endforeach
        </select>
        @error('employment_status_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Tanggal Bergabung</label>
        <input type="date" name="hire_date" value="{{ old('hire_date', $employee->hire_date?->toDateString() ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('hire_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Tempat Lahir</label>
        <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $employee->place_of_birth ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('place_of_birth') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Tanggal Lahir</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->toDateString() ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('date_of_birth') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">NIK</label>
        <input type="text" name="national_id_number" value="{{ old('national_id_number', $employee->national_id_number ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('national_id_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Status Pernikahan</label>
        <select name="marital_status" class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">-- Pilih --</option>
            @foreach(['single' => 'Belum Menikah', 'married' => 'Menikah', 'divorced' => 'Cerai', 'widowed' => 'Janda/Duda'] as $value => $label)
            <option value="{{ $value }}" @selected(old('marital_status', $employee->marital_status ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('marital_status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Pendidikan Terakhir</label>
        <input type="text" name="education_level" placeholder="mis. S1, D3, SMA" value="{{ old('education_level', $employee->education_level ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('education_level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-900">Jurusan</label>
        <input type="text" name="education_major" value="{{ old('education_major', $employee->education_major ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('education_major') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-sky-900">Alamat</label>
        <textarea name="address" rows="2" class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('address', $employee->address ?? '') }}</textarea>
        @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-sky-900">Foto</label>
        <input type="file" name="photo" accept="image/*"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100">
        @if($isEdit && $employee->photo)
        <p class="mt-1 text-xs text-slate-400">Foto saat ini akan tetap dipakai kalau tidak diganti.</p>
        @endif
        @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2 sm:col-span-2">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            @checked(old('is_active', $employee->is_active ?? true))
        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        <label for="is_active" class="text-sm text-sky-900">Employee aktif</label>
    </div>

</div>