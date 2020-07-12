<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class BaseModel extends Model
{
    use Userstamps;
}
