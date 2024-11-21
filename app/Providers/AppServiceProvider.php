<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\RequestDocument;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('employee_id', function ($attribute, $value, $parameters, $validator) {
        return true;
    });

    View::composer('layouts.admin_layout', function ($view) {
        // Logic for pendingCount
        $documentPendingCount = Document::where('document_status', 'Pending')->count();
        $requestPendingCount = RequestDocument::where('approval_status', 'pending')->count();
        $pendingCount = $documentPendingCount + $requestPendingCount;

        // Logic for notificationCount
        $forwardPendingCount = DB::table('forwarded_documents')->where('status', 'delivered')->count();
        $sendPendingCount = DB::table('send_document')->where('status', 'delivered')->count();
        $notificationCount = $forwardPendingCount + $sendPendingCount;

        $view->with([
            'documentPendingCount'=>$documentPendingCount,
            'requestPendingCount'=> $requestPendingCount,
            'pendingCount' => $pendingCount,
            'notificationCount' => $notificationCount,
        ]);
    });
        // View::composer('layouts.admin_layout', function ($view) {
        //     $user = Auth::user();
        //     $firstInitial = strtoupper(preg_replace('/[^A-Za-z]/', '', substr($user->first_name, 0, 2)));
        //     $lastInitial = strtoupper(substr($user->last_name, 0, 1));
        //     $initials = $firstInitial . $lastInitial;            
        //     $view->with('initials', $initials);
        // });
        
    }
}
