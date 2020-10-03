<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
* model for quiz:failed_jobs
*
*/
class failedJobs extends Model {

    public $timestamps = false;
    protected $table = 'failed_jobs';

}