<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\DumpHandler;

class ListingController extends Controller
{
    public static function index(){
        return view('listing.index' , [
            'listings' => Listing::latest()->filter(request(['tag' , 'search']))->paginate(6)
        ]);
    }

    public static function show(Listing $listing){
        
        return view('listing.show' ,[ 'listing' => $listing ]);
    }

    public static function create(){
        return view('listing.create');
    }

    public static function store(Request $request){
        $formValidate = $request->validate([
            'company' => ['required',Rule::unique('listings' , 'company')],
            'title' => 'required' ,
            'location' => 'required' ,
            'email' => ['required' , 'email'] ,
            'website' => 'required' ,
            'tags' => 'required' ,
            'description' => 'required' 
        ]);
        if($request->hasFile('logo')){
            $formValidate['logo'] = $request->file('logo')->store('logos' , 'public');
        }
        $formValidate['user_id'] = auth()->id();
        
        Listing::create($formValidate);
        return redirect('/')->with('message' , 'Listing Created Success' );
    }

    public static function edit(Listing $listing){
        return view('listing.edit',['listing'=>$listing]);
    }

    public static function update(Listing $listing , Request $request){

        if($listing->user_id != auth()->id()){
            abort(403 , 'Unauthorized');
        }
        $formValidate = $request->validate([
            'company' => 'required',
            'title' => 'required' ,
            'location' => 'required' ,
            'email' => ['required' , 'email'] ,
            'website' => 'required' ,
            'tags' => 'required' ,
            'description' => 'required' 
        ]);
        if($request->hasFile('logo')){
            $formValidate['logo'] = $request->file('logo')->store('logos' , 'public');
        }
        $listing->update($formValidate);
        return back()->with('message' , 'Listing Updated Success' );
    }

    public static function delete(Listing $listing){
        if($listing->user_id != auth()->id()){
            abort(403 , 'Unauthorized');
        }
        $listing->delete();
        return redirect('/')->with('message' , 'Listing Deleted Success' );
    }

    public static function manage(){
        return view('listing.manage' , [
            'listings' => auth()->user()->listings()->get()]);
    }

    
}