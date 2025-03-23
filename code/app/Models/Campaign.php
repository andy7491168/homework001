<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $primaryKey = 'id';

    public function lineItems()
    {
        return $this->hasMany(LineItem::class, 'campaign_id');
    }

}