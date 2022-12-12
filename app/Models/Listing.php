<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    protected $fillable =[
        'company' , 'title' , 'tags' , 'description' , 'location' ,
        'email' , 'website' , 'logo' , 'user_id'
    ];
    use HasFactory;
    
    public function scopeFilter($query , array $filters){
        if($filters['tag'] ?? false){
            $query->where('tags','like','%'.request('tag').'%');
        };
        
        if($filters['search'] ?? false){
            $query->where('tags','like','%'.request('search').'%')
            ->orWhere('title','like','%'.request('search').'%')
            ->orWhere('description','like','%'.request('search').'%')
            ->orWhere('location','like','%'.request('search').'%')
            ->orWhere('company','like','%'.request('search').'%');
        };
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
