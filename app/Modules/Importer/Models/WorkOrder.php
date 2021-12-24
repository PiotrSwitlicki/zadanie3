<?php

namespace App\Modules\Importer\Models;

use App\Core\LogModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrder extends LogModel
{
    use HasFactory;

    protected $table = 'work_order';
    protected $primaryKey  = 'work_order_id';

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Importer\Database\factories\WorkOrderFactory::new();
    }
 
}
