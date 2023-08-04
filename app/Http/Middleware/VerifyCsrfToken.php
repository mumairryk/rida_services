<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'admin/Excel/*',
        'Excel/upload_file',
        'update_location',
        '/update_location',
        'admin/doctors/get_events',
        'admin/doctors/add_event',
        'admin/doctors/remove_event',
        'admin/groomers/get_events',
        'admin/groomers/add_event',
        'admin/groomers/remove_event'
    ];
}
