<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\SiteData;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $messageCount = ContactMessage::where('is_read', false)->count();
        $siteDataCount = SiteData::count();

        return Inertia::render('Dashboard', [
            'stats' => [
                'messages' => $messageCount,
                'data_entries' => $siteDataCount
            ]
        ]);
    }
}
