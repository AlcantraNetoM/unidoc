<?php
/**
 * IDE Helper for Laravel Application
 * This file helps IDEs understand Laravel's dynamic methods and relationships
 */

namespace Illuminate\Support\Facades {
    /**
     * @method static \App\Models\User|null user()
     */
    class Auth extends \Illuminate\Support\Facades\Auth {}
}

namespace Illuminate\Contracts\Auth {
    /**
     * @mixin \App\Models\User
     */
    interface Authenticatable {}
}
