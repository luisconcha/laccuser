<?php
namespace LaccUser\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use Notifiable;

    protected $dates = [ 'deleted_at' ];

    protected $fillable = [
      'name',
      'cor',
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
