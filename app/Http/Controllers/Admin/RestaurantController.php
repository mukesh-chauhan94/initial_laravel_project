<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Restaurant;
use App\RestaurantImage;
use File;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(\Gate::allows('user_access'), 403);

        $restaurantData = Restaurant::with('restaurantImage')->get()->toArray();
        
        return view('admin.restaurant.index', compact('restaurantData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|unique:restaurant,email',
                'description' =>'required',
                'phone_number' =>'required',
                'code' =>'required',
                'image' =>'required',
            ],[
                'name.required' => "Name should not be blank.",
                'name.max' => "Name in 255 character.",
                'email.required' => "Email address should not be blank.",
                'email.unique' => "Email address already exists.",
                'phone_number.required' => "Contact number should not be blank.",
                'phone_number.max' => "Contact number may not be greater than 15 digits.",
                'phone_number.min' => "Contact number must be at least 10 digits.",
                'image.required' => "Image should not be blank.",
                'description.required' => "Description should not be blank.",
            ]);
        $request_all = $request->all();
        $RestaurantDetail = Restaurant::firstOrNew(['id'=>$request->get('id')]);
        $RestaurantDetail->fill($request_all);
        
        $RestaurantDetail->save();
        $restaurantId = $RestaurantDetail->id;

        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $imagePath = public_path("image");
            
            if(!empty($files)){
                $image = $files;
                $imageName = time().$image->getClientOriginalname();
                if (!file_exists($imagePath)) {
                   mkdir($imagePath, 0777);
                   chmod($imagePath, 0777);
                }
                $image->move($imagePath, $imageName);
            }   
            $RestaurantImageDetail = RestaurantImage::firstOrNew(['id'=>'']);
            $RestaurantImageDetail->restaurant_id = $restaurantId;
            $RestaurantImageDetail->image_name = $imageName;
            $RestaurantImageDetail->save();

        }
        return response()->json(['status'=>true],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editData(Request $request)
    {
        $id = $request->get('id');

        $RestaurantDetail = Restaurant::find($id);
        $RestaurantImage = RestaurantImage::where('restaurant_id',$id)->get();
        return response()->json(['status'=>true,'RestaurantDetail'=>$RestaurantDetail,'RestaurantImage'=>$RestaurantImage]);
    }
    
    public function show(Request $request)
    {
        $id = $request->get('id');

        $RestaurantDetail = Restaurant::find($id);
        $RestaurantImage = RestaurantImage::where('restaurant_id',$id)->get();
        return response()->json(['status'=>true,'RestaurantDetail'=>$RestaurantDetail,'RestaurantImage'=>$RestaurantImage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|unique:restaurant,email,'.$request->get('id'),
                'description' =>'required',
                'phone_number' =>'required',
                'code' =>'required',
            ],[
                'name.required' => "Name should not be blank.",
                'name.max' => "Name in 255 character.",
                'email.required' => "Email address should not be blank.",
                'email.unique' => "Email address already exists.",
                'phone_number.required' => "Contact number should not be blank.",
                'phone_number.max' => "Contact number may not be greater than 15 digits.",
                'phone_number.min' => "Contact number must be at least 10 digits.",
                'description.required' => "Description should not be blank.",
            ]);
        $request_all = $request->all();
        $RestaurantDetail = Restaurant::firstOrNew(['id'=>$request->get('id')]);
        $RestaurantDetail->fill($request_all);        
        $RestaurantDetail->save();
        $restaurantId = $RestaurantDetail->id;

        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $RestaurantImageDetail = RestaurantImage::where(['restaurant_id'=>$restaurantId])->get()->first();
            
            $imagePath = public_path("image");            
            $oldImage = public_path("image/").$RestaurantImageDetail['image_name']; 
                unlink($oldImage);
            if(!empty($files)){
                $image = $files;
                $imageName = time().$image->getClientOriginalname();
                if (!file_exists($imagePath)) {
                   mkdir($imagePath, 0777);
                   chmod($imagePath, 0777);
                }
                $image->move($imagePath, $imageName);
            }   
            $RestaurantImageDetail->restaurant_id = $restaurantId;
            $RestaurantImageDetail->image_name = $imageName;
            $RestaurantImageDetail->save();

        }
        return response()->json(['status'=>true],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        $id =  $request->get('ids');
        Restaurant::whereIn('id', $id)->delete();
        foreach ($id as $key => $value) {
            $restaurantImage = RestaurantImage::where('restaurant_id', $value)->value('image_name');
            $oldImage = public_path("image/").$restaurantImage; 
            unlink($oldImage);
            $delete = RestaurantImage::where('restaurant_id', $value)->delete();
            # code...
        }

        return response(null, 204);
    }
    public function destroy(Request $request)
    {

        $id =  $request->get('id');
        Restaurant::where('id', $id)->delete();
         $restaurantImage = RestaurantImage::where('restaurant_id', $id)->value('image_name');
        $oldImage = public_path("image/").$restaurantImage; 
        unlink($oldImage);
        $delete = RestaurantImage::where('restaurant_id', $id)->delete();
        return back();
    }
}

