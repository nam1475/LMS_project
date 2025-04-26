<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'start_date',
        'expire_date',
        'status',
    ];

    // protected function casts(): array
    // {
    //     return [
    //         'expire_date' => 'datetime',
    //     ];
    // }

    protected static function booted()
    {
        static::retrieved(function ($coupon) {
            if ($coupon->expire_date && Carbon::parse($coupon->expire_date)->lt(now()) && $coupon->status) {
                $coupon->update(['status' => false]);
            }
        }); 
    }

    // Tự động in hoa mã coupon
    // public function code(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn ($value) => strtoupper($value),
    //     );
    // }
    

    public function findCouponCode($code) {
        $couponCode = Coupon::where(['code' => $code, 'status' => 1])->first();
        return $couponCode->code ?? '';
    }
}
