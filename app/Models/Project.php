<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'reservation_cost',
        'mailchimp_server',
        'mailchimp_api_key',
        'mailchimp_list_id',
        'lead_redirect_url',
        'reservation_redirect_url',
        'data_app_project_id',
        'stripe_key',
        'stripe_secret',
        'pixel_id',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];
}
