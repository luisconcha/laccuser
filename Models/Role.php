<?php
namespace LaccUser\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use Notifiable;

    protected $dates = [ 'deleted_at' ];

    protected $fillable = [
      'name',
      'description',
    ];

    public function permissions()
    {
        return $this->belongsToMany( Permission::class );
    }

    public function users()
    {
        return $this->belongsToMany( User::class );
    }
}
