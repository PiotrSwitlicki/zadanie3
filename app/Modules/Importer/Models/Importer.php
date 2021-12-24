<?php

namespace App\Modules\Importer\Models;

use App\Core\LogModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Importer extends LogModel
{
	use HasFactory;
    protected $table = 'importer_log';
    protected $primaryKey  = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Importer\Database\factories\WorkOrderFactory::new();
    }

    // relationships

    // scopes

    // getters
}
