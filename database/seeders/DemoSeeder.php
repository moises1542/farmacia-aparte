<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        $medicines = [
            'Analgésicos' => [
                ['Paracetamol 500 mg', 3.50, 120],
                ['Ibuprofeno 400 mg', 6.00, 8],
                ['Naproxeno 550 mg', 9.50, 45],
            ],
            'Antibióticos' => [
                ['Amoxicilina 500 mg', 12.00, 5],
                ['Azitromicina 500 mg', 18.50, 30],
                ['Ciprofloxacino 500 mg', 14.00, 22],
            ],
            'Antigripales' => [
                ['Antigripal día y noche', 8.00, 60],
                ['Jarabe para la tos 120 ml', 15.90, 18],
            ],
            'Vitaminas' => [
                ['Vitamina C 1 g', 22.00, 40],
                ['Complejo B', 16.50, 35],
            ],
            'Gastrointestinales' => [
                ['Omeprazol 20 mg', 7.50, 50],
                ['Sales de rehidratación', 2.50, 0],
            ],
            'Antialérgicos' => [
                ['Loratadina 10 mg', 5.50, 75],
            ],
            'Dermatológicos' => [
                ['Clotrimazol crema 1 %', 11.00, 9],
                ['Protector solar FPS 50', 39.90, 12],
            ],
        ];

        $descriptions = [
            'Analgésicos'        => 'Alivian el dolor y la fiebre',
            'Antibióticos'       => 'Tratan infecciones causadas por bacterias',
            'Antigripales'       => 'Alivian los síntomas del resfrío y la gripe',
            'Vitaminas'          => 'Suplementos y complejos vitamínicos',
            'Gastrointestinales' => 'Para la acidez y los malestares digestivos',
            'Antialérgicos'      => 'Controlan los síntomas de las alergias',
            'Dermatológicos'     => 'Cuidado y tratamiento de la piel',
        ];

        foreach ($medicines as $category => $items) {
            $category = Category::firstOrCreate(
                ['name' => $category],
                ['description' => $descriptions[$category]],
            );

            foreach ($items as [$name, $price, $stock]) {
                Medicine::firstOrCreate(
                    ['name' => $name],
                    ['category_id' => $category->id, 'price' => $price, 'stock' => $stock],
                );
            }
        }

        $customers = [
            ['Carlos Pérez Rojas', '45678912', '987111222', 34],
            ['María Quispe Huamán', '41236547', '986222333', 41],
            ['Luis Ramírez Soto', '47896541', '985333444', 28],
            ['Rosa Mendoza Díaz', '40125874', '984444555', 56],
            ['Jorge Castillo Vega', '46321478', null, 63],
            ['Lucía Fernández Paz', '48965412', '983555666', 22],
        ];

        foreach ($customers as [$name, $dni, $phone, $age]) {
            Customer::firstOrCreate(
                ['dni' => $dni],
                ['name' => $name, 'phone' => $phone, 'age' => $age],
            );
        }

        if (Sale::exists()) {
            return;
        }

        $users = User::orderBy('id')->pluck('id');
        $customers = Customer::orderBy('id')->pluck('id');
        $medicines = Medicine::orderBy('id')->get();
        $salesPerDay = [2, 1, 3, 2, 4, 3, 1, 2, 3, 5, 2, 4, 3, 2];
        $number = 0;

        foreach ($salesPerDay as $day => $count) {
            $date = now()->subDays(count($salesPerDay) - 1 - $day);

            for ($i = 0; $i < $count; $i++) {
                $number++;

                $sale = Sale::create([
                    'user_id'     => $users[$number % $users->count()],
                    'customer_id' => $customers[$number % $customers->count()],
                    'date'        => $date->toDateString(),
                    'total'       => 0,
                    'igv'         => 0,
                ]);

                for ($line = 0; $line <= $number % 3; $line++) {
                    $medicine = $medicines[($number * 3 + $line * 5) % $medicines->count()];
                    $amount = 1 + ($number + $line) % 3;

                    $sale->details()->create([
                        'medicine_id' => $medicine->id,
                        'amount'      => $amount,
                        'unit_price'  => $medicine->price,
                        'subtotal'    => round($amount * $medicine->price, 2),
                    ]);
                }

                $sale->calculateTotal();
            }
        }
    }
}
