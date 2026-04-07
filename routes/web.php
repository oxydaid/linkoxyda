<?php

use App\Livewire\ShowPage;
use App\Models\AppSetting;
use App\Models\Link;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowPage::class)->name('home');

// Sitemap XML untuk SEO
Route::get('/sitemap.xml', function () {
    $lastUpdated = collect([
        AppSetting::query()->max('updated_at'),
        Link::query()->max('updated_at'),
    ])->filter()->max() ?? now();

    $homeUrl = route('home');

    $response = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $response .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    // Add homepage
    $response .= "    <url>\n";
    $response .= '        <loc>'.htmlspecialchars($homeUrl, ENT_XML1 | ENT_QUOTES, 'UTF-8')."</loc>\n";
    $response .= '        <lastmod>'.$lastUpdated->toAtomString()."</lastmod>\n";
    $response .= "        <changefreq>weekly</changefreq>\n";
    $response .= "        <priority>1.0</priority>\n";
    $response .= "    </url>\n";

    $response .= '</urlset>';

    return response($response, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');
