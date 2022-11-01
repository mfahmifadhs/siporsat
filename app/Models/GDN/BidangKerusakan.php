<?php

namespace App\Models\gdn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangKerusakan extends Model
{
    use HasFactory;
    protected $table        = "gdn_tbl_bid_kerusakan";
    protected $primaryKey   = "id_bid_kerusakan";
    public $timestamps      = false;

    protected $fillable = [
        'id_bid_kerusakan',
        'jenis_bid_kerusakan',
        'bid_kerusakan'
    ];
}
