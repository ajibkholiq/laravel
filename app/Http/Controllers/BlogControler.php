<?php

namespace App\Http\Controllers;
use  App\Models\blogs;
use Illuminate\Http\Request;
use Storage;


class BlogControler extends Controller
{
    public function index(){
    $blogs = blogs::latest()->paginate(10);
    return view('blog.index', compact('blogs'));
}
    public function create(){
        return view('blog.create');
    }
    public function store(Request $request){
        $this->validate( $request, [
            'image' => 'required|image|mimes:png,jpg,jpeg',
            'title' => 'required',
            'alamat' => 'required',
            'content' => 'required'
            ]);
        $image = $request->file('image');
        $image->storeAs('public/blogs', $image->hashName());
        $blog = blogs::create([
        'image' => $image->hashName(),
        'title' => $request->title,
        'alamat' => $request->alamat,
        'content' => $request->content
        ]);

        if($blog){
            //redirect dengan pesan sukses
            return redirect()->route('blog.index')->with(['success' => 'Data Berhasil Disimpan!']); 
        }else{
            //redirect dengan pesan error 
            return redirect()->route('blog.index')->with(['error' => 'Data Gagal Disimpan!']);
         }
    }
/** 
    * edit
    *
    * @param  mixed $post
    * @return void
    */
    public function edit($blog)
    {
        $data = blogs::find($blog);
        return view('blog.edit', compact('data'));
        // return $blog;
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, $id)
    {
        //validate form
        $post = blogs::findOrFail($id);
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'alamat'     => 'required',
            'content'   => 'required'
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());

            //delete old image
            Storage::delete('public/blogs/'.$post->image);

            //update post with new image
            $post->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'alamat'     => $request->alamat,
                'content'   => $request->content
            ]);

        } else {

            //update post without image
            $post->update([
                'alamat'     => $request->alamat,
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        //redirect to index
        return redirect()->route('blog.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    function destroy($id){
        $data = blogs::findOrFail($id);
        Storage::delete('public/blogs/'.$data->image);
        $data->delete();

        return redirect()->route('blog.index')->with(['success'=> 'data dihapus']);

    }   
    function test (){
        $data = blogs::all();
        return json_encode($data);
    } 
    
}
