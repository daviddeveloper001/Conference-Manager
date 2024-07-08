<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const Qualifications = [
        'bussiness-leader' => 'Business Leader',
        'charisma' => 'Charismatic Speaker',
        'first-time' => 'First Time Speaker',
        'hometown-hero' => 'Hometwon Hero',
        'humanitarian' => 'Work In Humanitarian Field',
        'humanitarian' => 'Work In Humanitarian Field',
        'laracasts-contributor' => 'Laracasts Contributor',
        'twitter-influencer' => 'Large Twitter Following',
        'youtube-influencer' => 'Larga Youtube Following',
        'open-source' => 'Open Source Creator / Maintainer',
        'unique-perspective' => 'Unique Perspective',
    ];

    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            FileUpload::make('avatar')
                ->avatar()
                ->directory('avatars')
                ->preserveFilenames()
                ->imageEditor()
                ->maxSize(1024 * 1024 * 10),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Textarea::make('bio')
                ->required()
                ->columnSpanFull(),
            TextInput::make('twitter_handle')
                ->required()
                ->maxLength(255),

            CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->options([
                    'bussiness-leader' => 'Business Leader',
                    'charisma' => 'Charismatic Speaker',
                    'first-time' => 'First Time Speaker',
                    'hometown-hero' => 'Hometwon Hero',
                    'humanitarian' => 'Work In Humanitarian Field',
                    'humanitarian' => 'Work In Humanitarian Field',
                    'laracasts-contributor' => 'Laracasts Contributor',
                    'twitter-influencer' => 'Large Twitter Following',
                    'youtube-influencer' => 'Larga Youtube Following',
                    'open-source' => 'Open Source Creator / Maintainer',
                    'unique-perspective' => 'Unique Perspective',
                ])
                ->descriptions([
                    'bussiness-leader' => 'Here is a nice long description',
                    'charisma' => 'This is even more information about why you should pick this one',
                ])

                ->columns(3)
        ];
    }
}
