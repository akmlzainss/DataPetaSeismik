<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataSurvei;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DataSurveiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data dari Rack 16
        $dataRack16 = [
            ['no' => 'Rack 1', 'tahun' => '1982', 'daerah' => 'Perairan Cirebon', 'ketua_team' => 'Di Era P3G'],
            ['no' => 'Rack2', 'tahun' => '1982', 'daerah' => 'Perairan Selat Sunda', 'ketua_team' => 'Di Era P3G'],
            ['no' => 'Rack 3', 'tahun' => '1983', 'daerah' => 'Perairan Bangka', 'ketua_team' => 'Di Era P3G'],
            ['no' => 'Rack 4', 'tahun' => '1994', 'daerah' => 'Perairan Canti,Lampung', 'ketua_team' => 'Di Era P3G'],
            ['no' => 'Rack 5', 'tahun' => '1984/1985', 'daerah' => 'Perairan Buton, Muna, Kabaena, Sulawesi Tenggara', 'ketua_team' => 'Ir. Sukardjono'],
            ['no' => 'Rack 6', 'tahun' => '1984/1985', 'daerah' => 'Perairan Kangean', 'ketua_team' => '-'],
            ['no' => 'Rack 7', 'tahun' => '1984/1985', 'daerah' => 'Perairan Bali(Snelius II)', 'ketua_team' => 'Ekspedisi Snelius II'],
            ['no' => 'Rack 8', 'tahun' => '1984/1985', 'daerah' => 'Perairan pere-pare, Sulawesi Selatan', 'ketua_team' => '-'],
            ['no' => 'Rack 9', 'tahun' => '1985', 'daerah' => 'Perairan Seram - Buru & Halmahera', 'ketua_team' => 'Ekspedisi Tyro'],
            ['no' => 'Rack 10', 'tahun' => '1985/1986', 'daerah' => 'Perairan Celukan Bawang Pantai Utara Bali', 'ketua_team' => 'Ir. Subaktian Lubis'],
            ['no' => 'Rack 11', 'tahun' => '1985/1986', 'daerah' => 'Perairan Pantai Utara Cirebon', 'ketua_team' => 'Ir. Dida Kusnida'],
            ['no' => 'Rack 13', 'tahun' => '1987', 'daerah' => 'Perairan Selatan Jawa', 'ketua_team' => 'Ir. Agus Setya Budhi'],
            ['no' => 'Rack 14', 'tahun' => '1985/1986', 'daerah' => 'Perairan Teluk Ambon', 'ketua_team' => 'Bambang Dwiyanto, M. Sc'],
            ['no' => 'Rack 15', 'tahun' => '1985/1986', 'daerah' => 'Perairan Pantai Utara Cirebon', 'ketua_team' => 'Ir. Dida Kusnida'],
            ['no' => 'Rack 16', 'tahun' => '1986/1987', 'daerah' => 'Perairan Selat Karimata', 'ketua_team' => 'Bambang Dwiyanto, M.Sc'],
            ['no' => 'Rack 17', 'tahun' => '1986', 'daerah' => 'Perairan Sumbawa', 'ketua_team' => 'Ir. Fernando Silitonga'],
            ['no' => 'Rack 18', 'tahun' => '1986/1987', 'daerah' => 'Perairan Indramayu II', 'ketua_team' => '-'],
            ['no' => 'Rack 19', 'tahun' => '1986/1987', 'daerah' => 'Perairan Indramayu III', 'ketua_team' => '-'],
            ['no' => 'Rack 20', 'tahun' => '1987', 'daerah' => 'Perairan Samoedra Hindia', 'ketua_team' => '-'],
            ['no' => 'Rack 21', 'tahun' => '1987', 'daerah' => 'Perairan Sulawesi(Mariana Leg)', 'ketua_team' => '-'],
            ['no' => 'Rack 22', 'tahun' => '1987/1988', 'daerah' => 'Perairan Krawang I', 'ketua_team' => 'Juniar P Hutagaol, M. Sc'],
            ['no' => 'Rack 23', 'tahun' => '1987/1988', 'daerah' => 'Perairan Krawang II', 'ketua_team' => 'Ir. A. Masduki'],
            ['no' => 'Rack 24', 'tahun' => '1987/1988', 'daerah' => 'Perairan Tangerang', 'ketua_team' => 'Lili Sarmili,M. Sc'],
            ['no' => 'Rack 25', 'tahun' => '1988/1989', 'daerah' => 'Perairan Kute, Bali', 'ketua_team' => 'Bambang Dwiyanto, M.Sc'],
            ['no' => 'Rack 26', 'tahun' => '1988/1989', 'daerah' => 'Perairan Teluk Banten', 'ketua_team' => 'Ir. Sukardjono'],
            ['no' => 'Rack 27', 'tahun' => '1988/1989', 'daerah' => 'Perairan Tangerang', 'ketua_team' => 'Lili Sarmili,M.Sc'],
            ['no' => 'Rack 28', 'tahun' => '1988/1989', 'daerah' => 'Perairan Kalianda, Lampung', 'ketua_team' => 'Ir. Sukardjono'],
            ['no' => 'Rack 29', 'tahun' => '1988/1989', 'daerah' => 'Perairan Teluk Lampung', 'ketua_team' => 'Ir. Mulyana Widjaya Negara'],
            ['no' => 'Rack 30', 'tahun' => '1989/1990', 'daerah' => 'Perairan Teluk Semangko Lampung', 'ketua_team' => 'Ir. Sukardjono'],
            ['no' => 'Rack 31', 'tahun' => '1989/1990', 'daerah' => 'Perairan Teluk Lada, Banten', 'ketua_team' => 'Ir. Asep Faturachman'],
            ['no' => 'Rack 32', 'tahun' => '1989/1999', 'daerah' => 'Perairan Selat Bali', 'ketua_team' => 'Bambang Dwiyanto, M.Sc'],
        ];

        // Data dari Rack 17
        $dataRack17 = [
            ['no' => 'Rack 1', 'tahun' => '1990/1991', 'daerah' => 'Perairan Lembar Peta 1210', 'ketua_team' => 'Ir.Harnanto Kurnio'],
            ['no' => 'Rack 2', 'tahun' => '1991/1992', 'daerah' => 'Perairan Lembar Peta 1609', 'ketua_team' => 'Drs.Lukman Arifin'],
            ['no' => 'Rack 3', 'tahun' => '1991/1992', 'daerah' => 'Perairan Lembar Peta 1709', 'ketua_team' => 'Ir.Subaktian Lubis, M.sc'],
            ['no' => 'Rack 4', 'tahun' => '1992/1993', 'daerah' => 'Perairan Lembar 1710', 'ketua_team' => 'Ir.Delyuzar ilahude'],
            ['no' => 'Rack 5', 'tahun' => '1992/1993', 'daerah' => 'Perairan Lembar Peta 1211', 'ketua_team' => 'Koesnadi HS, Dipl.Geol'],
            ['no' => 'Rack 6', 'tahun' => '1992/1993', 'daerah' => 'Perairan Lembar peta 1111/1112', 'ketua_team' => 'Ir.Subaktian Lubis, M.Sc'],
            ['no' => 'Rack 7', 'tahun' => '1994/1995', 'daerah' => 'Perairan Lembar Peta 1213', 'ketua_team' => 'DR.Mangatas Situmorang'],
            ['no' => 'Rack 8', 'tahun' => '1993/1994', 'daerah' => 'Perairan Lembar Peta 1311', 'ketua_team' => 'Ir.Udaya Kamiludin'],
            ['no' => 'Rack 9', 'tahun' => '1990', 'daerah' => 'Perairan Bone', 'ketua_team' => '-'],
            ['no' => 'Rack 10', 'tahun' => '1993/1994', 'daerah' => 'Perairan Maumere,Flores', 'ketua_team' => '-'],
            ['no' => 'Rack 11', 'tahun' => '1994/1995', 'daerah' => 'Perairan Lembar Peta 1114', 'ketua_team' => 'Ir.Yusup Adam p'],
            ['no' => 'Rack 12', 'tahun' => '1994/1995', 'daerah' => 'Perairan Lembar Peta 1110 selat sunda', 'ketua_team' => 'Ir.Kuntoro'],
            ['no' => 'Rack 13', 'tahun' => '1994/1995', 'daerah' => 'Perairan Lembar Peta 1016', 'ketua_team' => 'Ir.A.Masduki'],
            ['no' => 'Rack 14', 'tahun' => '1995/1996', 'daerah' => 'Perairan Lembar Peta 1015 Singkep', 'ketua_team' => 'Prijontono Astjario, M.Sc'],
            ['no' => 'Rack 15', 'tahun' => '1995/1996', 'daerah' => 'Perairan Lembar Peta 1014-1113', 'ketua_team' => 'Ir.Imelda R Silalahi'],
            ['no' => 'Rack 16', 'tahun' => '1995/1996', 'daerah' => 'Perairan Lembar Peta 1014-1113', 'ketua_team' => 'Ir.Imelda R Silalahi'],
            ['no' => 'Rack 17', 'tahun' => '1995/1996', 'daerah' => 'Perairan Lembar Peta 1010 Selat sunda', 'ketua_team' => 'Ir. K Hardja Widjaksana, M.Sc'],
            ['no' => 'Rack 18', 'tahun' => '1996/1997', 'daerah' => 'Perairan Lembar Peta 1212', 'ketua_team' => 'Koesnadi HS, Dipl.Geol.'],
            ['no' => 'Rack 19', 'tahun' => '1996/1997', 'daerah' => 'Perairan Lembar Peta 1212', 'ketua_team' => 'Koesnadi HS, Dipl.Geol.'],
            ['no' => 'Rack 20', 'tahun' => '1996/1997', 'daerah' => 'Perairan Lembar Peta 1312', 'ketua_team' => 'DRS.Abdul Wahib'],
            ['no' => 'Rack 21', 'tahun' => '1996/1997', 'daerah' => 'Perairan Lembar Peta 1313', 'ketua_team' => 'DRS.Abdul Wahib'],
            ['no' => 'Rack 22', 'tahun' => '1996/1997', 'daerah' => 'Perairan Lembar Peta 1413-1414', 'ketua_team' => 'Lili sarmili, M.Sc'],
            ['no' => 'Rack 23', 'tahun' => '1996/1997', 'daerah' => 'Perairan Lembar Peta 1413-1414', 'ketua_team' => 'Lili sarmili, M.Sc'],
            ['no' => 'Rack 24', 'tahun' => '1998/1999', 'daerah' => 'Perairan Teluk Sepi -Blongas,Lombok Barat,NTB', 'ketua_team' => 'Ir.Yusup Adam P'],
            ['no' => 'Rack 25', 'tahun' => '1998/1999', 'daerah' => 'Perairan Lembar 1711', 'ketua_team' => 'Ir.Andy H Sianipar'],
            ['no' => 'Rack 26', 'tahun' => '1999/2000', 'daerah' => 'Perairan Lembar Peta 1813-1814', 'ketua_team' => 'Ir.Delyuzar ilahude'],
            ['no' => 'Rack 27', 'tahun' => '1999/2000', 'daerah' => 'Perairan Lembar Peta 1813-1814', 'ketua_team' => 'Ir.Delyuzar ilahude'],
            ['no' => 'Rack 28', 'tahun' => '2000', 'daerah' => 'Perairan Lembar Peta 1811', 'ketua_team' => 'Ir. Noor Cahyo D'],
            ['no' => 'Rack 29', 'tahun' => '1999/2000', 'daerah' => 'Perairan Lembar Peta 1314', 'ketua_team' => 'Ir.Yogi Noviadi'],
            ['no' => 'Rack 30', 'tahun' => '1999/2000', 'daerah' => 'Perairan Lembar Peta 1313', 'ketua_team' => 'Drs.Abdul Wahib'],
            ['no' => 'Rack 31', 'tahun' => '2002', 'daerah' => 'Perairan Lembar Peta 1215', 'ketua_team' => 'Drs.Syaiful Hakim'],
            ['no' => 'Rack 32', 'tahun' => '2005', 'daerah' => 'Perairan Lembar Peta 1017 Batam', 'ketua_team' => 'Ir.Ediar Usman, MT'],
        ];

        // Data dari Rack 18
        $dataRack18 = [
            ['no' => 'Rack 1', 'tahun' => '1988/1989', 'daerah' => 'Perairan Tegal, Jawa Tengah', 'ketua_team' => 'Prijantinio Astjario, M.Sc'],
            ['no' => 'Rack2', 'tahun' => '1989/1990', 'daerah' => 'Perairan Pekalongan, Jawa Tengah', 'ketua_team' => 'Ir. K Hardja Widjaksana, M.Sc'],
            ['no' => 'Rack 3', 'tahun' => '1989/1990', 'daerah' => 'Perairan Kendal - Semarang, Jawa Tengah', 'ketua_team' => 'Suharno, B.Sc'],
            ['no' => 'Rack 4', 'tahun' => '1989/1990', 'daerah' => 'Perairan Pasuruan - Probolinggo, Jawa Timur', 'ketua_team' => 'Suharno, B.Sc'],
            ['no' => 'Rack 5', 'tahun' => '1989/1990', 'daerah' => 'Perairan Selat Madura Jawa Timur', 'ketua_team' => 'Kris Budiono, M.Sc'],
            ['no' => 'Rack 6', 'tahun' => '1990/1991', 'daerah' => 'Perairan Jepara, Jawa Tengah', 'ketua_team' => 'Ir. K Hardja Widjaksana, M.Sc'],
            ['no' => 'Rack 7', 'tahun' => '1990/1991', 'daerah' => 'Perairan Rembang, Jawa Tengah', 'ketua_team' => 'Ir. K Hardja Widjaksana, M.Sc'],
            ['no' => 'Rack 8', 'tahun' => '1990/1991', 'daerah' => 'Perairan Selat Madura(lembar 1608), Jawa Timur', 'ketua_team' => 'Prijantono Astjario, M.Sc'],
            ['no' => 'Rack 9', 'tahun' => '1990/1991', 'daerah' => 'Perairan Kendari, Sulawesi Tenggara', 'ketua_team' => 'Kris Budiono, M.Sc'],
            ['no' => 'Rack 10', 'tahun' => '1990/1991', 'daerah' => 'Perairan Bali(Benoa)', 'ketua_team' => 'GM. Hermansyah, Dipl. Geol.'],
            ['no' => 'Rack 11', 'tahun' => '1991/1992', 'daerah' => 'Perairan Bali(Klungkung)', 'ketua_team' => 'Ir. Agus Setya Budhi, M.Sc'],
            ['no' => 'Rack 12', 'tahun' => '1991/1992', 'daerah' => 'Perairan Muria, Jawa Tengah', 'ketua_team' => 'Ir. K Hardja Widjaksana, M.Sc'],
            ['no' => 'Rack 13', 'tahun' => '1991/1992', 'daerah' => 'Perairan Tuban, Jawa Timur', 'ketua_team' => 'Prijantinio Astjario, M.Sc'],
            ['no' => 'Rack 14', 'tahun' => '1992/1993', 'daerah' => 'Perairan Madura (Ambunteun), Jawa Timur', 'ketua_team' => 'DRS. Lukman Arifin'],
            ['no' => 'Rack 15', 'tahun' => '1992/1993', 'daerah' => 'Perairan Madura(Pamekasan), Jawa Timur', 'ketua_team' => '-'],
            ['no' => 'Rack 16', 'tahun' => '1992/1993', 'daerah' => 'Perairan Madura (Gresik Barat), Jawa Timur', 'ketua_team' => 'Ir. A. Masduki'],
            ['no' => 'Rack 17', 'tahun' => '1992/1993', 'daerah' => 'Perairan Madura(Tanjung Bumi), Jawa Timur', 'ketua_team' => 'Ir. Maman Surachman'],
            ['no' => 'Rack 18', 'tahun' => '1992/1993', 'daerah' => 'Perairan Batam', 'ketua_team' => 'Kris Budiono'],
            ['no' => 'Rack 19', 'tahun' => '1992/1993', 'daerah' => 'Perairan Pelabuhan Ratu, Jawa Barat', 'ketua_team' => 'Ir. K Hardja Widjaksana, M.Sc'],
            ['no' => 'Rack 20', 'tahun' => '1993/1994', 'daerah' => 'Perairan Besuki, Jawa Timur', 'ketua_team' => 'Ir. Kuntoro'],
            ['no' => 'Rack 21', 'tahun' => '1994/1995', 'daerah' => 'Perairan Asembagus - Situbondo, Jawa Timur', 'ketua_team' => 'Ir. Hananto Kurnio, M.Sc'],
            ['no' => 'Rack 22', 'tahun' => '1994/1995', 'daerah' => 'Perairan P. Sapudi, Madura, Jawa Timur', 'ketua_team' => 'Ir. Udaya Kamiludin'],
            ['no' => 'Rack 23', 'tahun' => '1994/1995', 'daerah' => 'Perairan Celukan Bawang, Bali', 'ketua_team' => 'Ir. I Nyoman Astawa'],
            ['no' => 'Rack 24', 'tahun' => '1995/1996', 'daerah' => 'Perairan Yehsani, Bali', 'ketua_team' => 'Ir. Maman Surachman'],
            ['no' => 'Rack 25', 'tahun' => '1995/1996', 'daerah' => 'Perairan Teluk Jakarta', 'ketua_team' => 'Ir. I Nyoman Astawa'],
            ['no' => 'Rack 26', 'tahun' => '1995/1996', 'daerah' => 'Perairan Bengkulu', 'ketua_team' => 'Ir. Nasrun'],
            ['no' => 'Rack 27', 'tahun' => '1996/1997', 'daerah' => 'Perairan Bluto,Madura, Jawa Timur', 'ketua_team' => 'Ir. Achmad Masduki'],
            ['no' => 'Rack 28', 'tahun' => '1997/1998', 'daerah' => 'Perairan Teluk Amet, Bali Timur', 'ketua_team' => 'Ir. I Wayan Lugra'],
            ['no' => 'Rack 29', 'tahun' => '1997/1998', 'daerah' => 'Perairan Kangean', 'ketua_team' => 'Ir. Achmad Masduki'],
            ['no' => 'Rack 30', 'tahun' => '1997/1998', 'daerah' => 'Perairan Cilacap, Teluk Penyu dan sekitarnya', 'ketua_team' => 'Lili Sarmili, M.Sc'],
            ['no' => 'Rack 31', 'tahun' => '1998/1999', 'daerah' => 'Perairan Sumbawa Barat', 'ketua_team' => 'Ir. I Wayan Lugra'],
            ['no' => 'Rack 32', 'tahun' => '1998/1999', 'daerah' => 'Padang, Sumatera Barat', 'ketua_team' => 'Ir. Nasrun'],
        ];

        // Data dari Rack 19
        $dataRack19 = [
            ['no' => 'Rack 1', 'tahun' => '2005', 'daerah' => 'Kawasan Kab. Cirebon, Potensi ESDM', 'ketua_team' => 'Ir. Purnomo Raharjo'],
            ['no' => 'Rack 2', 'tahun' => '2005', 'daerah' => 'Perairan Sebatik, Kabupaten Nunukan Kalimantan Timur', 'ketua_team' => 'Ir. Yogy Noviandi'],
            ['no' => 'Rack 3', 'tahun' => '2005', 'daerah' => 'Perairan Lebak, Banten', 'ketua_team' => 'Ir. Hersenanto Catur Widi'],
            ['no' => 'Rack 4', 'tahun' => '2005', 'daerah' => 'Perairan Kabupaten Sukabumi (potensi mineral)', 'ketua_team' => 'Ir. Denny Setyadi'],
            ['no' => 'Rack 5', 'tahun' => '2005', 'daerah' => 'Perairan Muara Kakap, Kalimantan Barat (Expedisi Gas Biogenik)', 'ketua_team' => 'Ir. Yudi Darlan, M.Sc'],
            ['no' => 'Rack 6', 'tahun' => '2005', 'daerah' => 'Perairan Delta Mahakam, Kalimantan Timur', 'ketua_team' => 'Ir, Delyuzar Ilahude'],
            ['no' => 'Rack 7', 'tahun' => '2005', 'daerah' => 'Perairan Lembar Peta 1809', 'ketua_team' => 'Ir. Delyuzar Ilahude'],
            ['no' => 'Rack 8', 'tahun' => '1990/1991', 'daerah' => 'Perairan Pati (Lembar Peta 1509)', 'ketua_team' => 'Ir, Tjoek Azis Soeprapto'],
            ['no' => 'Rack 9', 'tahun' => '1990/1991', 'daerah' => 'Perairan Lembar Peta 1210', 'ketua_team' => 'Ir Hananto Kurnio'],
            ['no' => 'Rack 10', 'tahun' => '1991/1992', 'daerah' => 'Perairan Lembar Peta 1609', 'ketua_team' => 'Drs. Lukman Arifin'],
            ['no' => 'Rack 11', 'tahun' => '1995/1996', 'daerah' => 'Perairan Lembar Peta 1010 (Selat sunda)', 'ketua_team' => 'Ir. K. Hardja Widjaksana M. Sc'],
            ['no' => 'Rack 12', 'tahun' => '1998/1999', 'daerah' => 'Perairan Paciran dan Sekitarnya, Lamongan, Jawa Timur', 'ketua_team' => 'Ir, Udaya Kamiludin'],
            ['no' => 'Rack 13', 'tahun' => '1999/2000', 'daerah' => 'Perairan Pulau Rupat, Riau', 'ketua_team' => 'Ir, Yudi Darlan'],
            ['no' => 'Rack 14', 'tahun' => '2000', 'daerah' => 'Muara Kampar, Riau', 'ketua_team' => 'Ir. Andy H Sianipar'],
            ['no' => 'Rack 15', 'tahun' => '2000', 'daerah' => 'Perairan Nusanive, Teluk Pangandaran Ciamis, Jawa Barat', 'ketua_team' => 'Lili Sarmil M. Sc'],
            ['no' => 'Rack 16', 'tahun' => '2002', 'daerah' => 'Pantai Tirtanaya, Kab Indramyu, Jawa Barat', 'ketua_team' => 'Ir. Yudi Darlan M. Sc'],
            ['no' => 'Rack 17', 'tahun' => '2003', 'daerah' => 'Delta Mahakam, Kal-Tim', 'ketua_team' => 'Ir. Yudi Darlan M. Sc'],
            ['no' => 'Rack 18', 'tahun' => '2016', 'daerah' => 'Pulau Bangka dan Sekitarnya (Likupang) Minahasa Utara, Sulawesi Utara', 'ketua_team' => 'Catur Purwanto'],
            ['no' => 'Rack 19', 'tahun' => '1997/1998', 'daerah' => 'Dumai dan Sekitarnya, Riau (Lembar peta 0817, 1818, 1916 & 0917)', 'ketua_team' => 'Ir. Maman Surachman'],
            ['no' => 'Rack 20', 'tahun' => '2000', 'daerah' => 'Surve dan Studi Potensi ESDM di Cekungan Jawa Tengah-Jawa Timur Bagian Utara', 'ketua_team' => 'Ir. Maman Surachman'],
            ['no' => 'Rack 21', 'tahun' => '1997/1999', 'daerah' => 'Lombok Timur, NTB', 'ketua_team' => 'Ir. Deny Setiadi, M.T.'],
            ['no' => 'Rack 22', 'tahun' => '2003', 'daerah' => 'Perairan Pamekasan - Sumenep Selat Madura', 'ketua_team' => 'Ir . Risa Rahardiawan'],
        ];

        // Gabungkan semua data
        $allData = array_merge($dataRack16, $dataRack17, $dataRack18, $dataRack19);

        $adminId = 1; // Asumsi admin pertama

        $insertedCount = 0;
        $skippedCount = 0;

        foreach ($allData as $data) {
            // Skip hanya jika benar-benar kosong (bukan '-')
            if (empty($data['daerah']) || 
                empty($data['tahun']) ||
                (empty($data['ketua_team']) && $data['ketua_team'] !== '-')) {
                $skippedCount++;
                continue;
            }

            // Parsing tahun
            $tahun = $this->parseYear($data['tahun']);
            if (!$tahun || $tahun < 1900 || $tahun > 2025) {
                $skippedCount++;
                continue; // Skip jika tahun tidak valid
            }

            // Buat judul survei dari nama daerah
            $judul = $this->createJudulSurvei($data['daerah']);
            
            // Buat deskripsi singkat
            $deskripsi = $this->createDeskripsi($data['daerah']);

            // ✅ CEK DUPLIKASI - berdasarkan wilayah, tahun, dan ketua tim
            $exists = DataSurvei::where('wilayah', $data['daerah'])
                ->where('tahun', $tahun)
                ->where('ketua_tim', $data['ketua_team'])
                ->exists();

            if ($exists) {
                $this->command->info("⏭ Skip (sudah ada): {$judul} ({$tahun}) - {$data['ketua_team']}");
                $skippedCount++;
                continue; // Skip jika data sudah ada
            }

            // Buat data survei baru
            $survei = DataSurvei::create([
                'judul' => $judul,
                'ketua_tim' => $data['ketua_team'],
                'tahun' => $tahun,
                'tipe' => 'Lainnya',
                'wilayah' => $data['daerah'],
                'deskripsi' => $deskripsi,
                'gambar_pratinjau' => null,
                'tautan_file' => null,
                'diunggah_oleh' => $adminId,
            ]);

            $insertedCount++;

            // ========================================
            // DEPRECATED: Sistem marker lama di-nonaktifkan
            // Sekarang menggunakan Grid System - data survei akan di-assign 
            // ke grid kotak secara manual oleh admin via UI
            // ========================================
            // $this->tryCreateLokasiMarker($survei, $data['daerah']);
        }

        $this->command->info("✅ Seeder selesai!");
        $this->command->info("📊 Total diproses: " . count($allData));
        $this->command->info("✅ Berhasil insert: {$insertedCount}");
        $this->command->info("⏭ Di-skip (duplikat/invalid): {$skippedCount}");
    }

    /**
     * Parse tahun dari format "1984/1985" menjadi tahun terakhir (1985)
     */
    private function parseYear($tahunString)
    {
        if (strpos($tahunString, '/') !== false) {
            $years = explode('/', str_replace(' ', '', $tahunString));
            // Ambil tahun terakhir jika ada range
            return (int) trim(end($years));
        }
        
        // Ambil angka pertama jika ada karakter lain
        preg_match_all('/\d{4}/', $tahunString, $matches);
        if (!empty($matches[0])) {
            return (int) $matches[0][0];
        }
        
        return null;
    }

    /**
     * Buat judul survei berdasarkan nama daerah
     */
    private function createJudulSurvei($daerah)
    {
        // Ekstrak nama lokasi utama dari daerah
        $daerah = str_replace(['Perairan ', 'Selat ', 'Teluk '], '', $daerah);
        
        // Ambil nama kota/kabupaten/provinsi pertama yang ditemukan
        $namaLokasi = '';
        
        // Pattern untuk menangkap nama lokasi Indonesia
        if (preg_match('/([A-Z][a-z\s]+(?:Jawa|Bali|Sulawesi|Sumatra|Borneo|Lampung|Banten|Sumbawa|Flores|NTB|NTT))/i', $daerah, $matches)) {
            $namaLokasi = trim($matches[1]);
        } elseif (preg_match('/([A-Z][a-z\s]+(?:Kabupaten|Kab\.))/i', $daerah, $matches)) {
            $namaLokasi = trim(str_replace(['Kabupaten', 'Kab.'], '', $matches[1]));
        } elseif (preg_match('/([A-Z][a-z\s]+)(?=,| dan | dan )/i', $daerah, $matches)) {
            $namaLokasi = trim($matches[1]);
        } elseif (preg_match('/([A-Z][a-z]+)/i', $daerah, $matches)) {
            $namaLokasi = trim($matches[1]);
        }
        
        // Jika tidak ada nama yang ditemukan, gunakan bagian pertama dari daerah
        if (empty($namaLokasi)) {
            $parts = explode(',', $daerah);
            $namaLokasi = trim($parts[0]);
        }
        
        return "Survei {$namaLokasi}";
    }

    /**
     * Buat deskripsi singkat
     */
    private function createDeskripsi($daerah)
    {
        return "Survei kelautan di daerah {$daerah}. Data seismik untuk penelitian potensi sumber daya alam.";
    }


    // Fungsi marker dihapus - sistem sudah menggunakan Grid Kotak
    // Sekarang data survei di-assign ke grid kotak secara manual oleh admin via UI
}