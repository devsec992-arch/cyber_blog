<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;


class UserController extends Controller
{
    // UNSECURE
   // public function show($id)
	//{
      //  if(auth()->id() != $id)
       // {
      //      return redirect()->with('message' => 'Forbidden Operation');
        //}

        //$user = User::findOrFail($id);

      //  return view('auth.profile',compact('user'));
	//}

    // SECURE
    public function profile(){
     if(!$user = Auth::user())
      return response()->json(['message' => 'Forbidden Operation'], 403);
        
         return view('auth.profile',compact('user'));
    }

    public function update(Request $request, $id){
        $user = User::find($id);

        $user->update($request->all());

        return back()->with('message','User updated');
    }
    public function changeEmail(Request $request){
        
        if(!$user = Auth::user())
        return response()->json(['message' => 'Forbidden Operation'], 403);
        
        $user->email = $request->email;
        $user->save();
        
        return back()->with('message','Changed successfully');
    }
    
    public function changeName(Request $request)
    {
        if(!$user = Auth::user())
        return response()->json(['message' => 'Forbidden Operation'], 403);
        
        $user->name = $request->name;
        $user->save();
        
        return back()->with('message','Changed successfully');
    }
    
    public function changeImg(Request $request)
    {
        if(!$user = Auth::user()){
            return back()->with('message','Please Log In');
        }
        
        if(!$request->hasFile('avatar')) {
            return back()->with('message','Forbidden Operation');
        }
        
        if (!file_exists(storage_path("app/public/images/users/".$user->id))) {
            mkdir(storage_path("app/public/images/users/".$user->id), 0777, true);
        }

        // retrieve uploaded image
        $newImage = $request->file('avatar');
        // calculate hash

        // UNSECURE with md5
        //$newImageHash = md5_file($newImage);

        // SECURE with sha56
        $newImageHash = hash_file('sha256', $newImage);
    
        // compare hash
        if($newImageHash == $user->avatar){
            return redirect()->back()->with('message','Image not updated, same');
        }
        // Define the path to store the image
        $path = "images/users/".$user->id;

       
        Storage::deleteDirectory($path);
    
        
        // Store the image in the defined path
        $filePath = $newImage->storeAs($path, $newImageHash, 'public');
    
        // save new user avatar name
        $user->avatar = $newImageHash;
        $user->save();

        return redirect()->back()->with('message','Image updated');
    }

    public function download(Request $request) {


        return response()->download(storage_path('app/private/'.$request->get('filename')));
    }



    public function upload(Request $request) {
        if(!$user = Auth::user()){
            return back()->with('message','Please Log In');
        }
        
        if(!$request->hasFile('file')) {
            return back()->with('message','Forbidden Operation');
        }
//        $path = storage_path("app/public/docs/users/".$user->id);
        



    //    if (!file_exists($path)) {
         //   mkdir($path, 0777, true);
      //  }

//        // retrieve uploaded file
        //$newFile = $request->file('file');
        //$filename = $newFile->getClientOriginalName();
       // $newFile->move($path, $filename);
       // File::create([
        //    'user_id' => $user->id,
          //  'filename' => $filename,
      //  ]);

        // calculate hash

      

        // SECURE with 
        //define allowed file types
        $allowedExtension = ['pdf', 'jpeg', 'png', 'gif','jpg'];
        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        if (!in_array($extension, $allowedExtension) || !in_array($mimeType, $allowedMimeTypes)) {
            return redirect()->back()->with('message','File type not allowed');
        }
        $filename = $file->getClientOriginalName();
        $fileuid = uniqid() . '_' . $extension;
        $path=$file->storeAs('docs/users/'.$user->id, $fileuid, 'local');
        //save file nell database
        File::create([
            'user_id' => $user->id,
            'name' => $filename,
            'uid' => $fileuid,]);
    
                
        return redirect()->back()->with('message','File updated');
    }
    public function downloadfile($file) {
        if(!$user = Auth::user()){
            return back()->with('message','Please Log In');
        }
                $fileRecord = File::where('uid', $file)->where('user_id', $user->id)->firstOrFail();

       // $fileRecord = File::where('uid', $file)->where('user_id', $user->id)->firstOrFail();

        IF(!$fileRecord) {
            return back()->with('message','File not found');
                    }

        
        $path = "docs/users/{$user->id}/{$fileRecord->uid}";
        if (!Storage::disk('local')->exists($path))
            {abort(404, 'File not found');}
        return Storage::disk('local')->download($path,$fileRecord->uid);
        
       
    }
}
