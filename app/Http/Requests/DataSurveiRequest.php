<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DataSurveiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // karena sudah dilindungi middleware auth.admin
    }

    public function rules(): array
    {
        return [
            'judul'          => ['required', 'string', 'max:255'],
            'ketua_tim'      => ['required', 'string', 'max:100'], // Tambahkan validasi
            'tahun'          => ['required', 'integer', 'min:1900', 'max:9999'],
            'tipe'            => ['required', 'string', Rule::in(['2D', '3D', 'HR', 'Lainnya'])],
            'wilayah'        => ['required', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string'],
            'gambar_pratinjau' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'tautan_file'    => ['nullable', 'string', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required'    => 'Judul survei wajib diisi.',
            'ketua_tim.required' => 'Ketua tim wajib diisi.', // Tambahkan pesan
            'ketua_tim.max'     => 'Ketua tim maksimal 100 karakter.',
            'tahun.required'    => 'Tahun wajib diisi.',
            'tahun.integer'     => 'Tahun harus berupa angka.',
            'tipe.required'     => 'Tipe survei wajib dipilih.',
            'tipe.in'           => 'Tipe hanya boleh 2D, 3D, HR, atau Lainnya.',
            'wilayah.required'  => 'Wilayah / blok wajib diisi.',
            'gambar_pratinjau.file' => 'File harus berupa gambar.',
            'gambar_pratinjau.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar_pratinjau.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}