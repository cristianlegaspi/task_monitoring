<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\User;  // Make sure to import the User model

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Form fields for creating or editing a task
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required(),
            
                Forms\Components\Select::make('status')
                    ->options([
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ])
                    ->default('todo')
                    ->required(),
                Forms\Components\Select::make('assignee_id')
                    ->label('Assignee')
                    ->options(User::all()->pluck('name', 'id'))
                    ->nullable(),
            ]);
    }

    // Table configuration for listing tasks
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                ->searchable(),
                Tables\Columns\TextColumn::make('description'),
                BadgeColumn::make('status')
                    ->getStateUsing(function ($record) {
                     
                        switch ($record->status) {
                            case 'todo':
                                return [
                                    'label' => 'To Do',
                                    
                                ];
                            case 'in_progress':
                                return [
                                    'label' => 'In Progress',
                                   
                                ];
                            case 'done':
                                return [
                                    'label' => 'Done',
                                  
                                ];
                            default:
                                return [
                                    'label' => 'Unknown',
                                  
                                ];
                        }
                    }),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Assignee'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ]),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);

    }

    // Relationships (if any)
    public static function getRelations(): array
    {
        return [
            // Define any relations here if needed
        ];
    }

    

    // Pages configuration for resource
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
    
}