<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslations
{
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Ambil nilai field untuk locale tertentu, fallback ke kolom asli (bahasa default) kalau tidak ada.
     * Contoh: $post->translate('title', 'en')
     */
    public function translate(string $field, string $locale): string
    {
        $translation = $this->translations
            ->firstWhere(fn ($t) => $t->locale === $locale && $t->field === $field);

        return $translation?->value ?? $this->{$field} ?? '';
    }

    public function setTranslation(string $field, string $locale, string $value): void
    {
        $this->translations()->updateOrCreate(
            ['locale' => $locale, 'field' => $field],
            ['value' => $value]
        );
    }
}
