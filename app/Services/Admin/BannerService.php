<?php

namespace App\Services\Admin;

use App\Models\BannerTranslation;
use App\Models\Language;
use App\Repositories\Admin\Banner\BannerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    protected $bannerRepository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function getAllBanners()
    {
        return $this->bannerRepository->getAllBanners();
    }

    public function store(Request $request)
    {
        $languagesInput = $request->input('languages', []);

        $rules = [
            'type' => 'required|in:promotion,sale,seasonal,featured,announcement',
        ];

        foreach (array_keys($languagesInput) as $code) {
            $rules["languages.$code.title"] = 'required|string|max:255';
            $rules["languages.$code.image"] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000';
            $rules["languages.$code.image_title"] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $banner = $this->bannerRepository->createBanner($request->only('type'));

        foreach (array_keys($languagesInput) as $code) {
            $languageData = $request->input("languages.$code");
            $image = $request->file("languages.$code.image");

            $imageUrl = $image ? $image->store('banner_images', 'public') : null;

            BannerTranslation::create([
                'banner_id' => $banner->id,
                'language_code' => $code,
                'title' => $languageData['title'],
                'description' => $languageData['description'] ?? null,
                'image_title' => $languageData['image_title'] ?? null,
                'image_url' => $imageUrl,
            ]);
        }
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'languages.*.title' => 'required|string|max:255',
            'languages.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
            'type' => 'required|in:promotion,sale,seasonal,featured,announcement',
        ]);

        $banner = $this->bannerRepository->getBannerById($id);

        $this->bannerRepository->updateBanner($banner, $request->only('type'));

        foreach ($request->languages as $languageData) {
            $translation = BannerTranslation::where('banner_id', $banner->id)
                ->where('language_code', $languageData['language_code'])
                ->first();

            if ($translation) {
                $imageUrl = null;
                if (isset($languageData['image']) && $languageData['image']) {
                    if ($translation->image_url && Storage::exists($translation->image_url)) {
                        Storage::delete($translation->image_url);
                    }
                    $imageUrl = $languageData['image']->store('public/banner_images');
                }

                $translation->title = $languageData['title'];
                $translation->image_url = $imageUrl ?: $translation->image_url;
                $translation->description = $languageData['description'] ?? $translation->description;
                $translation->save();
            } else {
                $imageUrl = null;
                if (isset($languageData['image']) && $languageData['image']) {
                    $imageUrl = $languageData['image']->store('public/banner_images');
                }

                BannerTranslation::create([
                    'banner_id' => $banner->id,
                    'language_code' => $languageData['language_code'],
                    'title' => $languageData['title'],
                    'description' => $languageData['description'] ?? null,
                    'image_url' => $imageUrl,
                ]);
            }
        }
    }

    public function delete(int $id)
    {
        $banner = $this->bannerRepository->getBannerById($id);
        $this->bannerRepository->deleteBanner($banner);
    }
}
