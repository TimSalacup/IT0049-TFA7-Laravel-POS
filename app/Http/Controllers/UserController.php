<?php
namespace App\Http\Controllers;
use App\Models\User; use Illuminate\Http\Request; use Illuminate\Support\Facades\Storage; use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager; use Intervention\Image\Drivers\Gd\Driver;
class UserController extends Controller {
 public function index(){ return view('users.index',['users'=>User::orderBy('username')->get()]); }
 public function create(){ return view('users.form',['user'=>new User]); }
 public function store(Request $request){ $data=$request->validate(['username'=>['required','string','max:255',Rule::unique('users')], 'full_name'=>'required|string|max:255']); User::create($data); return redirect()->route('users.index')->with('success','User added.'); }
 public function edit(User $user){ return view('users.form',compact('user')); }
 public function update(Request $request, User $user){
  $data=$request->validate(['username'=>['required','string','max:255',Rule::unique('users')->ignore($user)], 'full_name'=>'required|string|max:255','avatar'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:2048']);
  $old=$user->avatar; $path=null;
  if($request->hasFile('avatar')) { $image=(new ImageManager(new Driver()))->read($request->file('avatar')->getRealPath())->cover(256,256)->toJpeg(85); $path='avatars/'.\Illuminate\Support\Str::uuid().'.jpg'; if(!Storage::disk('public')->put($path,(string)$image)) throw new \RuntimeException('Could not store the avatar.'); $data['avatar']=$path; }
  try { $user->update($data); } catch(\Throwable $e) { if($path) Storage::disk('public')->delete($path); throw $e; }
  if($path && $old) Storage::disk('public')->delete($old);
  return redirect()->route('users.index')->with('success','User updated.');
 }
}
