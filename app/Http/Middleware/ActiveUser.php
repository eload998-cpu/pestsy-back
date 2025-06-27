<?php
namespace App\Http\Middleware;

use App\Models\Status;
use App\Models\StatusType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user        = Auth::user();
        $status_type = StatusType::where('name', 'user')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'inoperative')->first();

        if ($user->status_id == $status->id) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('Error', 'Cuenta expirada, comuníquese con el administrador');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $next($request);
    }
}
