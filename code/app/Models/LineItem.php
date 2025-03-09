<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    use HasFactory;
    protected $fillable = ['name','booked_amount','actual_amount','campaign_id'];
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
