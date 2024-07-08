<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Get;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Actions\Star;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'region' => Region::class,
        'venue_id' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array {
        return [
            Section::make('Conference Details')
            ->collapsible()
            ->description('Provide some básic information about the conference.')
            ->icon('heroicon-o-information-circle')
            ->schema([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->label('Conference Name')
                    ->helperText('The name of the conference')
                    ->required()
                    ->default('My Conference')
                    ->maxLength(60),
                MarkdownEditor::make('description')
                    ->columnSpanFull()
                    ->required(),
                DatePicker::make('start_date')
                    ->native(false)
                    ->required(),
                DateTimePicker::make('end_date')
                    ->native(false)
                    ->required(),
                Fieldset::make('Status')
                    ->columns(1)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ]),
                        Toggle::make('is_published')
                            ->default(true),
                    ])
            ]),

            Section::make('Location')
            ->collapsible()
            ->columns(2)
            ->schema([
                Select::make('region')
                    ->live()
                    ->enum(Region::class)
                    ->options(Region::class),
                Select::make('venue_id')
                    ->searchable()
                    ->preload()
                    ->createOptionForm(Venue::getForm())
                    ->editOptionForm(Venue::getForm())
                    ->relationship('venue', 'name', modifyQueryUsing: function(Builder $query, Get $get)
                    {
                        return $query->where('region', $get('region'));
                    }),
                /* CheckboxList::make('speakers')
                    ->relationship('speakers', 'name')
                    ->options(
                        Speaker::all()->pluck('name', 'id')
                    )
                    ->required() */
            ]),
            Actions::make([
                Action::make('star')
                    ->label('Fill with Factory Data')
                    ->icon('heroicon-m-star')
                    ->visible(function (string $operation){
                        if($operation != 'create'){
                            return false;
                        }
                        if(!app()->environment('local')){
                            return false;
                        }

                        return true;
                    })
                    ->action(function ($livewire) {
                        $data = Conference::factory()->make()->toArray();
                        $livewire->form->fill($data);
                    }),
                
            ]), 
        ];
    }
}
