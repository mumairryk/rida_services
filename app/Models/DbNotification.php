<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class DbNotification extends DatabaseNotification
{
    protected $table = 'db_notifications';

    protected $fillable = [
        'id', 'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at', 'created_at', 'updated_at','related_to'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const Order = 'Order';
    public const Veterinary = 'Veterinary';
    public const DoggyPlay = 'DoggyPlay';
    public const Boarding  ='Boarding';
    public const Grooming ='Grooming';
    public const DayCare  ='Day Care';
    

    const NotificationTypes = [
        self::Order => 'Order',
        self::Veterinary => 'Veterinary',
        self::DoggyPlay=>"DoggyPlay",
        self::Boarding=>"Boarding",
        self::Grooming=>"Grooming",
        self::DayCare=>"Day Care"
       
    ];

    
}
