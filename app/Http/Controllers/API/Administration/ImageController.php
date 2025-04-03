<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Administration\Order\Image\{CreateImageRequest,UpdateImageRequest};

use App\Models\Module\{Image};

use Storage,DB;

class ImageController extends Controller
{
    private $image;
    private $paginate_size=3;

    public function __construct(Image $image)
    {
        $this->image=$image;

    }

    //
    public function index(Request $request)
    {
        $images = $this->image
        ->select('images.*')
        ->leftJoin('orders','images.order_id','orders.id')
        ->where('orders.id',$request->order_id);

        $images= $images->orderBy("images.created_at","desc");

        
        $images=$images->paginate($this->paginate_size);
        $images=parsePaginator($images);

        return response()->json($images);
    }


    public function store(CreateImageRequest $request)
    {
       
        $data=DB::transaction(function () use ($request) {
        $path = "/public/order/{$request->order_id}/images";
        if(!Storage::exists($path))
        {
            Storage::makeDirectory($path,0755);
            
            $folder_path=str_replace('public','storage',$path);

        }

        foreach ($request->images as $key => $value) 
        {
            // Getting file name
            $filename = rand().'_'.$value->getClientOriginalName();
            // Location
             $location = storage_path()."/app/public/order/{$request->order_id}/images/{$filename}";
            // Compress Image
            optimizeImage($value,$location,60);
            $storage_link="/storage/order/{$request->order_id}/images/{$filename}";

            $image = Image::create([
                    'file_name' => $storage_link,
                    'order_id'  =>$request->order_id
    
            ]);
          
        }
        exec('chmod -R 755 '.storage_path().'/app/public/order');

        });

        return response()->json(
            ["success"=>true,
             "data"=>[],
             "message"=>"Exito!"
            ]
        );
        

    }




    public function update(UpdateImageRequest $request, $id)
    {

        DB::transaction(function () use ($request,$id) {

        $data=$request->all();
        unset($data["_method"]); 


        $trap=Trap::where('id',$id)->update($data);

        });

        return response()->json(['success'=>true,'message'=>'Exito']);

    }


       /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model=Image::find($id);
        return response()->json(['success'=>true,'data'=>$model]);

    }


       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image=Image::destroy($id);
        return response()->json(['success'=>true,'message'=>'Exito']);

    }
}
