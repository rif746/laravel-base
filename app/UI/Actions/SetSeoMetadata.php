<?php

namespace App\UI\Actions;

use App\Attributes\Seo;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class SetSeoMetadata
{
    public function __construct(protected ResolveDynamicText $textResolver) {}

    public function applySeo(Seo $seo, array|object $viewData, Request $request): void
    {
        $rawData = [
            'title' => $seo->title,
            'description' => $seo->description,
            'keywords' => is_array($seo->keywords) ? implode(', ', $seo->keywords) : $seo->keywords,
            'image' => $seo->image,
            'context_target' => $seo->context, // 👈 Save context target
        ];

        $this->updateSeoTools(rawData: $rawData, viewData: $viewData, request: $request);
    }

    public function updateSeoTools(array $rawData, array|object $viewData, Request $request): void
    {
        $contextTarget = $rawData['context_target'] ?? null;

        // Pass the context target to the text resolver
        $title = $this->textResolver->execute(value: $rawData['title'], context: $viewData, contextTarget: $contextTarget);
        $description = $this->textResolver->execute(value: $rawData['description'], context: $viewData, contextTarget: $contextTarget);
        $keywords = $this->textResolver->execute(value: $rawData['keywords'], context: $viewData, contextTarget: $contextTarget);
        $image = $rawData['image'];

        SEOTools::setTitle($title ?? __('seo.default_title'));
        SEOTools::setDescription($description ?? __('seo.default_description'));
        SEOTools::metatags()->setCanonical($request->url());

        if ($keywords) {
            SEOTools::metatags()->setKeywords($keywords);
        }
        if ($image) {
            SEOTools::addImages($image);
            SEOTools::opengraph()->addProperty('image', asset($image));
        }
    }
}
