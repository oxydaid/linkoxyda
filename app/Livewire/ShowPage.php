<?php

namespace App\Livewire;

use App\Models\AppSetting;
use App\Models\Link;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ShowPage extends Component
{
    #[Layout('components.layouts.app')]
    public function handleClick(int $linkId)
    {
        $link = Link::query()
            ->select(['id', 'url'])
            ->find($linkId);

        if ($link) {
            $link->increment('clicks');

            return redirect()->away($link->url);
        }

        return null;
    }

    protected function resolveSettings(): AppSetting
    {
        return AppSetting::current();
    }

    protected function resolveLinks()
    {
        return Link::query()
            ->active()
            ->ordered()
            ->get();
    }

    public function render()
    {
        $settings = $this->resolveSettings();
        $links = $this->resolveLinks();

        return view('livewire.show-page', [
            'settings' => $settings,
            'links' => $links,
        ])->layout('components.layouts.app', ['settings' => $settings]);
    }
}
