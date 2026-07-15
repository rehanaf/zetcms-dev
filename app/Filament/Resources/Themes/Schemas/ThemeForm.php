<?php

namespace App\Filament\Resources\Themes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ThemeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique('themes', 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                \App\Filament\Helpers\MediaSelectHelper::make('screenshot_id', 'Screenshot', isRelation: true),
                TextInput::make('version')
                    ->required()
                    ->default('1.0.0'),
                TextInput::make('author'),
                Toggle::make('is_active')
                    ->required()
                    ->default(false),
                \Filament\Forms\Components\Section::make('Theme Settings')
                    ->schema(function (?\App\Models\Theme $record) {
                        if (!$record) return [];
                        
                        $jsonPath = resource_path("views/themes/{$record->slug}/theme.json");
                        if (!file_exists($jsonPath)) return [];
                        
                        $json = json_decode(file_get_contents($jsonPath), true);
                        $schema = $json['settings_schema'] ?? [];
                        
                        $components = [];
                        foreach ($schema as $field) {
                            if ($field['type'] === 'image_select') {
                                $options = [];
                                foreach ($field['options'] as $opt) {
                                    $imgUrl = route('theme.asset', ['theme' => $record->slug, 'path' => $opt['image']]);
                                    $options[$opt['value']] = "<div class='flex items-center gap-3' style='align-items: center;'>
                                        <img src='{$imgUrl}' style='width: 3rem; height: 2rem; object-fit: cover; border-radius: 0.25rem; border: 1px solid #e5e7eb;' />
                                        <span class='font-medium'>{$opt['label']}</span>
                                    </div>";
                                }
                                
                                $components[] = \Filament\Forms\Components\Select::make('settings.' . $field['name'])
                                    ->label($field['label'])
                                    ->options($options)
                                    ->allowHtml()
                                    ->default($field['default'] ?? null);
                            } elseif ($field['type'] === 'select') {
                                $options = [];
                                foreach ($field['options'] as $key => $label) {
                                    $options[$key] = $label;
                                }
                                $components[] = \Filament\Forms\Components\Select::make('settings.' . $field['name'])
                                    ->label($field['label'])
                                    ->options($options)
                                    ->default($field['default'] ?? null);
                            } elseif ($field['type'] === 'text') {
                                $components[] = \Filament\Forms\Components\TextInput::make('settings.' . $field['name'])
                                    ->label($field['label'])
                                    ->default($field['default'] ?? null);
                            }
                        }
                        
                        return $components;
                    })
                    ->visible(fn (?\App\Models\Theme $record) => 
                        $record && file_exists(resource_path("views/themes/{$record->slug}/theme.json"))
                    )
            ]);
    }
}
