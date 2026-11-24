<?php
namespace Database\Seeders;
use App\Models\Customer; use App\Models\User; use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  foreach ([['Ana Santos','ana@example.com','09171234567'],['Ben Cruz','ben@example.com','09181234567'],['Cara Reyes','cara@example.com','09191234567'],['David Lim','david@example.com','09201234567'],['Ella Tan','ella@example.com','09211234567']] as [$name,$email,$phone]) Customer::updateOrCreate(['email'=>$email],['full_name'=>$name,'phone'=>$phone]);
  foreach ([['admin','Alex Santos'],['cashier1','Bea Cruz'],['cashier2','Chris Reyes'],['manager','Dana Lim'],['staff','Eli Tan']] as [$username,$name]) User::updateOrCreate(['username'=>$username],['full_name'=>$name,'name'=>$name]);
 }
}
