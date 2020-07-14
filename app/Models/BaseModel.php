<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class BaseModel extends Model
{
    use Userstamps;
}
