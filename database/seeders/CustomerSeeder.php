<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    private array $customers = [
        [
            'first_name' => 'Rahul', 'last_name' => 'Sharma', 'email' => 'rahul.sharma@gmail.com',
            'phone' => '9876543210', 'gender' => 'male', 'date_of_birth' => '1992-03-15',
        ],
        [
            'first_name' => 'Priya', 'last_name' => 'Patel', 'email' => 'priya.patel@gmail.com',
            'phone' => '9812345678', 'gender' => 'female', 'date_of_birth' => '1995-07-22',
        ],
        [
            'first_name' => 'Amit', 'last_name' => 'Kumar', 'email' => 'amit.kumar@yahoo.com',
            'phone' => '9988776655', 'gender' => 'male', 'date_of_birth' => '1988-11-08',
        ],
        [
            'first_name' => 'Sneha', 'last_name' => 'Reddy', 'email' => 'sneha.reddy@gmail.com',
            'phone' => '9765432109', 'gender' => 'female', 'date_of_birth' => '1993-05-30',
        ],
        [
            'first_name' => 'Vikram', 'last_name' => 'Singh', 'email' => 'vikram.singh@outlook.com',
            'phone' => '9654321098', 'gender' => 'male', 'date_of_birth' => '1990-01-12',
        ],
        [
            'first_name' => 'Anjali', 'last_name' => 'Mehta', 'email' => 'anjali.mehta@gmail.com',
            'phone' => '9543210987', 'gender' => 'female', 'date_of_birth' => '1997-09-03',
        ],
        [
            'first_name' => 'Rohan', 'last_name' => 'Gupta', 'email' => 'rohan.gupta@gmail.com',
            'phone' => '9432109876', 'gender' => 'male', 'date_of_birth' => '1985-12-25',
        ],
        [
            'first_name' => 'Kavya', 'last_name' => 'Nair', 'email' => 'kavya.nair@gmail.com',
            'phone' => '9321098765', 'gender' => 'female', 'date_of_birth' => '1994-06-18',
        ],
        [
            'first_name' => 'Arjun', 'last_name' => 'Verma', 'email' => 'arjun.verma@gmail.com',
            'phone' => '9210987654', 'gender' => 'male', 'date_of_birth' => '1991-08-07',
        ],
        [
            'first_name' => 'Deepika', 'last_name' => 'Joshi', 'email' => 'deepika.joshi@gmail.com',
            'phone' => '9109876543', 'gender' => 'female', 'date_of_birth' => '1996-02-14',
        ],
        [
            'first_name' => 'Karthik', 'last_name' => 'Reddy', 'email' => 'karthik.reddy@outlook.com',
            'phone' => '9098765432', 'gender' => 'male', 'date_of_birth' => '1989-04-20',
        ],
        [
            'first_name' => 'Meera', 'last_name' => 'Joshi', 'email' => 'meera.joshi@gmail.com',
            'phone' => '8987654321', 'gender' => 'female', 'date_of_birth' => '1993-10-05',
        ],
        [
            'first_name' => 'Sanjay', 'last_name' => 'Tiwari', 'email' => 'sanjay.tiwari@gmail.com',
            'phone' => '8876543210', 'gender' => 'male', 'date_of_birth' => '1987-07-16',
        ],
        [
            'first_name' => 'Ritu', 'last_name' => 'Agarwal', 'email' => 'ritu.agarwal@gmail.com',
            'phone' => '8765432109', 'gender' => 'female', 'date_of_birth' => '1995-01-28',
        ],
        [
            'first_name' => 'Aditya', 'last_name' => 'Bhatt', 'email' => 'aditya.bhatt@gmail.com',
            'phone' => '8654321098', 'gender' => 'male', 'date_of_birth' => '1992-11-11',
        ],
        [
            'first_name' => 'Pooja', 'last_name' => 'Gupta', 'email' => 'pooja.gupta@gmail.com',
            'phone' => '8543210987', 'gender' => 'female', 'date_of_birth' => '1998-03-22',
        ],
        [
            'first_name' => 'Rajesh', 'last_name' => 'Nambiar', 'email' => 'rajesh.nambiar@gmail.com',
            'phone' => '8432109876', 'gender' => 'male', 'date_of_birth' => '1986-09-09',
        ],
        [
            'first_name' => 'Lakshmi', 'last_name' => 'Iyer', 'email' => 'lakshmi.iyer@gmail.com',
            'phone' => '8321098765', 'gender' => 'female', 'date_of_birth' => '1994-12-01',
        ],
        [
            'first_name' => 'Nikhil', 'last_name' => 'Desai', 'email' => 'nikhil.desai@gmail.com',
            'phone' => '8210987654', 'gender' => 'male', 'date_of_birth' => '1990-05-17',
        ],
        [
            'first_name' => 'Kavitha', 'last_name' => 'Rajan', 'email' => 'kavitha.rajan@gmail.com',
            'phone' => '8109876543', 'gender' => 'female', 'date_of_birth' => '1997-08-29',
        ],
    ];

    private array $addresses = [
        ['label' => 'Home', 'address_line1' => '12, MG Road', 'address_line2' => 'Koramangala', 'city' => 'Bengaluru', 'state' => 'Karnataka', 'pincode' => '560034'],
        ['label' => 'Home', 'address_line1' => '45, Linking Road', 'address_line2' => 'Bandra West', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'pincode' => '400050'],
        ['label' => 'Work', 'address_line1' => '78, Connaught Place', 'address_line2' => null, 'city' => 'New Delhi', 'state' => 'Delhi', 'pincode' => '110001'],
        ['label' => 'Home', 'address_line1' => '23, Anna Salai', 'address_line2' => 'Teynampet', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'pincode' => '600018'],
        ['label' => 'Home', 'address_line1' => '56, Park Street', 'address_line2' => null, 'city' => 'Kolkata', 'state' => 'West Bengal', 'pincode' => '700016'],
        ['label' => 'Work', 'address_line1' => '34, Jubilee Hills', 'address_line2' => null, 'city' => 'Hyderabad', 'state' => 'Telangana', 'pincode' => '500033'],
        ['label' => 'Home', 'address_line1' => '89, CG Road', 'address_line2' => 'Navrangpura', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'pincode' => '380009'],
        ['label' => 'Home', 'address_line1' => '15, FC Road', 'address_line2' => 'Shivajinagar', 'city' => 'Pune', 'state' => 'Maharashtra', 'pincode' => '411005'],
        ['label' => 'Work', 'address_line1' => '101, Whitefield Main Road', 'address_line2' => null, 'city' => 'Bengaluru', 'state' => 'Karnataka', 'pincode' => '560066'],
        ['label' => 'Home', 'address_line1' => '67, Gomti Nagar', 'address_line2' => null, 'city' => 'Lucknow', 'state' => 'Uttar Pradesh', 'pincode' => '226010'],
    ];

    public function run(): void
    {
        foreach ($this->customers as $index => $data) {
            $customer = Customer::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make('password'),
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'is_active' => true,
                'email_verified' => true,
                'email_verified_at' => now()->subDays(rand(1, 60)),
                'accepts_marketing' => rand(0, 100) > 40,
                'login_count' => rand(1, 25),
                'last_login_at' => now()->subDays(rand(0, 14)),
            ]);

            // Create 1-2 addresses per customer
            $addrCount = rand(1, 2);
            $usedIndexes = [];
            for ($a = 0; $a < $addrCount; $a++) {
                do {
                    $addrIndex = array_rand($this->addresses);
                } while (in_array($addrIndex, $usedIndexes));
                $usedIndexes[] = $addrIndex;

                $addr = $this->addresses[$addrIndex];
                CustomerAddress::create([
                    'customer_id' => $customer->id,
                    'label' => $addr['label'],
                    'full_name' => $data['first_name'] . ' ' . $data['last_name'],
                    'phone' => $data['phone'],
                    'address_line1' => $addr['address_line1'],
                    'address_line2' => $addr['address_line2'],
                    'city' => $addr['city'],
                    'state' => $addr['state'],
                    'pincode' => $addr['pincode'],
                    'country' => 'India',
                    'is_default' => $a === 0,
                ]);
            }
        }

        $this->command->info('✅ Customers seeded — ' . Customer::count() . ' customers with ' . CustomerAddress::count() . ' addresses.');
    }
}
