<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class FilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->key = Storage::disk("local")->get("key.txt");
    }

    /**
     * Show the application page for files.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function content()
    {
        return view('files', [
            "files" => $this->getFilesFromUser(),
            "ciphers" => $this->availableCiphers()
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard(){
        return view("dashboard", [
            "files" => $this->getPublicPictureFiles()
        ]);
    }

    /**
     * Function to return all user files
     */
    private function getFilesFromUser()
    {
        $files = Auth::user()->files()->get();
        
        return $files;
    }

    /**
     * Function to digest POST body to upload new file, POST body requires the fields `fileToUpload` file, `encryptMethod` string, `visibility` string
     * 
     * @param Request $request takes a request with fields given
     */
    public function newFile(Request $request)
    {   
        if($request->hasFile("fileToUpload")){
            $validated = $request->validate([
                "fileToUpload" => "required|max:5120",
                "encryptMethod" => "required|in:" . $this->availableCiphers("commaString"),
                "visibility" => "required|in:public,private"
            ]); //5MB limit
            $cipher = $request->input("encryptMethod", "NONE");
            if(in_array($cipher, openssl_get_cipher_methods())){
                $input = $request->file("fileToUpload");
                $content = base64_encode(file_get_contents($input->getRealPath()));

                $iv_len = openssl_cipher_iv_length($cipher);
                $tag_len = 16;
                $iv = openssl_random_pseudo_bytes($iv_len);
                $tag = "";

                $encryptedData = openssl_encrypt($content, $cipher, $this->key, OPENSSL_RAW_DATA, $iv, $tag, "", $tag_len);

                if($encryptedData === false){
                    dd(openssl_error_string());
                }

                $encryptedData = base64_encode($encryptedData);

                $file = new File();
                $file->filename = pathinfo($input->getClientOriginalName(), PATHINFO_FILENAME);
                $file->extension = $input->getClientOriginalExtension();
                $file->size = $input->getSize();
                $file->MIME = $input->getClientMimeType();

                $file->cipher = $cipher;
                $file->iv = base64_encode($iv);
                $file->tag = base64_encode($tag);

                $file->visibility = $request->input("visibility", "public");
                $file->user_id = Auth::user()->id;
                $file->save();

                $file->file_data()->create(["data" => $encryptedData, "file_id" => $file->id]);
                $file->save();

                return redirect(App::getLocale() . "/files");
            } else {
                $input = $request->file("fileToUpload");
                $content = file_get_contents($input->getRealPath());
                $content = base64_encode($content);
    
                $file = new File();
    
                $file->filename = pathinfo($input->getClientOriginalName(), PATHINFO_FILENAME);
                $file->extension = $input->getClientOriginalExtension();
                $file->size = $input->getSize();
                $file->MIME = $input->getClientMimeType();

                $file->cipher="NONE";
                $file->iv = "NONE";
                $file->tag="NONE";

                $file->visibility = $request->input("visibility", "public");
                $file->user_id = Auth::user()->id;
                $file->save();
    
                $file->file_data()->create(["data"=>$content,"file_id"=>$file->id]);
                $file->save();
                
                return redirect(App::getLocale() . "/files");
            }
        } else {
            return redirect(App::getLocale() . "/");
        }
    }
    
    /**
     * Function to list available tested ciphers
     * 
     * @param string $mode has value `values` by default so it returns array of string, `commaString` returns all array members concatenated with a comma between, used in validation
     */
    private function availableCiphers($mode = "values"){
        $available = [
            "NONE",
            "aes-128-gcm",
            "aes-192-gcm",
            "aes-256-gcm",
            "aes-128-cbc",
            "aes-192-cbc",
            "aes-256-cbc",
            "aria-128-gcm",
            "aria-192-gcm",
            "aria-256-gcm",
            "chacha20",
            "camellia-128-cbc",
            "camellia-128-cfb",
            "camellia-128-ctr",
            "camellia-128-ofb"
        ];

        if($mode === "commaString"){
            $temp = $available[0];
            foreach ($available as $c) {
                if($c !== $available[0])
                    $temp .= "," .$c;
            }
            return $temp;
        } else if($mode === "values") {
            return $available;
        }
    }

    /**
     * Function for providing the file for direct download without opening another tab
     */
    public function downloadFile($locale, $id){
        return $this->download($id, "attachment");
    }

    /**
     * Function for providing the file for a target="_blank" link so it displays inline, not as a download
     */
    public function viewFile($locale, $id){
        return $this->download($id, "inline");
    }

    /**
     * General function to get the file, decrypt it and serve it depending on the $disposition argument.
     * 
     * @param int $id The file id
     * 
     * @param string $disposition  By default has value `attachment` so it downloads without opening another tab, `inline` for file viewing (if possible)
     * 
     */
    public function download($id, $disposition = 'attachment'){
        $file = File::find($id);
        if($file === null){
            return redirect(App::getLocale() . "/dashboard");
        }

        if($file->visibility === "public" || $file->visibility === "private" && $file->user_id === Auth::user()->id){
            if($file->cipher === "NONE"){
                $content = $file->file_data->data;
            } else {
                $content = openssl_decrypt(base64_decode($file->file_data->data), $file->cipher, $this->key, OPENSSL_RAW_DATA, base64_decode($file->iv), base64_decode($file->tag));

                if($content === false){
                    //not meant for production
                    dd(openssl_error_string());
                }
            }

            return response()->streamDownload(function() use ($content) {
                echo base64_decode($content);
            }, $file->filename . "." . $file->extension, [
                "Content-Type" => $file->MIME,
                "Content-Disposition" => $disposition,
                "filename" => '"' . $file->filename . '.' . $file->extension . '"'
            ], $disposition);
        } else {
            return redirect(App::getLocale() . "/dashboard");
        }
    }

    /**
     * Delete the file via id given in POST body, has to be the owners file
     */
    public function deleteFile(Request $request){
        $validated = $request->validate(["id" => "required|numeric"]);
        $id = $request->input("id");

        Auth::user()->files()->where("id",$id)->delete();
        return redirect(App::getLocale() . "/files");
    }

    /**
     * Changes the visibility of a file, requires the file id in the POST body
     */
    public function changeVisibility(Request $request){
        $validated = $request->validate([
            "id" => "required|numeric",
            "wish" => "required|in:public,private"
        ]);
        $id = $request->input("id");

        $file = File::find($id);
        $file->visibility = $request->input("wish");
        $file->save();

        return redirect(App::getLocale() . "/files");
    }

    /**
     * Fetches 30 latest public pictures for dashboard.["image/bmp","image/jpeg","image/jpg","image/png","image/svg+xml"]
     */
    private function getPublicPictureFiles(){
        $pictures = File::whereIn("MIME",["image/bmp","image/jpeg","image/jpg","image/png","image/svg+xml"])->where("visibility","public")->latest()->take(30)->get();
        //dd($pictures);
        return $pictures;
    }

    /**
     * Function to serve a web route, used in GET forms.
     * 
     * @param Request $request The request body in which the username query lies
     */
    public function viewUserFilesGetRequest(Request $request){
        $validate = $request->validate(["username" => "required|string|max:255"]);

        if($request->query("username")){
            return $this->viewUserFiles(" ", $request->query("username"));
        } else {
            return view("/viewUserFiles", ["exists" => "no", "files" => []]);
        }
        
    }

    /**
     * Function to serve as an endpoint for a web URL, used in url() to directly link to a users files
     * 
     * @param string $locale not used, only here to soak up the GET route parameters
     * @param string $username The username to look for
     */
    public function viewUserFiles($locale, $username){
        $user = $this->getUserFromUsername($username);

        if($user){
            return view("/viewUserFiles", ["exists" => "yes", "files" => $user->files()->where("visibility", "public")->get(), "user" => $user]);
        } else {
            return view("/viewUserFiles", ["exists" => "no", "files" => []]);
        }
    }

    /**
     * Helper function for viewUserFiles functions
     * 
     * @param string $username The username to look for, names are unique so it's ok to look for them
     * 
     * @return string if no user found
     * @return array array of files if user is found
     */
    private function getUserFromUsername($username){
        $user = User::where("name", $username)->get();
        if($user->isEmpty()){
            return null;
        } else {
            return $user[0];
        }
    }
}
