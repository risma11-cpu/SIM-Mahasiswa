@php $m = $mahasiswa ?? null; @endphp

<div class="grid">
    <div class="field @error('npm') has-error @enderror">
        <label for="npm">NPM</label>
        <input id="npm" type="text" name="npm" value="{{ old('npm', $m->npm ?? '') }}" inputmode="numeric">
        @error('npm')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field @error('nama') has-error @enderror">
        <label for="nama">Nama Lengkap</label>
        <input id="nama" type="text" name="nama" value="{{ old('nama', $m->nama ?? '') }}">
        @error('nama')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field @error('email') has-error @enderror">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $m->email ?? '') }}">
        @error('email')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field @error('no_hp') has-error @enderror">
        <label for="no_hp">Nomor HP</label>
        <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp', $m->no_hp ?? '') }}" inputmode="tel">
        @error('no_hp')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field @error('program_studi_id') has-error @enderror">
        <label for="program_studi_id">Program Studi</label>
        <select id="program_studi_id" name="program_studi_id">
            <option value="">-- Pilih program studi --</option>
            @foreach ($programStudis as $p)
                <option value="{{ $p->id }}" @selected(old('program_studi_id', $m->program_studi_id ?? '') == $p->id)>{{ $p->nama }}</option>
            @endforeach
        </select>
        @error('program_studi_id')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field @error('semester') has-error @enderror">
        <label for="semester">Semester</label>
        <select id="semester" name="semester">
            <option value="">-- Pilih semester --</option>
            @for ($i = 1; $i <= 14; $i++)
                <option value="{{ $i }}" @selected(old('semester', $m->semester ?? '') == $i)>Semester {{ $i }}</option>
            @endfor
        </select>
        @error('semester')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field @error('jenis_kelamin') has-error @enderror">
        <label for="jenis_kelamin">Jenis Kelamin</label>
        <select id="jenis_kelamin" name="jenis_kelamin">
            <option value="">-- Pilih --</option>
            <option value="L" @selected(old('jenis_kelamin', $m->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', $m->jenis_kelamin ?? '') === 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field full @error('alamat') has-error @enderror">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3">{{ old('alamat', $m->alamat ?? '') }}</textarea>
        @error('alamat')<div class="error-text">{{ $message }}</div>@enderror
    </div>
</div>

<div class="btn-group">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('mahasiswa.index') }}" class="btn">Batal</a>
</div>