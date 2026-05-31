<?php

namespace App\Exports;

use App\Models\Textile;
use App\Models\Product;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Limbah Kain'  => new TextilesSheet(),
            'Produk'       => new ProductsSheet(),
            'Ringkasan'    => new SummarySheet(),
        ];
    }
}

class SummarySheet implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle
{
    public function title(): string { return 'Ringkasan'; }

    public function array(): array
    {
        $totalWeight = Textile::whereIn('status',['claimed','processing','completed'])->sum('weight');
        return [
            ['Metrik','Nilai'],
            ['Total Limbah', Textile::count()],
            ['Total Produk', Product::count()],
            ['Total UMKM', User::where('role','upcycler')->count()],
            ['Total Berat Diselamatkan (kg)', number_format((float)$totalWeight,2)],
            ['Total Penghematan (Rp)', number_format((float)$totalWeight * 50000,0,'.',',')],
            ['Tanggal Export', now()->format('d M Y H:i')],
        ];
    }
}

class TextilesSheet implements \Maatwebsite\Excel\Concerns\FromQuery, \Maatwebsite\Excel\Concerns\WithTitle, \Maatwebsite\Excel\Concerns\WithHeadings
{
    public function title(): string { return 'Limbah Kain'; }

    public function headings(): array
    {
        return ['ID','Judul','Kontributor','Jenis Bahan','Berat (kg)','Alamat','Status','Tanggal'];
    }

    public function query()
    {
        return Textile::with('owner')->select('id','title','user_id','fabric_type','weight','address','status','created_at');
    }

    public function map($textile): array
    {
        return [
            $textile->id,
            $textile->title,
            $textile->owner?->name ?? '—',
            ucfirst($textile->fabric_type ?? '—'),
            $textile->weight,
            $textile->address ?? '—',
            ucfirst($textile->status),
            $textile->created_at->format('d/m/Y'),
        ];
    }
}

class ProductsSheet implements \Maatwebsite\Excel\Concerns\FromQuery, \Maatwebsite\Excel\Concerns\WithTitle, \Maatwebsite\Excel\Concerns\WithHeadings
{
    public function title(): string { return 'Produk'; }

    public function headings(): array
    {
        return ['ID','Nama Produk','UMKM/Upcycler','Limbah Asal','Harga (Rp)','Tanggal Upload'];
    }

    public function query()
    {
        return Product::with('upcycler','textile')->select('id','product_name','upcycler_id','textile_id','price','created_at');
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->product_name,
            $product->upcycler?->name ?? '—',
            $product->textile?->title ?? '—',
            $product->price,
            $product->created_at->format('d/m/Y'),
        ];
    }
}
