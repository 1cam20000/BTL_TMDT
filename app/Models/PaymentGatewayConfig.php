<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentGatewayConfig extends Model
{
    protected $fillable = ['gateway_id', 'key_name', 'key_value', 'is_encrypted', 'environment'];

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'gateway_id');
    }

    public function setKeyValueAttribute($value)
    {
        if ($this->is_encrypted) {
            $this->attributes['key_value'] = Crypt::encryptString($value);
        } else {
            $this->attributes['key_value'] = $value;
        }
    }

    public function getKeyValueAttribute($value)
    {
        if ($this->is_encrypted) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }

        return $value;
    }
}
