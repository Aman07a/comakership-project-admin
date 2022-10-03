<?php

namespace App\Traits;

use App\Models\Broker;

trait BelongsToBroker
{
    public function broker() {
        return $this->belongsTo(Broker::class);
    }
}
